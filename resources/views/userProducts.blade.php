@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    .alert {
        position: relative;
        padding: 15px;
        margin: 5px 0;
        border: 1px solid transparent;
        border-radius: 4px;
        opacity: 0;
        /* Initially hidden */
        animation: fadeIn 0.5s forwards, fadeOut 1s 2s forwards;
        /* Chain animations */
    }

    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .alert-info {
        color: #31708f;
        background-color: #d9edf7;
        border-color: #bce8f1;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        0% {
            opacity: 1;
        }

        80% {
            opacity: 1;
        }

        100% {
            opacity: 0;
            display: none;
        }
    }

    .card {
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: none;
        height: 100%;
    }

    .card-img-top {
        width: 100%;
        height: 150px;
        object-fit: contain;
        border-radius: 8px;
    }


    .card-body {
        text-align: center;
    }
    .out-of-stock {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 10px;
        border-radius: 5px;
        font-size: 1.2rem;
    }
    .custom-link {
        color: black;       
        font-weight: bold;   
        text-decoration: none;
    }

</style>

@if(!auth()->user()->is_admin)
<div class="container py-4 pb-0">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" style="font-weight: bold; font-size: 24px;">
                    <span><a href="userProducts" class="custom-link">Products</a></span>
                    <form method="GET" action="{{ url('userProducts') }}" class="position-relative">
                        <input type="text" name="search" class="form-control w-150 pl-5" placeholder="Search Products..." value="{{ $search }}">
                        <!-- 放大镜图标 -->
                        <button type="submit" class="btn btn-link position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%); padding: 0;">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
                <div id="notification"></div>
                <div class="row" id="product-list">
                    @foreach($products as $product)
                    <div class="col-md-3 mb-3 product-item" data-name="{{ strtolower($product->productName) }}">
                        <div class="card" data-id="{{ $product->id }}">
                            <img src="{{ $product->productImage ? asset('storage/' . $product->productImage) : 'https://via.placeholder.com/150' }}" class="card-img-top" alt="{{ $product->productName }}">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $product->productName }}</h5>
                                <p class="card-text">
                                    <strong>Price: </strong> RM{{ number_format($product->productPrice, 2) }}<br>
                                    <strong>Stock: </strong> <span class="product-quantity">{{ $product->productQuantity }}</span><br>
                                </p>
                                @if($product->productQuantity <= 0)
                                    <div class="out-of-stock">Out of Stock</div>
                                @endif
                                <div class="justify-content-between align-items-center">
                                    <button class="btn btn-primary add-to-cart" data-id="{{ $product->id }}" @if($product->productQuantity <= 0) disabled @endif>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 d-flex justify-content-center">
                    {{ $products->appends(['search' => $search])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


@endif

@endsection
@section("script")
<script type="module">
    window.Echo.channel("products")
    .listen(".create", (e) => {
        if (!@json(auth()->user()->is_admin)) {
            const notification = document.getElementById('notification');
            notification.insertAdjacentHTML('beforeend', `<div class="alert alert-success">${e.message}</div>`);
        }
    });

    window.Echo.channel("products")
    .listen(".addToCart", (e) => {
        if (@json(auth()->user()->is_admin)) {
            const notification = document.getElementById('notification');
            notification.insertAdjacentHTML('beforeend', `<div class="alert alert-info">${e.message}</div>`);
        }

        const productCard = document.querySelector(`.card[data-id="${e.product.id}"]`);
        if (productCard) {
            const quantityElement = productCard.querySelector('.product-quantity');
            const addToCartButton = productCard.querySelector('.add-to-cart');
            const outOfStockElement = productCard.querySelector('.out-of-stock');

            if (quantityElement) {
                quantityElement.textContent = e.product.productQuantity;
            }

            if (addToCartButton) {
                addToCartButton.disabled = e.product.productQuantity <= 0;
            }

            if (e.product.productQuantity <= 0) {
                // 如果没有 "Out of Stock" 标签，添加
                if (!outOfStockElement) {
                    const outOfStockDiv = document.createElement('div');
                    outOfStockDiv.className = 'out-of-stock';
                    outOfStockDiv.textContent = 'Out of Stock';
                    productCard.appendChild(outOfStockDiv);
                }
            } else if (outOfStockElement) {
                // 如果库存大于 0，移除 "Out of Stock" 标签
                outOfStockElement.remove();
            }
        }
    });

    window.Echo.channel('products')
    .listen('.update', (e) => {
        const notification = document.getElementById('notification');
        notification.insertAdjacentHTML('beforeend', `<div class="alert alert-info">${e.message}</div>`);

        const productCard = document.querySelector(`.card[data-id="${e.product.id}"]`);
        if (productCard) {
            const quantityElement = productCard.querySelector('.product-quantity');
            const addToCartButton = productCard.querySelector('.add-to-cart');
            const outOfStockElement = productCard.querySelector('.out-of-stock');

            if (quantityElement) {
                quantityElement.textContent = e.product.quantity;
            }

            if (addToCartButton) {
                addToCartButton.disabled = e.product.quantity <= 0;
            }

            if (e.product.quantity <= 0) {
                // 如果没有 "Out of Stock" 标签，添加
                if (!outOfStockElement) {
                    const outOfStockDiv = document.createElement('div');
                    outOfStockDiv.className = 'out-of-stock';
                    outOfStockDiv.textContent = 'Out of Stock';
                    productCard.appendChild(outOfStockDiv);
                }
            } else if (outOfStockElement) {
                // 如果库存大于 0，移除 "Out of Stock" 标签
                outOfStockElement.remove();
            }
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('add-to-cart')) {
            const productId = event.target.getAttribute('data-id');
            
            fetch('/cart/addToCart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ productId })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err });
                }
                return response.json();
            })
            .then(data => {
                alert(data.message || 'Product added to cart!');
            })
            .catch(error => {
                alert(error.message || 'Unable to add product to cart.');
            });
        }
    });


</script>

@endsection


