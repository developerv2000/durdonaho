@extends('layouts.app')
@section('main')

@section('title', ' Муаллифони барҷаста')

<div class="main__content favorite-authors-page-content">
    <x-filter-categories :request="$request" class="categories-filter--full_width"/>

    <section class="authors-section" id="authors-section">
        <div class="authors-list" id="main-list">
            <x-list-inner-authors :authors="$authors" card-class="card_with_medium_image card--full_width"/>
        </div>
    </section>
</div>

@endsection