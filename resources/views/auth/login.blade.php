<x-guest-layout>
<section class="auth-page">
    <h1 class="auth-title">Login</h1>
    <form method="POST" action="/login" class="auth-card auth-card-login">
        @csrf
        <label class="auth-field">メールアドレス
            <input class="auth-input" name="email" value="{{ old('email') }}" placeholder="email@example.com">
            @error('email')<p class="error">{{ $message }}</p>@enderror
        </label>
        <label class="auth-field">パスワード
            <input class="auth-input" type="password" name="password" placeholder="password">
            @error('password')<p class="error">{{ $message }}</p>@enderror
        </label>
        <div class="auth-actions auth-actions-login">
            <button class="auth-submit auth-submit-login" type="submit">ログイン</button>
        </div>
    </form>
</section>
</x-guest-layout>
