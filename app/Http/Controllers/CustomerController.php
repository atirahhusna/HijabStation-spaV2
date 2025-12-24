<?php

namespace App\Http\Controllers;
use App\Models\Treatment;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    public function index()
    {
        $treatments = Treatment::all();
        return view('Customer.Index', compact('treatments'));
    }

      public function List()
{
    $today = Carbon::today();

    $upcomingBookings = Booking::with('treatment')
        ->where('userID', Auth::id())
        ->whereDate('date', '>=', $today)
        ->orderBy('date', 'asc')
        ->get();

    $pastBookings = Booking::with('treatment')
        ->where('userID', Auth::id())
        ->whereDate('date', '<', $today)
        ->orderBy('date', 'desc')
        ->get();

    return view('customer.BookingList', compact('upcomingBookings', 'pastBookings'));
}
       
     public function showBooking($id, Request $request)
{
    $treatment = Treatment::findOrFail($id);
    $allSlots = collect(json_decode($treatment->slotTime, true))->keys()->toArray();
    $selectedDate = $request->query('date');

    if ($selectedDate) {
        $bookedSlots = Booking::where('treatmentID', $id)
            ->where('date', $selectedDate)
            ->pluck('slotTime')
            ->toArray();

        $availableSlots = array_diff($allSlots, $bookedSlots);
    } else {
        $availableSlots = $allSlots;
    }

    // Get all staff for optional selection
    $staffList = \App\Models\User::where('role', 'staff')->get();

    return view('Customer.booking', [
        'treatment' => $treatment,
        'slotList' => $availableSlots,
        'selectedDate' => $selectedDate,
        'staffList' => $staffList, // <-- pass staff list to view
    ]);
}


         public function cancelBooking($id)
{
    $booking = Booking::findOrFail($id);

    // Check if the booking belongs to this customer
    if ($booking->userID !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    // Only allow cancel if date > today
    if (\Carbon\Carbon::parse($booking->date)->isPast()) {
        return back()->with('error', 'You can only cancel bookings a day before the treatment.');
    }

    $booking->delete();

    return redirect()->route('Customer.Index')->with('success', 'Booking successfully cancelled.');
}



      public function showUpcomingBooking()
{
    $today = Carbon::today();

    $upcomingBookings = Booking::with('treatment')
        ->where('userID', Auth::id())
        ->whereDate('date', '>=', $today)
        ->orderBy('date', 'asc')
        ->get();

    return view('Customer.Dashboard', compact('upcomingBookings'));
}

public function showBookingHistory()
{
    $today = Carbon::today();

    $pastBookings = Booking::with('treatment')
        ->where('userID', Auth::id())
        ->whereDate('date', '<', $today)
        ->orderBy('date', 'desc')
        ->get();

    return view('Customer.BookingList', compact('pastBookings'));
}




public function showSkinAnalysis()
{
    // Make sure your blade file is named 'skinAnalysis.blade.php' 
    // inside resources/views/customer/ folder.
    return view('Customer.SkinAnalysis'); 
}
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
        $path = $image->storeAs('images', $imageName, 'public');

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'data' => [
                'filename' => $imageName,
                'path' => $path,
                'url' => Storage::url($path) // ✅ Correct way to generate the URL
            ]
        ], 200);
    }
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

    public function create($treatmentID, Request $request)
{
    $treatment = Treatment::findOrFail($treatmentID);
    $selectedDate = $request->input('date') ?? null;

    // All registered staff
    $staffList = User::where('role', 'staff')->get();

    // Example: slotList could be dynamic or static
    $slotList = [
        '10:00 AM','11:00 AM','12:00 PM','2:00 PM','3:00 PM'
    ];

    return view('booking.form', compact('treatment', 'selectedDate', 'slotList', 'staffList'));
}

public function store(Request $request)
{
    $request->validate([
        'userID' => 'required|exists:users,id',
        'treatmentID' => 'required|exists:treatments,treatmentID',
        'email' => 'required|email',
        'name' => 'required|string',
        'phone' => 'required|string',
        'slotTime' => 'required|string',
        'date' => [
            'required',
            'date',
            'after_or_equal:today',
            function ($attribute, $value, $fail) {
                if (date('N', strtotime($value)) == 1) {
                    $fail('Sorry, Monday we are closed.');
                }
            },
        ],
    ], [
        'date.after_or_equal' => 'Booking date cannot be in the past. Please select today or a future date.',
    ]);

    Booking::create([
        'userID' => $request->userID,
        'treatmentID' => $request->treatmentID,
        'email' => $request->email,
        'name' => $request->name,
        'phone' => $request->phone,
        'slotTime' => $request->slotTime,
        'slotNum' => 0,
        'date' => $request->date,
        'staffName' => $request->staffName, // Optional
    ]);

    return redirect()->back()->with('success', 'Booking successfully made!');
}


}




