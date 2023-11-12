@extends('layouts.app')
@section('main')

@section('title', ' Ҷӯстуҷӯ')

<div class="main__content search-page-content">
    @if($authors->count() + $quotes->count() == 0)
        <div class="theme-styled-block empty-query-warning">
            <div class="empty-query-warning__inner">
                Ба дархости шумо ягон натича ёфт нашуд !
            </div>
        </div>
    @endif


    @if(count($authors))
        <section class="authors-section search-authors-section">
            <div class="authors-list">
                @foreach ($authors as $author)
                    <x-card-author :author="$author" class="theme-styled-block card--full_width card_with_medium_image" />
                @endforeach
            </div>
        </section>
    @endif

    @if(count($quotes))
        <section class="quotes-section search-quotes-section">
            <div class="quotes-list">
                @foreach ($quotes as $quote)
                    <x-card-quote :quote="$quote" class="theme-styled-block card--full_width card_with_small_image" />
                @endforeach
            </div>
        </section>
    @endif
</div>

@endsection