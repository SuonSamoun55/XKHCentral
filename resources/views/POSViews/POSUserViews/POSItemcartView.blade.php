<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>POS Cart</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>

*{
box-sizing:border-box;
margin:0;
padding:0;
}

body{
font-family:Arial, sans-serif;
background:#f3f4f6;
color:#222;
}

.page-wrap{
display:flex;
min-height:100vh;
gap:16px;
padding:14px;
}

.sidebar{
width:270px;
background:#fff;
border-radius:20px;
padding:22px;
box-shadow:0 4px 18px rgba(0,0,0,0.05);
display:flex;
flex-direction:column;
justify-content:space-between;
}

.brand{
font-size:18px;
font-weight:700;
margin-bottom:20px;
display:flex;
gap:8px;
align-items:center;
}

.brand-dot{
width:14px;
height:14px;
background:orange;
border-radius:50%;
}

.menu-item{
display:flex;
gap:12px;
align-items:center;
padding:12px;
border-radius:12px;
margin-bottom:6px;
font-size:16px;
color:#333;
}

.menu-item.active{
color:#19bcc5;
font-weight:700;
}

.content-area{
flex:1;
background:#fff;
border-radius:20px;
padding:30px;
box-shadow:0 4px 18px rgba(0,0,0,0.05);
}

.cart-header{
display:flex;
align-items:center;
gap:10px;
margin-bottom:25px;
}

.cart-title{
font-size:24px;
font-weight:700;
color:#19bcc5;
}

.cart-list{
display:flex;
flex-direction:column;
gap:22px;
}

.cart-row{
display:grid;
grid-template-columns:110px 1fr auto auto;
align-items:center;
gap:20px;
}

.cart-image{
width:100px;
height:100px;
display:flex;
align-items:center;
justify-content:center;
}

.cart-image img{
width:100%;
height:100%;
object-fit:contain;
}

.cart-name{
font-size:18px;
font-weight:700;
margin-bottom:4px;
}

.cart-uom{
font-size:13px;
color:#777;
margin-bottom:8px;
}

.cart-price{
font-size:16px;
font-weight:700;
}

.cart-actions{
display:flex;
align-items:center;
gap:16px;
}

.qty-box{
display:flex;
align-items:center;
gap:10px;
}

.qty-btn{
width:28px;
height:28px;
border:none;
border-radius:50%;
font-size:18px;
cursor:pointer;
display:flex;
align-items:center;
justify-content:center;
}

.minus{
background:#d1d5db;
color:white;
}

.plus{
background:#19bcc5;
color:white;
}

.qty-number{
font-size:18px;
width:24px;
text-align:center;
}

.remove-btn{
border:none;
background:transparent;
color:#ef4444;
font-size:22px;
cursor:pointer;
}

.line-total{
font-size:18px;
font-weight:700;
min-width:80px;
text-align:right;
}

.summary{
width:380px;
margin-left:auto;
margin-top:30px;
}

.summary-row{
display:flex;
justify-content:space-between;
padding:14px 0;
border-bottom:1px solid #e5e7eb;
}

.summary-total{
font-size:22px;
font-weight:700;
}

.checkout-btn{
width:100%;
height:52px;
background:#19bcc5;
border:none;
border-radius:14px;
color:white;
font-size:18px;
font-weight:700;
margin-top:18px;
cursor:pointer;
}

.empty-box{
padding:40px;
text-align:center;
background:#fafafa;
border-radius:14px;
}

</style>
</head>

<body>

<div class="page-wrap">

<!-- Sidebar -->
<aside class="sidebar">

<div>

<div class="brand">
<span class="brand-dot"></span>
Orange
</div>

<a href="{{ route('user.posinterface') }}" class="menu-item">
<i class="bi bi-grid"></i>
Dashboard
</a>

<a href="{{ route('user.pos.cart') }}" class="menu-item active">
<i class="bi bi-cart"></i>
Cart
</a>

<a class="menu-item">
<i class="bi bi-star"></i>
Favorite
</a>

