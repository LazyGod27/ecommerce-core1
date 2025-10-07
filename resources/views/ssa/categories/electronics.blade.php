@extends('ssa.categories.layout')

@section('title', 'Electronics - iMarket PH')

@section('content')
<section class="hero">
    <div class="hero-slides">
        <img class="slide active" src="https://i.pinimg.com/736x/a2/4a/b5/a24ab53b4bf89b079978fad2e813e6d8.jpg" alt="Slide 1">
        <img class="slide" src="https://i.pinimg.com/1200x/95/62/40/9562401b17aa8803c45564fa87a7523a.jpg" alt="Slide 2">
        <img class="slide" src="https://i.pinimg.com/1200x/3f/29/e2/3f29e2a0f203313db21fa45734f73b29.jpg" alt="Slide 3">
        <img class="slide" src="https://i.pinimg.com/736x/fd/a6/8c/fda68c5189f7cd37f044e201a77acc1e.jpg" alt="Slide 4">
        <img class="slide" src="https://i.pinimg.com/736x/5c/b4/98/5cb49830b398136f9c2aa341f847958d.jpg" alt="Slide 5">
    </div>
    <div class="hero-content">
        <h1>ELECTRONICS</h1>
    </div>
    <div class="hero-dots">
        <span class="dot active" data-index="0"></span>
        <span class="dot" data-index="1"></span>
        <span class="dot" data-index="2"></span>
        <span class="dot" data-index="3"></span>
        <span class="dot" data-index="4"></span>
    </div>
</section>

