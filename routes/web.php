<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\UserLeaveDashboard;
use Google\Service\CloudCommercePartnerProcurementService\Approval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware(UserLeaveDashboard::class)->group(function () {
    //privacy-policy page
    Route::get('/privacy-policy', function () {
        return view('privacy-policy');
    })->name('privacy-policy');

    //terms-of-services page
    Route::get('/terms-of-services', function () {
        return view('terms-of-services');
    })->name('terms-of-services');

    // Route untuk halaman utama 
    Route::get('/yayasan', [HomeController::class, 'homeYayasan'])->name('home.yayasan');
    Route::get('/mikael', [HomeController::class, 'homeMikael'])->name('home.mikael');
    Route::get('/', [HomeController::class, 'homeAll'])->name('home');


    // Route untuk Approval
    Route::get('/approval/{id}', [ApprovalController::class, 'index'])->name('approval.index');
    Route::get('/approval/show/{id}', [ApprovalController::class, 'show'])->name('approval.show');
    Route::get('/approval/confirm/{id}/{response}', [ApprovalController::class, 'confirm'])->name('approval.confirm');

    // Route::get('/admin/login', [AdminController::class, 'indexLogin'])->name('rooms.index-login');

    Route::get('/bookings/create/{id}', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/list', [BookingController::class, 'list'])->name('bookings.list');
    Route::post('/bookings/login', [BookingController::class, 'login'])->name('bookings.login');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/reset-session', [BookingController::class, 'resetSession'])->name('bookings.reset-session');
    Route::get('/bookings/available/{room}', [BookingController::class, 'roomAvailable'])->name('bookings.room-available');

    Route::get('/rooms/list', [RoomController::class, 'list'])->name('rooms.list');

    // Route untuk authentication (login/register)
    // Auth::routes();

    Route::get('/login', [AuthController::class, 'indexLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.store');

    // Route untuk dashboard setelah login
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Group route yang hanya bisa diakses setelah user login
    Route::middleware('auth')->group(function () {

        // Route untuk User yang bisa melakukan booking
        // Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        // Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');

        Route::get('/user/dashboard', [DashboardController::class, 'indexUser'])->name('user.dashboard');

        Route::middleware(AdminOnly::class)->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

            Route::get('/admin/user', [UserController::class, 'get'])->name('user.get');
            Route::post('/admin/user/import', [UserController::class, 'import'])->name('user.import');
            Route::post('/admin/user', [UserController::class, 'store'])->name('user.store');
            Route::put('/admin/user', [UserController::class, 'update'])->name('user.update');
            Route::get('/admin/user/destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');

            // Route untuk Admin (hanya admin yang bisa mengakses CRUD room dan approve booking)
            Route::get('/admin/rooms', [RoomController::class, 'index'])->name('rooms.index');
            Route::get('/admin/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
            Route::post('/admin/rooms/store', [RoomController::class, 'store'])->name('rooms.store');
            Route::get('/admin/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
            Route::put('/admin/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
            Route::delete('/admin/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

            Route::get('/admin/bookings/{room}', [BookingController::class, 'indexAdmin'])->name('admin.bookings.index');
            // Route::post('/admin/bookings/{id}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');

            Route::get('/bookings/destroy', [BookingController::class, 'destroy'])->name('bookings.destroy');
        });
    });

    // Route untuk Google OAuth Login (menggunakan Socialite)
    Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

    //Route untuk export PDF dan Excel

    Route::get('/bookings/export/{room}', [BookingController::class, 'export'])->name('bookings.export');

    // use Spatie\GoogleCalendar\Event;
    // use Carbon\Carbon;

    // Route::get('/test-calendar', function () {
    //     $event = new Event;
    //     $event->name = 'Test Event';
    //     $event->startDateTime = Carbon::now();
    //     $event->endDateTime = Carbon::now()->addHour();
    //     $event->save();

    //     return 'Event created in Google Calendar!';
    // });

}); 