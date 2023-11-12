{{--
    This component is used everywhere as a Card for all types of quote cards

    $class ->                   Additional classes: card_with_medium_image || card--vertical || card--full_width etc
    $routeName ->               Used only to determine if it is search page
    $keyword ->                 Highlighting search Keywords on search page
    $showEditButton ->          Used to display edit button (true only on users.current.quotes route)
    data-card-id ->             Used in JS (liking/favoriting same cards)
--}}

@props(['quote', 'class' => '', 'routeName' => request()->route()->getName(), 'keyword' => request()->keyword, 'showEditButton' => null])

<div class="card {{ $class }}" data-card-id="quote{{ $quote->id }}">
    <div class="card__inner">

        @php
            switch ($quote->source->key) {
                case App\Models\Source::AUTHORS_QUOTE_KEY:
                    $image = 'img/authors/' . $quote->author->image;
                    $title = $quote->author->name;
                    $link = $quote->author->approved ? route('authors.show', $quote->author->slug) : null;
                    break;

                case App\Models\Source::OWN_QUOTE_KEY:
                    $image = 'img/users/' . $quote->publisher->image;
                    $title = $quote->publisher->name;
                    $link = route('users.show', $quote->publisher->slug);
                    break;

                case App\Models\Source::UNKNOWN_AUTHOR_KEY:
                    $image = 'img/sources/' . ($quote->source_image ?? App\Models\Source::UNKNOWN_AUTHOR_DEFAULT_IMAGE);
                    $title = 'Муаллифи номаълум';
                    $link = null;
                    break;

                case App\Models\Source::FROM_BOOK_KEY:
                    $image = 'img/sources/' . ($quote->source_image ?? App\Models\Source::FROM_BOOK_DEFAULT_IMAGE);
                    $title = $quote->sourceBook->title;
                    $link = null;
                    break;

                case App\Models\Source::FROM_MOVIE_KEY:
                    $image = 'img/sources/' . ($quote->source_image ?? App\Models\Source::FROM_MOVIE_DEFAULT_IMAEG);
                    $title = $quote->sourceMovie->title;
                    $link = null;
                    break;

                case App\Models\Source::FROM_SONG_KEY:
                    $image = 'img/sources/' . ($quote->source_image ?? App\Models\Source::FROM_SONG_DEFAULT_IMAGE);
                    $title = $quote->sourceSong->title;
                    $link = null;
                    break;

                case App\Models\Source::FROM_PROVERB_KEY:
                    $image = 'img/sources/' . ($quote->source_image ?? App\Models\Source::FROM_PROVERB_DEFAULT_IMAGE);
                    $title = 'Зарбулмасал/гуфтор';
                    $link = null;
                    break;

                case App\Models\Source::FROM_PARABLE_KEY:
                    $image = 'img/sources/' . ($quote->source_image ?? App\Models\Source::FROM_PARABLE_DEFAULT_IMAGE);
                    $title = 'Масал';
                    $link = null;
                    break;
            }
        @endphp

        {{-- Card Header start --}}
        <div class="card__header">
            <h3 class="card__title card__title--mobile">
                @if($link)
                    <a class="card__title-link" href="{{ $link }}">
                        @if($routeName == 'search' && $quote->source->key)
                            {!! App\Helpers\Helper::highlightKeyword($keyword, $title) !!}
                        @else
                            {{ $title }}
                        @endif
                    </a>
                @else
                    {{ $title }}
                @endif
            </h3>

            <img class="card__image card__image--small" src="{{ asset($image) }}" alt="{{ $title }}">

            <div class="card__header-text">
                <h3 class="card__title">
                    @if($link)
                        <a class="card__title-link" href="{{ $link }}">
                            @if($routeName == 'search' && $quote->source->key)
                                {!! App\Helpers\Helper::highlightKeyword($keyword, $title) !!}
                            @else
                                {{ $title }}
                            @endif
                        </a>
                    @else
                        {{ $title }}
                    @endif
                </h3>

                <ul class="card__categories">
                    @foreach ($quote->categories as $category)
                        <li class="card__categories-item">
                            <a class="card__categories-link" href="{{ route('quotes.index') }}?category_id={{ $category->id }}">{{ $category->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div> {{-- Card Header end --}}

        {{-- Card Body start --}}
        <div class="card__body">
            <img class="card__image card__image--medium" src="{{ asset($image) }}" alt="{{ $title }}">

            <div class="card__body-text-container">
                <p class="card__body-text">{!! $routeName == 'search' ? App\Helpers\Helper::highlightKeyword($keyword, $quote->body) : $quote->body !!}</p>
                {{-- <a class="button button--secondary card__body-link" href="#">Муфассал</a> --}}
            </div>

            @if($showEditButton)
                <a class="card__edit-btn button button--transparent" href="{{ route('users.quotes.edit', $quote->id) }}">
                    <span class="material-icons-outlined card__edit-btn-icon">edit</span> Вироиш
                </a>
            @endif
        </div> {{-- Card Body end --}}

        {{-- Card Footer start --}}
        <div class="card__footer">
            <div class="card__actions">
                @guest
                    <button class="card__actions-button card__actions-favorite" data-action="show-modal" data-target-id="login-modal">
                        <span class="material-icons-outlined card__actions-favorite-icon">bookmarks</span> Гулчинҳо
                    </button>

                    <button class="card__actions-button card__actions-share">
                        <div class="ya-share2" data-copy="last" data-curtain data-limit="0" data-more-button-type="long"
                            data-services="vkontakte,facebook,telegram,twitter,viber,whatsapp,skype" data-title="{{ App\Helpers\Helper::generateShareText($title . ': “' . $quote->body) . '”'}}" data-image="{{ asset('img/main/logo-share.png') }}" data-url="{{ route('quotes.index') }}">
                        </div>
                    </button>

                    <button class="card__actions-button card__actions-like" data-action="show-modal" data-target-id="login-modal">
                        <span class="material-icons-outlined card__actions-like-icon">favorite_border</span>
                        Писандидан: <span class="card__actions-like-counter">{{ $quote->likes->count() }}</span>
                    </button>
                @endguest

                @auth
                    <button class="card__actions-button card__actions-favorite" data-action="favorite" data-quote-id="{{ $quote->id }}">
                        @php
                            $favorited = App\Models\Favorite::where('user_id', auth()->user()->id)->where('quote_id', $quote->id)->first();
                        @endphp

                        <span class="material-icons{{ $favorited ? '' : '-outlined' }} card__actions-favorite-icon">bookmarks</span> Гулчинҳо
                    </button>

                    <button class="card__actions-button card__actions-share">
                        <div class="ya-share2" data-copy="last" data-curtain data-limit="0" data-more-button-type="long"
                            data-services="vkontakte,facebook,telegram,twitter,viber,whatsapp,skype" data-title="{{ App\Helpers\Helper::generateShareText($title . ': “' . $quote->body) . '”'}}" data-image="{{ asset('img/main/logo-share.png') }}" data-url="{{ route('quotes.index') }}">
                        </div>
                    </button>

                    <button class="card__actions-button card__actions-like" data-action="like" data-quote-id="{{ $quote->id }}">
                        @php
                            $liked = App\Models\Like::where('user_id', auth()->user()->id)->where('quote_id', $quote->id)->first();
                        @endphp

                        <span class="material-icons-outlined card__actions-like-icon">{{ $liked ? 'favorite' : 'favorite_border' }}</span>
                        Писандидан: <span class="card__actions-like-counter">{{ $quote->likes->count() }}</span>
                    </button>
                @endauth
            </div>

            <div class="card__publication">
                @php
                    $formatted = Carbon\Carbon::create($quote->created_at);
                @endphp

                <p class="card__publication-date">{{ $formatted->isoFormat("DD.MM.YYYY HH:mm") }}</p>
                <p class="card__publication-text">Мунташиршуда:</p>
                <a class="card__publication-user" href="{{ route('users.show', $quote->publisher->slug) }}"><span class="material-icons">person</span> {{ $quote->publisher->name }}</a>
                {{-- <a class="card__publication-chat" href="#"><span class="material-icons-outlined">message</span> Написать</a> --}}
            </div>

            @auth
                <button class="report-bug-button" data-action="show-report-bug-modal" data-quote-id="{{ $quote->id }}">
                    <span class="material-icons-outlined report-bug-button__icon">error_outline</span>
                </button>
            @endauth
        </div> {{-- Card Footer end --}}

    </div>  {{-- Card Inner end --}}
</div>
