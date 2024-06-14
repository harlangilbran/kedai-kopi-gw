<?php
// Ensure the POST data is set before proceeding
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for the required fields
    if (!isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['total'], $_POST['items'])) {
        echo "Incomplete form data";
        exit;
    }

    // Include the Midtrans PHP SDK
    require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php';

    // Set your Merchant Server Key
    \Midtrans\Config::$serverKey = 'SB-Mid-server-vKohbaJZvL6_xaZTidvj69Lp';
    // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
    \Midtrans\Config::$isProduction = false;
    // Set sanitization on (default)
    \Midtrans\Config::$isSanitized = true;
    // Set 3DS transaction for credit card to true
    \Midtrans\Config::$is3ds = true;

    // Setup transaction parameters
    $params = array(
        'transaction_details' => array(
            'order_id' => rand(),  // Generate a unique order_id
            'gross_amount' => (int)$_POST['total'],  // Cast to integer
        ),
        'item_details' => json_decode($_POST['items'], true),  // Decode the JSON string into an array
        'customer_details' => array(
            'first_name' => htmlspecialchars($_POST['name']),  // Sanitize user input
            'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),  // Sanitize email
            'phone' => htmlspecialchars($_POST['phone']),  // Sanitize user input
        ),
    );

    try {
        // Get the Snap token
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        echo $snapToken;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
