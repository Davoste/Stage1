<?php
header('Content-Type: application/json');

$ipaddress = '';

function get_client_ip() {
    
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
    
    return $ipaddress;
}
 
 $clientIp = get_client_ip();

function get_location($clientIp) {
// set IP address and API access key
$ip = $clientIp ;
$access_key = '7879e052086bd13ff2b285c600280adb';

// Initialize CURL:
$ch = curl_init('http://api.ipapi.com/'.$ip.'?access_key='.$access_key.'');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
$json = curl_exec($ch);
curl_close($ch);

//echo $json;

// Decode JSON response:
$api_result = json_decode($json, true);

return $api_result ;

}

$location = get_location($clientIp);
$city = $location['city'];

function get_temperature($city) {
    $apiKey = '51d08ed5bd0aa26f024ec8c75acf5ff2'; // Replace with your OpenWeatherMap key
    $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&appid={$apiKey}";
    $weatherData = file_get_contents($url);
    $weatherArray = json_decode($weatherData, true);
    return $weatherArray['main']['temp'];
}

$visitorName = isset($_GET['visitor_name']) ? htmlspecialchars($_GET['visitor_name']) : 'Guest';
$temperature = get_temperature($city);

$response = array(
    'client_ip' => $clientIp,
    'location' => $city,
    'greeting' => "Hello, {$visitorName}!, the temperature is {$temperature} degrees Celsius in {$city}"
);


echo json_encode($response);
?>