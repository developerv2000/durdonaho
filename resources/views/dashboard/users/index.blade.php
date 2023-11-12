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

                <th width="100">
                    Аватар
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'name' ? 'active' : '' }}" href="{{ route($modelShortcut . '.dashboard.index') }}?page={{ $activePage }}&orderBy=name&orderType={{ $reversedOrderType }}">Имя</a>
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'email' ? 'active' : '' }}" href="{{ route($modelShortcut . '.dashboard.index') }}?page={{ $activePage }}&orderBy=email&orderType={{ $reversedOrderType }}">Почта</a>
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'verified_email' ? 'active' : '' }}" href="{{ route($modelShortcut . '.dashboard.index') }}?page={{ $activePage }}&orderBy=verified_email&orderType={{ $reversedOrderType }}">Подт/почту</a>
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'quotes_count' ? 'active' : '' }}" href="{{ route($modelShortcut . '.dashboard.index') }}?page={{ $activePage }}&orderBy=quotes_count&orderType={{ $reversedOrderType }}">Кол. опуб/цитат</a>
                </th>

                <th>
                    <a class="{{ $orderType }} {{ $orderBy == 'created_at' ? 'active' : '' }}" href="{{ route($modelShortcut . '.dashboard.index') }}?page={{ $activePage }}&orderBy=created_at&orderType={{ $reversedOrderType }}">Дата рег.</a>
                </th>

                <th width="80">
                    Действие
                </th>
            </tr>
        </thead>  {{-- Table Head end --}}

        {{-- Table Body start --}}
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td><img src="{{ asset('img/users/' . $item->image) }}"></td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->verified_email ? 'Да' : 'Нет' }}</td>
                    <td>{{ $item->quotes_count }}</td>
                    <td>{{ Carbon\Carbon::create($item->created_at)->locale('ru')->isoFormat('DD MMMM YYYY HH:mm') }}</td>

                    {{-- Actions --}}
                    <td>
                        <div class="table__actions">
                            <a class="button--main" href="{{ route('users.show', $item->slug) }}" target="_blank"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Посмотреть">
                                <span class="material-icons">visibility</span>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>  {{-- Table Body end --}}
    </table>  {{-- Table end --}}

    {{ $items->links('dashboard.layouts.pagination') }}
</form>  {{-- Table form end --}}

@endsection