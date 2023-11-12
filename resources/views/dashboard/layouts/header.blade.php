<header class="header" id="header">
    {{-- Title start --}}
    <h1 class="header__title">
        {{-- first level --}}
        @if(strpos($route, 'dashboard.unapproved') !== false) Цитаты на рассмотрении
        @elseif(strpos($route, 'quotes') !== false  || $route == 'dashboard.index') Цитаты 
        @elseif(strpos($route, 'sources') !== false) Источники
        @elseif(strpos($route, 'authors') !== false) Авторы
        @elseif(strpos($route, 'categories') !== false) Категории
        @elseif(strpos($route, 'options') !== false) Тексты
        @elseif(strpos($route, 'users') !== false) Пользователи
        @elseif(strpos($route, 'reports') !== false) Жалобы
        @endif

        {{-- First levels items count --}}
        @if( strpos($route, 'index') ) ({{ count($allItems) }}) @endif

        {{-- second level for CREATE --}}
        @if(strpos($route, 'create') ) / Добавить

        {{-- second level for EDIT --}}
        @elseif($route == 'quotes.edit') / #{{ $item->id }}
        @elseif($route == 'quotes.dashboard.unapproved.edit') / #{{ $item->id }}
        @elseif($route == 'authors.edit') / {{ $item->name }}
        @elseif($route == 'categories.edit') / {{ $item->title }}
        @elseif($route == 'options.edit') / {{ $item->title }}
        @elseif($route == 'reports.edit') / #{{ $item->id }}
        @endif
    </h1>  {{-- Title end --}}

    {{-- Actions start --}}
    <div class="header__actions">
        {{-- Create Items for index routes --}}
        @switch($route)
            @case('dashboard.index')
            @case('authors.dashboard.index')
            @case('categories.dashboard.index')
                <a href="{{ route($modelShortcut . '.create') }}">
                    <span class="material-icons">add</span> Добавить
                </a>
            @break
        @endswitch

        {{-- Destroy Multiple Items actions for index routes --}}
        @switch($route)
            @case('dashboard.index')
            @case('authors.dashboard.index')
            @case('categories.dashboard.index')
            @case('reports.dashboard.index')
                <button id="header-select-all-button">
                    <span class="material-icons">done_all</span> Отметить все
                </button>

                <button data-bs-toggle="modal" data-bs-target="#destroy-multiple-items-form">
                    <span class="material-icons">clear</span> Удалить отмеченные
                </button>
            @break
        @endswitch
    </div>  {{-- Actions end --}}
</header>