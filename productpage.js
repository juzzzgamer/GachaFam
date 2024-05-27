document.addEventListener('DOMContentLoaded', (event) => {
    const productImgContainer = document.querySelector('.product_img');

    productImgContainer.addEventListener('wheel', (e) => {
        e.preventDefault();
        productImgContainer.scrollLeft += e.deltaY;
    });

    let valueCount = 1;
    let increment = document.querySelector('.btn-increment');
    let decrement = document.querySelector('.btn-decrement');
    let count = document.querySelector('#quantity');
    let totalcount = document.querySelector('#price');

    increment.addEventListener('click', () => {
        valueCount++;
        count.value = valueCount;
        totalcount.innerHTML = valueCount * 5;
    });

    decrement.addEventListener('click', () => {
        if (valueCount > 1) {
            valueCount--;
            count.value = valueCount;
            totalcount.innerHTML = valueCount * 5;
        }
    });
});
