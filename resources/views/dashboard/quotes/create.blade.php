@extends('dashboard.layouts.app')
@section("main")

<form action="{{ route($modelShortcut . '.store') }}" method="POST" class="form" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label class="required">Текст</label>
        <textarea class="form-textarea" name="body" rows="7" required>{{ old('body') }}</textarea>
    </div>

    @php $activeSource = old('source_key', App\Models\Source::AUTHORS_QUOTE_KEY); @endphp
    {{-- Validating additional Source Inputs via JS (visibility and required statements) --}}
    <script>let activeSource = '{{ $activeSource }}';</script>

    <div class="form-group">
        <label class="required">Источник цитаты</label>

        <select class="source-selectize" name="source_key" required>
            @foreach ($sources as $source)
                <option value="{{ $source->key }}" @selected($source->key == $activeSource)>{{ $source->title }}</option>
            @endforeach
        </select>
    </div>

    {{-- Additional Source Inputs --}}
    <div class="form-group" data-source-key="book">
        <label class="required">Автор книги</label>
        <input type="text" class="form-input" name="book_title" />
    </div>

    <div class="form-group" data-source-key="book">
        <label class="required">Название книги</label>
        <input type="text" class="form-input" name="book_author" />
    </div>

    <div class="form-group" data-source-key="movie">
        <label class="required">Название фильма</label>
        <input type="text" class="form-input" name="movie_title" />
    </div>

    <div class="form-group" data-source-key="movie">
        <label class="required">Год выпуска</label>
        <input type="text" class="form-input" name="movie_year" />
    </div>

    <div class="form-group" data-source-key="song">
        <label class="required">Название песни</label>
        <input type="text" class="form-input" name="song_title" />
    </div>

    <div class="form-group" data-source-key="song">
        <label class="required">Исполнитель</label>
        <input type="text" class="form-input" name="song_singer" />
    </div>

    <div class="form-group" data-source-key="author">
        <label class="required">Выберите автора цитаты</label>

        <select class="selectize-singular" name="author_id">
            @foreach ($authors as $author)
                <option value="{{ $author->id }}">{{ $author->name }}</option>
            @endforeach
        </select>
    </div>
    {{-- /end Additional Source Inputs --}}

    <div class="form-group">
        <label>Изображение источника. Отображается вместо изображение цитаты, если источником цитаты не является известный автор. (Необъязательное поле)</label>
        <input class="form-input" name="source_image" type="file" accept=".png, .jpg, .jpeg"
        data-action="show-image-from-local" data-target="local-image">

        <img class="form-image" src="{{ asset('img/dashboard/default-image.png') }}" id="local-image">
    </div>

    <div class="form-group">
        <label class="required">Издатель</label>
        <select class="selectize-singular" name="user_id" required>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label class="required">Категории</label>
        <select class="selectize-multiple" name="categories[]" multiple="multiple" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label class="required">Добавить в популярные цитаты?</label>
        <select class="selectize-singular" name="popular" required>
            <option value="0">Нет</option>
            <option value="1">Да</option>
        </select>
    </div>

    <div class="form__actions">
        <button class="button button--success" type="submit">
            <span class="material-icons">done_all</span> Добавить
        </button>
    </div>

</form>

@endsection