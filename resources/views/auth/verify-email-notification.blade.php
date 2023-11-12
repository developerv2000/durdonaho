@extends('layouts.app')
@section('main')

<div class="main__content email-verification-page">
    <div class="theme-styled-block email-verification-block">
        <p class="email-verification-text">
            Ташаккур барои номнависӣ! Қабл аз шурӯъ оё метавонед, нишонии имейли худро бо клик ба рӯи пайванде ки аз тариқи имейл барои шумо ирсол кардаем тасдиқ кунед? Агар имейл дарёфт накардаед бо камоли майл имейли дигаре барои шумо ирсол мекунем.
        </p>

        <div class="email-verification-actions">
            <form action="{{ route('verification.resend.email') }}" method="POST" id="verification-resend-email-form">
                @csrf
                <button class="button button--main email-verification-resend">Аз нав фиристодани таъйиднома</button>
            </form>
    
            <form action="/logout" method="POST">
                @csrf
                <button class="button email-verification-logout">Хуруҷ аз ҳисоб</button>
            </form>
        </div>
    </div>
</div>

@endsection