<section class="feature">
    <div class="feature-content">
        <div class="product-card" 
            data-name="KEELAT Cordless Electric Impact Wrench/Drill" 
            data-sizes="N/A" 
            data-colors="Red,Blue" 
            data-price="1389" 
            data-orig-price="1922" 
            data-img-red="https://down-my.img.susercontent.com/file/my-11134207-7r98q-lq4ig9w0mehhc5"
            data-img-blue="https://down-ph.img.susercontent.com/file/sg-11134201-22120-w4z9fczs0olv80"
            onclick="viewProduct('KEELAT Cordless Electric Impact Wrench/Drill', 1389, 'https://down-my.img.susercontent.com/file/my-11134207-7r98q-lq4ig9w0mehhc5', 'Professional cordless electric impact wrench/drill with high torque and long battery life.')">
            <div class="product-img">
                <img src="https://down-my.img.susercontent.com/file/my-11134207-7r98q-lq4ig9w0mehhc5" alt="Cordless Drill">
                <span class="discount">-22%</span>
            </div>
            <div class="product-info">
                <h6>KEELAT Cordless Electric Impact Wrench/Drill</h6>
                <h3>₱1,389 <del>₱1,922</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 500 sold</p>
                <div class="actions">
                    <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('KEELAT Cordless Electric Impact Wrench/Drill', 1389, 'https://down-my.img.susercontent.com/file/my-11134207-7r98q-lq4ig9w0mehhc5')">Buy Now</a>
                    <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('KEELAT Cordless Electric Impact Wrench/Drill', 1389, 'https://down-my.img.susercontent.com/file/my-11134207-7r98q-lq4ig9w0mehhc5')"><i class="fas fa-shopping-cart"></i></a>
                </div>
            </div>
        </div>

        <div class="product-card" 
            data-name="KEELAT Electric Wrench" 
            data-sizes="N/A" 
            data-colors="Red,Yellow" 
            data-price="1389" 
            data-orig-price="1922"
            data-img-red="https://down-id.img.susercontent.com/file/sg-11134201-7qvg2-lhyo2uv9l8ia6a"
            data-img-yellow="https://down-id.img.susercontent.com/file/id-11134207-7qul3-lhx9s43b0n03a7">
            <div class="product-img">
                <img src="https://down-id.img.susercontent.com/file/sg-11134201-7qvg2-lhyo2uv9l8ia6a" alt="Cordless Wrench">
                <span class="discount">-22%</span>
            </div>
            <div class="product-info">
                <h6>KEELAT Electric Wrench</h6>
                <h3>₱1,389 <del>₱1,922</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 500 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Bluetooth Wireless Earbuds" 
            data-sizes="N/A" 
            data-colors="Black,White" 
            data-price="438" 
            data-orig-price="730"
            data-img-black="https://m.media-amazon.com/images/I/71Uo4RmAdHL._AC_.jpg"
            data-img-white="https://m.media-amazon.com/images/I/61Nl42G+BPL._AC_SL1500_.jpg"
            onclick="viewProduct('Bluetooth Wireless Earbuds', 438, 'https://m.media-amazon.com/images/I/71Uo4RmAdHL._AC_.jpg', 'High-quality wireless earbuds with noise cancellation and long battery life.')">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/71Uo4RmAdHL._AC_.jpg" alt="Wireless Earbuds">
                <span class="discount">-40%</span>
            </div>
            <div class="product-info">
                <h6>Bluetooth Wireless Earbuds</h6>
                <h3>₱438 <del>₱730</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 750 sold</p>
                <div class="actions">
                    <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Bluetooth Wireless Earbuds', 438, 'https://m.media-amazon.com/images/I/71Uo4RmAdHL._AC_.jpg')">Buy Now</a>
                    <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Bluetooth Wireless Earbuds', 438, 'https://m.media-amazon.com/images/I/71Uo4RmAdHL._AC_.jpg')"><i class="fas fa-shopping-cart"></i></a>
                </div>
            </div>
        </div>

        <div class="product-card" 
            data-name="Fitness Smartwatch" 
            data-sizes="N/A" 
            data-colors="Black,Silver,Rose Gold" 
            data-price="1890" 
            data-orig-price="2100"
            data-img-black="https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg"
            data-img-silver="https://m.media-amazon.com/images/I/71wYg9WJ4GL._AC_SL1500_.jpg"
            data-img-rose-gold="https://m.media-amazon.com/images/I/61y+1k61fJL._AC_SL1500_.jpg"
            onclick="viewProduct('Fitness Smartwatch', 1890, 'https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg', 'Advanced fitness smartwatch with heart rate monitoring, GPS, and water resistance.')">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg" alt="Smart Watch">
                <span class="discount">-10%</span>
            </div>
            <div class="product-info">
                <h6>Fitness Smartwatch</h6>
                <h3>₱1,890 <del>₱2,100</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 1,200 sold</p>
                <div class="actions">
                    <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Fitness Smartwatch', 1890, 'https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg')">Buy Now</a>
                    <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Fitness Smartwatch', 1890, 'https://m.media-amazon.com/images/I/61Q79ulDs6L._AC_SL1500_.jpg')"><i class="fas fa-shopping-cart"></i></a>
                </div>
            </div>
        </div>

        <div class="product-card" 
            data-name="Portable Power Bank" 
            data-sizes="10000mAh,20000mAh" 
            data-colors="Black,White" 
            data-price="3955" 
            data-orig-price="4653"
            data-img-black="https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg"
            data-img-white="https://m.media-amazon.com/images/I/61T4cI754oL._AC_SL1500_.jpg"
            onclick="viewProduct('Portable Power Bank', 3955, 'https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg', 'High-capacity portable power bank with fast charging and multiple device support.')">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg" alt="Portable Power Bank">
                <span class="discount">-15%</span>
            </div>
            <div class="product-info">
                <h6>Portable Power Bank</h6>
                <h3>₱3,955 <del>₱4,653</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 110 sold</p>
                <div class="actions">
                    <a href="#" class="btn buy" onclick="event.stopPropagation(); buyNow('Portable Power Bank', 3955, 'https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg')">Buy Now</a>
                    <a href="#" class="btn cart" onclick="event.stopPropagation(); addToCart('Portable Power Bank', 3955, 'https://m.media-amazon.com/images/I/61L-mHU13oL._AC_SL1500_.jpg')"><i class="fas fa-shopping-cart"></i></a>
                </div>
            </div>
        </div>

        <div class="product-card" 
            data-name="Classic Mini Game Console" 
            data-sizes="N/A" 
            data-colors="Gray,Red" 
            data-price="349" 
            data-orig-price="459"
            data-img-gray="https://infinitebasics.com/cdn/shop/files/main_71e69d6a-e0b0-4af1-b272-c75b8353b0b2.jpg?v=1730917133"
            data-img-red="https://m.media-amazon.com/images/I/61lD2o16lGL._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://infinitebasics.com/cdn/shop/files/main_71e69d6a-e0b0-4af1-b272-c75b8353b0b2.jpg?v=1730917133" alt="Mini Game Console">
                <span class="discount">-24%</span>
            </div>
            <div class="product-info">
                <h6>Classic Mini Game Console</h6>
                <h3>₱349 <del>₱459</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 194 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Smart RGB Floor Lamp" 
            data-sizes="1m,1.5m,2m" 
            data-colors="Black,White" 
            data-price="469" 
            data-orig-price="1332"
            data-img-black="https://m.media-amazon.com/images/I/71p5fisYQCL._AC_SL1500_.jpg"
            data-img-white="https://m.media-amazon.com/images/I/61x5sP-mFDL._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/71p5fisYQCL._AC_SL1500_.jpg" alt="RGB Floor Lamp">
                <span class="discount">-65%</span>
            </div>
            <div class="product-info">
                <h6>Smart RGB Floor Lamp</h6>
                <h3>₱469 <del>₱1,332</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 300 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Wireless Gamepad Controller" 
            data-sizes="N/A" 
            data-colors="Black,Red,Blue" 
            data-price="632" 
            data-orig-price="973"
            data-img-black="https://i5.walmartimages.com/asr/b30bd280-e303-4d72-802e-1098752e3741_1.f164d3532b3ba852a89e92972f4d4754.jpeg"
            data-img-red="https://i5.walmartimages.com/asr/31c4f4d1-8b35-4f4a-9e1f-7b24c165f49e.e9d1e34f40f04e13e0e7a049d5a7d6e6.jpeg"
            data-img-blue="https://i5.walmartimages.com/asr/f93475c5-4d64-4d8b-b9d9-f5c7e0c7e2b7.625c56c8b91b5d1e4e37e96b3a0e633d.jpeg">
            <div class="product-img">
                <img src="https://i5.walmartimages.com/asr/b30bd280-e303-4d72-802e-1098752e3741_1.f164d3532b3ba852a89e92972f4d4754.jpeg" alt="Wireless Gamepad">
                <span class="discount">-35%</span>
            </div>
            <div class="product-info">
                <h6>Wireless Gamepad Controller</h6>
                <h3>₱632 <del>₱973</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 655 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Logitech Wireless Gaming Headset" 
            data-sizes="N/A" 
            data-colors="Black,White" 
            data-price="5000" 
            data-orig-price="5882"
            data-img-black="https://resource.astrogaming.com/d_transparent.gif/content/dam/astro/en/products/a30/pdp-gallery-a30-white-01.png"
            data-img-white="https://resource.astrogaming.com/d_transparent.gif/content/dam/astro/en/products/a30/pdp-gallery-a30-black-01.png">
            <div class="product-img">
                <img src="https://resource.astrogaming.com/d_transparent.gif/content/dam/astro/en/products/a30/pdp-gallery-a30-white-01.png" alt="Gaming Headset">
                <span class="discount">-15%</span>
            </div>
            <div class="product-info">
                <h6>Logitech Wireless Gaming Headset</h6>
                <h3>₱5,000 <del>₱5,882</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 498 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Fast Wireless Charging Pad" 
            data-sizes="N/A" 
            data-colors="Black,White" 
            data-price="850" 
            data-orig-price="1000"
            data-img-black="https://i5.walmartimages.com/asr/d493dbcf-87a9-49d6-a3d9-ce24b976e839.decbcde8849150199b8f1e0d47787236.jpeg"
            data-img-white="https://m.media-amazon.com/images/I/61b7U+0dD8L._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://i5.walmartimages.com/asr/d493dbcf-87a9-49d6-a3d9-ce24b976e839.decbcde8849150199b8f1e0d47787236.jpeg" alt="Wireless Charger">
                <span class="discount">-15%</span>
            </div>
            <div class="product-info">
                <h6>Fast Wireless Charging Pad</h6>
                <h3>₱850 <del>₱1,000</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 1,500 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Smart Speaker with Voice Assistant" 
            data-sizes="N/A" 
            data-colors="Gray,Charcoal,White" 
            data-price="1999" 
            data-orig-price="2500"
            data-img-gray="https://img.freepik.com/premium-photo/smart-speaker-with-voice-assistant_506134-10019.jpg"
            data-img-charcoal="https://m.media-amazon.com/images/I/71u9S+7qGLL._AC_SL1500_.jpg"
            data-img-white="https://m.media-amazon.com/images/I/71wK8nS-78L._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://img.freepik.com/premium-photo/smart-speaker-with-voice-assistant_506134-10019.jpg" alt="Smart Speaker">
                <span class="discount">-20%</span>
            </div>
            <div class="product-info">
                <h6>Smart Speaker with Voice Assistant</h6>
                <h3>₱1,999 <del>₱2,500</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 800 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Waterproof Portable Bluetooth Speaker" 
            data-sizes="N/A" 
            data-colors="Black,Red,Green" 
            data-price="750" 
            data-orig-price="1000"
            data-img-black="https://i5.walmartimages.com/seo/Bluetooth-Speaker-HD-Sound-Portable-Wireless-IPX5-Waterproof-Up-24H-Playtime-TWS-Pairing-BT5-3-Home-Party-Outdoor-Beach-Electronic-Gadgets-Birthday-G_6b79ed12-e892-459c-a917-b00eb54c8c20.c08273c52c6820892dfc55dfdcd9ca0e.jpeg"
            data-img-red="https://m.media-amazon.com/images/I/815yv4a5gOL._AC_SL1500_.jpg"
            data-img-green="https://m.media-amazon.com/images/I/81T6f5fS2vL._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://i5.walmartimages.com/seo/Bluetooth-Speaker-HD-Sound-Portable-Wireless-IPX5-Waterproof-Up-24H-Playtime-TWS-Pairing-BT5-3-Home-Party-Outdoor-Beach-Electronic-Gadgets-Birthday-G_6b79ed12-e892-459c-a917-b00eb54c8c20.c08273c52c6820892dfc55dfdcd9ca0e.jpeg" alt="Portable Speaker">
                <span class="discount">-25%</span>
            </div>
            <div class="product-info">
                <h6>Waterproof Portable Bluetooth Speaker</h6>
                <h3>₱750 <del>₱1,000</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 2,100 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="1TB Portable External Hard Drive" 
            data-sizes="500GB,1TB,2TB" 
            data-colors="Black" 
            data-price="2800" 
            data-orig-price="3111"
            data-img-black="https://www.royalcomputersolution.com/wp-content/uploads/2019/06/lenovo-extrnal-2tb-hard-drive-3-1024x984.jpg">
            <div class="product-img">
                <img src="https://www.royalcomputersolution.com/wp-content/uploads/2019/06/lenovo-extrnal-2tb-hard-drive-3-1024x984.jpg" alt="External Hard Drive">
                <span class="discount">-10%</span>
            </div>
            <div class="product-info">
                <h6>1TB Portable External Hard Drive</h6>
                <h3>₱2,800 <del>₱3,111</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 400 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="128GB High-Speed USB Flash Drive" 
            data-sizes="32GB,64GB,128GB,256GB" 
            data-colors="N/A" 
            data-price="550" 
            data-orig-price="786"
            data-img-default="https://images-na.ssl-images-amazon.com/images/I/61wk1z9PrZL.jpg">
            <div class="product-img">
                <img src="https://images-na.ssl-images-amazon.com/images/I/61wk1z9PrZL.jpg" alt="USB Flash Drive">
                <span class="discount">-30%</span>
            </div>
            <div class="product-info">
                <h6>128GB High-Speed USB Flash Drive</h6>
                <h3>₱550 <del>₱786</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 3,500 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="4K Camera Drone with GPS" 
            data-sizes="N/A" 
            data-colors="White,Black" 
            data-price="12000" 
            data-orig-price="14118"
            data-img-white="https://m.media-amazon.com/images/I/718Btp+-iCL._AC_.jpg"
            data-img-black="https://m.media-amazon.com/images/I/71u9WJ8X+rL._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/718Btp+-iCL._AC_.jpg" alt="Drone">
                <span class="discount">-15%</span>
            </div>
            <div class="product-info">
                <h6>4K Camera Drone with GPS</h6>
                <h3>₱12,000 <del>₱14,118</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 50 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="GoPro HERO12 Action Camera" 
            data-sizes="N/A" 
            data-colors="Black" 
            data-price="18500" 
            data-orig-price="21023"
            data-img-black="https://microless.com/cdn/products/6a05af6ac59c50a51c41219ac3dbc57c-hi.jpg">
            <div class="product-img">
                <img src="https://microless.com/cdn/products/6a05af6ac59c50a51c41219ac3dbc57c-hi.jpg" alt="Action Camera">
                <span class="discount">-12%</span>
            </div>
            <div class="product-info">
                <h6>GoPro HERO12 Action Camera</h6>
                <h3>₱18,500 <del>₱21,023</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 150 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Smart Power Strip with USB Ports" 
            data-sizes="N/A" 
            data-colors="White,Black" 
            data-price="890" 
            data-orig-price="967"
            data-img-white="https://m.media-amazon.com/images/I/61MScwtXX2L._AC_SL1500_.jpg"
            data-img-black="https://m.media-amazon.com/images/I/71N4y+8V5zL._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/61MScwtXX2L._AC_SL1500_.jpg" alt="Power Strip">
                <span class="discount">-8%</span>
            </div>
            <div class="product-info">
                <h6>Smart Power Strip with USB Ports</h6>
                <h3>₱890 <del>₱967</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 600 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="Digital Smart Scale" 
            data-sizes="N/A" 
            data-colors="Black,White" 
            data-price="999" 
            data-orig-price="1281"
            data-img-black="https://m.media-amazon.com/images/I/61eFoh9Kg9L._AC_SL1500_.jpg"
            data-img-white="https://m.media-amazon.com/images/I/613Fj98xQ9L._AC_SL1500_.jpg">
            <div class="product-img">
                <img src="https://m.media-amazon.com/images/I/61eFoh9Kg9L._AC_SL1500_.jpg" alt="Smart Scale">
                <span class="discount">-22%</span>
            </div>
            <div class="product-info">
                <h6>Digital Smart Scale</h6>
                <h3>₱999 <del>₱1,281</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 320 sold</p>
            </div>
        </div>

        <div class="product-card" 
            data-name="1080P Car Dash Cam" 
            data-sizes="N/A" 
            data-colors="Black" 
            data-price="1750" 
            data-orig-price="1944"
            data-img-black="https://i5.walmartimages.com/asr/e21a1dff-559d-4ef0-a06a-764c1b7cd4ed_1.b0b46d7905d569fb68cbfa8862099d69.jpeg">
            <div class="product-img">
                <img src="https://i5.walmartimages.com/asr/e21a1dff-559d-4ef0-a06a-764c1b7cd4ed_1.b0b46d7905d569fb68cbfa8862099d69.jpeg" alt="Dash Cam">
                <span class="discount">-10%</span>
            </div>
            <div class="product-info">
                <h6>1080P Car Dash Cam</h6>
                <h3>₱1,750 <del>₱1,944</del></h3>
                <p class="buyers"><i class="fas fa-user-check"></i> 480 sold</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/cart-auth.js') }}"></script>
