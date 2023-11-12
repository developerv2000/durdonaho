@extends('dashboard.layouts.app')
@section("main")

<form action="{{ route($modelShortcut . '.store') }}" method="POST" class="form" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label class="required">Заголовок</label>
        <input type="text" class="form-input" name="title" value="{{ old('title') }}" required />
    </div>

    <div class="form-group">
        <label class="required">Добавить в популярные категории?</label>
        <select class="selectize-singular" name="popular" required>
            <option value="0">Нет</option>
            <option value="1">Да</option>
        </select>
    </div>

    <div class="form-group">
        <label>Изображение. !!! Изображение объязательное поле для популярных категорий !!!</label>
        <input class="form-input" name="image" type="file" accept=".png, .jpg, .jpeg"
        data-action="show-image-from-local" data-target="local-image">

        <img class="form-image" src="{{ asset('img/dashboard/default-image.png') }}" id="local-image">
    </div>

    <div class="form__actions">
        <button class="button button--success" type="submit">
            <span class="material-icons">done_all</span> Добавить
        </button>
    </div>

</form>

@endsection