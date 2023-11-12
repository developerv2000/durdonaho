@extends('layouts.app')
@section('main')

@section('title', 'Сиёсати маҳрамият')

<div class="main__content terms-page-content">
    <section class="terms-section">
        <div class="theme-styled-block terms-section__inner">
            <h1 class="main-title terms-section__title">Сиёсати маҳрамият</h1>
            <div class="terms-section__text">{!! App\Models\Option::where('key', 'privacy-policy')->first()->value !!}</div>
        </div>
    </section>
</div>

@endsection