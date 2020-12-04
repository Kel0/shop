<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <title>Cybersport Forum</title>

    @yield('static')
</head>

<body>
    <header class="header">
        <a href="/" class="header-logo">
            Cybersport academy
        </a>

        <nav class="header-nav">
            <ul>
                <li class="header-nav__item"><a href="{{ route('phorum') }}">Форум</a></li>
                <li class="header-nav__item"><a href="{{ route('shop') }}">Магазин</a></li>
                @guest
                @else
                    @if (Auth::user()->type == "admin")
                        <li class="header-nav__item"><a href="{{ route('admin') }}">Панель управления</a></li>
                    @endif
                @endguest
            </ul>
            @guest
                <a href="{{ route('login') }}" class="header-login">
                    Войти
                </a>
                <a href="{{ route('register') }}" class="header-register">
                    Зарегистрироватся
                </a>
            @else
                <a style="text-decoration: none; color: inherit;" href="profile" class="header-login">{{ Auth::user()->name }}</a>
            @endguest
        </nav>
    </header>

    <section>
        @yield('content')
    </section>

    @yield('scripts')
</body>
</html>