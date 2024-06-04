document.addEventListener('DOMContentLoaded', (event) => {
    const productImgContainer = document.querySelector('.product_img');

    productImgContainer.addEventListener('wheel', (e) => {
        e.preventDefault();
        productImgContainer.scrollLeft += e.deltaY;
    });

    const incrementButton = document.querySelector('.btn-increment');
    const decrementButton = document.querySelector('.btn-decrement');
    const quantityInput = document.getElementById('quantity');
    const priceValueElement = document.querySelector('.priceValue');
    let totalPriceElement = document.getElementById('totalPrice');

    const priceValue = parseInt(priceValueElement.textContent);
    let quantity = parseInt(quantityInput.value);

    function updateTotalPrice() {
        if(totalPriceElement){
        const totalPrice = quantity * priceValue;
        totalPriceElement.textContent = totalPrice; // Update total price
        }
    }

    incrementButton.addEventListener('click', (event) => {
        event.preventDefault();
        quantity++;
        quantityInput.value = quantity;
        updateTotalPrice();
    });

    decrementButton.addEventListener('click', (event) => {
        event.preventDefault();
        if (quantity > 1) {
            quantity--;
            quantityInput.value = quantity;
            updateTotalPrice();
        }
     });
     quantityInput.addEventListener('input', (event) => {
        event.preventDefault();
        quantity = parseInt(quantityInput.value);
        if (isNaN(quantity) || quantity < 1) {
            quantity = 1;
            quantityInput.value = quantity;
        }
        updateTotalPrice();
    });
});