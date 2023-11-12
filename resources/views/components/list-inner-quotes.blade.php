{{-- Props not used because of controllers component return error --}}

@unless(count($quotes))
    <div class="theme-styled-block empty-query-warning">
        <div class="empty-query-warning__inner">
            Ба дархости шумо ягон андарз ёфт нашуд !
        </div>
    </div>
@endunless

@if(isset($showEditButton))
    @foreach ($quotes as $quote)
        <x-card-quote :quote="$quote" class="theme-styled-block {{ $cardClass }}" show-edit-button="1" />
    @endforeach

@else
    @foreach ($quotes as $quote)
        <x-card-quote :quote="$quote" class="theme-styled-block {{ $cardClass }}" />
    @endforeach
@endif

{{ $quotes->links('layouts.pagination') }}
