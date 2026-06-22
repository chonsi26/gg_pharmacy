{{--
  Partial: partials/product-card.blade.php
  Variables:
    $product   — App\Models\Product  (with category & brand eager-loaded)
    $showQty   — bool   show qty + add-to-cart row  (default: false)
    $imgHeight — string css height override e.g. '150px' (default: null)
--}}
@php
  $showQty   = $showQty   ?? false;
  $imgHeight = $imgHeight ?? null;
@endphp

<div class="product-card">
  <div class="product-img"@if($imgHeight) style="height:{{ $imgHeight }};"@endif>
    <img src="{{ $product->image }}" alt="{{ $product->name }}">

    @if($product->isMostSold())
      <div class="most-sold">{{ $product->badge }}</div>
    @elseif($product->badge)
      <div class="badge {{ $product->isSaleBadge() ? 'sale-badge' : '' }}">{{ $product->badge }}</div>
    @endif
  </div>

  <div class="product-info">
    {{-- Category name loaded via BelongsTo relationship --}}
    @if($product->category)
      <div class="product-cat">{{ $product->category->name }}</div>
    @endif

    <div class="product-name">{{ $product->name }}</div>

    <div class="product-price">
      @if($product->old_price)
        <span class="old-price">{{ $product->formattedOldPrice() }}</span>
      @endif
      {{ $product->formattedPrice() }}
    </div>

    @if($showQty)

    @endif
  </div>
</div>
