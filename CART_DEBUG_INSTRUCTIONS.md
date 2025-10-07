## Cart Debugging Instructions

### Step 1: Check Browser Console
1. Open your browser's developer tools (F12)
2. Go to the Console tab
3. Try to add items to cart and proceed to checkout
4. Look for any JavaScript errors

### Step 2: Check Laravel Logs
1. Check storage/logs/laravel.log for any errors
2. Look for the debug messages we added to CartController

### Step 3: Test Cart Functionality
1. Add items to cart
2. Check if cart displays correctly
3. Try to proceed to checkout
4. Check if selected items are passed correctly

### Step 4: Common Issues and Fixes

#### Issue: Cart appears empty on checkout
**Possible Causes:**
- Session not working properly
- JavaScript errors preventing form submission
- CSRF token issues
- Cart data not being passed correctly

**Fixes:**
1. Clear browser cache and cookies
2. Check if JavaScript is enabled
3. Verify session configuration
4. Check Laravel logs for errors

#### Issue: Selected items not passed to checkout
**Possible Causes:**
- JavaScript not selecting items properly
- Form not submitting selected items
- Server not receiving selected items

**Fixes:**
1. Check cart.blade.php JavaScript
2. Verify checkout form has selected_items field
3. Check CartController debugging logs

### Step 5: Manual Testing
1. Add items to cart
2. Select items using checkboxes
3. Click "Proceed to Checkout"
4. Check if items appear on checkout page
5. Fill out checkout form and submit
6. Check if order is created successfully

### Step 6: Debug Information
- Session ID: Check browser cookies
- Cart data: Check browser developer tools > Application > Local Storage
- Request data: Check Network tab in developer tools
- Server logs: Check storage/logs/laravel.log
