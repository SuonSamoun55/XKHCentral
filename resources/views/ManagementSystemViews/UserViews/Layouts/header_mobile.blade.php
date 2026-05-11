
<div class="mobile">
<header class="cart-boxM">
    <div class="logo-wrap">
        <img src="{{ asset('images/pos/logo.png') }}" alt="Logo" class="logo">
    </div>
        {{-- <a href="{{ route('') }}" class="cart-btn"> --}}
                <a href="{{ route('user.pos.cart') }}" class="cart">
            <img src="{{ asset('images/pos/Button - Square.png') }}" alt="Cart" class="cart-icon">
          <span class="cart-count" id="cartCount">{{ (int) ($cartCount ?? 0) }}</span>
        </a>
</header>
</div>
<style>

.mobile{
       position: sticky;   /* 🔑 makes it stick */
    top: 0;             /* stick to top */
    z-index: 1000;      /* stay above content */
    background: #f7fbff;
}
.cart-boxM {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding-bottom: 16px;
    padding-top: 6px;
    padding-left: 6px;
    padding-right: 6px;
}

    /* Logo */

.logo-wrap {
    padding: 8px;
}

.logo {
    height: 20px;
    width: auto;
    display: block;
}

    /* Cart button container */
    .cart{
        position: relative;
        width: 46px;
        height: 46px;
    
    }
    .cart-count{
    
    position: absolute;
    top: -6px !important;
    right: -12px;
    background: var(--primary);
    color: #fff;
    border-radius: 999px;
    min-width: 22px;
    height: 22px;
    padding: 0 6px;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    line-height: 1;

    }

    /* Cart icon */
    .cart-icon {
        width: 46px;
        height: 46px;
    }

    /* Badge */
    .cart-count {
       position: absolute;
    top: -4px;
    right: -12px;
    background: var(--primary);
    color: #fff;
    border-radius: 999px;
    min-width: 22px;
    height: 22px;
    padding: 0 6px;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    line-height: 1;
    }
</style>
