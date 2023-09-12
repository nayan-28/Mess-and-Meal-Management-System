<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorderProfileUpdateRequest;
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

    public function paymentdetails(){
        $id=Auth::user()->id;
        $borderPayments = DB::table('payment_details')->where('user_id','=',$id)->get();
        return view('border.paymentdetails', compact('borderPayments'));
    }

    public function mealdetails(){
        $id=Auth::user()->id;
        $bordermeals = DB::table('meal')->where('user_id','=',$id)->get();
        return view('border.mealdetails', compact('bordermeals'));
    }

    public function addmeals(Request $request){
        $id = Auth::user()->id;
        $morning = $request->input('morning') ?? 0;
        $lunch = $request->input('lunch') ?? 0;
        $dinner = $request->input('dinner') ?? 0;
        $date = $request->input('date') ?? now();

DB::table('meal')->insert([
    'user_id' => $id,
    'morning' => $morning,
    'lunch' => $lunch,
    'dinner' => $dinner,
    'date' => $date,
]);
        return redirect()->back();
    }
}
