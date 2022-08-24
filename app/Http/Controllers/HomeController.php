<?php

namespace App\Http\Controllers;

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
        $this->middleware('auth');
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
        if(Auth::check()){
            return view('dashboard', $data);
        }else{
            return view('login');
        }
    }

    public function beneficiaries()
    {
        if(Auth::check()){
            return view('beneficiaries');
        }else{
            return view('login');
        }
    }

    public function encoding()
    {
        $psgcs = Psgc::all();
        $school_levels = SchoolLevel::all();
        $sectors = Sector::all();
        $sector_others = SectorOther::all();
        $payouts = Payout::all();
        $data = [
            'psgcs' => $psgcs,
            'school_levels' => $school_levels,
            'sectors' => $sectors,
            'payouts' => $payouts,
            'sector_others' => $sector_others,
        ];
        if(Auth::check()){
            return view('composition', $data);
        }else{
            return view('login');
        }
    }
}
