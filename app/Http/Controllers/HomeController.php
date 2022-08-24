<?php

namespace App\Http\Controllers;

use App\Models\Psgc;
use App\Models\SchoolLevel;
use App\Models\Sector;
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
        $psgcs = Psgc::all();
        $school_levels = SchoolLevel::all();
        $sectors = Sector::all();
        $data = [
            'psgcs' => $psgcs,
            'school_levels' => $school_levels,
            'sectors' => $sectors,
        ];
        if(Auth::check()){
            return view('composition', $data);
        }else{
            return view('login');
        }
    }

    public function encoded()
    {
        if(Auth::check()){
            return view('encoded');
        }else{
            return view('login');
        }
    }
}
