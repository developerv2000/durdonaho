<header class="header">
    <div class="header__inner main-container">
        <div class="header-top">
            <a class="logo" href="{{ route('home') }}">
                <img class="logo__image" src="{{ asset('img/main/logo.svg') }}" alt="Дурдонаҳо лого">
            </a>

            <div class="header-top__actions">
                @auth
                    <div class="dropdown profile-dropdown">
                        <button class="dropdown__button"><span class="material-icons">person</span></button>

                        <div class="dropdown__content">
                            <div class="dropdown__background"></div>
                            <x-profile-menu />
                        </div>
                    </div>

                    <a class="button button--main header-top__add-quote" href="{{ route('users.quotes.create') }}"><span class="material-icons-outlined">drive_file_rename_outline</span> Илова намудани андарз</a>
                @endauth

                @guest
                    <button class="button button--main header-top__login" data-action="show-modal" data-target-id="login-modal">
                        <span class="material-icons">person</span> Вуруд
                    </button>
                @endguest
            </div>
        </div>

        <div class="header__bottom">
            <nav class="header-nav">
                <ul class="header-nav__ul">
                    <li class="header-nav__item">
                        <a class="header-nav__link header-nav__link--home @if($route == "home") active @endif" href="{{ route('home') }}"><span class="material-icons unselectable">home</span></a>
                    </li>

                    <li class="header-nav__item">
                        <a class="header-nav__link @if($route == "quotes.index") active @endif" href="{{ route('quotes.index') }}">Андарзҳо</a>
                    </li>

                    <li class="header-nav__item">
                        <a class="header-nav__link
                            @if($route == 'authors.index' || $route == 'authors.show') active @endif"
                            href="{{ route('authors.index') }}">Муаллифон
                        </a>
                    </li>

                    <li class="header-nav__item">
                        <a class="header-nav__link @if($route == "quotes.top") active @endif" href="{{ route('quotes.top') }}">Андарзҳои беҳтарин</a>
                    </li>
                </ul>
            </nav>

            <button class="mobile-menu-toggler" data-action="show-mobile-menu"><span class="material-icons-outlined">menu</span></button>

            <form action="{{ route('search') }}" method="GET" class="search header__search {{ $route == 'search' ? 'search--active' : '' }}">
                <input class="search__input" type="text" placeholder="Ҷустуҷӯ" name="keyword" value="{{ $route == 'search' ? $keyword : '' }}" minlength="3" required>
                <button class="search__button"><span class="material-icons search__button-icon">search</span></button>
            </form>
        </div>

        <x-mobile-menu />
    </div>
</header>
