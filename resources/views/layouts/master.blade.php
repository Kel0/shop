<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <title>Cybersport Forum</title>
    @yield('static')
    <link rel="stylesheet" href="{{ asset('css/adaptive.css') }}">
</head>

<body>
    <header class="header">
        <a href="{{ route('phorum') }}" class="header-logo">
            Cybersport academy
        </a>
        <input class="menu-btn" type="checkbox" id="menu-btn" />
        <label class="menu-icon" for="menu-btn">
            <span class="navicon"></span>
        </label>
        
        <nav class="header-nav">
            <ul>
                <li class="header-nav__item"><a href="{{ route('phorum') }}">Форум</a></li>
                <li class="header-nav__item"><a href="{{ route('shop') }}">Магазин</a></li>
                @guest
                @else
                    @if (Auth::user()->type == "admin")
                        <li class="header-nav__item">
                            <a href="{{ route('admin') }}">Панель управления</a>
                        </li>
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
                <a href="profile" class="header-login" style="text-decoration: none; color: black;">
                    {{ Auth::user()->name }}
                </a>
            @endguest
        </nav>
    </header>

    <section>
        @yield('content')
    </section>

    @yield('scripts')
</body>
</html>