<script>

    // buyNow function is now loaded from cart-auth.js

    function viewProduct(productName, price, image, description) {
        // Create and show product detail modal
        const modal = document.createElement('div');
        modal.className = 'product-modal';
        modal.innerHTML = `
            <div class="product-modal-content">
                <div class="product-modal-header">
                    <h3>${productName}</h3>
                    <button class="product-modal-close" onclick="closeProductModal()">&times;</button>
                </div>
                <div class="product-modal-body">
                    <div class="product-modal-image">
                        <img src="${image}" alt="${productName}">
                    </div>
                    <div class="product-modal-info">
                        <h4>₱${price.toLocaleString()}</h4>
                        <p class="product-description">${description}</p>
                        <div class="product-modal-actions">
                            <button class="btn buy" onclick="buyNow('${productName}', ${price}, '${image}'); closeProductModal();">Buy Now</button>
                            <button class="btn cart" onclick="addToCart('${productName}', ${price}, '${image}'); closeProductModal();">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal styles
        const style = document.createElement('style');
        style.textContent = `
            .product-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 10000;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .product-modal.show {
                opacity: 1;
            }
            .product-modal-content {
                background: white;
                border-radius: 12px;
                max-width: 600px;
                width: 90%;
                max-height: 90vh;
                overflow-y: auto;
                transform: scale(0.8);
                transition: transform 0.3s ease;
                display: flex;
                flex-direction: column;
            }
            .product-modal.show .product-modal-content {
                transform: scale(1);
            }
            .product-modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px;
                border-bottom: 1px solid #eee;
            }
            .product-modal-header h3 {
                margin: 0;
                color: #333;
            }
            .product-modal-close {
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
                color: #999;
            }
            .product-modal-close:hover {
                color: #333;
            }
            .product-modal-body {
                display: flex;
                padding: 20px;
                gap: 20px;
                flex: 1;
                overflow-y: auto;
            }
            .product-modal-image {
                flex: 1;
            }
            .product-modal-image img {
                width: 100%;
                height: 200px;
                object-fit: cover;
                border-radius: 8px;
            }
            .product-modal-info {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .product-modal-info h4 {
                font-size: 24px;
                color: #e74c3c;
                margin: 0 0 15px 0;
            }
            .product-description {
                color: #666;
                line-height: 1.6;
                margin-bottom: 20px;
            }
            .product-modal-actions {
                display: flex;
                gap: 10px;
                margin-top: auto;
                padding-top: 20px;
                flex-shrink: 0;
            }
            .product-modal-actions .btn {
                padding: 12px 20px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.3s ease;
                flex: 1;
                text-align: center;
                white-space: nowrap;
                font-size: 14px;
            }
            .product-modal-actions .btn.buy {
                background: #e74c3c;
                color: white;
            }
            .product-modal-actions .btn.buy:hover {
                background: #c0392b;
            }
            .product-modal-actions .btn.cart {
                background: #3498db;
                color: white;
            }
            .product-modal-actions .btn.cart:hover {
                background: #2980b9;
            }
            @media (max-width: 768px) {
                .product-modal-body {
                    flex-direction: column;
                    padding: 15px;
                }
                .product-modal-actions {
                    flex-direction: column;
                    gap: 8px;
                }
                .product-modal-actions .btn {
                    padding: 14px 20px;
                    font-size: 16px;
                }
                .product-modal-content {
                    max-height: 95vh;
                    width: 95%;
                }
            }
        `;
        
        document.head.appendChild(style);
        document.body.appendChild(modal);
        
        // Show modal with animation
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        
        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeProductModal();
            }
        });
    }

    function closeProductModal() {
        const modal = document.querySelector('.product-modal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.remove();
            }, 300);
        }
    }
</script>
@endsection
