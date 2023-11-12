@props(['categories', 'class' => ''])

<div class="popular-categories {{ $class }} theme-styled-block">
    <h2 class="popular-categories__title main-title">Категорияҳои маъмул</h2>
    <div class="popular-categories__text">{!! App\Models\Option::where('key', 'popular-categories-text')->first()->value !!}</div>

    <ul class="categories-card-list">
        @foreach ($categories as $category)
            <a class="category-card" href="{{ route('quotes.index') }}?category_id={{ $category->id }}">
                <img class="category-card__image" src="{{ asset('img/categories/' . $category->image) }}" alt="{{ $category->title }}">
                <h6 class="category-card__title">{{ $category->title }}</h6>
            </a>
        @endforeach
    </ul>
</div>
