document.addEventListener('DOMContentLoaded', function() {
    // Order details modal
    const modal = document.getElementById("orderDetailsModal");
    const closeBtn = document.querySelector(".close-btn");

    // Open the modal with order details
    document.querySelectorAll(".view-details").forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            fetchOrderDetails(orderId);
        });
    });

    // Close modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = "none";
    });

    // Fetch and display order details
    function fetchOrderDetails(orderId) {
        fetch(`order_details.php?order_id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("order-details-content").innerHTML = `
                    <p>Order ID: ${data.order_id}</p>
                    <p>Customer: ${data.name}</p>
                    <p>Items: ${data.food_items}</p>
                    <p>Total Price: ₹${data.total_price}</p>
                    <p>Status: ${data.status}</p>
                    <p>Payment Status: ${data.payment_status}</p>
                `;
                modal.style.display = "block";
            });
    }
});
