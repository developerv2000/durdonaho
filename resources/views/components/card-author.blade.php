{{--
    This component is used everywhere as a Card for all types of author cards

    $class ->                   Additional classes: card_with_medium_image || card--vertical || card--full_width etc
    $routeName ->               Used only to determine if it is search page
    $keyword ->                 Keyword for search page
    data-card-id ->             Used in JS (liking/favoriting same cards)
--}}

@props(['author', 'class' => '', 'routeName' => request()->route()->getName(), 'keyword' => request()->keyword])

<div class="{{ $class }} card" data-card-id="author{{ $author->id }}">
    <div class="card__inner">

        {{-- Card Header start --}}
        <div class="card__header">
            <img class="card__image card__image--small" src="{{ asset('img/authors/' . $author->image) }}" alt="{{ $author->name }}">

            <div class="card__header-text">
                <a class="card__title" href="{{ route('authors.show', $author->slug) }}">
                    {!! $routeName == 'search' ? App\Helpers\Helper::highlightKeyword($keyword, $author->name) : $author->name !!}
                </a>

                <ul class="card__categories">
                    {{-- Generate collection of unique categories --}}
                    @php
                        $quotes = $author->quotes;
                        $categories = collect();

                        foreach($quotes as $quote) {
                            foreach($quote->categories as $category) {
                                $categories->push($category);
                            }
                        }
                    @endphp

                    @foreach ($categories->unique('title')->shuffle()->take(3) as $category)
                        <li class="card__categories-item">
                            <a class="card__categories-link" href="{{ route('quotes.index') }}?category_id={{ $category->id }}">{{ $category->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>  {{-- Card Header end --}}

        {{-- Card Body start --}}
        <div class="card__body">
            <img class="card__image card__image--medium" src="{{ asset('img/authors/' . $author->image) }}" alt="{{ $author->name }}">

            <div class="card__body-text-container">
                <p class="card__body-text">{!! $routeName == 'search' ? App\Helpers\Helper::highlightKeyword($keyword, $author->biography) : $author->biography !!}</p>
                <a class="button button--transparent card__body-link" href="{{ route('authors.show', $author->slug) }}">
                    Муфассал <span class="material-icons-outlined">chevron_right</span>
                </a>
            </div>
        </div>  {{-- Card Body end --}}

        {{-- Card Footer start --}}
        <div class="card__footer">
            <div class="card__actions">
                @guest
                    <button class="card__actions-button card__actions-favorite" data-action="show-modal" data-target-id="login-modal">
                        <span class="material-icons-outlined card__actions-favorite-icon">bookmarks</span> Гулчинҳо
                    </button>

                    <button class="card__actions-button card__actions-share">
                        <div class="ya-share2" data-copy="last" data-curtain data-limit="0" data-more-button-type="long"
                            data-services="vkontakte,facebook,telegram,twitter,viber,whatsapp,skype" data-url="{{ route('authors.show', $author->slug) }}">
                        </div>
                    </button>

                    <button class="card__actions-button card__actions-like" data-action="show-modal" data-target-id="login-modal">
                        <span class="material-icons-outlined card__actions-like-icon">favorite_border</span>
                        Писандидан: <span class="card__actions-like-counter">{{ $author->likes->count() }}</span>
                    </button>
                @endguest

                @auth
                    <button class="card__actions-button card__actions-favorite" data-action="favorite" data-author-id="{{ $author->id }}">
                        @php
                            $favorited = App\Models\Favorite::where('user_id', auth()->user()->id)->where('author_id', $author->id)->first();
                        @endphp

                        <span class="material-icons{{ $favorited ? '' : '-outlined' }} card__actions-favorite-icon">bookmarks</span> Гулчинҳо
                    </button>

                    <button class="card__actions-button card__actions-share">
                        <div class="ya-share2" data-copy="last" data-curtain data-limit="0" data-more-button-type="long"
                            data-services="vkontakte,facebook,telegram,twitter,viber,whatsapp,skype" data-url="{{ route('authors.show', $author->slug) }}">
                        </div>
                    </button>

                    <button class="card__actions-button card__actions-like" data-action="like" data-author-id="{{ $author->id }}">
                        @php
                            $liked = App\Models\Like::where('user_id', auth()->user()->id)->where('author_id', $author->id)->first();
                        @endphp
                        <span class="material-icons-outlined card__actions-like-icon">{{ $liked ? 'favorite' : 'favorite_border' }}</span>
                        Писандидан: <span class="card__actions-like-counter">{{ $author->likes->count() }}</span>
                    </button>
                @endauth
            </div>

            <div class="card__publication">
                @php
                    $formatted = Carbon\Carbon::create($author->created_at);
                @endphp

                <p class="card__publication-date">{{ $formatted->isoFormat("DD.MM.YYYY HH:mm") }}</p>
                <p class="card__publication-text">Мунташиршуда:</p>
                <a class="card__publication-user" href="{{ route('users.show', $author->publisher->slug) }}"><span class="material-icons">person</span> {{ $author->publisher->name }}</a>
                {{-- <a class="card__footer-chat" href="#"><span class="material-icons-outlined">message</span> Написать</a> --}}
            </div>

            @auth
                <button class="report-bug-button" data-action="show-report-bug-modal" data-author-id="{{ $author->id }}">
                    <span class="material-icons-outlined report-bug-button__icon">error_outline</span>
                </button>
            @endauth
        </div>  {{-- Card Footer end --}}

    </div>  {{-- Card Inner end --}}
</div>
