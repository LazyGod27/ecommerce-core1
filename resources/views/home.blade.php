@extends('layouts.frontend')

@section('title', 'iMarket - Home')

@section('content')
<section class="slideshow-section bg-[#add8e6] p-5 rounded-lg shadow-md flex justify-center">
    <div class="flex flex-col items-center w-full">
        <div class="relative w-full overflow-hidden rounded-lg shadow-lg">
            <div id="slideshow-container" class="relative w-full aspect-[21/9]">
                <a href="#">
                    <img class="slide w-full h-full object-cover rounded-md shadow-lg transition-opacity duration-500" 
                        src="{{ asset('images/laptop hot deals.jpg') }}" 
                        alt="Gaming Deals">
                </a>
                <a href="#">
                    <img class="slide w-full h-full object-cover rounded-md shadow-lg hidden transition-opacity duration-500" 
                        src="{{ asset('images/NIKE.png') }}" 
                        alt="New Shoes Collection">
                </a>
                <a href="#">
                    <img class="slide w-full h-full object-cover rounded-md shadow-lg hidden transition-opacity duration-500" 
                        src="{{ asset('images/shirt hot deals.jpg') }}" 
                        alt="Flash Sale on shirt">
                </a>
            </div>
            <button id="prevBtn" class="absolute top-1/2 -translate-y-1/2 left-4 text-white bg-[#353c61] bg-opacity-50 hover:bg-opacity-75 transition-all duration-300 rounded-full p-2 focus:outline-none" aria-label="Previous Slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="nextBtn" class="absolute top-1/2 -translate-y-1/2 right-4 text-white bg-[#353c61] bg-opacity-50 hover:bg-opacity-75 transition-all duration-300 rounded-full p-2 focus:outline-none" aria-label="Next Slide">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<section class="product-grid-section grid gap-5 justify-center grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
    
    <div class="product-card bg-[#add8e6] p-5 rounded-lg shadow-md flex flex-col items-start">
        <h2 class="text-xl font-bold mb-4 text-[#353c61]">Gaming</h2>
        <div class="card-grid grid grid-cols-2 gap-4 w-full mb-4">
            <div class="card-item text-center">
                <img src="https://www.lifewire.com/thmb/AnvL-ZvMMbbFQJhV4n8GILdfnlE=/1280x1280/filters:no_upscale():max_bytes(150000):strip_icc()/pictek-gaming-mouse-5b2c1da8a9d4f90037b42f8b.jpg" alt="Gaming Mouse" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Gaming Mouse</p>
            </div>
            <div class="card-item text-center">
                <img src="https://m.media-amazon.com/images/I/71DYlRN51tL._AC_SL1500_.jpg" alt="Keyboards" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Keyboards</p>
            </div>
            <div class="card-item text-center">
                <img src="https://laptopmedia.com/wp-content/uploads/2022/07/3-1.jpg" alt="Gaming Chair" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Laptop</p>
            </div>
            <div class="card-item text-center">
                <img src="https://m-cdn.phonearena.com/images/articles/400612-image/ROG-Phone-7-Group-Photo-06.jpg" alt="Monitor" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Phone</p>
            </div>
        </div>
        <a href="{{ route('products.category', 'gaming') }}" class="text-xs text-[#2c3c8c] font-bold hover:text-[#4bc5ec] hover:underline">See All</a>
    </div>
    <div class="product-card bg-[#add8e6] p-5 rounded-lg shadow-md flex flex-col items-start">
        <h2 class="text-xl font-bold mb-4 text-[#353c61]">Accessories</h2>
        <div class="card-grid grid grid-cols-2 gap-4 w-full mb-4">
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/4bc5ec/ffffff?text=Earrings" alt="Earrings" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Earrings</p>
            </div>
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/94dcf4/353c61?text=Bracelet" alt="Bracelet" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Bracelet</p>
            </div>
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/5c8c9c/ffffff?text=Necklace" alt="Necklace" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Necklace</p>
            </div>
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/bdccdc/353c61?text=Watch" alt="Watch" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Watch</p>
            </div>
        </div>
        <a href="{{ route('products.category', 'accessories') }}" class="text-xs text-[#2c3c8c] font-bold hover:text-[#4bc5ec] hover:underline">See all</a>
    </div>
    <div class="product-card bg-[#add8e6] p-5 rounded-lg shadow-md flex flex-col items-start">
        <h2 class="text-xl font-bold mb-4 text-[#353c61]">Wear Essentials</h2>
        <div class="card-grid grid grid-cols-2 gap-4 w-full mb-4">
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/4bc5ec/ffffff?text=T-Shirts" alt="T-Shirts" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">T-Shirts</p>
            </div>
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/94dcf4/353c61?text=Jeans" alt="Jeans" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Jeans</p>
            </div>
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/5c8c9c/ffffff?text=Shoes" alt="Shoes" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Shoes</p>
            </div>
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/bdccdc/353c61?text=Jackets" alt="Jackets" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Jackets</p>
            </div>
        </div>
        <a href="{{ route('products.category', 'jeans') }}" class="text-xs text-[#2c3c8c] font-bold hover:text-[#4bc5ec] hover:underline">See All</a>
    </div>
    <div class="product-card bg-[#add8e6] p-5 rounded-lg shadow-md flex flex-col items-start">
        <h2 class="text-xl font-bold mb-4 text-[#353c61]">Makeup & Skincare</h2>
        <div class="card-grid grid grid-cols-2 gap-4 w-full mb-4">
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/4bc5ec/ffffff?text=Makeup" alt="Makeup" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Makeup</p>
            </div>
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/94dcf4/353c61?text=Sun+Screen" alt="Sun Screen" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Sun Screen</p>
            </div>
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/5c8c9c/ffffff?text=Skincare" alt="Skincare" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Skin Care</p>
            </div>
            <div class="card-item text-center">
                <img src="https://placehold.co/200x200/bdccdc/353c61?text=Lotion" alt="Lotion" class="w-full h-24 rounded-md mb-2 object-cover">
                <p class="text-xs text-gray-500">Lotion</p>
            </div>
        </div>
        <a href="{{ route('products.category', 'make-up') }}" class="text-xs text-[#2c3c8c] font-bold hover:text-[#4bc5ec] hover:underline">See All</a>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.slide');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        let currentSlide = 0;

        /**
         * @param {number} index 
         */
        function showSlide(index) {
            if (index >= slides.length) {
                currentSlide = 0;
            } else if (index < 0) {
                currentSlide = slides.length - 1;
            } else {
                currentSlide = index;
            }

            slides.forEach(slide => {
                slide.classList.add('hidden');
                slide.classList.remove('active');
            });
            slides[currentSlide].classList.remove('hidden');
            slides[currentSlide].classList.add('active');
        }
        nextBtn.addEventListener('click', () => {
            showSlide(currentSlide + 1);
        });

        prevBtn.addEventListener('click', () => {
            showSlide(currentSlide - 1);
        });

        const startAutoSlide = () => {
            setInterval(() => {
                showSlide(currentSlide + 1);
            }, 5000);
        };
        
        showSlide(currentSlide);
        startAutoSlide();
    });
</script>
@endsection