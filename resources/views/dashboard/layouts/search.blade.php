<div class="search">
    <div class="selectize-singular-container">
        <select class="selectize-singular-linked" placeholder="Поиск...">
            <option></option>
            @foreach($allItems as $item)
                <option value="{{ route($modelShortcut . '.edit', $item->id) }}">{{ $item->title }}</option>
            @endforeach
        </select>
    </div>
</div>