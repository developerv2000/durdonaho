@extends('layouts.app')
@section('main')

@section('title', 'Аҳдномаи истифодабарӣ')

<div class="main__content terms-page-content">
    <section class="terms-section">
        <div class="theme-styled-block terms-section__inner">
            <h1 class="main-title terms-section__title">Аҳдномаи истифодабарӣ</h1>
            <div class="terms-section__text">{!! App\Models\Option::where('key', 'terms-of-use')->first()->value !!}</div>
        </div>
    </section>
</div>

@endsection