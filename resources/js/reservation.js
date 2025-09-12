document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const totalAmountSpan = document.getElementById('totalAmount');
    const submitBtn = document.getElementById('submitBtn');

    function totalPrice() {
        let total = 0;
        let hasItems = false;

        quantityInputs.forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price);
            const tierId = input.dataset.tierId;
            const subtotal = quantity * price;

        
            const subtotalElement = document.getElementById('subtotal-' + tierId);
            subtotalElement.textContent = '$' + subtotal.toFixed(2);

            total += subtotal;
            if (quantity > 0) hasItems = true;
        });

        totalAmountSpan.textContent = total.toFixed(2);
                
                
        submitBtn.disabled = !hasItems;
        submitBtn.textContent = hasItems ? 'Proceed to Review →' : 'Select tickets to continue';
    }

            
        quantityInputs.forEach(input => {
            input.addEventListener('input', totalPrice);
        });

            
    totalPrice();
});
