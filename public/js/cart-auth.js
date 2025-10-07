// Shared cart functionality with authentication check
function goToCart() {
    // Check if user is authenticated
    fetch('/api/user', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.status === 401 || response.status === 403) {
            // User not authenticated, redirect to login
            window.location.href = '/login';
            return;
        }
        if (response.ok) {
            // User is authenticated, proceed to cart
            window.location.href = '/cart';
        }
    })
    .catch(error => {
        console.error('Error checking authentication:', error);
        // On error, redirect to login to be safe
        window.location.href = '/login';
    });
}

function addToCart(productName, price, image) {
    // Check if user is authenticated
    fetch('/api/user', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.status === 401 || response.status === 403) {
            // User not authenticated, redirect to login
            window.location.href = '/login';
            return;
        }
        if (response.ok) {
            // User is authenticated, proceed with adding to cart
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const form = document.getElementById('add-to-cart-form');
            const syntheticId = Date.now();
            form.setAttribute('action', `${window.location.origin}/cart/add/${syntheticId}`);
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrf}">
                <input type="hidden" name="product_name" value="${productName}">
                <input type="hidden" name="product_price" value="${price}">
                <input type="hidden" name="product_image" value="${image}">
                <input type="hidden" name="quantity" value="1">
            `;
            form.submit();
        }
    })
    .catch(error => {
        console.error('Error checking authentication:', error);
        // On error, redirect to login to be safe
        window.location.href = '/login';
    });
}

function addToCartWithOptions(productName, price, image) {
    // Check if user is authenticated
    fetch('/api/user', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.status === 401 || response.status === 403) {
            // User not authenticated, redirect to login
            window.location.href = '/login';
            return;
        }
        if (response.ok) {
            // User is authenticated, proceed with adding to cart
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const form = document.getElementById('add-to-cart-form');
            const syntheticId = Date.now();
            form.setAttribute('action', `${window.location.origin}/cart/add/${syntheticId}`);
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrf}">
                <input type="hidden" name="product_name" value="${productName}">
                <input type="hidden" name="product_price" value="${price}">
                <input type="hidden" name="product_image" value="${image}">
                <input type="hidden" name="quantity" value="1">
            `;
            form.submit();
        }
    })
    .catch(error => {
        console.error('Error checking authentication:', error);
        // On error, redirect to login to be safe
        window.location.href = '/login';
    });
}

function buyNow(productName, price, image) {
    // Check if user is authenticated
    fetch('/api/user', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.status === 401 || response.status === 403) {
            // User not authenticated, redirect to login
            window.location.href = '/login';
            return;
        }
        if (response.ok) {
            // User is authenticated, proceed with direct checkout
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/checkout/direct';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrf;
            form.appendChild(csrfInput);
            
            // Add product details
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'product_name';
            nameInput.value = productName;
            form.appendChild(nameInput);
            
            const priceInput = document.createElement('input');
            priceInput.type = 'hidden';
            priceInput.name = 'product_price';
            priceInput.value = price;
            form.appendChild(priceInput);
            
            const imageInput = document.createElement('input');
            imageInput.type = 'hidden';
            imageInput.name = 'product_image';
            imageInput.value = image;
            form.appendChild(imageInput);
            
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = '1';
            form.appendChild(quantityInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    })
    .catch(error => {
        console.error('Error checking authentication:', error);
        // On error, redirect to login to be safe
        window.location.href = '/login';
    });
}

function buyNowWithOptions(productName, price, image) {
    // Check if user is authenticated
    fetch('/api/user', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.status === 401 || response.status === 403) {
            // User not authenticated, redirect to login
            window.location.href = '/login';
            return;
        }
        if (response.ok) {
            // User is authenticated, proceed with direct checkout
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/checkout/direct';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrf;
            form.appendChild(csrfInput);
            
            // Add product details
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'product_name';
            nameInput.value = productName;
            form.appendChild(nameInput);
            
            const priceInput = document.createElement('input');
            priceInput.type = 'hidden';
            priceInput.name = 'product_price';
            priceInput.value = price;
            form.appendChild(priceInput);
            
            const imageInput = document.createElement('input');
            imageInput.type = 'hidden';
            imageInput.name = 'product_image';
            imageInput.value = image;
            form.appendChild(imageInput);
            
            // Get quantity from options (if available)
            const quantity = getSelectedQuantity ? getSelectedQuantity() : 1;
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = quantity;
            form.appendChild(quantityInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    })
    .catch(error => {
        console.error('Error checking authentication:', error);
        // On error, redirect to login to be safe
        window.location.href = '/login';
    });
}
