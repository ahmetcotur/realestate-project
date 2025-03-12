<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Property;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class ContactController extends Controller
{
    /**
     * Tüm iletişim mesajlarını göster
     */
    public function index(Request $request): View
    {
        // Admin rolündeki kullanıcı tüm mesajları görebilir, agent rolündeki kullanıcı sadece kendi mesajlarını görebilir
        if (auth()->user()->isAdmin()) {
            $query = Contact::withoutGlobalScope('agentContacts')
                ->with(['property'])
                ->orderBy('created_at', 'desc');
        } else {
            // Danışmanlar sadece kendi mesajlarını görebilir
            $query = Contact::with(['property'])
                ->where('agent_id', auth()->user()->agent_id)
                ->orderBy('created_at', 'desc');
        }
        
        // Filtreleme: Durum (okundu/okunmadı)
        if ($request->has('is_read') && $request->is_read !== '') {
            $query->where('is_read', $request->is_read);
        }
        
        // Filtreleme: Tarih aralığı
        if ($request->has('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', Carbon::now()->subDays(7));
                    break;
                case 'month':
                    $query->where('created_at', '>=', Carbon::now()->subDays(30));
                    break;
            }
        }
        
        // Filtreleme: Arama (isim, e-posta, konu)
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('subject', 'like', '%' . $searchTerm . '%')
                  ->orWhere('message', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $contacts = $query->paginate(15);
        
        // Okunmamış mesaj sayısını kullanıcı rolüne göre hesapla
        if (auth()->user()->isAdmin()) {
            $unreadCount = Contact::withoutGlobalScope('agentContacts')
                ->where('is_read', false)
                ->count();
        } else {
            $unreadCount = Contact::where('agent_id', auth()->user()->agent_id)
                ->where('is_read', false)
                ->count();
        }
        
        return view('admin.contacts.index', [
            'contacts' => $contacts,
            'unreadCount' => $unreadCount
        ]);
    }
    
    /**
     * Okunmamış iletişim mesajlarını göster
     */
    public function unread(): View
    {
        // Admin rolündeki kullanıcı tüm okunmamış mesajları görebilir, agent rolündeki kullanıcı sadece kendi okunmamış mesajlarını görebilir
        if (auth()->user()->isAdmin()) {
            $contacts = Contact::withoutGlobalScope('agentContacts')
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
                
            $unreadCount = Contact::withoutGlobalScope('agentContacts')
                ->where('is_read', false)
                ->count();
        } else {
            // Danışmanlar sadece kendi okunmamış mesajlarını görebilir
            $contacts = Contact::where('agent_id', auth()->user()->agent_id)
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
                
            $unreadCount = Contact::where('agent_id', auth()->user()->agent_id)
                ->where('is_read', false)
                ->count();
        }
        
        return view('admin.contacts.index', [
            'contacts' => $contacts,
            'unreadOnly' => true,
            'unreadCount' => $unreadCount
        ]);
    }
    
    /**
     * Belirli bir iletişim mesajı detayını göster
     */
    public function show(Contact $contact): View
    {
        // Admin rolündeki kullanıcı tüm mesajları görebilir, agent rolündeki kullanıcı sadece kendi mesajlarını görebilir
        if (auth()->user()->isAdmin()) {
            // Global scope'u kaldır - admin için
            $contact = Contact::withoutGlobalScope('agentContacts')->findOrFail($contact->id);
        } else {
            // Danışmanlar için erişim kontrolü
            if ($contact->agent_id !== auth()->user()->agent_id) {
                abort(403, 'Bu mesaja erişim yetkiniz yok.');
            }
        }
        
        // Mesaj okundu olarak işaretle
        if (!$contact->is_read) {
            $contact->is_read = true;
            $contact->save();
        }
        
        return view('admin.contacts.show', compact('contact'));
    }
    
    /**
     * Mesajın okundu/okunmadı durumunu değiştir
     */
    public function toggleRead(Contact $contact)
    {
        // Admin rolündeki kullanıcı tüm mesajları değiştirebilir, agent rolündeki kullanıcı sadece kendi mesajlarını değiştirebilir
        if (auth()->user()->isAdmin()) {
            // Global scope'u kaldır - admin için
            $contact = Contact::withoutGlobalScope('agentContacts')->findOrFail($contact->id);
        } else {
            // Danışmanlar için erişim kontrolü
            if ($contact->agent_id !== auth()->user()->agent_id) {
                return response()->json(['error' => 'Bu mesaja erişim yetkiniz yok.'], 403);
            }
        }
        
        $contact->is_read = !$contact->is_read;
        $contact->save();
        
        return response()->json([
            'success' => true,
            'is_read' => $contact->is_read
        ]);
    }
    
    /**
     * Belirli bir iletişim mesajını sil
     */
    public function destroy(Contact $contact)
    {
        // Admin rolündeki kullanıcı tüm mesajları silebilir, agent rolündeki kullanıcı sadece kendi mesajlarını silebilir
        if (auth()->user()->isAdmin()) {
            // Global scope'u kaldır - admin için
            $contact = Contact::withoutGlobalScope('agentContacts')->findOrFail($contact->id);
        } else {
            // Danışmanlar için erişim kontrolü
            if ($contact->agent_id !== auth()->user()->agent_id) {
                abort(403, 'Bu mesaja erişim yetkiniz yok.');
            }
        }
        
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Mesaj başarıyla silindi.');
    }
    
    /**
     * Seçili mesajları toplu olarak sil
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Hiç mesaj seçilmedi.'
            ]);
        }
        
        // Admin rolündeki kullanıcı tüm mesajları silebilir, agent rolündeki kullanıcı sadece kendi mesajlarını silebilir
        if (auth()->user()->isAdmin()) {
            // Global scope'u kaldır - admin için
            Contact::withoutGlobalScope('agentContacts')->whereIn('id', $ids)->delete();
        } else {
            // Danışmanlar için erişim kontrolü - sadece kendi mesajlarını silebilir
            Contact::where('agent_id', auth()->user()->agent_id)
                ->whereIn('id', $ids)
                ->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => count($ids) . ' mesaj başarıyla silindi.'
        ]);
    }
} 