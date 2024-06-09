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



function showWinnerForm() {
    document.getElementById('winnerpage').style.display = 'block';
}


document.addEventListener("DOMContentLoaded", function() {
    const winnerPopup = document.getElementById("winnerpage");
    const showPopup = winnerPopup.getAttribute('data-show-popup') === 'true';

    if (showPopup) {
        setTimeout(function() {
            winnerPopup.classList.add("show");

            // Confetti animation
            let canvas = document.createElement("canvas");
            document.body.appendChild(canvas);

            canvas.style.position = 'fixed';
            canvas.style.top = '0';
            canvas.style.left = '0';
            canvas.style.width = '100%';
            canvas.style.height = '100%';
            canvas.style.pointerEvents = 'none';
            canvas.style.zIndex = '9999';

            let confettiInstance = confetti.create(canvas, {
                resize: true,
                useWorker: true
            });

            confettiInstance({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 }
            }).then(() => document.body.removeChild(canvas));
        }, 300); // Slight delay for the animation to be noticeable
    }
});

function closePopup() {
    const winnerPopup = document.getElementById("winnerpage");
    winnerPopup.classList.remove("show");
    setTimeout(function() {
        winnerPopup.style.display = "none";
    }, 500); // Matches the transition duration
}

