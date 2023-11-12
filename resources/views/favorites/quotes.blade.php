@extends('layouts.app')
@section('main')

@section('title', 'Андарзҳои мунтахаб')

<div class="main__content favorite-quotes-page-content">
    <x-filter-categories :request="$request" class="categories-filter--full_width"/>

    <section class="quotes-section" id="quotes-section">
        <div class="quotes-list" id="main-list">
            <x-list-inner-quotes :quotes="$quotes" card-class="card_with_small_image card--full_width"/>
        </div>
    </section>
</div>

@endsection
