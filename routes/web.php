<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

// For Customer
Route::get('/LandingPageCustomer', function () {
    return view('Customer.LandingPage');
})->name('Customer.LandingPage');

// For Staff
Route::get('/LandingPageStaff', function () {
    return view('Staff.Dashboard');
})->name('Staff.Dashboard');

// For Owner
Route::get('/LandingPageOwner', function () {
    return view('Owner.LandingPage');
})->name('Owner.LandingPage');

Route::post('/owner/register-user', [OwnerController::class, 'register'])
    ->name('owner.register.user');

Route::delete('/owner/user/{user}', [OwnerController::class, 'deleteUser'])
    ->name('owner.user.destroy');
Route::get('/Register/staff', [OwnerController::class, 'registerStaff'])->name('Owner.registerStaff');




Route::get('/LandingPageOwner', [OwnerController::class, 'todayBookings'])->name('Owner.LandingPage');

// Show skin analysis interface
Route::get('/skin-analysis', [CustomerController::class, 'showSkinAnalysis'])->name('skin.show');

// Image upload page
Route::get('/upload', [CustomerController::class, 'showUploadForm']);

// Results page - displays classification results
Route::get('/results', [CustomerController::class, 'showResults']);



Route::get('/DisplayBooking', [StaffController::class, 'DisplayList'])->name('Staff.BookingList');
Route::post('/booking/{id}/done', [StaffController::class, 'markAsDone'])->name('booking.done');

// Show all treatments
Route::get('/DisplayTreatment', [OwnerController::class, 'DisplayTreatment'])->name('Owner.Treatment.DisplayTreatment');

// Add Treatment - Form
Route::get('/AddTreatment', [OwnerController::class, 'AddTreatment'])->name('Owner.Treatment.AddTreatment');

// Add Treatment - Store
Route::post('/treatments/store', [OwnerController::class, 'store'])->name('Treatment.Store');

// Edit Treatment
Route::get('/edit/{id}', [OwnerController::class, 'edit'])->name('Owner.Treatment.Edit');

// Update Treatment
Route::put('/update/{id}', [OwnerController::class, 'update'])->name('Owner.Treatment.Update');

// Delete Treatment
Route::delete('Owner/delete/{id}', [OwnerController::class, 'destroy'])->name('Owner.Treatment.Delete');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/BookingIndex', [CustomerController::class, 'index'])->name('Customer.Index');

// Upcoming bookings for dashboard
Route::get('/Customer/Dashboard', [CustomerController::class, 'showUpcomingBooking'])->name('Customer.Dashboard');

// Booking history for booking list
Route::get('/Customer/BookingList', [CustomerController::class, 'showBookingHistory'])->name('Customer.BookingList');


Route::get('/customer/treatments/{id}/book', [CustomerController::class, 'showBooking'])->name('Customer.booking');

Route::post('/customer/booking/store', [CustomerController::class, 'store'])->name('booking.store');

Route::get('/LandingPageStaff', [StaffController::class, 'dashboard'])->name('Staff.Dashboard');

Route::delete('/customer/booking/{id}/cancel', [CustomerController::class, 'cancelBooking'])->name('customer.booking.cancel');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/users/owners', [OwnerController::class, 'ownersAjax'])->name('users.owners.ajax');
Route::get('/users/staffs', [OwnerController::class, 'staffsAjax'])->name('users.staffs.ajax');


require __DIR__.'/auth.php';
