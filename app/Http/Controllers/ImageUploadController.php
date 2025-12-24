<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageUploadController extends Controller
{
    public function showUploadForm()
    {
        return view('image-upload');
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'CasesImage.jpg';
            
            // Save directly to public/images directory
            $destinationPath = public_path('images');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $imageName);

           
            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'filename' => $imageName,
                    'path' => 'images/' . $imageName,
                    'url' => asset('images/' . $imageName)
                ]
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image file found'
        ], 400);
    }

    public function receiveData(Request $request)
    {
        // Log incoming request
        \Log::info('========================================');
        \Log::info('📥 API RECEIVE DATA REQUEST');
        \Log::info('Timestamp: ' . now()->toDateTimeString());
        \Log::info('IP Address: ' . $request->ip());
        \Log::info('Request Data: ' . json_encode($request->all()));
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::warning('❌ Validation failed: ' . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Store data in cache instead of session for better reliability
        $data = $request->all();
        $data['created_at'] = now()->toDateTimeString();
        
        // Get existing data from cache
        $receivedData = \Cache::get('received_data', []);
        $receivedData[] = $data;
        
        // Store in cache for 24 hours
        \Cache::put('received_data', $receivedData, now()->addHours(24));
        
        \Log::info('✅ Data stored successfully in cache');
        \Log::info('Total records in cache: ' . count($receivedData));
        \Log::info('========================================');

        return response()->json([
            'success' => true,
            'message' => 'Data received successfully',
            'received_data' => $data
        ], 200);
    }

    public function getReceivedData()
    {
        // Get data from cache
        $data = \Cache::get('received_data', []);
        
        \Log::info('📊 GET RECEIVED DATA REQUEST');
        \Log::info('Records found: ' . count($data));
        
        return response()->json([
            'success' => true,
            'data' => array_reverse($data) // Show newest first
        ], 200);
    }

    public function showResults()
    {
        return view('results');
    }

    public function clearReceivedData()
    {
        \Cache::forget('received_data');
        
        return response()->json([
            'success' => true,
            'message' => 'Data cleared successfully'
        ]);
    }
}
