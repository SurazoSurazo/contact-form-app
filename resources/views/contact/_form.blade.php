@php
    $tel = old('tel', request('tel', ''));
    $telFirst = substr($tel, 0, 3);
    $telSecond = strlen($tel) === 10 ? substr($tel, 3, 3) : substr($tel, 3, 4);
    $telThird = strlen($tel) === 10 ? substr($tel, 6) : substr($tel, 7);
@endphp

<form method="POST" action="/contacts/confirm" class="contact-form" id="contactForm">
    @csrf

    <div class="form-row">
        <div class="form-label">お名前 <span>※</span></div>
        <div class="form-field">
            <div class="name-grid">
                <div>
                    <input class="form-input" name="first_name" value="{{ old('first_name', request('first_name')) }}" placeholder="例: 山田">
                    @error('first_name')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <input class="form-input" name="last_name" value="{{ old('last_name', request('last_name')) }}" placeholder="例: 太郎">
                    @error('last_name')<p class="error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">性別 <span>※</span></div>
        <div class="form-field">
            <div class="choice-group">
                @foreach ([1 => '男性', 2 => '女性', 3 => 'その他'] as $value => $label)
                    <label class="choice-label">
                        <input type="radio" name="gender" value="{{ $value }}" @checked((string) old('gender', request('gender')) === (string) $value)>
                        {{ $label }}
                    </label>
                @endforeach
            </div>
            @error('gender')<p class="error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">メールアドレス <span>※</span></div>
        <div class="form-field">
            <input class="form-input" name="email" value="{{ old('email', request('email')) }}" placeholder="例: test@example.com">
            @error('email')<p class="error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">電話番号 <span>※</span></div>
        <div class="form-field">
            <input type="hidden" name="tel" id="tel" value="{{ $tel }}">
            <div class="tel-grid">
                <input class="form-input tel-part" inputmode="numeric" maxlength="3" value="{{ $telFirst }}" placeholder="080">
                <span>-</span>
                <input class="form-input tel-part" inputmode="numeric" maxlength="4" value="{{ $telSecond }}" placeholder="1234">
                <span>-</span>
                <input class="form-input tel-part" inputmode="numeric" maxlength="4" value="{{ $telThird }}" placeholder="5678">
            </div>
            @error('tel')<p class="error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">住所 <span>※</span></div>
        <div class="form-field">
            <input class="form-input" name="address" value="{{ old('address', request('address')) }}" placeholder="例: 東京都渋谷区千駄ヶ谷1-2-3">
            @error('address')<p class="error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">建物名</div>
        <div class="form-field">
            <input class="form-input" name="building" value="{{ old('building', request('building')) }}" placeholder="例: 千駄ヶ谷マンション305">
            @error('building')<p class="error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">お問い合わせの種類 <span>※</span></div>
        <div class="form-field">
            <select class="form-input form-select" name="category_id">
                <option value="">選択してください</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', request('category_id')) === (string) $category->id)>{{ $category->content }}</option>
                @endforeach
            </select>
            @error('category_id')<p class="error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-label">タグ</div>
        <div class="form-field">
            <div class="choice-group tag-group">
                @foreach ($tags as $tag)
                    <label class="choice-label">
                        <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" @checked(in_array($tag->id, old('tag_ids', request('tag_ids', []))))>
                        {{ $tag->name }}
                    </label>
                @endforeach
            </div>
            @error('tag_ids.*')<p class="error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="form-row form-row-start">
        <div class="form-label">お問い合わせ内容 <span>※</span></div>
        <div class="form-field">
            <textarea class="form-input form-textarea" name="detail" placeholder="お問い合わせ内容をご記載ください">{{ old('detail', request('detail')) }}</textarea>
            @error('detail')<p class="error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="form-actions">
        <button class="btn-primary" type="submit">確認画面</button>
    </div>
</form>
