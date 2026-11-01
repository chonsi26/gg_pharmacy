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
  $inStock   = $product->isInStock();   // false when no active, unexpired stock
  // Unique per rendered card (not just per product) in case the same
  // product appears in more than one section on the same page.
  $pcQtyId   = 'pcQty-' . $product->id . '-' . uniqid();
@endphp

{{--
  Scoped once-per-page so including this partial many times (once per
  product card) doesn't repeat the <style> block. Falls back to sane
  colors so the card still looks right even on a page that doesn't
  define --red / --border / --dark-red itself.
--}}
@once
<style>
  .pc-cart-row { display: flex; align-items: center; gap: 6px; margin-top: 8px; }
  .pc-qty { display: flex; align-items: center; border: 1px solid var(--border, #e0e0e0); border-radius: 6px; overflow: hidden; flex-shrink: 0; }
  .pc-qty button { width: 26px; height: 30px; border: none; background: #f5f5f5; font-size: 14px; font-weight: 800; cursor: pointer; color: var(--text, #222); }
  .pc-qty button:hover { background: #eee; }
  .pc-qty input { width: 30px; height: 30px; border: none; border-left: 1px solid var(--border, #e0e0e0); border-right: 1px solid var(--border, #e0e0e0); text-align: center; font-size: 12px; font-weight: 700; }
  .pc-add-btn { flex: 1; background: var(--red, #C01A1A); color: #fff; border: none; border-radius: 6px; height: 30px; font-family: inherit; font-weight: 800; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px; transition: background 0.15s; }
  .pc-add-btn:hover { background: var(--dark-red, #8B1010); }
  .pc-add-btn.is-loading { opacity: 0.7; cursor: wait; }
  .product-card.is-oos .product-img img { opacity: 0.45; filter: grayscale(1); }
  .pc-oos-label { position: absolute; left: 50%; bottom: 10px; transform: translateX(-50%); background: rgba(34,34,34,0.88); color: #fff; font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 800; letter-spacing: .3px; padding: 4px 10px; border-radius: 4px; white-space: nowrap; text-transform: uppercase; }
  .pc-add-btn:disabled { background: #bdbdbd; cursor: not-allowed; }
</style>
@endonce

<a href="{{ route('product.show', $product) }}" class="product-card {{ $inStock ? '' : 'is-oos' }}" style="text-decoration:none;color:inherit;display:block;">
  <div class="product-img"@if($imgHeight) style="height:{{ $imgHeight }};"@endif>
    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">

    @unless($inStock)
      <div class="pc-oos-label">Out of Stock</div>
    @endunless

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
      @if($inStock)
      <div class="pc-cart-row">
        <div class="pc-qty">
          <button type="button" class="js-qty-btn" data-qty-target="#{{ $pcQtyId }}" data-qty-step="minus" aria-label="Decrease quantity">−</button>
          <input type="number" id="{{ $pcQtyId }}" value="1" min="1" max="99" readonly>
          <button type="button" class="js-qty-btn" data-qty-target="#{{ $pcQtyId }}" data-qty-step="plus" aria-label="Increase quantity">+</button>
        </div>
        <button
          type="button"
          class="pc-add-btn js-add-to-cart"
          data-product-id="{{ $product->id }}"
          data-qty-target="#{{ $pcQtyId }}"
        ><i class="fas fa-cart-plus"></i> Add</button>
      </div>
      @else
      {{-- No js-add-to-cart class, so cart.js never binds to it. --}}
      <div class="pc-cart-row">
        <button type="button" class="pc-add-btn" disabled><i class="fas fa-ban"></i> Out of Stock</button>
      </div>
      @endif
    @endif
  </div>
</a>