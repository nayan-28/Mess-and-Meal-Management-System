<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function newMember(Request $request)
    {
        $key=Auth::user()->key;
        $search=$request['search']??"";
        if($search!==""){
            $members=DB::table('borders')->where('status','=',0)->where('key','=',$key)->where('phone','=',$search)->get()->paginate(6);
        }else{
            $members=DB::table('borders')->select('*')->where('key','=',$key)->where('status','=',0)->get()->paginate(6);
        }
        $users=compact('members','members');
        return view('newMember', compact('members'));
}
public function approvemember(Request $request)
{
    $key = $request->input('key'); // Retrieve 'key' from the form
    $id = $request->input('id');   // Retrieve 'id' from the form

    // Ensure $id is an array
    if (!is_array($id)) {
        $id = [$id]; // Convert to an array if it's not already
    }

    // Update the "status" column in the "borders" table for selected records
    DB::table("borders")->whereIn('id', $id)->update(['status' => '1']);

    $currentDate = Carbon::now();

    // Insert records into the "payment_details" table for each selected user
    foreach ($id as $userId) {
        DB::table("payment_details")->insert([
            'user_id' => $userId,
            'key' => $key,
            'date' => $currentDate,
        ]);
    }
    return redirect()->back();
}
public function rejectmember(Request $request)
{
    $delete = $request->delete;

    $users=DB::table("borders")->whereIn('id',$delete)->delete();

    return redirect()->back();

}

public function oldMember(Request $request)
{
    $key=Auth::user()->key;
        $search=$request['search']??"";
        if($search!==""){
            $members = DB::table('borders')
    ->join('payment_details', 'borders.id', '=', 'payment_details.user_id')
    ->where('borders.status', '=', 1)
    ->where('borders.key', '=', $key)
    ->where('borders.phone', '=', $search)
    ->paginate(6);
    return view('oldMember', compact('members'));
        }else{
            $members = DB::table('borders')
    ->select('borders.*', 'payment_details.*')
    ->join('payment_details', 'borders.id', '=', 'payment_details.user_id')
    ->where('borders.key', '=', $key)
    ->where('borders.status', '=', 1)
    ->paginate(6);
            return view('oldMember', compact('members'));
}
}
public function removemember(Request $request)
{
    $remove = $request->remove;

    $users=DB::table("borders")->whereIn('id',$remove)->update(['status'=>'0']);

    return redirect()->back();

}
public function payment(Request $request)
{
    // Get the current month
    $currentMonth = Carbon::now()->month;
    $key = Auth::user()->key;
    // Retrieve payment details for 'borders' in the current month
    $borderPayments = DB::table('borders')
        ->join('payment_details', function ($join) {
            $join->on('borders.key', '=', 'payment_details.key')
                 ->on('borders.id', '=', 'payment_details.user_id');
        })
        ->where('borders.status', 1)
        ->where('payment_details.key', $key)
        ->whereMonth('payment_details.date', $currentMonth)
        ->select('borders.name','borders.id', 'payment_details.*')
        ->get();

    // Retrieve 'borders_mealcharge_deposit' data for the current month
    $bordertotaldeposit = DB::table('payment_details')
    ->join('borders_mealcharge_deposit', function ($join) use ($currentMonth) {
        $join->on('payment_details.user_id', '=', 'borders_mealcharge_deposit.user_id')
             ->whereMonth('borders_mealcharge_deposit.date', $currentMonth);
    })
    ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
    ->groupBy('payment_details.user_id')
    ->get();

    // Pass the data to the 'payment' view
    return view('payment', compact('borderPayments', 'bordertotaldeposit'));
}

