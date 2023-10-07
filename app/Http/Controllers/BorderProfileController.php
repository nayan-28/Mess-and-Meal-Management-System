<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorderProfileUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BorderProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('border.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(BorderProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('border.profile.edit')->with('status', 'profile-updated');
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

    public function paymentdetails(Request $request){
        $id=Auth::user()->id;
        $key=Auth::user()->key;
        $currentMonth = Carbon::now()->month;
        $status = Auth::user()->status;
        $hideButtons = true;
        $hidedate = true;
        if ($status == 0) {
            session()->flash('message', 'আপনাকে এখনো মেসে যুক্ত করেনি,দয়া করে ম্যানেজারের সাথে যোগাযোগ করুন');
            return redirect()->route('border.dashboard');
        }
        $month=$request['month']??"";

        if ($month !== "") {
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

            $totalAmount = DB::table('bazardetails')
        ->select(DB::raw('SUM(amount) as totalAmount'))
        ->where('join_key', '=', $key)
        ->whereMonth('date', '=', $month)
        ->whereYear('date', now()->year)
        ->first();

        // Retrieve 'borders_mealcharge_deposit' data for the current month
        $bordertotaldeposit = DB::table('payment_details')
        ->join('borders_mealcharge_deposit', function ($join) use ($month) {
            $join->on('payment_details.user_id', '=', 'borders_mealcharge_deposit.user_id')
                 ->whereMonth('borders_mealcharge_deposit.date', $month)
                 ->whereYear('borders_mealcharge_deposit.date', now()->year);
        })
        ->where('payment_details.user_id', $id)
        ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
        ->groupBy('payment_details.user_id')
        ->get();

        $borderPayments = DB::table('payment_details')->where('user_id','=',$id)->whereMonth('date', $month)->get();
        $bordermeals = DB::table('meal')
        ->where('user_id', '=', $id)
        ->whereMonth('date', $month)
        ->whereYear('date', now()->year)
        ->get();
        $hideButtons = false;
        }else{
            $mealdetails = DB::table('meal')
            ->join('payment_details', function ($join) use ($key, $currentMonth) {
                $join->on('meal.user_id', '=', 'payment_details.user_id')
                    ->where('payment_details.key', '=', $key)
                    ->whereMonth('payment_details.date', $currentMonth)
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

            $totalAmount = DB::table('bazardetails')
        ->select(DB::raw('SUM(amount) as totalAmount'))
        ->where('join_key', '=', $key)
        ->whereMonth('date', '=', $currentMonth)
        ->whereYear('date', now()->year)
        ->first();

        // Retrieve 'borders_mealcharge_deposit' data for the current month
        $bordertotaldeposit = DB::table('payment_details')
        ->join('borders_mealcharge_deposit', function ($join) use ($currentMonth) {
            $join->on('payment_details.user_id', '=', 'borders_mealcharge_deposit.user_id')
                 ->whereMonth('borders_mealcharge_deposit.date', $currentMonth)
                 ->whereYear('borders_mealcharge_deposit.date', now()->year);
        })
        ->where('payment_details.user_id', $id)
        ->select('payment_details.user_id', DB::raw('SUM(borders_mealcharge_deposit.amount) as total_amount'))
        ->groupBy('payment_details.user_id')
        ->get();

        $borderPayments = DB::table('payment_details')->where('user_id','=',$id)->whereMonth('date', $currentMonth)->get();
        $bordermeals = DB::table('meal')
        ->where('user_id', '=', $id)
        ->whereMonth('date', $currentMonth)
        ->whereYear('date', now()->year)
        ->get();
        $hidedate = false;
        }

        return view('border.paymentdetails', compact('borderPayments','bordertotaldeposit','mealdetails','totalAmount','bordermeals','hidedate','hideButtons','month'));
    }

    public function mealdetails(Request $request){
        $id = Auth::user()->id;
        $status = Auth::user()->status;
        $currentMonth = Carbon::now()->month;
        if ($status == 0) {
            session()->flash('message', 'আপনাকে এখনো মেসে যুক্ত করেনি,দয়া করে ম্যানেজারের সাথে যোগাযোগ করুন');
            return redirect()->route('border.dashboard');
        }
            $bordermeals = DB::table('meal')
            ->where('user_id', '=', $id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', now()->year)
            ->get();
        return view('border.mealdetails', compact('bordermeals'));

    }

    public function allmonthmeal(){
        $id = Auth::user()->id;
        $status = Auth::user()->status;
        $currentMonth = Carbon::now()->month;
        if ($status == 0) {
            session()->flash('message', 'আপনাকে এখনো মেসে যুক্ত করেনি,দয়া করে ম্যানেজারের সাথে যোগাযোগ করুন');
            return redirect()->route('border.dashboard');
        }
            $bordermeals = DB::table('meal')
            ->where('user_id', '=', $id)
            ->whereYear('date', now()->year)
            ->get();
            return view('border.allmonthmeal', compact('bordermeals'));
    }
    public function addmeals(Request $request)
    {
        $id = Auth::user()->id;
        $morning = $request->input('input');
        $currentDate = Carbon::now();
        $upcomingDate = $currentDate->addDay();
        $request->validate([
            'morning' => ['required', 'regex:/^\d+$/'],
            'lunch' => ['required', 'regex:/^\d+$/'],
            'dinner' => ['required', 'regex:/^\d+$/'],
        ]);

        $morning = $request->input('morning');
        $lunch = $request->input('lunch');
        $dinner = $request->input('dinner');

        DB::table('meal')->insert([
            'user_id' => $id,
            'morning' => $morning,
            'lunch' => $lunch,
            'dinner' => $dinner,
            'date' => $upcomingDate,
            'created_at' => now(),
        ]);

        return redirect()->back();


    }

}
