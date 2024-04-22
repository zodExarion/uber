<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function showForm()
    {
        return view('location_form');
    }

    public function getCoordinates(Request $request)
    {
        try {
            $from = urlencode($request->input('from'));
            $to = urlencode($request->input('to'));
            $apiKey = env('GOOGLE_MAPS_API_KEY');
            
            // Geocoding API request to get latitude and longitude
            $fromResponse = Http::get("https://maps.googleapis.com/maps/api/geocode/json?address=$from&key=$apiKey");
            $toResponse = Http::get("https://maps.googleapis.com/maps/api/geocode/json?address=$to&key=$apiKey");
    
            $fromCoords = $fromResponse['results'][0]['geometry']['location'];
            $toCoords = $toResponse['results'][0]['geometry']['location'];
    
            return response()->json([
                'from' => $fromCoords,
                'to' => $toCoords,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

}
