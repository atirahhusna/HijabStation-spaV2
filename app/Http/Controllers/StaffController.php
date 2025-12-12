<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function DisplayList()
{
    $upcomingBookings = Booking::with(['treatment', 'user', 'order'])
        ->whereDoesntHave('order', function ($query) {
            $query->where('orderStatus', 'Done');
        })
        ->orderBy('date', 'asc')
        ->get();

    return view('Staff.BookingList', compact('upcomingBookings'));
}

    public function markAsDone($bookingID)
    {
        $booking = Booking::with('user')->findOrFail($bookingID);

        Order::updateOrCreate(
            ['bookingID' => $booking->BookingID],
            [
                'userID' => Auth::user()->id, // Staff ID
                'name' => Auth::user()->name, // Staff name
                'orderStatus' => 'Done',
            ]
        );

        return redirect()->route('Staff.BookingList')->with('success', 'Booking marked as Done!');
    }



        
                public function dashboard()
{
    $staffId = Auth::id();

    $ordersPerMonth = DB::table('orders')
        ->select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->where('userID', $staffId)
        ->where('orderStatus', 'Done')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    $months = array_fill(1, 12, 0);

    foreach ($ordersPerMonth as $row) {
        $months[$row->month] = $row->total;
    }

    $labels = [];
    $data = [];
    foreach ($months as $monthNum => $total) {
        $labels[] = date('F', mktime(0, 0, 0, $monthNum, 10));
        $data[] = $total;
    }

    return view('Staff.Dashboard', compact('labels', 'data'));
}

}
