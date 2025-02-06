function togglePayPalButton() {
    const paymentMethod = document.getElementById("payment-method").value;
    const paypalButtonContainer = document.getElementById("paypal-button-container");
 
    if (paymentMethod === "paypal") {
        paypalButtonContainer.style.display = "block"; // Show PayPal button
        paypalButtonContainer.innerHTML = ''; // Clear previous buttons
 
        // Render the PayPal button
        paypal.Buttons({
            style: {
                shape: "rect",
                layout: "vertical",
                color: "gold",
                label: "paypal",
            },
            createOrder: function (data, actions) {
                // Get the grand total from the page
                var cart_total = document.querySelector('.grand-total span').textContent.replace('$', '').replace('/-', '').trim();
                
                if (cart_total < 1) {
                    alert("Please enter a valid amount");
                    throw new Error("Invalid amount.");
                }

                // Assuming you have the userId from the frontend (via a hidden input or another method)
                var userId = document.getElementById("user-id").value;  // Get user ID from a hidden input or session
                
                // Save order details to backend
                fetch('/save_order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ total: cart_total, user_id: userId })
                }).then(response => response.json())
                  .then(data => {
                      console.log("Order saved successfully:", data);
                  }).catch(error => {
                      console.error("Error saving order:", error);
                      alert("Error saving order. Please try again.");
                  });

                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: cart_total
                        },
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    console.log(details);
                    alert("Payment successful! Transaction ID: " + details.id);
            
                    // Send order ID to your backend to capture the payment
                    fetch('/paypal_api.php?action=capture_payment&orderId=' + details.id, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                    })
                    .then(response => response.json())
                    .then(captureData => {
                        console.log('Payment capture response:', captureData);
                        if (captureData.success) {
                            alert("Payment capture successful!");
                        } else {
                            alert("Error capturing payment: " + captureData.error);
                        }
                    })
                    .catch(err => {
                        console.error("Error capturing payment:", err);
                        alert("An error occurred during payment capture.");
                    });
                });
            },
            onCancel: function (data) {
                alert("Payment canceled.");
            },
            onError: function (err) {
                console.error(err);
                alert("An error occurred during the payment process.");
            } 
        }).render('#paypal-button-container'); // Ensure correct container ID
    } else {
        paypalButtonContainer.style.display = "none"; // Hide PayPal button
    }
}
 
// Call togglePayPalButton when the payment method is changed
document.getElementById("payment-method").addEventListener("change", togglePayPalButton);
 
// Call togglePayPalButton on page load to check the initial payment method
window.onload = function () {
    togglePayPalButton();
};
