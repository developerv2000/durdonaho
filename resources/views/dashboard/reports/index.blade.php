@extends('dashboard.layouts.app')
@section("main")

{{-- Table form start --}}
<form action="{{ route($modelShortcut . '.destroy') }}" method="POST" class="table-form" id="table-form">
    @csrf
    {{-- Table start --}}
    <table class="main-table" cellpadding = "8" cellspacing = "10">
        {{-- Table Head start --}}
        <thead>
            <tr>
                {{-- Empty space for checkbox --}}
                <th width="20"></th>

                @php $reversedOrderType = App\Helpers\Helper::reverseOrderType($orderType); @endphp

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'user_name' ? 'active' : '' }}" href="{{ route($modelShortcut . '.dashboard.index') }}?page={{ $activePage }}&orderBy=user_name&orderType={{ $reversedOrderType }}">Пользователь</a>
                </th>

                <th>
                    Текст
                </th>

                <th>
                    Объект
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'new' ? 'active' : '' }}" href="{{ route($modelShortcut . '.dashboard.index') }}?page={{ $activePage }}&orderBy=new&orderType={{ $reversedOrderType }}">Статус</a>
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'created_at' ? 'active' : '' }}" href="{{ route($modelShortcut . '.dashboard.index') }}?page={{ $activePage }}&orderBy=created_at&orderType={{ $reversedOrderType }}">Дата подачи</a>
                </th>

                <th width="120">
                    Действие
                </th>
            </tr>
        </thead>  {{-- Table Head end --}}

        {{-- Table Body start --}}
        <tbody>
            @foreach ($items as $item)
                <tr>
                    {{-- Checkbox for multidelete --}}
                    <td>
                        <div class="checkbox">
                            <label for="item{{$item->id}}">
                                <input id="item{{$item->id}}" type="checkbox" name="id[]" value="{{$item->id}}">
                                <span></span>
                            </label>
                        </div>
                    </td>

                    <td>{{ $item->user_name }}</td>
                    <td>{{ mb_strlen($item->message) > 200 ? (mb_substr($item->message, 0, 200) . '...') : $item->message }}</td>
                    <td>{{ $item->author ? $item->author->name : 'Цитата #' . $item->quote->id }}</td>
                    <td>{!! $item->new ? '<span class="highlight">НОВЫЙ</span>' : 'Просмотрено' !!}</td>
                    <td>{{ Carbon\Carbon::create($item->created_at)->locale('ru')->isoFormat('DD MMMM YYYY HH:mm') }}</td>

                    {{-- Actions --}}
                    <td>
                        <div class="table__actions">
                            <a class="button--secondary" href="{{ route($modelShortcut . '.edit', $item->id) }}" 
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Редактировать">
                                <span class="material-icons">edit</span>
                            </a>

                            <button class="button--danger" type="button" data-action="show-single-item-destroy-modal" data-item-id="{{ $item->id }}"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Удалить">
                                <span class="material-icons">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>  {{-- Table Body end --}}
    </table>  {{-- Table end --}}

    {{ $items->links('dashboard.layouts.pagination') }}
</form>  {{-- Table form end --}}

@include('dashboard.modals.single-item-destroy')
@include('dashboard.modals.multiple-items-destroy')

@endsection