<?php
header('Content-Type: application/json');

$url = "http://10.248.1.10/BIPHMESWEBTEST/api/IqcDataInfo/GetSamplingOrders?beginInDate=2025-03-01&endInDate=2025-03-31";

$response = @file_get_contents($url);
if ($response === false) {
    echo json_encode(["error" => "Failed to fetch data from API"]);
    exit;
}

$data = json_decode($response, true);
if ($data === null) {
    echo json_encode(["error" => "Invalid JSON response"]);
    exit;
}

echo json_encode($data); // ✅ This actually sends the JSON to the client
