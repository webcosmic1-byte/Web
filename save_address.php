<?php
if (isset($_POST['lat']) && isset($_POST['lon'])) {
    $lat = $_POST['lat'];
    $lon = $_POST['lon'];

    // 1. The API Link (Same as before)
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lon&zoom=18&addressdetails=1";

    // 2. Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // CRITICAL: OpenStreetMap requires a User-Agent header
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) CodeTester/1.0');
    
    // Execute the request
    $response = curl_exec($ch);
    
    // Check for errors
    if(curl_errno($ch)) {
        $error_msg = curl_error($ch);
        file_put_contents('error_log.txt', "cURL Error: $error_msg" . PHP_EOL, FILE_APPEND);
        die("Connection Error");
    }
    
    curl_close($ch);

    // 3. Decode the result
    $data = json_decode($response, true);
    $address = isset($data['display_name']) ? $data['display_name'] : "Address not found";

    // 4. Save to answers.txt
    $logEntry = "[" . date('Y-m-d H:i:s') . "] " . $address . PHP_EOL;
    
    // Ensure the file is writable
    if (file_put_contents('answers.txt', $logEntry, FILE_APPEND)) {
        echo "Saved: " . $address;
    } else {
        echo "Error: Could not write to file. Check folder permissions.";
    }
} else {
    echo "No coordinates received.";
}
?>
