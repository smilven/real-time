@extends('layouts.app')
@section('content')
<style>
    .image-container {
        width: 100%;
        height: 200px;
        /* 固定高度（可根据需求调整） */
        overflow: hidden;
        /* 隐藏超出部分 */
    }

    .card-img-top {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* 保证图片适应容器 */
    }

    /* 卡片整体响应式样式 */
    .card {
        border: 1px solid #ddd;
        /* 可选：添加边框 */
        border-radius: 10px;
        /* 可选：圆角 */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* 可选：阴影效果 */
    }

    .card-body {
        padding: 16px;
    }

    .card-footer {
        padding: 16px;
    }

    .pb-0 {
        padding-bottom: 0 !important;
        padding-top: 0 !important;
    }

    .featured-category img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        border-radius: 10px;
    }

    .banner img {
        max-width: 150px;
    }

    .scroll-container {
        overflow-x: auto;
        /* 容器内部可横向滚动 */
        scroll-behavior: smooth;
        /* 滑动时平滑 */
        display: flex;
        -ms-overflow-style: none;
        /* 隐藏滚动条（IE 和 Edge） */
        scrollbar-width: none;
        /* 隐藏滚动条（Firefox） */
    }

    .scroll-container::-webkit-scrollbar {
        display: none;
        /* 隐藏滚动条（Chrome 和 Safari） */
    }

    .featured-category img {
        height: 250px;
        object-fit: contain;
    }

    .btn {
        z-index: 1;
    }

    .featured-category {
        max-width: 100%;
        /* 确保卡片宽度适配 */
    }

    .text-muted {
        color: white !important;
    }

    .feature-box {
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: box-shadow 0.3s ease-in-out;
    }

    .feature-box:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .icon {
        font-size: 2rem;
    }

    .my-5 {
    margin-bottom: 2rem !important;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<header class="bg-dark py-5"
    style="background-image: url('/storage/product_images/banner.png'); background-size: cover; background-position: center; background-repeat: no-repeat; height:450px">
    <div class="container px-4 px-lg-5 my-5">
    </div>
</header>

<section>
    <div class="container position-relative px-4 px-lg-5 mt-5">
        <h2 class="mb-4">New Arrival </h2>
        <!-- Scrollable Wrapper -->
        <div class="overflow-hidden position-relative">
            <!-- Scrollable Content -->
            <div class="d-flex scroll-container" id="scrollableRow">
                @foreach($products as $product)
                <div class="card text-center me-3 featured-category" style="min-width: 25%; flex: 0 0 auto;">
                    <img class="card-img-top"
                        src="{{ $product->productImage ? asset('storage/' . $product->productImage) : 'https://via.placeholder.com/150' }}"
                        alt="Product Image" style="height: 200px;" />
                    <div class="card-body">
                        <p class="card-text">{{ $product->productName }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    // 自动滑动功能
    const scrollableRow = document.getElementById('scrollableRow');
    let autoScroll;

    const startAutoScroll = () => {
        autoScroll = setInterval(() => {
            scrollableRow.scrollBy({ left: 100, behavior: 'smooth' });
            if (
                scrollableRow.scrollLeft + scrollableRow.offsetWidth >=
                scrollableRow.scrollWidth
            ) {
                scrollableRow.scrollTo({ left: 0, behavior: 'smooth' });
            }
        }, 3000); // 每3秒滑动一次
    };

    const stopAutoScroll = () => {
        clearInterval(autoScroll);
    };

    startAutoScroll();

    // 左右按钮滚动功能
    document.getElementById('scrollLeft').addEventListener('click', function () {
        scrollableRow.scrollBy({ left: -100, behavior: 'smooth' });
        stopAutoScroll();
    });

    document.getElementById('scrollRight').addEventListener('click', function () {
        scrollableRow.scrollBy({ left: 100, behavior: 'smooth' });
        stopAutoScroll();
    });

    // 暂停自动滑动（当鼠标悬停时）
    scrollableRow.addEventListener('mouseover', stopAutoScroll);
    scrollableRow.addEventListener('mouseout', startAutoScroll);
</script>


<section>
    <section>
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row">
                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                    <div class="py-5 px-8 rounded"
                        style="background: url(/storage/product_images/grocery-banner.png) no-repeat; background-size: cover; background-position: center; padding:15px;">
                        <h3 class="fw-bold mb-1">New &amp; Snaker</h3>
                        <p class="mb-4">
                            Get Upto
                            <span class="fw-bold">30%</span>
                            Off
                        </p>
                        @auth
                        @if(auth()->user()->is_admin)
                        <div class=""><a class="btn btn-outline-dark mt-auto" href="products">View More</a></div>
                        @else
                        <div class=""><a class="btn btn-outline-dark mt-auto" href="userProducts">View More</a></div>
                        @endif
                    @else
                        <div class="t"><a class="btn btn-outline-dark mt-auto" href="{{ route('login') }}">Login to View More</a></div>
                    @endauth                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div>
                        <div class="py-5 px-8 rounded"
                            style="background: url(storage/product_images/grocery-banner-2.jpg) no-repeat; background-size: cover; background-position: center;padding:15px;">
                            <div>
                                <h3 class="fw-bold mb-1">Fast Food</h3>
                                <p class="mb-4">
                                    Get Upto
                                    <span class="fw-bold">25%</span>
                                    Off
                                </p>
                                @auth
                                @if(auth()->user()->is_admin)
                                <div class=""><a class="btn btn-outline-dark mt-auto" href="products">View More</a></div>
                                @else
                                <div class=""><a class="btn btn-outline-dark mt-auto" href="userProducts">View More</a></div>
                                @endif
                            @else
                                <div class="t"><a class="btn btn-outline-dark mt-auto" href="{{ route('login') }}">Login to View More</a></div>
                            @endauth                   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
<div class="container px-4 px-lg-5 mt-5">
    <h2 class="mb-4">Our Product</h2>

    <div class="row gx-4 gx-lg-2.5 row-cols-2 row-cols-md-5 justify-content-center">
        @foreach($products->take(10) as $product)
        <div class="col mb-5">
            <!-- Card Container -->
            <div class="card h-100">
                <!-- Image Container -->
                <div class="featured-category" style="min-width: 25%; flex: 0 0 auto;">
                    <img class="card-img-top"
                        src="{{ $product->productImage ? asset('storage/' . $product->productImage) : 'https://via.placeholder.com/150' }}"
                        alt="Product Image" />
                </div>
                <!-- Product Details -->
                <div class="card-body p-4">
                    <div class="text-center">
                        <!-- Product Name -->
                        <h5 class="fw-bolder">{{ $product->productName }}</h5>
                        <!-- Product Price -->
                        RM {{ $product->productPrice }}
                    </div>
                </div>
                <!-- Product Actions -->
                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                    @auth
                    @if(auth()->user()->is_admin)
                    <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="products">View More</a></div>
                    @else
                    <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="userProducts">View More</a></div>
                    @endif
                @else
                    <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="{{ route('login') }}">Login to View More</a></div>
                @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>


</div>

<section>
    <section>
        <div class="container px-4 px-lg-5 mt-50">
                    <div class="py-5 px-8 rounded"
                        style="background: url(/storage/product_images/whychooseue.png) no-repeat; background-size: cover; background-position: center; height:200px;">
                    </div>
                </div>
       
    </section>
</section>

<div class="container my-5 px-4 px-lg-8 mt-5">
    <div class="row text-center">
        <!-- Feature 1 -->
        <div class="col-md-3">
            <div class="feature-box">
                <div class="icon mb-3">
                    <i class="bi bi-clock" style="font-size: 2rem; color: green;"></i>
                </div>
                <h5 class="fw-bold">10 minute grocery now</h5>
                <p>Get your order delivered to your doorstep at the earliest from FreshCart pickup stores near you.</p>
            </div>
        </div>
        <!-- Feature 2 -->
        <div class="col-md-3 mb-4">
            <div class="feature-box">
                <div class="icon mb-3">
                    <i class="bi bi-gift" style="font-size: 2rem; color: green;"></i>
                </div>
                <h5 class="fw-bold">Best Prices & Offers</h5>
                <p>Cheaper prices than your local supermarket, great cashback offers to top it off. Get best prices &
                    offers.</p>
            </div>
        </div>
        <!-- Feature 3 -->
        <div class="col-md-3 mb-4">
            <div class="feature-box">
                <div class="icon mb-3">
                    <i class="bi bi-box" style="font-size: 2rem; color: green;"></i>
                </div>
                <h5 class="fw-bold">Wide Assortment</h5>
                <p>Choose from 5000+ products across food, personal care, household, bakery, veg and non-veg & other
                    categories.</p>
            </div>
        </div>
        <!-- Feature 4 -->
        <div class="col-md-3 mb-4">
            <div class="feature-box">
                <div class="icon mb-3">
                    <i class="bi bi-arrow-repeat" style="font-size: 2rem; color: green;"></i>
                </div>
                <h5 class="fw-bold">Easy Returns</h5>
                <p>Not satisfied with a product? Return it at the doorstep & get a refund within hours. No questions
                    asked <a href="#" class="text-success">policy</a>.</p>
            </div>
        </div>
    </div>
</div>
<footer class="py-5 bg-black">
    <div class="container" style="color: white;">
        <center>
            <h2>Mini Shop</h2>
        </center>
        <div class="row row-cols-5 py-5 mt-5 border-top">
            <div class="col">
                <a href="/" class="d-flex align-items-center mb-3 link-dark text-decoration-none">
                    <svg class="bi me-2" width="40" height="32">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                </a>
                <p class="text-muted">© 2024</p>
            </div>

            <div class="col">

            </div>

            <div class="col">
                <h5>Section</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Home</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Features</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Pricing</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">FAQs</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">About</a></li>
                </ul>
            </div>

            <div class="col">
                <h5>Section</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Home</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Features</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Pricing</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">FAQs</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">About</a></li>
                </ul>
            </div>

            <div class="col">
                <h5>Section</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Home</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Features</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Pricing</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">FAQs</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">About</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
@endsection