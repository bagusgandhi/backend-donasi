<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donatur;

class DashboardController extends Controller
{
    public function index(){
        $donaturs = Donatur::count();
        $campaigns = Campaign::count();
        $donations = Donation::where('status', 'success')->sum('amount');
        return view('admin.dashboard.index', compact('donaturs', 'donations', 'campaigns'));
    }
}
