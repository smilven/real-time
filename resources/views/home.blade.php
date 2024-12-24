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

    .card-img-top {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .featured-category img {
        height: 250px;
        object-fit: contain;
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .scroll-container {
        overflow-x: auto;
        scroll-behavior: smooth;
        display: flex;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scroll-container::-webkit-scrollbar {
        display: none;
    }

0      .banner {
        position: relative;
        width: 100%; /* 容器宽度全屏适配 */
        overflow: hidden;
    }

    .banner-img {
        width: 100%; /* 宽度适配容器 */
        height: auto; /* 保持图片比例 */
        object-fit: cover; /* 确保内容充满容器并裁剪溢出部分 */
        display: block; /* 防止图片周围出现多余间距 */
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        .banner {
            height: auto; /* 在小屏幕上自动调整高度 */
        }
    }

    .featured-category {
        max-width: 100%;
        /* 确保卡片宽度适配 */
    }

    .text-muted {
        color: rgb(0, 0, 0) !important;
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
        <h2 class="mb-4">New Arrival</h2>
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
        <div class="d-flex justify-content-between mt-3">
            <button id="scrollLeft" class="btn btn-primary" style="display:none;">Left</button>
            <button id="scrollRight" class="btn btn-primary" style="display:none;">Right</button>
        </div> 
    </div>
</section>

<script>
    const scrollableRow = document.getElementById('scrollableRow');
    let autoScrollFrame;
    const scrollDistance = 2; // 每帧滚动的像素距离

    // 开始自动滚动
    const startAutoScroll = () => {
        const smoothScroll = () => {
            scrollableRow.scrollLeft += scrollDistance;
            if (
                scrollableRow.scrollLeft + scrollableRow.offsetWidth >=
                scrollableRow.scrollWidth
            ) {
                scrollableRow.scrollLeft = 0; // 滚动到头部
            }
            autoScrollFrame = requestAnimationFrame(smoothScroll);
        };
        autoScrollFrame = requestAnimationFrame(smoothScroll);
    };

    // 停止自动滚动
    const stopAutoScroll = () => {
        cancelAnimationFrame(autoScrollFrame);
    };

    // 左右滚动按钮功能
    document.getElementById('scrollLeft').addEventListener('click', () => {
        scrollableRow.scrollBy({ left: -100, behavior: 'smooth' });
        stopAutoScroll();
    });

    document.getElementById('scrollRight').addEventListener('click', () => {
        scrollableRow.scrollBy({ left: 100, behavior: 'smooth' });
        stopAutoScroll();
    });

    // 鼠标悬停暂停自动滚动
    scrollableRow.addEventListener('mouseover', stopAutoScroll);
    scrollableRow.addEventListener('mouseout', startAutoScroll);

    // 开始自动滚动
    startAutoScroll();
</script>

<section>
    <div class="container px-4 px-lg-5 mt-50">
        <div class="py-5 px-8 rounded banner">
            <img src="/storage/product_images/whyChooseUs.png" alt="Why Choose Us" class="banner-img">
        </div>
    </div>
</section>



<div class="container px-4 px-lg-5">
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
        <div class="container px-4 px-lg-5">
            <div class="row">
                <div class="col-12 col-md-6 mb-3 mb-lg-0">
                    <div class="py-5 px-8 rounded"
                        style="background: url(/storage/product_images/baneer4.png) no-repeat; background-size: cover; background-position: center; padding:15px;">
                        <h3 class="fw-bold mb-1">最火泡面</h3>
                        <p class="mb-4">
                            全网
                            <span class="fw-bold">最低</span>
                            价钱
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
                            style="background: url(storage/product_images/banner3.png) no-repeat; background-size: cover; background-position: center;padding:15px;">
                            <div>
                                <h3 class="fw-bold mb-1">爆款零食</h3>
                                <p class="mb-4">
                                    全网
                                    <span class="fw-bold">最低</span>
                                    价钱
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

<div class="container my-5 px-4 px-lg-5 mt-5">
    <div class="row text-center">
        <!-- 特点 1 -->
        <div class="col-md-3">
            <div class="feature-box">
                <div class="icon mb-3">
                    <i class="bi bi-clock" style="font-size: 2rem; color: rgb(0, 0, 0);"></i>
                </div>
                <h5 class="fw-bold">10分钟送达</h5>
                <p>最快速配送，从附近的FreshCart门店直接送到您家门口。</p>
            </div>
        </div>
        <!-- 特点 2 -->
        <div class="col-md-3 mb-4">
            <div class="feature-box">
                <div class="icon mb-3">
                    <i class="bi bi-gift" style="font-size: 2rem; color: rgb(0, 0, 0);"></i>
                </div>
                <h5 class="fw-bold">超值价格与优惠</h5>
                <p>价格低于超市，还有超值返现优惠。尽享实惠购物体验。</p>
            </div>
        </div>
        <!-- 特点 3 -->
        <div class="col-md-3 mb-4">
            <div class="feature-box">
                <div class="icon mb-3">
                    <i class="bi bi-box" style="font-size: 2rem; color: rgb(0, 0, 0);"></i>
                </div>
                <h5 class="fw-bold">种类齐全</h5>
                <p>提供5000+种商品，包括食品、日用品、烘焙、蔬果、肉类等多种类别。</p>
            </div>
        </div>
        <!-- 特点 4 -->
        <div class="col-md-3 mb-4">
            <div class="feature-box">
                <div class="icon mb-3">
                    <i class="bi bi-arrow-repeat" style="font-size: 2rem; color: rgb(0, 0, 0);"></i>
                </div>
                <h5 class="fw-bold">退货无忧</h5>
                <p>对商品不满意？在家门口即可退货，几小时内退款，无需多问。</p>
            </div>
        </div>
    </div>
</div>

<footer class="py-5" style="background-color: #f1f0eb;">
    <div class="container" style="color: rgb(0, 0, 0);">
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
                <p class="text-muted">© 2024 All rights reserved. Powered by MII SI HENG & JIM NG YING GEE .</p>
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