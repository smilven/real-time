@extends('layouts.app')

@section('content')
<style>
    .alert {
        position: relative;
        padding: 15px;
        margin: 5px 0;
        border: 1px solid transparent;
        border-radius: 4px;
        opacity: 0; /* Initially hidden */
        animation: fadeIn 0.5s forwards, fadeOut 1s 2s forwards; /* Chain animations */
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
    </style>
    
@if(auth()->user()->is_admin)
<div class="container py-4 pb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Products</div>
                <div class="card-body">
                    <div id="notification"></div>

                    <form class="mb-3" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mt-2">
                            <label for="productName">Product Name:</label>
                            <input type="text" class="form-control" name="productName" id="productName" required>
                        </div>
                        <div class="mt-2">
                            <label for="productPrice">Product Price:</label>
                            <input type="text" class="form-control" name="productPrice" id="productPrice" required>
                        </div>
                        <div class="mt-2">
                            <label for="productQuantity">Product Quantity:</label>
                            <input type="number" class="form-control" name="productQuantity" id="productQuantity" required>
                        </div>
                        <div class="mt-2">
                            <label for="productImage">Product Image:</label>
                            <input type="file" class="form-control" name="productImage" id="productImage" required>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-success">Submit</button>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Image</td>
                                <td>Name</td>
                                <td>Price</td>
                                <td>Quantity</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr data-id="{{ $product->id }}">
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->productImage)
                                        <img src="{{ asset('storage/' . $product->productImage) }}" alt="{{ $product->productName }}" width="50">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $product->productName }}</td>
                                <td>{{ $product->productPrice }}</td>
                                <td class="product-quantity">{{ $product->productQuantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        const notification = document.getElementById('notification'); // 获取通知容器
        notification.insertAdjacentHTML('beforeend', `<div class="alert alert-info">${e.message}</div>`);

        const productId = e.product.id;

        // 更新用户界面的 div 布局
        const productCard = document.querySelector(`.card[data-id="${productId}"]`);
        if (productCard) {
            const quantityElement = productCard.querySelector('.product-quantity');
            if (quantityElement) {
                quantityElement.textContent = e.product.productQuantity;
            }

            const addToCartButton = productCard.querySelector('.add-to-cart');
            if (addToCartButton) {
                addToCartButton.disabled = e.product.productQuantity <= 0;
            }
        }

        // 更新管理员界面的 table
        const adminRow = document.querySelector(`tr[data-id="${productId}"]`);
        if (adminRow) {
            const quantityCell = adminRow.querySelector('.product-quantity');
            if (quantityCell) {
                quantityCell.textContent = e.product.productQuantity;
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

