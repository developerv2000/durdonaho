@props(['user', 'class' => ''])

<div class="{{ $class }} card">
    <div class="card__inner">

        <h1 class="main-title users-card__main-title">Профили корбар</h1>

        {{-- Card Header start --}}
        <div class="card__header">
            <div class="card__header-text">
                <h1 class="card__title">{{ $user->name }}</h1>
            </div>
        </div> {{-- Card Header end --}}

        <div class="card__footer">
            <div class="card__actions">
                {{-- <a class="card__actions-button" href="#"><span class="material-icons-outlined card__actions-bookmark-icon">message</span> Написать</a> --}}
                <a class="card__actions-button" href="{{ route('users.quotes', $user->slug) }}"><span class="material-icons-outlined card__actions-bookmark-icon">message</span>Посмотреть цитаты</a>
            </div>
        </div>

        {{-- Card Body start --}}
        <div class="card__body">
            <img class="card__image card__image--medium" src="{{ asset('img/users/' . $user->image) }}" alt="{{ $user->name }}">
            <div class="card__body-text-container">
                <p class="card__body-text">{{ $user->biography }}</p>
            </div>
        </div> {{-- Card Body end --}}

    </div>  {{-- Card Inner end --}}
</div>