<a class="menu-item">
<i class="bi bi-bag"></i>
Order History
</a>

</div>

<div>

<div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
<img src="{{ asset('images/no-image.png') }}" style="width:40px;height:40px;border-radius:50%">
<div>
<strong>{{ auth()->user()->name }}</strong>
<div style="font-size:12px;color:#19bcc5">{{ auth()->user()->role }}</div>
</div>
</div>

<a href="{{ route('logout') }}" style="color:#ef4444">
<i class="bi bi-box-arrow-left"></i> Logout
</a>

</div>

</aside>


<!-- Main -->
<main class="content-area">

<div class="cart-header">
<div class="cart-title">Cart</div>
</div>


@if(!$cart || $cart->items->isEmpty())

<div class="empty-box">
No items found.
</div>

@else

<div class="cart-list">

@foreach($cart->items as $cartItem)

<div class="cart-row">

<div class="cart-image">
<img src="{{ optional($cartItem->item)->image_url ?? asset('images/no-image.png') }}">
</div>

<div>
<div class="cart-name">{{ $cartItem->item_name }}</div>
<div class="cart-uom">{{ optional($cartItem->item)->base_unit_of_measure_code ?? 'PCS' }}</div>
<div class="cart-price">${{ number_format($cartItem->unit_price,2) }}</div>
</div>

<div class="cart-actions">

<button class="remove-btn remove-item" data-id="{{ $cartItem->id }}">
<i class="bi bi-trash"></i>
</button>

<div class="qty-box">

<button class="qty-btn minus qty-update"
data-id="{{ $cartItem->id }}"
data-action="minus">-</button>

<div class="qty-number">{{ $cartItem->qty }}</div>

<button class="qty-btn plus qty-update"
data-id="{{ $cartItem->id }}"
data-action="plus">+</button>

</div>

</div>

<div class="line-total">
${{ number_format($cartItem->line_total,2) }}
</div>

</div>

@endforeach

</div>


<!-- Summary -->

<div class="summary">

<div class="summary-row">
<span>Subtotal</span>
<strong>${{ number_format($subtotal,2) }}</strong>
</div>

<div class="summary-row summary-total">
<span>Total</span>
<strong>${{ number_format($total,2) }}</strong>
</div>

<button id="checkoutBtn" class="checkout-btn">
Go to Checkout
</button>

</div>

@endif

</main>

</div>


<script>

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


async function updateQty(id,qty){

const res = await fetch(`/pos-system/cart/update/${id}`,{
method:'PUT',
headers:{
'Content-Type':'application/json',
'X-CSRF-TOKEN':csrfToken
},
body:JSON.stringify({qty})
})

location.reload()

}

document.querySelectorAll('.qty-update').forEach(btn=>{

btn.onclick=function(){

let row=this.closest('.cart-row')
let qty=parseInt(row.querySelector('.qty-number').innerText)

if(this.dataset.action==='minus'){
if(qty>1) qty--
}else{
qty++
}

updateQty(this.dataset.id,qty)

}

})


document.querySelectorAll('.remove-item').forEach(btn=>{

btn.onclick=async function(){

await fetch(`/pos-system/cart/remove/${this.dataset.id}`,{
method:'DELETE',
headers:{
'X-CSRF-TOKEN':csrfToken
}
})

location.reload()

}

})


document.getElementById('checkoutBtn').onclick=async function(){

let currency=prompt("Choose currency (USD or KHR)","USD")

if(!currency) return

currency=currency.toUpperCase()

let factor=1

if(currency==="KHR"){
factor=prompt("Enter KHR rate example 4100","4100")
}

await fetch("{{ route('user.pos.checkout') }}",{
method:'POST',
headers:{
'Content-Type':'application/json',
'X-CSRF-TOKEN':csrfToken
},
body:JSON.stringify({
currency_code:currency,
currency_factor:factor
})
})

alert("Order created")

location.reload()

}

</script>

</body>
</html>
