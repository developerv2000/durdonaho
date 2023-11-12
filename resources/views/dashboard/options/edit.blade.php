@extends('dashboard.layouts.app')
@section("main")

<form action="{{ route($modelShortcut . '.update') }}" method="POST" class="form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">

    <div class="form-group">
        <label class="required">Текст</label>
        <textarea class="{{ $item->wysiwyg ? 'simditor-wysiwyg' : 'form-textarea' }}" name="value" rows="7" required>{{ $item->value }}</textarea>
    </div>

    <div class="form__actions">
        <button class="button button--success" type="submit">
            <span class="material-icons">done_all</span> Обновить
        </button>
    </div>

</form>

@endsection