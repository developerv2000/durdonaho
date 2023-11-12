<div class="mobile-menu">
    <div class="mobile-menu__inner">
        @auth
            <x-profile-menu />
        @endauth

        <div class="mobile-nav">
            <a class="logo mobile-menu__logo" href="{{ route('home') }}">
                <img class="mobile-menu__logo-img" src="{{ asset('img/main/logo-white.svg') }}" alt="Durdonaho white logo">
            </a>

            <ul class="mobile-nav__ul">
                <li class="mobile-nav__li">
                    <a class="mobile-nav__link" href="{{ route('quotes.index') }}">Андарзҳо</a>
                </li>

                <li class="mobile-nav__li">
                    <a class="mobile-nav__link" href="{{ route('authors.index') }}">Муаллифон</a>
                </li>

                <li class="mobile-nav__li">
                    <a class="mobile-nav__link" href="{{ route('quotes.top') }}">Андарзҳои беҳтарин</a>
                </li>

                @guest
                    <li class="mobile-nav__li">
                        <button class="mobile-nav__link" data-action="show-modal" data-target-id="login-modal">Вуруд</button>
                    </li>
                @endguest
            </ul>

            <button class="mobile-menu__hide-btn" data-action="hide-mobile-menu"><span class="material-icons-outlined">close</span></button>
        </div>
    </div>
</div>
