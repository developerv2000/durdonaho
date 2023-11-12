@extends('layouts.app')
@section('main')

<aside class="aside">
    <div class="aside-text theme-styled-block">
        <h2 class="aside-text__title main-title">Роҷеъ ба сомона</h2>

        <div class="aside-text__body">{!! App\Models\Option::where('key', 'welcome-text')->first()->value !!}</div>
        <button class="button button--transparent aside-text__more only-mobile">
            Муфассал <span class="material-icons-outlined">chevron_right</span>
        </button>
    </div>

    <x-aside-categories />
    <x-popular-categories class="mobile-hidden" />

</aside>

<div class="main__content home-page-content">
    <section class="latest-quotes">
        <div class="latest-quotes__list">
            @foreach ($latestQuotes as $quote)
                <div class="latest-quotes__item theme-styled-block">
                    <h2 class="latest-quotes__title main-title">Андарзҳои ахир</h2>
                    <x-card-quote :quote="$quote" class="card_with_small_image" />
                </div>
            @endforeach
        </div>
    </section>

    <section class="popular-quotes carousel-section theme-styled-block">
        <h2 class="carousel-section__title main-title">Андарзҳои маъмул</h2>
        <x-carousel-quotes :quotes="$popularQuotes" />
    </section>

    <section class="popular-authors carousel-section theme-styled-block">
        <h2 class="carousel-section__title main-title">Муаллифони машхур</h2>
        <x-carousel-authors :authors="$popularAuthors" />
    </section>

    {{-- Only mobile --}}
    <x-popular-categories class="only-mobile" />
</div>

@endsection
