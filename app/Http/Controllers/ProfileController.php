<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Log;
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
            $members=DB::table('borders')->select('name','phone','key','id')->where('key','=',$key)->where('status','=',0)->get()->paginate(6);
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

    $currentDate = Carbon::now();
    $currentMonth = $currentDate->format('Y-m');
    // Insert records into the "payment_details" table for each selected user
    foreach ($id as $userId) {
        // Check if a record already exists for the same user and month
        $existingRecord = DB::table('payment_details')
            ->where('user_id', $userId)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $currentMonth)
            ->first();
            if (!$existingRecord) {
                DB::table("borders")->whereIn('id', $id)->update(['status' => '1']);
                // Insert a new record if no record exists for the user in the same month
                DB::table("payment_details")->insert([
                    'user_id' => $userId,
                    'key' => $key,
                    'date' => $currentDate,
                ]);
            } else {
                // Set a message if a record already exists for the user in the same month
                session()->flash('message', 'এই ব্যবহারকারীকে ইতিমধ্যে যুক্ত করেছেন');
            }
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
            ->select('borders.name', 'borders.phone', 'payment_details.user_id', 'payment_details.key')
    ->join('payment_details', 'borders.id', '=', 'payment_details.user_id')
    ->where('borders.status', '=', 1)
    ->where('borders.key', '=', $key)
    ->where('borders.phone', '=', $search)
    ->distinct('payment_details.user_id')
    ->paginate(6);

    return view('oldMember', compact('members'));
        }else{
            $members = DB::table('borders')
    ->select('borders.name', 'borders.phone', 'payment_details.user_id', 'payment_details.key')
    ->join('payment_details', 'borders.id', '=', 'payment_details.user_id')
    ->where('borders.key', '=', $key)
    ->where('borders.status', '=', 1)
    ->distinct('payment_details.user_id') // Add this line to select distinct user_ids
    ->paginate(6);

//dd( $members);
    return view('oldMember', compact('members'));
}
}
public function removemember(Request $request)
{
    $remove = $request->remove;

    $users=DB::table("borders")->whereIn('id',$remove)->update(['status'=>'0']);
    DB::table("payment_details")
    ->where('user_id', $remove)
    ->delete();

    return redirect()->back();

}
public function payment(Request $request)
{
    // Get the current month
    $currentMonth = Carbon::now()->month;
    $key = Auth::user()->key;
    $month=$request['month']??"";
    // Check if a specific month is requested
    if ($month !== "") {
        $borderPayments = DB::table('borders')
            ->join('payment_details', function ($join) {
                $join->on('borders.key', '=', 'payment_details.key')
                    ->on('borders.id', '=', 'payment_details.user_id');
            })
            ->where('borders.status', 1)
            ->where('payment_details.key', $key)
            ->whereMonth('payment_details.date', $month)
            ->whereYear('payment_details.date', now()->year)
            ->select('borders.name','borders.id', 'payment_details.*')
            ->get();

        // Retrieve 'borders_mealcharge_deposit' data for the specified month
        $bordertotaldeposit = DB::table('payment_details')
            ->join('borders_mealcharge_deposit', function ($join) use ($month) {
                $join->on('payment_details.user_id', '=', 'borders_mealcharge_deposit.user_id')
                    ->whereMonth('borders_mealcharge_deposit.date', $month)
                    ->whereYear('borders_mealcharge_deposit.date', now()->year);
            })
            ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
            ->groupBy('payment_details.user_id')
            ->get();
    } else {
        // Retrieve payment details for 'borders' in the current month
        $borderPayments = DB::table('borders')
            ->join('payment_details', function ($join) {
                $join->on('borders.key', '=', 'payment_details.key')
                    ->on('borders.id', '=', 'payment_details.user_id');
            })
            ->where('borders.status', 1)
            ->where('payment_details.key', $key)
            ->whereMonth('payment_details.date', $currentMonth)
            ->whereYear('payment_details.date', now()->year)
            ->select('borders.name','borders.id', 'payment_details.*')
            ->get();

        // Retrieve 'borders_mealcharge_deposit' data for the current month
        $bordertotaldeposit = DB::table('payment_details')
            ->join('borders_mealcharge_deposit', function ($join) use ($currentMonth) {
                $join->on('payment_details.user_id', '=', 'borders_mealcharge_deposit.user_id')
                    ->whereMonth('borders_mealcharge_deposit.date', $currentMonth)
                    ->whereYear('borders_mealcharge_deposit.date', now()->year);
            })
            ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
            ->groupBy('payment_details.user_id')
            ->get();
    }

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
    $currentMonth = Carbon::now()->month;

    // Attempt to update the record
    $updated = DB::table('payment_details')
        ->where('user_id', $id)
        ->whereMonth('date', $currentMonth)
        ->update([
            'house_rent' => $houseRent,
            'wifi_bill' => $wifiBill,
            'electric_bill' => $electricBill,
            'radhuni_bill' => $radhuniBill,
            'extra_bill' => $extraBill,
        ]);

    if ($updated) {
        // Data was successfully updated
        return redirect()->back();
    } else {
        // Data update failed
        return redirect()->back();
    }
}


public function addcurrentmember(Request $request) {
    $key = $request->input('key');
    $id = $request->input('id');

    if (!is_array($id)) {
        $id = [$id]; // Convert to an array if it's not already
    }

    $currentDate = Carbon::now();
    $currentMonth = $currentDate->format('Y-m');

    foreach ($id as $userId) {
        // Check if a record already exists for the same user and month
        $existingRecord = DB::table('payment_details')
            ->where('user_id', $userId)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $currentMonth)
            ->first();

        if (!$existingRecord) {
            // Insert a new record if no record exists for the user in the same month
            DB::table("payment_details")->insert([
                'user_id' => $userId,
                'key' => $key,
                'date' => $currentDate,
            ]);
        } else {
            // Set a message if a record already exists for the user in the same month
            session()->flash('message', 'এই ব্যবহারকারীকে ইতিমধ্যে যুক্ত করেছেন');
        }
    }

    return redirect()->back();
}



