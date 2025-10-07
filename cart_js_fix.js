// Enhanced cart JavaScript with debugging
function proceedToCheckout() {
    console.log("=== CART CHECKOUT DEBUG ===");
    
    const selectedItems = [];
    const checkboxes = document.querySelectorAll(".item-checkbox:checked");
    
    console.log("Found checkboxes:", checkboxes.length);
    
    if (checkboxes.length === 0) {
        alert("Please select at least one item to proceed to checkout.");
        return;
    }
    
    checkboxes.forEach(checkbox => {
        const cartItem = checkbox.closest(".cart-item");
        const rowId = cartItem.getAttribute("data-row-id");
        console.log("Adding item to checkout:", rowId);
        selectedItems.push(rowId);
    });
    
    console.log("Selected items for checkout:", selectedItems);
    
    // Create form with enhanced debugging
    const form = document.createElement("form");
    form.method = "GET";
    form.action = "{{ route("checkout") }}";
    
    // Add selected items
    selectedItems.forEach((item, index) => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = `selected_items[${index}]`;
        input.value = item;
        form.appendChild(input);
    });
    
    // Add debugging information
    const debugInput = document.createElement("input");
    debugInput.type = "hidden";
    debugInput.name = "debug_selected_count";
    debugInput.value = selectedItems.length;
    form.appendChild(debugInput);
    
    console.log("Submitting form to checkout...");
    document.body.appendChild(form);
    form.submit();
}

// Enhanced cart update function
function updateCart() {
    console.log("Updating cart display...");
    
    const cartItems = document.querySelectorAll(".cart-item");
    let selectedCount = 0;
    
    cartItems.forEach(item => {
        const checkbox = item.querySelector(".item-checkbox");
        if (checkbox && checkbox.checked) {
            selectedCount++;
        }
    });
    
    console.log("Selected items count:", selectedCount);
    
    // Update UI
    const countElement = document.getElementById("checkout-items-count");
    if (countElement) {
        countElement.textContent = selectedCount;
    }
    
    // Update checkout button
    const checkoutButton = document.getElementById("checkout-button");
    if (checkoutButton) {
        checkoutButton.disabled = selectedCount === 0;
        if (selectedCount === 0) {
            checkoutButton.style.opacity = "0.5";
            checkoutButton.style.cursor = "not-allowed";
        } else {
            checkoutButton.style.opacity = "1";
            checkoutButton.style.cursor = "pointer";
        }
    }
}