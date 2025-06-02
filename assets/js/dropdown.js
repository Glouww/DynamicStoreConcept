document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category-select');
    const productSelect = document.getElementById('product-select');
    const productDisplay = document.getElementById('product-display');
    const productName = document.getElementById('product-name');
    const productPrice = document.getElementById('product-price');
    const productImage = document.getElementById('product-image');

    // Store the products data
    let currentProducts = [];

    // Handle category selection change
    categorySelect.addEventListener('change', function() {
        const selectedCategory = this.value;
        
        // Reset product select and display
        productSelect.innerHTML = '<option value="">Select a product</option>';
        productSelect.disabled = true;
        productDisplay.style.display = 'none';
        productImage.style.display = 'none';

        if (!selectedCategory) return;

        // Fetch products for selected category
        fetch(`../api/get_products.php?category=${encodeURIComponent(selectedCategory)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(products => {
                // Store the products data
                currentProducts = products;
                
                // Populate product dropdown
                products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.ProductID;
                    option.textContent = product.ProductName;
                    productSelect.appendChild(option);
                });
                productSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                alert('Error loading products. Please try again.');
            });
    });

    // Handle product selection change
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (!selectedOption.value) {
            productDisplay.style.display = 'none';
            productImage.style.display = 'none';
            return;
        }

        // Find the selected product from our cached data
        const selectedProduct = currentProducts.find(p => p.ProductID === parseInt(this.value));
        
        if (selectedProduct) {
            productName.textContent = selectedProduct.ProductName;
            // Convert price to number and format it
            const price = parseFloat(selectedProduct.Price);
            productPrice.textContent = isNaN(price) ? 'N/A' : price.toFixed(2);
            
            // Handle product image
            if (selectedProduct.ProductImage) {
                // Convert the binary image data to a base64 string
                const imageData = btoa(String.fromCharCode.apply(null, new Uint8Array(selectedProduct.ProductImage)));
                productImage.src = `data:image/jpeg;base64,${imageData}`;
                productImage.style.display = 'block';
            } else {
                productImage.style.display = 'none';
            }
            
            productDisplay.style.display = 'block';
        } else {
            console.error('Selected product not found in cached data');
            productDisplay.style.display = 'none';
            productImage.style.display = 'none';
        }
    });
}); 