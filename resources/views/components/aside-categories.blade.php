@props(['categories', 'class' => ''])

<div class="aside-categories {{ $class }} theme-styled-block">
    <h2 class="aside-categories__title main-title">Категорияҳо</h2>

    <form action="javascript:void(0);" class="search aside__search">
        <input class="search__input" type="text" placeholder="Ҷустҷӯи категорияҳо" id="aside-search-input">
        <button type="button" class="search__button"><span class="material-icons search__button-icon">search</span></button>
    </form>

    <ul class="aside-categories__list" id="aside-categories-list">
        @foreach ($categories as $category)
            <li class="aside-categories__item">
                <a class="aside-categories__link" href="{{ route('quotes.index') }}?category_id={{ $category->id }}">{{ $category->title }}</a>
            </li>
        @endforeach
    </ul>
</div>
