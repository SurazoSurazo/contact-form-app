<x-guest-layout>
<section class="auth-page">
    <h1 class="auth-title">Register</h1>
    <form method="POST" action="/register" class="auth-card">
        @csrf
        <label class="auth-field">お名前
            <input class="auth-input" name="name" value="{{ old('name') }}" placeholder="山田 太郎">
            @error('name')<p class="error">{{ $message }}</p>@enderror
        </label>
        <label class="auth-field">メールアドレス
            <input class="auth-input" name="email" value="{{ old('email') }}" placeholder="email@example.com">
            @error('email')<p class="error">{{ $message }}</p>@enderror
        </label>
        <label class="auth-field">パスワード
            <input class="auth-input" type="password" name="password" placeholder="password">
            @error('password')<p class="error">{{ $message }}</p>@enderror
        </label>
        <label class="auth-field">パスワード確認
            <input class="auth-input" type="password" name="password_confirmation" placeholder="password">
        </label>
        <div class="auth-actions">
            <button class="auth-submit" type="submit">登録</button>
        </div>
    </form>
</section>
</x-guest-layout>
