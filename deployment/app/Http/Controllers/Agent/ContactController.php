<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Danışmana gelen iletişim mesajlarını listeler
     */
    public function index()
    {
        // Giriş yapmış danışmanın ID'sini al
        $agentId = Auth::user()->agent_id;
        
        // Sadece bu danışmana ait mesajları getir
        $contacts = Contact::where('agent_id', $agentId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('agent.contacts.index', compact('contacts'));
    }
    
    /**
     * Danışmana gelen okunmamış mesajları listeler
     */
    public function unread()
    {
        // Giriş yapmış danışmanın ID'sini al
        $agentId = Auth::user()->agent_id;
        
        // Sadece bu danışmana ait okunmamış mesajları getir
        $contacts = Contact::where('agent_id', $agentId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('agent.contacts.index', compact('contacts'));
    }
    
    /**
     * Belirli bir iletişim mesajının detaylarını gösterir
     */
    public function show(Contact $contact)
    {
        // Giriş yapmış danışmanın ID'sini al
        $agentId = Auth::user()->agent_id;
        
        // Mesajın bu danışmana ait olup olmadığını kontrol et
        if ($contact->agent_id !== $agentId) {
            return redirect()->route('agent.contacts.index')
                ->with('error', 'Bu mesaj size ait değil.');
        }
        
        // Mesajı okundu olarak işaretle
        if (!$contact->is_read) {
            $contact->is_read = true;
            $contact->save();
        }
        
        return view('agent.contacts.show', compact('contact'));
    }
    
    /**
     * Mesajın okundu/okunmadı durumunu değiştirir
     */
    public function toggleRead(Request $request, Contact $contact)
    {
        // Giriş yapmış danışmanın ID'sini al
        $agentId = Auth::user()->agent_id;
        
        // Mesajın bu danışmana ait olup olmadığını kontrol et
        if ($contact->agent_id !== $agentId) {
            return response()->json(['error' => 'Bu mesaj size ait değil.'], 403);
        }
        
        $contact->is_read = !$contact->is_read;
        $contact->save();
        
        return response()->json([
            'success' => true,
            'is_read' => $contact->is_read
        ]);
    }
    
    /**
     * Belirli bir iletişim mesajını siler
     */
    public function destroy(Contact $contact)
    {
        // Giriş yapmış danışmanın ID'sini al
        $agentId = Auth::user()->agent_id;
        
        // Mesajın bu danışmana ait olup olmadığını kontrol et
        if ($contact->agent_id !== $agentId) {
            return redirect()->route('agent.contacts.index')
                ->with('error', 'Bu mesaj size ait değil.');
        }
        
        $contact->delete();
        
        return redirect()->route('agent.contacts.index')
            ->with('success', 'Mesaj başarıyla silindi.');
    }
} 