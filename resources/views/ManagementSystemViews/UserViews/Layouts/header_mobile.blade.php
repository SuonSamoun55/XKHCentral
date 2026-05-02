

      <div class="cart-box">
          <i class="bi bi-cart3"></i>
          <span class="cart-count" id="cartCount">{{ (int) ($cartCount ?? 0) }}</span>
      </div>

<style>
       .top {
       
    }
  /* Fix cart icon to top-right */
.cart-box {
    position: fixed;
    top: 14px;
    right: 24px;
    z-index: 999;

    display: inline-flex;
    align-items: center;
    font-size: 20px;
    color: var(--primary);

}

/* Keep badge aligned */
/* .cart-box .cart-count {
    position: absolute;
    top: -6px;
    right: -6px;
    min-width: 20px;
    height: 20px;
    padding: 0 5px;
    font-size: 11px;
} */
.cart-count {
    position: absolute;
    top: -8px;
    right: -10px;
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