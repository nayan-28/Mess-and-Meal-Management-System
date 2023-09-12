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
    $currentMonth = Carbon::now()->month;

    $borderPayments = DB::table('borders')
    ->join('payment_details', function ($join) {
        $join->on('borders.key', '=', 'payment_details.key')
             ->on('borders.id', '=', 'payment_details.user_id');
    })
    ->where('borders.status', 1)
    ->whereMonth('payment_details.date', $currentMonth)
    ->select('borders.*', 'payment_details.*')
    ->get();
        return view('payment', compact('borderPayments'));
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
    return view('mealdetails');
}


}
