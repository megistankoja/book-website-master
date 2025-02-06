paypal.Buttons({
    createOrder: async function(data, actions) {
        try {
            const amountField = document.getElementById('grand_total');
            if (!amountField || isNaN(parseFloat(amountField.value))) {
                throw new Error('Invalid amount value');
            }
            const amount = parseFloat(amountField.value);

            const response = await fetch('/paypal_api.php?action=create_order', { 
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ amount })
            });

            const orderData = await response.json();
            if (!orderData.id) throw new Error('Failed to create order');
            return orderData.id;
        } catch (error) {
            console.error('Error creating order:', error);
            alert('Order creation failed. Please try again.');
        }
    },
    
    onApprove: async function(data, actions) {
        try {
            const captureResponse = await fetch(`./Functional-Book-Store-Website-master/paypal_api.php?action=capture_payment&orderId=${data.orderID.trim()}`, { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' }
            });

            const captureData = await captureResponse.json();
            console.log("Capture Response:", captureData);

            //  Check if payment is actually captured
            if (!captureData || captureData.status !== 'COMPLETED') {
                throw new Error('Payment capture failed');
            }

            //  Form data handling
            const formData = {
                name: document.querySelector('input[name="name"]')?.value || 'N/A',
                number: document.querySelector('input[name="number"]')?.value || 'N/A',
                email: document.querySelector('input[name="email"]')?.value || 'N/A',
                method: 'paypal',
                address: [
                    document.querySelector('input[name="flat"]')?.value || '',
                    document.querySelector('input[name="street"]')?.value || '',
                    document.querySelector('input[name="city"]')?.value || '',
                    document.querySelector('select[name="european-countries"]')?.value || '',
                    document.querySelector('input[name="pin_code"]')?.value || ''
                ].filter(Boolean).join(', '),
                payment_status: 'completed'
            };

            const saveResponse = await fetch('save_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            const saveData = await saveResponse.json();
            if (saveData.success) {
                window.location.href = 'orders.php';
            } else {
                throw new Error(saveData.error);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Payment processing failed. Please contact support.');
        }
    }
}).render('#paypal-button-container');
