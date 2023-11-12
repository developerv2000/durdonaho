@props(['authors'])

<div class="owl-carousel-container">
    <div class="owl-carousel card-carousel">
        @foreach ($authors as $author)
            <x-card-author :author="$author" class="owl-carousel__item card_with_medium_image" data-carousel-item-index="{{ $loop->index + 1 }}" />
        @endforeach
    </div>
</div>