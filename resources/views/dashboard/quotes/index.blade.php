@extends('dashboard.layouts.app')
@section("main")

@if($activePage == 1)
    <div class="alert alert-warning">
        <span class="material-icons alert-icon">info</span>
        Список всех цитат, которые прошли проверку администратора и отображаются на сайте
    </div>
@endif

@include('dashboard.layouts.search')

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

                <th width="460">
                    Текст
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'source_id' ? 'active' : '' }}" href="{{ route('dashboard.index') }}?page={{ $activePage }}&orderBy=source_id&orderType={{ $reversedOrderType }}">Источник</a>
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'category_titles' ? 'active' : '' }}" href="{{ route('dashboard.index') }}?page={{ $activePage }}&orderBy=category_titles&orderType={{ $reversedOrderType }}">Категории</a>
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'updated_at' ? 'active' : '' }}" href="{{ route('dashboard.index') }}?page={{ $activePage }}&orderBy=updated_at&orderType={{ $reversedOrderType }}">Дата посл/измен</a>
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

                    <td>{{ mb_strlen($item->body) > 200 ? (mb_substr($item->body, 0, 200) . '...') : $item->body }}</td>
                    <td>{{ $item->source->title }}</td>

                    <td>
                        @if($orderBy == 'category_titles')
                            {!! str_replace(',', '<br>', $item->category_titles) !!}
                        @else
                            @foreach ($item->categories as $category)
                                {{ $category->title }}
                                @if(!$loop->last)
                                    <br>
                                @endif
                            @endforeach
                        @endif
                    </td>

                    <td>{{ Carbon\Carbon::create($item->updated_at)->locale('ru')->isoFormat('DD MMMM YYYY HH:mm') }}</td>

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