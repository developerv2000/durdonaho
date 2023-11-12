@extends('layouts.app')
@section('main')

@section('title', 'Иктибосхои самиздат')

<aside class="aside">
    <x-aside-categories />
    <x-aside-popularity />
</aside>

<div class="main__content quotes-page-content">
    <x-filter-categories :request="$request"/>

    <section class="quotes-section" id="quotes-section">
        <div class="quotes-list" id="main-list">
            <x-list-inner-quotes :quotes="$quotes" card-class="card_with_small_image"/>
        </div>
    </section>
</div>

@endsection