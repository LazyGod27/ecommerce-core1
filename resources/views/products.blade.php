<!DOCTYPE html>
<html>
<head>
    <title>Product Search</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <h1>Product Search</h1>
    
    <div>
        <input type="text" id="search" placeholder="Search products...">
        <button onclick="searchProducts()">Search</button>
    </div>
    
    <div id="results"></div>
    
    <script>
        function searchProducts() {
            const searchTerm = document.getElementById('search').value;
            
            axios.get('/api/products', {
                params: {
                    search: searchTerm
                }
            })
            .then(response => {
                const products = response.data.data;
                let html = '<ul>';
                
                products.forEach(product => {
                    html += `<li>${product.name} - $${product.price}</li>`;
                });
                
                html += '</ul>';
                document.getElementById('results').innerHTML = html;
            })
            .catch(error => {
                console.error(error);
            });
        }
        
        // Initial load
        searchProducts();
    </script>
</body>
</html>