<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'COACHTECH お問い合わせフォーム' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-stone-800">
    <header class="relative border-b border-[#e8e2de] bg-white">
        <div class="mx-auto flex h-12 max-w-6xl items-center justify-center px-6">
            <a href="/" class="font-serif text-xl font-normal text-[#8B6A56]">FashionablyLate</a>
            <nav class="absolute right-5 top-1/2 flex -translate-y-1/2 items-center gap-4 text-sm sm:right-8 {{ request()->is('/') || request()->is('contacts/confirm') || request()->is('thanks') ? 'hidden' : '' }}">
                @auth
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="auth-header-link" type="submit">logout</button>
                    </form>
                @else
                    @if (request()->is('register'))
                        <a class="auth-header-link" href="/login">login</a>
                    @elseif (request()->is('login'))
                        <a class="auth-header-link" href="/register">register</a>
                    @else
                        <a class="text-stone-600 hover:text-stone-900" href="/login">login</a>
                        <a class="text-stone-600 hover:text-stone-900" href="/register">register</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>
    <main class="{{ request()->is('register') || request()->is('login') ? 'auth-main' : 'mx-auto max-w-6xl px-6 py-10' }}">
        @if (session('status'))
            <div class="mb-6 border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                {{ session('status') }}
            </div>
        @endif
        {{ $slot }}
    </main>
    @stack('scripts')
</body>
</html>
