@extends('dashboard.layouts.app')
@section("main")

{{-- Novelty created by user warning --}}
@unless ($errors->any())
    @if($item->categories()->unapproved()->count() || ($item->author_id && !$item->author->approved))
    <div class="alert alert-warning alert--columned">
        <h3 class="alert-title"><span class="material-icons alert-icon">warning</span>
            Пользователь добавил нового автора или новую категорию! Новый автор или новые категории автоматический будут одобрены и опубликованы, после одобрение цитаты!
        </h3>

        <ul class="alert-list">
            @if($item->categories()->unapproved()->count())
                <li>
                    Новые категории добавленные пользователем:
                    @foreach ($item->categories()->unapproved()->get() as $category)
                        <a href="{{ route('categories.edit', $category->id) }}" target="_blank">{{ $category->title }}</a>
                    @endforeach
                </li>
            @endif

            @if($item->author_id && !$item->author->approved)
                <li>
                    Новый автор добавленный пользователем:
                    <a href="{{ route('authors.edit', $item->author->id) }}" target="_blank">{{ $item->author->name }}</a>
                </li>
            @endif
        </ul>
    </div>
    @endif
@endunless
{{-- Novelty created by user warning end --}}

<form action="{{ route('quotes.approve') }}" method="POST" class="form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">

    <div class="form-group">
        <label class="required">Текст</label>
        <textarea class="form-textarea" name="body" rows="7" required>{{ old('body', $item->body) }}</textarea>
    </div>

    @php $activeSource = old('source_key', $item->source->key); @endphp
    {{-- Validating additional Source Inputs via JS (visibility and required statements) --}}
    <script>let activeSource = '{{ $activeSource }}';</script>

    <div class="form-group">
        <label class="required">Источник цитаты</label>

        <select class="source-selectize" name="source_key">
            @foreach ($sources as $source)
                <option value="{{ $source->key }}" @selected($source->key == $activeSource)>{{ $source->title }}</option>
            @endforeach
        </select>
    </div>

    {{-- Additional Source Inputs --}}
    <div class="form-group" data-source-key="book">
        <label class="required">Автор книги</label>
        <input type="text" class="form-input" name="book_title" value="{{ $item->sourceBook ? $item->sourceBook->author : '' }}" />
    </div>

    <div class="form-group" data-source-key="book">
        <label class="required">Название книги</label>
        <input type="text" class="form-input" name="book_author" value="{{ $item->sourceBook ? $item->sourceBook->title : '' }}" />
    </div>

    <div class="form-group" data-source-key="movie">
        <label class="required">Название фильма</label>
        <input type="text" class="form-input" name="movie_title" value="{{ $item->sourceMovie ? $item->sourceMovie->title : '' }}" />
    </div>

    <div class="form-group" data-source-key="movie">
        <label class="required">Год выпуска</label>
        <input type="text" class="form-input" name="movie_year" value="{{ $item->sourceMovie ? $item->sourceMovie->year : '' }}" />
    </div>

    <div class="form-group" data-source-key="song">
        <label class="required">Название песни</label>
        <input type="text" class="form-input" name="song_title" value="{{ $item->sourceSong ? $item->sourceSong->title : '' }}" />
    </div>

    <div class="form-group" data-source-key="song">
        <label class="required">Исполнитель</label>
        <input type="text" class="form-input" name="song_singer" value="{{ $item->sourceSong ? $item->sourceSong->singer : '' }}" />
    </div>

    <div class="form-group" data-source-key="author">
        <label class="required">Выберите автора цитаты</label>

        <select class="selectize-singular" name="author_id">
            @if($item->author && !$item->author->approved)
                <option value="{{ $item->author->id }}" selected>{{ $item->author->name }}</option>
            @endif

            @foreach ($authors as $author)
                <option value="{{ $author->id }}" @if($item->author_id == $author->id) selected @endif>{{ $author->name }}</option>
            @endforeach
        </select>
    </div>
    {{-- /end Additional Source Inputs --}}

    <div class="form-group">
        <label>Изображение источника. Отображается вместо изображение цитаты, если источником цитаты не является известный автор. (Необъязательное поле)</label>
        <input class="form-input" name="source_image" type="file" accept=".png, .jpg, .jpeg"
        data-action="show-image-from-local" data-target="local-image">

        <img class="form-image" src="{{ asset('img/sources/' . $item->source_image) }}" id="local-image">
    </div>

    <div class="form-group">
        <label class="required">Издатель</label>
        <select class="selectize-singular" name="user_id" required>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected($user->id == $item->user_id)>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label class="required">Категории</label>

        <select class="selectize-multiple" name="categories[]" multiple="multiple" required>
            {{-- Unapproved categories --}}
            @foreach ($item->categories as $category)
                @if (!$category->approved)
                    <option value="{{ $category->id }}" selected>{{ $category->title }}</option>
                @endif
            @endforeach

            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    @foreach ($item->categories as $itemCat)
                        @selected($category->id == $itemCat->id)
                    @endforeach
                    >{{ $category->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label class="required">Добавить в популярные цитаты?</label>
        <select class="selectize-singular" name="popular" required>
            <option value="0" @selected(!$item->popular)>Нет</option>
            <option value="1" @selected($item->popular)>Да</option>
        </select>
    </div>

    <div class="form__actions">
        <button class="button button--success" type="submit">
            <span class="material-icons">done_all</span> Одобрить и опубликовать
        </button>
    </div>

</form>

@endsection