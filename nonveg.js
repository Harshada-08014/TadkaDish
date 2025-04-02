// Get references to DOM elements
const orderList = document.getElementById("order-list");
const totalPriceElement = document.getElementById("total-price");
const checkoutBtn = document.getElementById("checkout-btn");

let orderItems = []; // Array to store order items
let totalPrice = 0;  // Variable to store the total price

// Add event listeners to "Add to Order" buttons
const orderBtns = document.querySelectorAll('.order-btn');
orderBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
        const name = e.target.getAttribute('data-name');
        const price = parseFloat(e.target.getAttribute('data-price'));

        // Add item to order array
        orderItems.push({ name, price });

        // Update the total price
        totalPrice += price;

        // Update the order summary
        updateOrderSummary();
    });
});

// Function to update the order summary
function updateOrderSummary() {
    // Clear the existing order list
    orderList.innerHTML = "";

    // Add items to the order list
    orderItems.forEach(item => {
        const li = document.createElement("li");
        li.textContent = `${item.name} - ₹${item.price.toFixed(2)}`; // Change to ₹ for INR
        orderList.appendChild(li);
    });

    // Update total price
    totalPriceElement.textContent = `Total: ₹${totalPrice.toFixed(2)}`; // Change to ₹ for INR
}

// Checkout button action
checkoutBtn.addEventListener('click', () => {
    if (orderItems.length === 0) {
        alert("Your order is empty. Please add items to your order.");
        return;
    }

    alert("Thank you for your order! We will contact you shortly.");
    orderItems = []; // Clear the order
    totalPrice = 0;  // Reset the total price
    updateOrderSummary();  // Update the summary
});
