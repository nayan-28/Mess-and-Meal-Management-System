<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function ()
{
    $key=Auth::user()->key;
    $members=DB::table('borders')->select('name','phone','key')->where('key','=',$key)->where('status','=',0)->get();

    $oldemembers=DB::table('borders')->select('name','phone','key','id')->where('key','=',$key)->where('status','=',1)->get();
    return view('dashboard',compact('members','oldemembers'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('newMember', [ProfileController::class, 'newMember'])->name('newMember');
    Route::post('approvemember',[ProfileController::class,'approvemember'])
    ->name('approvemember');
    Route::post('rejectmember',[ProfileController::class,'rejectmember'])
    ->name('rejectmember');
    Route::get('oldMember', [ProfileController::class, 'oldMember'])
    ->name('oldMember');
    Route::post('removemember',[ProfileController::class,'removemember'])
    ->name('removemember');
    Route::get('payment', [ProfileController::class, 'payment'])
    ->name('payment');
    Route::post('updatedetails', [ProfileController::class, 'updatedetails'])
    ->name('updatedetails');

    Route::post('adddeposit', [ProfileController::class, 'adddeposit'])
    ->name('adddeposit');

    Route::post('addcurrentmember', [ProfileController::class, 'addcurrentmember'])
    ->name('addcurrentmember');
    Route::get('mealdetails', [ProfileController::class, 'mealdetails'])
    ->name('mealdetails');
    Route::post('history', [ProfileController::class, 'meallist'])
    ->name('meallist');
    Route::post('updating', [ProfileController::class, 'updatemeallist'])
    ->name('updatemeallist');
    Route::get('bazardetails', [ProfileController::class, 'bazardetails'])
    ->name('bazardetails');
    Route::post('addingbazar', [ProfileController::class, 'addbazar'])
    ->name('addbazar');
    Route::get('mealcalculation', [ProfileController::class, 'mealcalculation'])
    ->name('mealcalculation');
    Route::post('calculation', [ProfileController::class, 'calculation'])
    ->name('calculation');

});

require __DIR__.'/auth.php';


//Border//


Route::get('/border/dashboard', function () {

    $id=Auth::user()->id;
        $currentMonth = Carbon::now()->month;
        $status = Auth::user()->status;

    $bordertotaldeposit = DB::table('payment_details')
    ->join('borders_mealcharge_deposit', function ($join) use ($currentMonth) {
        $join->on('payment_details.user_id', '=', 'borders_mealcharge_deposit.user_id')
             ->whereMonth('borders_mealcharge_deposit.date', $currentMonth);
    })
    ->where('payment_details.user_id', $id)
    ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
    ->groupBy('payment_details.user_id')
    ->get();
    $borderPayments = DB::table('payment_details')->where('user_id','=',$id)->get();

    return view('border.dashboard',compact('bordertotaldeposit','borderPayments'));
})->middleware(['auth:border', 'verified'])->name('border.dashboard');


require __DIR__.'/borderauth.php';
