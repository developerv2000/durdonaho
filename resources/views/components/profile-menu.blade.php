<ul class="dropdown__menu profile-menu">
    <li><p class="profile-menu__username">{{ auth()->user()->name }}</p></li>

    <li><a class="dropdown__item" href="{{ route('favorite.authors') }}">
        <span class="material-icons dropdown__item-icon">face</span> Муаллифони барҷаста
    </a></li>

    <li><a class="dropdown__item" href="{{ route('favorite.quotes') }}">
        <span class="material-icons dropdown__item-icon">bookmark</span> Андарзҳои мунтахаб
    </a></li>

    <li class="only-mobile"><a class="dropdown__item" href="{{ route('users.quotes.create') }}">
        <span class="material-icons dropdown__item-icon">add</span> Илова намудани андарз
    </a></li>

    <li><a class="dropdown__item" href="{{ route('users.current.quotes') }}">
        <span class="material-icons dropdown__item-icon">edit</span> Вироиши андарзҳо
    </a></li>

    <li><a class="dropdown__item" href="{{ route('users.quotes.unverified') }}">
        <span class="material-icons dropdown__item-icon">schedule</span> Андарзҳои дар ҳоли баррасӣ
    </a></li>

    <li><a class="dropdown__item" href="{{ route('users.profile') }}">
        <span class="material-icons dropdown__item-icon">settings</span> Танзимоти намоя
    </a></li>

    <li>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="dropdown__item dropdown__item--logout">
                <span class="material-icons dropdown__item-icon">logout</span> Баромадан
            </button>
        </form>
    </li>
</ul>
