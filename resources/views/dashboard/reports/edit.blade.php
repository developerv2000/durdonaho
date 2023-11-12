@extends('dashboard.layouts.app')
@section("main")

<form action="javascript:void(0)" method="POST" class="form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">

    <div class="form-group">
        <label>Пожаловались на {{ $item->author ? $item->author->name : 'Цитата #' . $item->quote->id }}</label>
        <textarea class="form-textarea" rows="7" readonly>{{ $item->author ? $item->author->biography : $item->quote->body }}</textarea>
    </div>

    <div class="form-group">
        <label>Текст жалобы</label>
        <textarea class="form-textarea" rows="7" readonly>{{ $item->message }}</textarea>
    </div>

    <div class="form__actions">
        <button class="button button--danger" type="button" data-bs-toggle="modal" data-bs-target="#destroy-single-item-modal">
            <span class="material-icons">remove_circle</span> Удалить
        </button>
    </div>

</form>

@include('dashboard.modals.single-item-destroy', ['destroyItemId' => $item->id ])

@endsection