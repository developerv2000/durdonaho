@props(['quote', 'author'])

<div class="theme-styled-block aside-popularity aside-popular-quote mobile-hidden">
    <h2 class="aside-popularity__title main-title">Андарзҳои маъмул</h2>

    <x-card-quote :quote="$quote" class="card--vertical" />
</div>

<div class="theme-styled-block aside-popularity aside-popular-author mobile-hidden">
    <h2 class="aside-popularity__title main-title">Муаллифони машҳур</h2>

    <x-card-author :author="$author" class="card--vertical" />
</div>
