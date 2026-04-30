  <div class="top">

      <div class="cart-box">
          <i class="bi bi-cart3"></i>
          <span class="cart-count" id="cartCount">{{ (int) ($cartCount ?? 0) }}</span>
      </div>
  </div>
<style>
       .top {
        position: sticky;
        top: 0;
        background: white;
        z-index: 100;
    }
    .cart-box {
    position: relative;
    top: -68px;
    color: var(--primary);
    font-size: 30px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}
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