<footer class="footer">
    <div class="footer__inner main-container">
        <a class="logo footer__logo" href="{{ route('home') }}">
            <img class="logo__image" src="{{ asset('img/main/logo.svg') }}" alt="Дурдонаҳо лого">
        </a>

        <p class="footer__copyright">© 2017 - {{ date('Y') }} — ДУРДОНАҲО<br> Ҳамаи ҳуқуқ маҳфуз аст</p>

        <ul class="footer__list">
            <li>
                <a class="footer__list-link" href="{{ route('privacy-policy') }}">Сиёсати махрамият</a>
            </li>

            <li>
                <a class="footer__list-link" href="{{ route('terms-of-use') }}">Ахдномаи истифодабари</a>
            </li>
        </ul>

        @if($siteViews)
            <div class="footer__counter">
                <div class="footer__counter-numbers">
                    @foreach ($siteViews as $number)
                        <div class="footer__counter-number">{{ $number }}</div>
                    @endforeach
                </div>

                <div class="footer__counter-icon">
                    <span class="material-icons">person</span>
                </div>
            </div>
        @endif

        {{-- <div class="footer__block footer__socials">
            <div class="footer__socials-text">
                <p>Моро мутолиа</p>
                <p>намоед:</p>
            </div>

            <div class="footer__socials-container">
                <a class="footer__socials-link" href="#">
                    @include('svgs.twitter')
                </a>

                <a class="footer__socials-link" href="#">
                    @include('svgs.telegram')
                </a>

                <a class="footer__socials-link" href="#">
                    @include('svgs.facebook')
                </a>

                <a class="footer__socials-link" href="#">
                    @include('svgs.instagram')
                </a>
            </div>
        </div> --}}
    </div>
</footer>
