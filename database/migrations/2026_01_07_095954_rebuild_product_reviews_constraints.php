<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $table = 'product_reviews';

    private function dropForeignIfExists(string $table, string $fkName): void
    {
        $exists = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
              AND CONSTRAINT_NAME = ?
            LIMIT 1
        ", [$table, $fkName]);

        if ($exists) {
            DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$fkName`");
        }
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        $exists = DB::selectOne("
            SELECT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND INDEX_NAME = ?
            LIMIT 1
        ", [$table, $indexName]);

        if ($exists) {
            DB::statement("ALTER TABLE `$table` DROP INDEX `$indexName`");
        }
    }

    private function addForeignIfNotExists(string $table, string $fkName, string $col, string $refTable, string $refCol = 'id'): void
    {
        $exists = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
              AND CONSTRAINT_NAME = ?
            LIMIT 1
        ", [$table, $fkName]);

        if (!$exists) {
            DB::statement("
                ALTER TABLE `$table`
                ADD CONSTRAINT `$fkName`
                FOREIGN KEY (`$col`) REFERENCES `$refTable`(`$refCol`)
                ON DELETE CASCADE
            ");
        }
    }

    private function addUniqueIfNotExists(string $table, string $indexName, array $cols): void
    {
        $exists = DB::selectOne("
            SELECT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND INDEX_NAME = ?
            LIMIT 1
        ", [$table, $indexName]);

        if (!$exists) {
            $colsSql = implode('`,`', $cols);
            DB::statement("ALTER TABLE `$table` ADD UNIQUE `$indexName` (`$colsSql`)");
        }
    }

    public function up(): void
    {
        $t = $this->table;

        // 0) dọn data bẩn
        if (Schema::hasColumn($t, 'order_id')) {
            DB::statement("UPDATE `$t` SET order_id = NULL WHERE order_id = 0");
        }

        // 1) dọn orphan để lát add FK không bị lỗi
        // order_id không tồn tại trong orders => set NULL
        if (Schema::hasColumn($t, 'order_id')) {
            DB::statement("
                UPDATE `$t` pr
                LEFT JOIN `orders` o ON o.id = pr.order_id
                SET pr.order_id = NULL
                WHERE pr.order_id IS NOT NULL AND o.id IS NULL
            ");
        }

        // product_id không tồn tại => (tuỳ bạn) xoá review hoặc set NULL
        // Ở đây mình chọn XOÁ review orphan cho sạch (vì product_id thường NOT NULL)
        DB::statement("
            DELETE pr FROM `$t` pr
            LEFT JOIN `products` p ON p.id = pr.product_id
            WHERE p.id IS NULL
        ");

        // user_id không tồn tại => xoá review orphan
        DB::statement("
            DELETE pr FROM `$t` pr
            LEFT JOIN `users` u ON u.id = pr.user_id
            WHERE u.id IS NULL
        ");

        // 2) DROP các FK nếu đang tồn tại (đúng tên thật, không đoán)
        // (nếu trước đây bạn tạo FK theo tên mặc định, tên sẽ là: product_reviews_user_id_foreign ...)
        $this->dropForeignIfExists($t, 'product_reviews_user_id_foreign');
        $this->dropForeignIfExists($t, 'product_reviews_product_id_foreign');
        $this->dropForeignIfExists($t, 'product_reviews_order_id_foreign');

        // 3) DROP unique cũ đang chặn user review nhiều lần
        // unique(product_id, user_id) -> bỏ
        $this->dropIndexIfExists($t, 'product_reviews_product_id_user_id_unique');

        // nếu bạn đã từng tạo unique mới rồi mà fail, drop luôn để tạo lại sạch
        $this->dropIndexIfExists($t, 'uniq_review_per_order_product_user');

        // 4) ADD unique mới: mỗi đơn + mỗi sản phẩm + mỗi user => 1 review
        // => user mua nhiều đơn => order_id khác => review được nhiều lần
        $this->addUniqueIfNotExists($t, 'uniq_review_per_order_product_user', ['order_id', 'product_id', 'user_id']);

        // 5) ADD FK lại (tuỳ bạn muốn ràng buộc chặt)
        // Nếu bạn không muốn FK thì có thể comment 3 dòng dưới.
        $this->addForeignIfNotExists($t, 'product_reviews_user_id_foreign', 'user_id', 'users');
        $this->addForeignIfNotExists($t, 'product_reviews_product_id_foreign', 'product_id', 'products');
        $this->addForeignIfNotExists($t, 'product_reviews_order_id_foreign', 'order_id', 'orders');
    }

    public function down(): void
    {
        $t = $this->table;

        $this->dropForeignIfExists($t, 'product_reviews_order_id_foreign');
        $this->dropForeignIfExists($t, 'product_reviews_product_id_foreign');
        $this->dropForeignIfExists($t, 'product_reviews_user_id_foreign');

        $this->dropIndexIfExists($t, 'uniq_review_per_order_product_user');

        // (tuỳ bạn) có thể add lại unique cũ nếu muốn rollback hoàn toàn
        // $this->addUniqueIfNotExists($t, 'product_reviews_product_id_user_id_unique', ['product_id', 'user_id']);
    }
};