public function updateDetails(Request $request)
{

    $id = $request->input('id');
    $houseRent = $request->input('house_rent');
    $wifiBill = $request->input('wifi_bill');
    $electricBill = $request->input('electric_bill');
    $radhuniBill = $request->input('radhuni_bill');
    $extraBill = $request->input('extra_bill');

    // Update the record with the new data using a raw SQL query
    DB::update('UPDATE payment_details SET
        house_rent = ?,
        wifi_bill = ?,
        electric_bill = ?,
        radhuni_bill = ?,
        extra_bill = ?
        WHERE id = ?',
        [$houseRent, $wifiBill, $electricBill, $radhuniBill, $extraBill, $id]
    );

    return redirect()->back()->with('success', 'Record updated successfully.');
}
public function addcurrentmember(Request $request){
    $key = $request->input('key');
    $id = $request->input('id');


    if (!is_array($id)) {
        $id = [$id]; // Convert to an array if it's not already
    }
    $currentDate = Carbon::now();

    // Insert records into the "payment_details" table for each selected user
    foreach ($id as $userId) {
        DB::table("payment_details")->insert([
            'user_id' => $userId,
            'key' => $key,
            'date' => $currentDate,
        ]);
    }
    return redirect()->back();
}

public function mealdetails(){
    $currentMonth = Carbon::now()->month;
    $key = Auth::user()->key;

$mealdetails = DB::table('meal')
    ->join('payment_details', function ($join) use ($key, $currentMonth) {
        $join->on('meal.user_id', '=', 'payment_details.user_id')
            ->where('payment_details.key', '=', $key)
            ->whereMonth('payment_details.date', $currentMonth)
            ->whereMonth('meal.date', $currentMonth);
    })
    ->join('borders', 'payment_details.user_id', '=', 'borders.id')
    ->select(
        'borders.name',
        'payment_details.extra_bill',
        DB::raw('MAX(payment_details.user_id) as user_id'),
        DB::raw('SUM(morning) as total_morning'),
        DB::raw('SUM(dinner) as total_dinner'),
        DB::raw('SUM(lunch) as total_lunch')
    )
    ->groupBy('borders.name', 'payment_details.extra_bill') // Group by the user's name and extra_bill
    ->get();
    $bordertotaldeposit = DB::table('payment_details')
    ->join('borders_mealcharge_deposit', function ($join) use ($currentMonth) {
        $join->on('payment_details.user_id', '=', 'borders_mealcharge_deposit.user_id')
             ->whereMonth('borders_mealcharge_deposit.date', $currentMonth);
    })
    ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
    ->groupBy('payment_details.user_id')
    ->get();
return view('mealdetails',compact('mealdetails','bordertotaldeposit'));

}

public function meallist(Request $request){
    $user_id = $request->input('user_id');
    $currentMonth = Carbon::now()->month;
    $key = Auth::user()->key;

    if ($request->has('deposithistory')) {
        $deposithistory = DB::table('borders_mealcharge_deposit')
    ->join('payment_details', function ($join) use ($currentMonth,$user_id) {
        $join->on('borders_mealcharge_deposit.user_id', '=', 'payment_details.user_id')
            ->where('borders_mealcharge_deposit.user_id', '=', $user_id)
            ->whereMonth('borders_mealcharge_deposit.date', $currentMonth);
    })
    ->select('borders_mealcharge_deposit.*')
    ->get();

    return view('depositdetails',compact('deposithistory'));
    }
    elseif ($request->has('meallist')) {
    $meallist = DB::table('meal')
    ->join('payment_details', function ($join) use ($key, $currentMonth,$user_id) {
        $join->on('meal.user_id', '=', 'payment_details.user_id')
            ->where('payment_details.key', '=', $key)
            ->where('payment_details.user_id', '=', $user_id)
            ->whereMonth('payment_details.date', $currentMonth);
    })
    ->join('borders', 'payment_details.user_id', '=', 'borders.id')
    ->select('meal.*','borders.name')
    ->get();

    return view('meallist',compact('meallist'));
}
}
public function adddeposit(Request $request){
    // Get the user_id from the request
    $user_id = $request->input('user_id');
    $amount = $request->amount;
    $date = $request->date;
    DB::table("borders_mealcharge_deposit")->insert([
        'user_id' => $user_id,
        'amount' => $amount,
        'date' => $date,
    ]);
    return redirect()->back();
}

