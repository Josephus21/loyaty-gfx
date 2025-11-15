<?php
use App\Http\Controllers\Admin\RewardController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Member\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Role;
use App\Http\Controllers\Admin\GalleryController;
use Illuminate\Database\Capsule\Manager;
use App\Http\Controllers\Admin\RedeemController;

// Login Routes
Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login-submit');

// Login Routes
Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login-submit');

// PUBLIC MEMBER REGISTRATION ROUTES
Route::get('/member/register', [AuthController::class, 'memberRegisterForm'])->name('member.register.form');
Route::post('/member/register', [AuthController::class, 'registerMember'])->name('member.register');

// MEMBER DASHBOARD (Controller handles data)
Route::get('/member/dashboard', [MemberController::class, 'memberDashboard'])
    ->name('member-dashboard')
    ->middleware(['auth', 'role']);

// MEMBER POINTS
Route::get('/member/points', [MemberController::class, 'memberPoints'])
    ->name('member-points')
    ->middleware(['auth', 'role']);

// MEMBER REWARDS
Route::get('/member/rewards', [MemberController::class, 'memberRewards'])
    ->name('member-rewards')
    ->middleware(['auth','role']);
Route::get('/member/redeem/{id}', [MemberController::class, 'redeemReward'])->name('member.redeem');
Route::get('/member/q/{id}', [MemberController::class, 'qrInfo']);




