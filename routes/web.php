<?php

use Illuminate\Support\Facades\Route;
use Ixudra\Curl\Facades\Curl;
use App\Http\Controllers\LocationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('curl', function(){
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://t14ha70d-uber-v1.p.rapidapi.com/v1/products?latitude=3&longitude=2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: yYWDxH11hdTweY0ajPN5sTSSv3Q4bTJ1M7laovRP",
            "X-RapidAPI-Host: t14ha70d-uber-v1.p.rapidapi.com",
            "X-RapidAPI-Key: 7a980785b4mshc6d65853802486cp18cfc8jsnb420c0f81949"
        ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
});


Route::get('/', [LocationController::class, 'showForm']);
Route::post('/get-coordinates', [LocationController::class, 'getCoordinates']);
