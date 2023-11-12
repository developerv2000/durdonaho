@extends('dashboard.layouts.app')
@section("main")

{{-- Table form start --}}
<form action="javascript:void(0)" method="POST" class="table-form" id="table-form">
    @csrf
    {{-- Table start --}}
    <table class="main-table" cellpadding = "8" cellspacing = "10">
        {{-- Table Head start --}}
        <thead>
            <tr>
                @php $reversedOrderType = App\Helpers\Helper::reverseOrderType($orderType); @endphp

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'title' ? 'active' : '' }}" href="{{ route($modelShortcut . '.dashboard.index') }}?page={{ $activePage }}&orderBy=title&orderType={{ $reversedOrderType }}">Заголовок</a>
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'quotes_count' ? 'active' : '' }}" href="{{ route($modelShortcut . '.dashboard.index') }}?page={{ $activePage }}&orderBy=quotes_count&orderType={{ $reversedOrderType }}">Количество цитат</a>
                </th>
            </tr>
        </thead>  {{-- Table Head end --}}

        {{-- Table Body start --}}
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->quotes_count }}</td>
                </tr>
            @endforeach
        </tbody>  {{-- Table Body end --}}
    </table>  {{-- Table end --}}

    {{ $items->links('dashboard.layouts.pagination') }}
</form>  {{-- Table form end --}}

@endsection