{{--
    This component is used everywhere for AJAX searching quotes or authors and filtering them by categories
    On categories checkbox change or on search input value change, fires AJAX request to update items list

    $title ->        Title of the block
    $formAction ->   Used as url for AJAX request
    $favorite ->     Used as filter for favorite.quotes & favorite.authors routes. FALSE on other routes
    $authorId ->     Filter only specific authors quotes (authors.show route). NULL on other routes
    $userId ->       Filter only specific users quotes (users.quotes route). NULL on other routes
    $placeholder ->  Placeholder for search input
--}}

@props(['request', 'categories', 'class' => '', 'authorId' => null, 'userId' => null])

@php
    // Default values
    $favorite = 0;
    $formAction = '/quotes/ajax-get';
    $placeholder = 'Ҷустҷӯи андарзҳо';

    switch ($request->route()->getName()) {
        case 'quotes.index':
            $title = 'Ҳамаи андарзҳо';
            break;

        case 'authors.index':
            $title = 'Ҳама муаллифон';
            $formAction = '/authors/ajax-get';
            $placeholder = 'Ҷустуҷӯи муаллифон';
            break;

        case 'authors.show':
            $title = 'Ҳамаи андарзҳои муаллиф';
            break;

        case 'favorite.quotes':
            $title = 'Андарзҳои мунтахаб';
            $favorite = 1;
            break;

        case 'favorite.authors':
            $title = 'Муаллифони барҷаста';
            $formAction = '/authors/ajax-get';
            $placeholder = 'Ҷустуҷӯи муаллифон';
            $favorite = 1;
            break;

        case 'users.quotes':
            $title = 'Андарзҳои аз ҷониби ' . App\Models\User::find($userId)->name . ' нашршуда';
            break;

        case 'users.current.quotes':
            $title = 'Андарзҳои аз ҷониби ман нашршуда';
            break;
    }

    //Because of GET method and PAGINATION all categories have been joined by '-' as one string
    if($request->category_id) {
        //explode categories as array
        $activeCategories = explode('-', $request->category_id);
    } else {
        $activeCategories = false;
    }
@endphp

<section class="categories-filter theme-styled-block {{ $class }}">
    <div class="categories-filter__inner">

        <h2 class="categories-filter__title main-title">{{ $title }}</h2>

        <form class="categories-filter__form" action="{{ $formAction }}" id="categories-filter-form">
            <input type="hidden" name="favorite" value="{{ $favorite }}">

            @if ($authorId)
                <input type="hidden" name="author_id" value="{{ $authorId }}">
            @endif

            @if ($userId)
                <input type="hidden" name="user_id" value="{{ $userId }}">
            @endif

            <div class="search categories-filter__search">
                <input class="search__input categories-filter__search-input" type="text" id="categories-filter-search-input" placeholder="{{ $placeholder }}" name="keyword" value="{{ $request->keyword }}">
                <button type="button" class="search__button"><span class="material-icons search__button-icon">search</span></button>
            </div>

            <div class="categories-filter__list">
                {{-- All categories Button --}}
                <button class="categories-filter__button @if(!$activeCategories) categories-filter__button--active @endif" id="categories-filter-all-btn" type="button">Ҳама</button>

                {{-- Only first 14 of categories are visible while no active categories selected --}}
                @foreach ($categories as $category)
                    <input class="categories-filter__checkbox @if(!$activeCategories && $loop->index > 13) categories-filter__checkbox--hidden @endif"
                        type="checkbox" name="category_id" id="category{{ $category->id }}" value="{{ $category->id }}"
                        @if($activeCategories)
                            @foreach ($activeCategories as $activeId)
                                @checked($category->id == $activeId)
                            @endforeach
                        @endif
                    >
                    <label class="categories-filter__label" for="category{{ $category->id }}">{{ $category->title }}</label>
                @endforeach

                {{-- Displays all hidden categories button --}}
                @if(!$activeCategories && count($categories) > 13)
                    <button class="categories-filter__button categories-filter__button--active" id="categories-filter-more-btn" type="button">Бештар {{ count($categories) - 13 }}</button>
                @endif
            </div>
        </form>

    </div>
</section>
