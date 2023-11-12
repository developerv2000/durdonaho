@extends('layouts.app')
@section('main')

<div class="main__content unverified-quotes-page-content">
    <section class="unverified-quotes-section">
        <div class="theme-styled-block unverified-quotes-about">
            <div class="unverified-quotes-about__inner">
                <h1 class="main-title unverified-quotes-about__title">Андарзҳои дар ҳоли баррасӣ</h1>
                <p class="unverified-quotes-about__text">Андарзҳои аз ҷониби шумо иловашуда, ки аз ҷониби администратор баррасӣ карда мешаванд, дар ин ҷо намоиш дода мешаванд!</p>
            </div>
        </div>

        <div class="quotes-list" id="main-list">
            <x-list-inner-quotes :quotes="$quotes" card-class="card_with_small_image card--full_width" show-edit-button="1" />
        </div>
    </section>
</div>

@endsection
