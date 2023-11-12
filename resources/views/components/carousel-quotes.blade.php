@props(['quotes'])

<div class="owl-carousel-container">
    <div class="owl-carousel card-carousel">
        @foreach ($quotes as $quote)
            <x-card-quote :quote="$quote" class="owl-carousel__item card_with_small_image" />
        @endforeach
    </div>
</div>
