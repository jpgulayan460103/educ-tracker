<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Payout;
use App\Models\Psgc;
use App\Models\SchoolLevel;
use App\Models\Sector;
use App\Models\SectorOther;
use App\Models\SwadOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $swad_offices = SwadOffice::all();
        $payouts = Payout::all();
        $data = [
            'swad_offices' => $swad_offices,
            'payouts' => $payouts,
        ];
        return view('dashboard', $data);
    }

    public function beneficiaries()
    {
        return view('beneficiaries');
    }

    public function encoding(Request $request, $uuid = null)
    {
        $psgcs = Psgc::with('swad_office')->get();
        $school_levels = SchoolLevel::all();
        $sectors = Sector::all();
        $sector_others = SectorOther::all();
        $payouts = Payout::all();
        $user = Auth::user();
        $data = [
            'psgcs' => $psgcs,
            'school_levels' => $school_levels,
            'sectors' => $sectors,
            'payouts' => $payouts,
            'sector_others' => $sector_others,
            'user' => $user,
            'uuid' => $uuid,
        ];
        return view('composition', $data);
    }

    public function users()
    {
        $user = Auth::user();
        if($user->user_role != "Admin"){
            abort(403);
        }
        $swad_offices = SwadOffice::all();
        $offices = Office::all();
        $data = [
            'swad_offices' => $swad_offices,
            'offices' => $offices,
        ];
        return view('users', $data);
    }
    
    public function changePassword()
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
        ];
        return view('change-password', $data);
    }

    public function allocations(Request $request)
    {
        $swad_offices = SwadOffice::all();
        $payouts = Payout::all();
        $school_levels = SchoolLevel::all();
        $data = [
            'swad_offices' => $swad_offices,
            'payouts' => $payouts,
            'school_levels' => $school_levels,
        ];
        return view('allocation', $data);
    }
}