public function mealdetails(Request $request){
    $currentMonth = Carbon::now()->month;
    $key = Auth::user()->key;
    $month=$request['month']??"";

    $hideButtons = true;

    // Check if a specific month is requested
    if ($month !== "") {
        $mealdetails = DB::table('meal')
    ->join('payment_details', function ($join) use ($key, $month) {
        $join->on('meal.user_id', '=', 'payment_details.user_id')
            ->where('payment_details.key', '=', $key)
            ->whereMonth('payment_details.date', $month)
            ->whereMonth('meal.date', $month)
            ->whereYear('meal.date', now()->year);
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
             ->whereMonth('borders_mealcharge_deposit.date', $month)
             ->whereYear('borders_mealcharge_deposit.date', now()->year);
    })
    ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
    ->groupBy('payment_details.user_id')
    ->get();
    $hideButtons = false;
    }
    else{
        $mealdetails = DB::table('meal')
        ->join('payment_details', function ($join) use ($key, $currentMonth) {
            $join->on('meal.user_id', '=', 'payment_details.user_id')
                ->where('payment_details.key', '=', $key)
                ->whereMonth('payment_details.date', $currentMonth)
                ->whereMonth('meal.date', $currentMonth)
                ->whereYear('meal.date', now()->year);
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
                 ->whereMonth('borders_mealcharge_deposit.date', $currentMonth)
                 ->whereYear('borders_mealcharge_deposit.date', now()->year);
        })
        ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
        ->groupBy('payment_details.user_id')
        ->get();
    }
return view('mealdetails',compact('mealdetails','bordertotaldeposit','hideButtons'));

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
            ->whereMonth('borders_mealcharge_deposit.date', $currentMonth)
            ->whereYear('borders_mealcharge_deposit.date', now()->year);
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
            ->whereMonth('payment_details.date', $currentMonth)
            ->whereYear('payment_details.date', now()->year);
    })
    ->join('borders', 'payment_details.user_id', '=', 'borders.id')
    ->select('meal.*','borders.name')
    ->get();

    return view('meallist',compact('meallist'));
}
}

public function updatemeallist(Request $request){
    $ids = $request->input('id');
    $user_ids = $request->input('user_id');
    $mornings = $request->input('morning');
    $lunches = $request->input('lunch');
    $dinners = $request->input('dinner');

    $updated = DB::table('meal')
    ->where('user_id', $user_ids)
    ->where('id', $ids)
    ->update([
        'morning' => $mornings,
        'lunch' => $lunches,
        'dinner' => $dinners,
    ]);

if ($updated) {
    // Update was successful
    $message = 'সফলভাবে আপডেট হয়েছে';
} else {
    // Update failed
    $message = 'কিছু সমস্যা হয়েছে,পুনরায় চেষ্টা করুন';
}

return redirect()->route('mealdetails')->with('message', $message);

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

public function bazardetails(Request $request){
    $currentMonth = Carbon::now()->month;
    $key = Auth::user()->key;
    $hideButtons = true;

    $month=$request['month']??"";
    // Retrieve payment details for 'borders' in the current month

    if ($month !== "") {
        $border = DB::table('borders')
        ->join('payment_details', function ($join) {
            $join->on('borders.key', '=', 'payment_details.key')
                 ->on('borders.id', '=', 'payment_details.user_id');
        })
        ->where('borders.status', 1)
        ->where('payment_details.key',$key)
        ->whereMonth('payment_details.date', $month)
        ->whereYear('payment_details.date', now()->year)
        ->select('borders.name','borders.id')
        ->get();

        $bazardetails = DB::table('bazardetails')->select('*')->where('join_key',$key)->whereMonth('date' ,'=', $month)->whereYear('date', now()->year)->get();
        $hideButtons = false;
    }else{
        $border = DB::table('borders')
        ->join('payment_details', function ($join) {
            $join->on('borders.key', '=', 'payment_details.key')
                 ->on('borders.id', '=', 'payment_details.user_id');
        })
        ->where('borders.status', 1)
        ->where('payment_details.key',$key)
        ->whereMonth('payment_details.date', $currentMonth)
        ->whereYear('payment_details.date', now()->year)
        ->select('borders.name','borders.id')
        ->get();

        $bazardetails = DB::table('bazardetails')->select('*')->where('join_key',$key)->whereMonth('date' ,'=', $currentMonth)->whereYear('date', now()->year)->get();
    }

    return view('bazardetails',compact('border','bazardetails','hideButtons'));
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
            ->whereMonth('payment_details.date', $month)
            ->whereYear('payment_details.date', now()->year);
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
             ->whereMonth('borders_mealcharge_deposit.date', $month)
             ->whereYear('borders_mealcharge_deposit.date', now()->year);
    })
    ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
    ->groupBy('payment_details.user_id')
    ->get();

    $totalAmount = DB::table('bazardetails')
    ->select(DB::raw('SUM(amount) as totalAmount'))
    ->where('join_key', '=', $key)
    ->whereMonth('date', '=', $month)
    ->whereYear('date', now()->year)
    ->first();

        return view('mealcalculation',compact('bordertotaldeposit','mealdetails','totalAmount'));
}
}
