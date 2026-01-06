@extends('admin.admin')

@section('title','Homepage - News')
@section('page_title','🏠 Trang chủ / News')

@section('content')
    <div class="admin-container">
        <div class="admin-card">
            @if (session('success'))
                <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="admin-alert admin-alert--danger">
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.homepage.news.update') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="admin-form">
                @csrf

                @php
                    $cards = $data['cards'] ?? [[], [], []];
                @endphp

                <div class="admin-grid admin-grid--3">
                    @for($i=0; $i<3; $i++)
                        <div class="admin-subcard">
                            <div class="admin-subcard__title">News {{ $i+1 }}</div>

                            <div class="admin-field">
                                <label class="admin-label">Ảnh</label>
                                <input type="file" name="cards[{{ $i }}][image]" class="admin-input">
                                @if(!empty($cards[$i]['image']))
                                    <div class="admin-preview">
                                        <img class="admin-preview__img admin-preview__img--cover"
                                             src="{{ asset($cards[$i]['image']) }}"
                                             alt="news{{ $i+1 }}">
                                    </div>
                                @endif
                            </div>

                            <div class="admin-field">
                                <label class="admin-label">Nội dung</label>
                                <textarea name="cards[{{ $i }}][text]"
                                          class="admin-textarea admin-textarea--tall"
                                          placeholder="Nội dung ...">{{ old("cards.$i.text", $cards[$i]['text'] ?? '') }}</textarea>
                            </div>

                            <div class="admin-field">
                                <label class="admin-label">Link (URL)</label>
                                <input type="text"
                                       name="cards[{{ $i }}][link]"
                                       class="admin-input"
                                       value="{{ old("cards.$i.link", $cards[$i]['link'] ?? '') }}"
                                       placeholder="https://...">
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="admin-form__actions">
                    <button class="admin-btn admin-btn--primary">💾 Lưu News</button>
                </div>
            </form>
        </div>
    </div>
@endsection
