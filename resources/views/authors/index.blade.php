@extends('layouts.app')
@section('main')

@section('title', 'Муаллифон')

<aside class="aside mobile-hidden">
    <x-aside-categories />
    <x-aside-popularity />
</aside>

<div class="main__content authors-page-content">
    <x-filter-categories :request="$request"/>

    <section class="authors-section" id="authors-section">
        <div class="authors-list" id="main-list">
            <x-list-inner-authors :authors="$authors" card-class="card_with_medium_image"/>
        </div>
    </section>
</div>

@endsection
