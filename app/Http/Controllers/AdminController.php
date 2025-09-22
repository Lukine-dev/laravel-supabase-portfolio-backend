<?php


namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $services = Service::all();
        return view('admin.dashboard', compact('services'));
    }

    public function showService($serviceSlug)
    {
        $service = Service::where('slug', $serviceSlug)->firstOrFail();
        
        return view('admin.services.show', compact('service'));
    }
}