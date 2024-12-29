@extends('layouts.app')

@section('content')
<style>
    .cart-wrapper {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 40px 0;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        transition: transform 0.2s;
    }

    .product-card:hover {
        transform: translateY(-2px);
    }

    .quantity-input {
        width: 60px;
        text-align: center;
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }

    .product-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }

    .summary-card {
        background: white;
        border-radius: 12px;
        position: sticky;
        top: 20px;
    }

    .checkout-btn {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none;
        transition: transform 0.2s;
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #4f46e5, #4338ca);
    }

    .remove-btn {
        color: #dc2626;
        cursor: pointer;
        transition: all 0.2s;
    }

    .remove-btn:hover {
        color: #991b1b;
    }

    .quantity-btn {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background: #f3f4f6;
        border: none;
        transition: all 0.2s;
    }

    .quantity-btn:hover {
        background: #e5e7eb;
    }

    .discount-badge {
        background: #dcfce7;
        color: #166534;
        font-size: 0.875rem;
        padding: 4px 8px;
        border-radius: 6px;
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
    .form-control-lg {
    min-height: calc(1.6em + 1rem + calc(var(--bs-border-width)* 2));
    padding: 0.5rem 1rem;
    font-size: 0；
    border-radius: var(--bs-border-radius-lg);
}
</style>

</head>

<body>
    <div class="cart-wrapper">
        <div class="container">
            <div class="row g-4">
                <!-- Cart Items Section -->
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Shopping Cart</h4>
                        <span class="text-muted">You have {{$totalQuantity}} items in your cart</span>
                    </div>
                    @foreach ($cartItems as $item)
                    <!-- Product Cards -->
                    <div class="d-flex flex-column gap-3 mb-4">
                        <!-- Product 1 -->
                        <div class="product-card p-3 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $item->product->productImage ? asset('storage/' . $item->product->productImage) : 'https://via.placeholder.com/150' }}"
                                        alt="Product" class="product-image">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ $item->product->productName }}</h6>
                                    <p class="text-muted mb-0">RM{{ $item->product->productPrice }}</p>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <!--   <button class="quantity-btn" onclick="updateQuantity(1, -1)">-</button>
                                    <input type="number" class="quantity-input" value="1" min="1">
                                    <button class="quantity-btn" onclick="updateQuantity(1, 1)">+</button> -->
                                        <h6 class="mb-1">{{ $item->quantity }}</h6>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">RM{{ $item->product->productPrice * $item->quantity }}</span>
                                </div>
                                <div class="col-md-1">
                                    <i class="bi bi-trash remove-btn"></i>
                                </div>
                            </div>
                        </div>


                    </div>
                    @endforeach
                </div>

                <!-- Summary Section -->
                <div class="col-lg-4">
                    <div class="summary-card p-4 shadow-sm">
                        <h5 class="mb-4">Order Summary</h5>

                        <form class="mt-4">
                            <div data-mdb-input-init class="form-outline form-white mb-4">
                                <input type="text" id="typeName" class="form-control form-control-lg" siez="17"
                                    placeholder="Cardholder's Name" />
                                <label class="form-label" for="typeName">Cardholder's Name</label>
                            </div>

                            <div data-mdb-input-init class="form-outline form-white mb-4">
                                <input type="text"  class="form-control form-control-lg" siez="17"
                                    placeholder="1234 5678 9012 3457" minlength="19" maxlength="19" />
                                <label class="form-label" for="typeText">Card Number</label>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div data-mdb-input-init class="form-outline form-white">
                                        <input type="number" id="typeExp" class="form-control form-control-lg"
                                            placeholder="MM/YY" size="7" id="exp" minlength="7" maxlength="7" />
                                        <label class="form-label" for="typeExp">Expiration</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div data-mdb-input-init class="form-outline form-white">
                                        <input type="password" class="form-control form-control-lg" autocomplete="off"
                                            placeholder="&#9679;&#9679;&#9679;" size="1" minlength="3" maxlength="3" />
                                        <label class="form-label" for="typeText">Cvv</label>
                                    </div>
                                </div>
                            </div>

                        </form>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span>RM{{ $cartItems->sum(function ($item) {
                                return $item->product->productPrice * $item->quantity;
                                }) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping</span>
                            <span>RM0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold">RM{{ $cartItems->sum(function ($item) {
                                return $item->product->productPrice * $item->quantity;
                                }) }}</span>
                        </div>

                        <!-- Promo Code -->
                        <div class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Promo code">
                                <button class="btn btn-outline-secondary" type="button">Apply</button>
                            </div>
                        </div>
                        <form action="{{ route('place.order') }}" method="POST">
                            @csrf
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                class="btn btn-primary checkout-btn w-100 mb-3">
                               Checkout
                            </button>
                        </form>
                        <div class="d-flex justify-content-center gap-2">
                            <i class="bi bi-shield-check text-success"></i>
                            <small class="text-muted">Secure checkout</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateQuantity(productId, change) {
        const input = event.target.parentElement.querySelector('.quantity-input');
        let value = parseInt(input.value) + change;
        if (value >= 1) {
            input.value = value;
        }
    }
    </script>
    @endsection