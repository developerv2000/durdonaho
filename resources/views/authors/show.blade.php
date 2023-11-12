@extends('layouts.app')
@section('main')

@section('title', $author->name)

@section('meta-tags')
    @php
        $shareText = App\Helpers\Helper::generateShareText($author->biography);
    @endphp

    <meta name="description" content="{{ $shareText }}">
    <meta property="og:description" content="{{ $shareText }}">
    <meta property="og:title" content="{{ $author->name }}" />
    <meta property="og:image" content="{{ asset('img/authors/' . $author->image) }}">
    <meta property="og:image:alt" content="{{ $author->name }}">
@endsection

<aside class="aside mobile-hidden">
    <x-aside-categories />
    <x-aside-popularity />
</aside>

<div class="main__content authors-show-page-content">
    <x-card-author :author="$author" class="theme-styled-block card_with_medium_image authors-show-main-card"/>
    <x-filter-categories :request="$request" :author-id="$author->id"/>

    <section class="quotes-section" id="quotes-section">
        <div class="quotes-list" id="main-list">
            <x-list-inner-quotes :quotes="$quotes" card-class="card_with_small_image"/>
        </div>
    </section>
</div>

@endsection
