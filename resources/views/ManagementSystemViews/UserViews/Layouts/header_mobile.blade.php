
@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\ManagementSystem\Company;

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
<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/UserViews/Layouts/header_mobile.css') }}">
