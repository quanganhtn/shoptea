<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomepageController extends Controller
{
    private string $path = 'data/homepage.json';

    /* =====================
    HÀM DÙNG CHUNG
    ====================== */

    public function hero()
    {
        $data = $this->readData()['hero'] ?? [];
        return view('admin.homepage.hero', compact('data'));
    }

    private function readData(): array
    {
        $fullPath = storage_path('app/' . $this->path);

        if (!file_exists($fullPath)) {
            return [];
        }

        return json_decode(File::get($fullPath), true) ?? [];
    }

    /* =====================
    HERO / BANNER
    ====================== */

    public function updateHero(Request $request)
    {
        $request->validate([
            'title' => 'required|max:120',
            'subtitle' => 'required|max:180',
            'image' => 'nullable|image|max:2048',
        ]);

        $homepage = $this->readData();

        $hero = $request->only('title', 'subtitle');

        // ✅ nếu có upload ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $name = 'hero_' . time() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('uploads/hero', $name, 'public');

            // lưu đường dẫn public
            $hero['image'] = 'storage/' . $path;
        } else {
            // giữ ảnh cũ nếu không upload
            $hero['image'] = $homepage['hero']['image'] ?? null;
        }

        $homepage['hero'] = $hero;

        $this->writeData($homepage);

        return back()->with('success', 'Đã cập nhật Banner');
    }


    private function writeData(array $data): void
    {
        $fullPath = storage_path('app/' . $this->path);
        File::ensureDirectoryExists(dirname($fullPath));
        File::put($fullPath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /* =====================
    ABOUT
    ====================== */

    public function about()
    {
        $data = $this->readData()['about'] ?? [];
        return view('admin.homepage.about', compact('data'));
    }

    public function updateAbout(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'desc' => 'nullable',
            'text' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $homepage = $this->readData();
        $about = $homepage['about'] ?? [];
        $about['title'] = $request->title;
        $about['desc'] = $request->desc;
        $about['text'] = $request->text;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/homepage'), $name);
            $about['image'] = 'uploads/homepage/' . $name;
        }

        $homepage['about'] = $about;
        $this->writeData($homepage);

        return back()->with('success', 'Đã cập nhật About');
    }

    /* =====================
    NEWS
    ====================== */

    public function news()
    {
        $data = $this->readData()['news'] ?? [];
        return view('admin.homepage.news', compact('data'));
    }

    public function updateNews(Request $request)
    {
        $request->validate([
            'cards' => 'required|array|size:3',
            'cards.*.text' => 'required|string|max:3000',
            'cards.*.link' => 'required|url|max:500',
            'cards.*.image' => 'nullable|image|max:2048', // 2MB
        ]);

        $homepage = $this->readData();

        // đảm bảo có mảng cards
        $cards = $homepage['news']['cards'] ?? [[], [], []];

        // loop 3 cards
        for ($i = 0; $i < 3; $i++) {

            // text + link
            $cards[$i]['text'] = $request->input("cards.$i.text");
            $cards[$i]['link'] = $request->input("cards.$i.link");

            // upload ảnh nếu có
            if ($request->hasFile("cards.$i.image")) {
                $file = $request->file("cards.$i.image");

                $name = 'news_' . ($i + 1) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/homepage', $name);

                // lưu đường dẫn để asset() dùng
                $cards[$i]['image'] = 'storage/homepage/' . $name;
            }
        }

        // ❗title/desc cố định - nếu bạn muốn giữ cứng, đặt luôn ở đây
        $homepage['news']['title'] = 'Tin tức & Kiến thức';
        $homepage['news']['desc'] = 'Mẹo pha trà – lợi ích sức khỏe – văn hóa thưởng trà.';
        $homepage['news']['cards'] = $cards;

        $this->writeData($homepage);

        return back()->with('success', 'Đã cập nhật 3 mục News');
    }


    /* =====================
    CONTACT
    ====================== */

    public function contact()
    {
        $data = $this->readData()['contact'] ?? [];
        return view('admin.homepage.contact', compact('data'));
    }

    public function updateContact(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);

        $homepage = $this->readData();
        $homepage['contact'] = $request->only('phone', 'email', 'address');

        $this->writeData($homepage);

        return back()->with('success', 'Đã cập nhật Contact');
    }
}
