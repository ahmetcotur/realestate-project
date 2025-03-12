<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Agent;
use App\Models\Contact;
use App\Models\PropertyType;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Yönetim paneli ana sayfa
     */
    public function index()
    {
        $stats = [
            'properties' => Property::count(),
            'agents' => Agent::count(),
            'contacts' => Contact::count(),
            'propertyTypes' => PropertyType::count(),
        ];
        
        $recentProperties = Property::with(['agent', 'propertyType'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentContacts = Contact::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recentProperties', 'recentContacts'));
    }
}