public function bazardetails(){
    $currentMonth = Carbon::now()->month;
    $key = Auth::user()->key;
    // Retrieve payment details for 'borders' in the current month
    $border = DB::table('borders')
        ->join('payment_details', function ($join) {
            $join->on('borders.key', '=', 'payment_details.key')
                 ->on('borders.id', '=', 'payment_details.user_id');
        })
        ->where('borders.status', 1)
        ->where('payment_details.key',$key)
        ->whereMonth('payment_details.date', $currentMonth)
        ->select('borders.name','borders.id')
        ->get();

        $bazardetails = DB::table('bazardetails')->select('*')->where('join_key',$key)->whereMonth('date' ,'=', $currentMonth)->get();
    return view('bazardetails',compact('border','bazardetails'));
}

public function addbazar(Request $request)
{
    $key = Auth::user()->key;
    $validator = Validator::make($request->all(), [
        'details' => 'required|string',
        'amount' => 'required|numeric',
        'date' => 'required|date',
        'id' => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $details = $request->input('details');
    $amount = $request->input('amount');
    $date = $request->input('date');
    $id = $request->input('id');

    DB::table('bazardetails')->insert([
        'user_id' => $id,
        'join_key' => $key,
        'bazardetails' => $details,
        'amount' => $amount,
        'date' => $date,
    ]);

    return redirect()->back();
}

public function mealcalculation(){
    $currentMonth = Carbon::now()->month;
    $key = Auth::user()->key;

$mealdetails = DB::table('meal')
    ->join('payment_details', function ($join) use ($key, $currentMonth) {
        $join->on('meal.user_id', '=', 'payment_details.user_id')
            ->where('payment_details.key', '=', $key)
            ->whereMonth('payment_details.date', $currentMonth)
            ->whereMonth('meal.date', $currentMonth);
    })
    ->join('borders', 'payment_details.user_id', '=', 'borders.id')
    ->select(
        'borders.name',
        'payment_details.extra_bill',
        DB::raw('MAX(payment_details.user_id) as user_id'),
        DB::raw('SUM(morning) as total_morning'),
        DB::raw('SUM(dinner) as total_dinner'),
        DB::raw('SUM(lunch) as total_lunch')
    )
    ->groupBy('borders.name', 'payment_details.extra_bill') // Group by the user's name and extra_bill
    ->get();
    $bordertotaldeposit = DB::table('payment_details')
    ->join('borders_mealcharge_deposit', function ($join) use ($currentMonth) {
        $join->on('payment_details.user_id', '=', 'borders_mealcharge_deposit.user_id')
             ->whereMonth('borders_mealcharge_deposit.date', $currentMonth);
    })
    ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
    ->groupBy('payment_details.user_id')
    ->get();

return view('mealcalculation',compact('mealdetails','bordertotaldeposit'));
}

public function calculation(Request $request){

    $month=$request->month;

    $key = Auth::user()->key;

$mealdetails = DB::table('meal')
    ->join('payment_details', function ($join) use ($key, $month) {
        $join->on('meal.user_id', '=', 'payment_details.user_id')
            ->where('payment_details.key', '=', $key)
            ->whereMonth('payment_details.date', $month);
    })
    ->join('borders', 'payment_details.user_id', '=', 'borders.id')
    ->select(
        'borders.name',
        'payment_details.extra_bill',
        DB::raw('MAX(payment_details.user_id) as user_id'),
        DB::raw('SUM(morning) as total_morning'),
        DB::raw('SUM(dinner) as total_dinner'),
        DB::raw('SUM(lunch) as total_lunch')
    )
    ->groupBy('borders.name', 'payment_details.extra_bill') // Group by the user's name and extra_bill
    ->get();
    $bordertotaldeposit = DB::table('payment_details')
    ->join('borders_mealcharge_deposit', function ($join) use ($month) {
        $join->on('payment_details.user_id', '=', 'borders_mealcharge_deposit.user_id')
             ->whereMonth('borders_mealcharge_deposit.date', $month);
    })
    ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
    ->groupBy('payment_details.user_id')
    ->get();

    $totalAmount = DB::table('bazardetails')
    ->select(DB::raw('SUM(amount) as totalAmount'))
    ->where('join_key', '=', $key)
    ->whereMonth('date', '=', $month)
    ->first();

        return view('mealcalculation',compact('bordertotaldeposit','mealdetails','totalAmount'));
 //dd($totalAmount);
}
}
