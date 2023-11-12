@extends('dashboard.layouts.app')
@section("main")

@if(!$errors->any() && !$item->approved)
    <div class="alert alert-warning">
        <span class="material-icons alert-icon">warning</span>
        Автор был добавлен со стороны пользователя. Вам необходимо проверить данные и одобрить автора, для того чтобы он отображался на сайте!
    </div>
@endif

<form action="{{ route($modelShortcut . '.update') }}" method="POST" class="form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">

    <div class="form-group">
        <label class="required">Имя</label>
        <input type="text" class="form-input" name="name" value="{{ old('name') ?? $item->name }}" required />
    </div>

    <div class="form-group">
        <label class="required">Биография</label>
        <textarea class="form-textarea" name="biography" rows="7" required>{{ old('biography') ?? $item->biography }}</textarea>
    </div>

    <div class="form-group">
        <label>Изображение</label>
        <input class="form-input" name="image" type="file" accept=".png, .jpg, .jpeg"
        data-action="show-image-from-local" data-target="local-image">

        <img class="form-image" src="{{ asset('img/authors/' . $item->image) }}" id="local-image">
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
        <label class="required">Добавить в популярные авторы?</label>
        <select class="selectize-singular" name="popular" required>
            <option value="0" @selected(!$item->popular)>Нет</option>
            <option value="1" @selected($item->popular)>Да</option>
        </select>
    </div>

    <div class="form__actions">
        <button class="button button--success" type="submit">
            <span class="material-icons">done_all</span> Обновить @unless($item->approved) и одобрить @endunless
        </button>

        <button class="button button--danger" type="button" data-bs-toggle="modal" data-bs-target="#destroy-single-item-modal">
            <span class="material-icons">remove_circle</span> Удалить
        </button>
    </div>

</form>

@include('dashboard.modals.single-item-destroy', ['destroyItemId' => $item->id ])

@endsection