Route::middleware(['auth', Role::class])->group(function () {

    // Admin Routes
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin-dashboard');

    // Change Password Routes
    Route::get('/admin/change-password/{id}', [AuthController::class, 'changePasswordIndex'])->name('admin-change-password');
    Route::post('/admin/update-password/{id}', [AuthController::class, 'changePassword'])->name('admin-update-password');

    // User Register Routes
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register-form');
    Route::post('/register', [AuthController::class, 'registerUser'])->name('register-user');

    // Logout
    Route::get('/logout', [AuthController::class, 'logoutUser'])->name('logout');

    //User Routes
    Route::get('/user-index', [AuthController::class, 'userIndex'])->name('index-user');
    Route::get('/user-edit/{id}', [AuthController::class, 'userEdit'])->name('edit-user');
    Route::post('/update-user/{id}', [AuthController::class, 'userUpdate'])->name('update-user');
    Route::get('/user-delete/{id}', [AuthController::class, 'userDelete'])->name('delete-user');


    // Admin Manager Routes
    Route::get('/admin-index-manager', [ManagerController::class, 'adminManagerIndex'])->name('admin-manager-index');
    Route::get('/admin-add-manager', [ManagerController::class, 'adminManagerCreate'])->name('admin-manager-add');
    Route::post('/admin-store-manager', [ManagerController::class, 'adminManagerStore'])->name('admin-manager-store');
    Route::get('/admin-edit-manager/{id}', [ManagerController::class, 'adminManagerEdit'])->name('admin-manager-edit');
    Route::post('/admin-update-manager/{id}', [ManagerController::class, 'adminManagerUpdate'])->name('admin-manager-update');
    Route::get('/admin-delete-manager/{id}', [ManagerController::class, 'adminManagerDestroy'])->name('admin-manager-destroy');

    Route::get('/admin/rewards', [RewardController::class, 'index'])->name('admin-rewards-index');
Route::get('/admin/rewards/create', [RewardController::class, 'create'])->name('admin-rewards-create');
Route::post('/admin/rewards/store', [RewardController::class, 'store'])->name('admin-rewards-store');
Route::get('/admin/rewards/{id}/edit', [RewardController::class, 'edit'])
    ->name('admin-rewards-edit');

Route::put('/admin/rewards/{id}', [RewardController::class, 'update'])
    ->name('admin-rewards-update');

// NEW DELETE ROUTE
Route::delete('/admin/rewards/{id}', [RewardController::class, 'destroy'])
    ->name('admin-rewards-delete');


Route::get('/admin/redeem', [RedeemController::class, 'index'])->name('admin-redeem-index');
Route::post('/admin/redeem/{id}', [RedeemController::class, 'markRedeemed'])->name('admin-redeem-update');
Route::post('/admin/redeem/{id}/mark', 
    [App\Http\Controllers\Admin\RedeemController::class, 'markRedeemed']
)->name('admin-redeem-update');



    // Admin Member Routes
    Route::get('/admin-index-members', [MemberController::class, 'adminMemberIndex'])->name('admin-members-index');
    Route::get('/admin-add-members', [MemberController::class, 'adminMemberCreate'])->name('admin-members-add');
    Route::post('/admin-store-members', [MemberController::class, 'adminMemberStore'])->name('admin-members-store');
    Route::get('/admin-edit-members/{id}', [MemberController::class, 'adminMemberEdit'])->name('admin-members-edit');
    Route::post('/admin-update-members/{id}', [MemberController::class, 'adminMemberUpdate'])->name('admin-members-update');
    Route::get('/admin-delete-members/{id}', [MemberController::class, 'adminMemberDestroy'])->name('admin-members-destroy');


    // Admin Point Routes
    Route::get('/index-admin-point', [PointController::class, 'adminPointIndex'])->name('admin-point-index');
    Route::get('/add-admin-point', [PointController::class, 'adminPointCreate'])->name('admin-point-add');
    Route::post('/store-admin-point', [PointController::class, 'adminPointStore'])->name('admin-point-store');
    Route::get('/edit-admin-point/{id}', [PointController::class, 'adminPointEdit'])->name('admin-point-edit');
    Route::post('/update-admin-point/{id}', [PointController::class, 'adminPointUpdate'])->name('admin-point-update');
    Route::get('/admin-delete-members/{id}', [PointController::class, 'adminPointDelete'])->name('admin-members-destroy');
Route::post('/admin/settings/point-system', function (\Illuminate\Http\Request $request) {
    \App\Models\Setting::set('point_amount_per_point', $request->amount_per_point);
    return response()->json(['status' => 'ok']);
});

// Gallery Module
// -----------------
// GALLERY MODULE
// -----------------
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/gallery', [GalleryController::class, 'index'])->name('admin.gallery.index');

    Route::get('/admin/gallery/create', [GalleryController::class, 'create'])->name('admin.gallery.create');

    Route::post('/admin/gallery/store', [GalleryController::class, 'store'])->name('admin.gallery.store');

    Route::delete('/admin/gallery/{id}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');

});


    // Staff Routes
    Route::get('/staff/dashboard', [AuthController::class, 'staffDashboard'])->name('staff-dashboard');

    // Staff Member Routes
    Route::get('/index-members', [MemberController::class, 'memberIndex'])->name('members-index');
    Route::get('/add-members', [MemberController::class, 'memberCreate'])->name('members-add');
    Route::post('/store-members', [MemberController::class, 'memberStore'])->name('members-store');
    Route::get('/edit-members/{id}', [MemberController::class, 'memberEdit'])->name('members-edit');
    Route::post('/update-members/{id}', [MemberController::class, 'memberUpdate'])->name('members-update');

    // Staff Point Routes
    Route::get('/index-staff-point', [PointController::class, 'staffPointIndex'])->name('staff-point-index');
    Route::get('/add-staff-point', [PointController::class, 'staffPointCreate'])->name('staff-point-add');
    Route::post('/store-staff-point', [PointController::class, 'staffPointStore'])->name('staff-point-store');
    Route::get('/edit-staff-point/{id}', [PointController::class, 'staffPointEdit'])->name('staff-point-edit');
    Route::post('/update-staff-point/{id}', [PointController::class, 'staffPointUpdate'])->name('staff-point-update');
    Route::get('/staff-delete-members/{id}', [PointController::class, 'staffPointDelete'])->name('staff-members-destroy');
});
