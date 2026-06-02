
@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\MagamentSystemModel\Company;

    $company = null;

    if (session('selected_company_id')) {
        $company = Company::find(session('selected_company_id'));
    }

    if (!$company) {
        $company = Company::first();
    }

    $companyName = $company->display_name ?? $company->name ?? 'Company';
    $companyLogoUrl = asset('images/default-company.png');

    if ($company && !empty($company->logo)) {
        if (preg_match('/^https?:\/\//i', $company->logo)) {
            $companyLogoUrl = $company->logo;
        } else {
            $companyLogoUrl = Storage::url($company->logo);
        }
    }
@endphp

<div class="mobile">
<header class="cart-boxM">

    <div class="logo-wrap">
        <img src="{{ $companyLogoUrl }}"
             alt="{{ $companyName }} Logo"
             class="logo"
             onerror="this.onerror=null;this.src='{{ asset('images/default-company.png') }}';">
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
    background: white;
}
.cart-boxM {
     position: sticky;
    top: 0;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding-bottom: 16px;
    padding-top: 10px;
    padding-left: 6px;
    padding-right: 10px;
}

    /* Logo */

.logo-wrap {
    padding: 8px;
    /* -webkit-column-width: 50px */
}

.logo {
    height: 50px;
    width: 50px;
    /* max-width: 0px; */
    display: block;
    object-fit: contain;
    border:solid 1px #ddd;
    border-radius: 8px;
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
</style>
