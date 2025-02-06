<?php
require __DIR__ . '/vendor/autoload.php';

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client_id = $_ENV['PAYPAL_CLIENT_ID'];
$client_secret = $_ENV['PAYPAL_CLIENT_SECRET'];

$environment = new SandboxEnvironment($client_id, $client_secret);
$client = new PayPalHttpClient($environment);

function sendJsonResponse($data, $statusCode = 200)
{
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

// Ensure 'action' parameter exists before proceeding
$action = $_GET['action'] ?? $_POST['action'] ?? null;
if (!$action) {
    // Log the query string if available, else log 'None'
    error_log("No action specified. Query String: " . (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : 'None'));
    sendJsonResponse(["error" => "No action specified"], 400);
}

// Debugging: Display the request method and URI for logging purposes
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
error_log("Received action: " . $action);
header('Content-Type: application/json');

function getAccessToken($clientId, $secret)
{
    $authToken = base64_encode("$clientId:$secret");

    $ch = curl_init("https://api.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic $authToken",
        "Content-Type: application/x-www-form-urlencoded"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (isset($data['access_token'])) {
        return $data['access_token'];
    }

    throw new Exception("Failed to retrieve access token.");
}

// Set your PayPal Client ID and Secret 

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === "get_access_token") {
        try {
            $token = getAccessToken($client_id, $client_secret);
            echo json_encode(["access_token" => $token]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
        exit;
    }

    // Add other PayPal API actions (e.g., create order, capture payment)
}

// Handle create order request
if ($action === 'create_order' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $body = json_decode(file_get_contents('php://input'), true);
        $amount = $body['amount'] ?? '0.00';

        $orderRequest = new OrdersCreateRequest();
        $orderRequest->prefer('return=representation');
        $orderRequest->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $amount
                    ]
                ]
            ]
        ];

        $apiResponse = $client->execute($orderRequest);
        $orderData = $apiResponse->result;

        sendJsonResponse($orderData);
    } catch (Exception $e) {
        sendJsonResponse(['error' => $e->getMessage()], 500);
    }
}

// Handle capture payment request
if ($action === 'capture_payment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_GET['orderId'])) {
        sendJsonResponse(['error' => 'Order ID is required'], 400);
    }

    $orderId = $_GET['orderId'];

    try {
        $captureRequest = new OrdersCaptureRequest($orderId);
        $apiResponse = $client->execute($captureRequest);
        $captureData = $apiResponse->result;

        sendJsonResponse($captureData);
    } catch (Exception $e) {
        sendJsonResponse(['error' => $e->getMessage()], 500);
    }
}


// Default response
sendJsonResponse(['error' => 'Invalid endpoint'], 404);
