@extends('layouts.app')

@section('content')
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
</style>

@if(auth()->user()->is_admin)
<div class="container py-4 pb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Products</div>
                <div class="card-body">
                    <div id="notification"></div>

                    <form class="mb-3" method="POST" action="{{ route('products.store') }}"
                        enctype="multipart/form-data">
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
                            <input type="number" class="form-control" name="productQuantity" id="productQuantity"
                                required>
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
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr data-id="{{ $product->id }}">
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->productImage)
                                    <img src="{{ asset('storage/' . $product->productImage) }}"
                                        alt="{{ $product->productName }}" width="50">
                                    @else
                                    No Image
                                    @endif
                                </td>
                                <td>
                                    <span class="product-name">{{ $product->productName }}</span>
                                    <input type="text" class="form-control product-name-input d-none"
                                        value="{{ $product->productName }}">
                                </td>
                                <td>
                                    <span class="product-price">{{ $product->productPrice }}</span>
                                    <input type="text" class="form-control product-price-input d-none"
                                        value="{{ $product->productPrice }}">
                                </td>
                                <td>
                                    <span class="product-quantity">{{ $product->productQuantity }}</span>
                                    <input type="number" class="form-control product-quantity-input d-none"
                                        value="{{ $product->productQuantity }}">
                                </td>
                                <td>
                                    <button class="btn btn-primary edit-btn">Edit</button>
                                    <button class="btn btn-success submit-btn d-none">Submit</button>
                                    <button class="btn btn-danger delete-btn">Delete</button>
                                </td>
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
            const quantityInput = adminRow.querySelector('.product-quantity-input');

            if (quantityCell) {
                // 更新显示值
                quantityCell.textContent = e.product.productQuantity;
            }

            if (quantityInput) {
                // 更新输入框值
                quantityInput.value = e.product.productQuantity;
            }
        }
    });

    window.Echo.channel('products')
    .listen('.update', (e) => {
        // 显示通知
        const notification = document.getElementById('notification');
        notification.insertAdjacentHTML('beforeend', `<div class="alert alert-info">${e.message}</div>`);

        // 更新管理员界面 table 中的产品信息
        const adminRow = document.querySelector(`tr[data-id="${e.product.id}"]`);
        if (adminRow) {
            const quantityCell = adminRow.querySelector('.product-quantity');
            const quantityInput = adminRow.querySelector('.product-quantity-input');

            if (quantityCell) {
                // 更新显示值
                quantityCell.textContent = e.product.productQuantity;
            }

            if (quantityInput) {
                // 更新输入框值
                quantityInput.value = e.product.productQuantity;
            }
        }
    });

    document.addEventListener('click', function(event) {
        const row = event.target.closest('tr');

        // 编辑模式
        if (event.target.classList.contains('edit-btn')) {
            toggleEditMode(row, true);
        }

        // 提交更新
        if (event.target.classList.contains('submit-btn')) {
            const id = row.getAttribute('data-id');
            const productName = row.querySelector('.product-name-input').value;
            const productPrice = row.querySelector('.product-price-input').value;
            const productQuantity = row.querySelector('.product-quantity-input').value;

            fetch(`/products/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    productName,
                    productPrice,
                    productQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 更新显示值
                    row.querySelector('.product-name').textContent = productName;
                    row.querySelector('.product-price').textContent = productPrice;
                    row.querySelector('.product-quantity').textContent = productQuantity;

                    // 确保 input 字段值也同步更新
                    row.querySelector('.product-name-input').value = productName;
                    row.querySelector('.product-price-input').value = productPrice;
                    row.querySelector('.product-quantity-input').value = productQuantity;

                    toggleEditMode(row, false);
                } else {
                    alert(data.message || 'Update failed.');
                }
            })
            .catch(error => {
                console.error(error);
                alert('An error occurred while updating.');
            });
        }

        // 删除功能
        if (event.target.classList.contains('delete-btn')) {
            const id = row.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this product?')) {
                fetch(`/products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.remove();
                    } else {
                        alert(data.message || 'Delete failed.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('An error occurred while deleting.');
                });
            }
        }
    });

    // 切换编辑模式函数
    function toggleEditMode(row, enable) {
        row.querySelector('.edit-btn').classList.toggle('d-none', enable);
        row.querySelector('.submit-btn').classList.toggle('d-none', !enable);

        row.querySelector('.product-name').classList.toggle('d-none', enable);
        row.querySelector('.product-name-input').classList.toggle('d-none', !enable);

        row.querySelector('.product-price').classList.toggle('d-none', enable);
        row.querySelector('.product-price-input').classList.toggle('d-none', !enable);

        row.querySelector('.product-quantity').classList.toggle('d-none', enable);
        row.querySelector('.product-quantity-input').classList.toggle('d-none', !enable);
    }
</script>
@endsection
