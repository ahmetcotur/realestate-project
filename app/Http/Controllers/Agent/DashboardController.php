<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Emlak danışmanı dashboard sayfası
     */
    public function index()
    {
        $agent = Auth::user()->agent;
        
        // İstatistikler
        $stats = [
            'properties' => $agent->properties()->count(),
            'contacts' => $agent->contacts()->count(),
            'unread_contacts' => $agent->contacts()->where('is_read', false)->count(),
        ];
        
        // Son eklenen ilanlar
        $recentProperties = $agent->properties()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Son gelen mesajlar
        $recentContacts = $agent->contacts()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('agent.dashboard', compact('stats', 'recentProperties', 'recentContacts'));
    }
} 