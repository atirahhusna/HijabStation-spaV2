<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Treatment;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\User;

class OwnerController extends Controller
{
  public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'price' => 'required|numeric',
        'duration' => 'required|string',
        'description' => 'required|string',
        'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'slots' => 'nullable|array',
    ]);

    // Save picture
    $imagePath = $request->file('picture')->store('treatments', 'public');

    // Filter and format slots
    $slotTime = [];
    foreach ($request->slots ?? [] as $time => $slot) {
        if (isset($slot['enabled'])) {
            $count = min((int)($slot['count'] ?? 1), 15); // limit to 15
            $slotTime[$time] = $count;
        }
    }

    // Save treatment
    Treatment::create([
        't_name' => $validated['name'],
        't_price' => $validated['price'],
        't_duration' => $validated['duration'],
        't_desc' => $validated['description'],
        't_pic' => $imagePath,
        'slotNum' => array_sum($slotTime),
        'slotTime' => json_encode($slotTime),
    ]);

        return redirect()->back()->with('success', 'Treatment added successfully.');
    }

 // Display the page with paginated users
    public function registerStaff(Request $request)
    {
        $owners = User::where('role', 'owner')->orderBy('name')->paginate(5, ['*'], 'owners');
        $staffs = User::where('role', 'staff')->orderBy('name')->paginate(5, ['*'], 'staffs');

        return view('Owner.registerStaff', compact('owners', 'staffs'));
    }


//store new user
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:customer,staff,owner',
        'password' => 'required|min:8|confirmed',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => Hash::make($request->password),
    ]);

    //  Redirect BACK to same page with success message
    return redirect()->back()->with('success', 'User registered successfully!');
}

    public function DisplayTreatment()
   {
    $treatments = Treatment::all(); // You can also paginate or order if needed
    return view('Owner.Treatment.DisplayTreatment', compact('treatments'));
   }

   public function AddTreatment()
{
    return view('Owner.Treatment.AddTreatment');
}

  
public function edit($id)
{
    $treatment = Treatment::findOrFail($id);
    return view('Owner.Treatment.edit', compact('treatment'));
}

public function update(Request $request, $id)
{
    $treatment = Treatment::findOrFail($id);

    // Make all fields optional during update
    $request->validate([
        't_name' => 'nullable|string|max:255',
        't_price' => 'nullable|numeric|min:0',
        't_desc' => 'nullable|string',
        't_pic' => 'nullable|string',
        't_duration' => 'nullable|integer|min:1',
        // slots handled separately
    ]);

    // Only update fields if they are provided
    if ($request->filled('t_name')) {
        $treatment->t_name = $request->t_name;
    }

    if ($request->filled('t_price')) {
        $treatment->t_price = $request->t_price;
    }

    if ($request->filled('t_desc')) {
        $treatment->t_desc = $request->t_desc;
    }

    if ($request->filled('t_duration')) {
        $treatment->t_duration = $request->t_duration;
    }

    if ($request->hasFile('picture')) {
        $image = $request->file('picture');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/treatments', $imageName);
        $treatment->t_pic = 'treatments/' . $imageName;
    }

    // Convert updated slots to slotTime format
    $slotTime = [];

    if ($request->has('slots')) {
        foreach ($request->input('slots') as $time => $slot) {
            if (isset($slot['enabled'])) {
                $slotTime[$time] = (int) $slot['count'];
            }
        }

        $treatment->slotTime = $slotTime;
    }

    $treatment->save();

    return redirect()->route('Owner.Treatment.DisplayTreatment')->with('success', 'Treatment updated successfully!');
}

public function todayBookings()
{
    $today = Carbon::today();
    $todayBookings = Booking::with('user', 'treatment')
        ->whereDate('date', $today)
        ->orderBy('slotTime')
        ->get();

    return view('Owner.LandingPage', compact('todayBookings'));
}

public function destroy($id)
{
    $treatment = Treatment::findOrFail($id);

    // Delete image if exists
    if ($treatment->t_pic && Storage::disk('public')->exists($treatment->t_pic)) {
        Storage::disk('public')->delete($treatment->t_pic);
    }

    $treatment->delete();

    return redirect()->route('Owner.Treatment.DisplayTreatment')->with('success', 'Treatment deleted successfully.');
}



public function deleteUser(User $user)
{
    // Optional safety: prevent deleting yourself
    if ($user->id === auth()->id()) {
        return back()->withErrors('You cannot delete your own account.');
    }

    $user->delete();
    return back()->with('success', 'User account terminated successfully.');
}

// AJAX search for owners
    public function ownersAjax(Request $request)
    {
        $owners = User::where('role', 'owner')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                                                 ->orWhere('email', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(5, ['*'], 'owners');

        return view('owner.user-table', ['users' => $owners])->render();
    }

    // AJAX search for staff
    public function staffsAjax(Request $request)
    {
        $staffs = User::where('role', 'staff')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                                                 ->orWhere('email', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(5, ['*'], 'staffs');

        return view('owner.user-table', ['users' => $staffs])->render();
    }



}

