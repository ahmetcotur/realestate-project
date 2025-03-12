<?php

namespace App\Http\Controllers;

use App\Mail\AgentContactMail;
use App\Mail\ContactMail;
use App\Mail\PropertyInquiryMail;
use App\Models\Contact;
use App\Models\Agent;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Site ayarlarını getirir
     */
    private function getSettings()
    {
        $path = storage_path('app/public/settings/site_settings.json');
        
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        }
        
        return [];
    }
    
    /**
     * İletişim sayfasını gösterir
     */
    public function index(): View
    {
        // Site ayarlarını al
        $settings = $this->getSettings();
        
        return view('contact.index', [
            'settings' => $settings
        ]);
    }
    
    /**
     * İletişim formunu işler
     */
    public function store(Request $request)
    {
        // Form doğrulama
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'property_id' => 'nullable|exists:properties,id'
        ]);
        
        // İletişim kaydı oluştur
        $contact = Contact::create($validated);
        
        // Admin mail adresine email gönder
        $adminEmail = env('ADMIN_EMAIL', 'info@remaxpupa.com');
        Mail::to($adminEmail)->send(new ContactMail($contact));
        
        // Başarılı mesaj ile yönlendir
        return redirect()->back()->with('success', 'İletişim formunuz başarıyla gönderildi. En kısa sürede size dönüş yapacağız.');
    }
    
    /**
     * Özel iletişim formu - Emlak detay sayfasından gelen
     */
    public function propertyInquiry(Request $request)
    {
        // Form doğrulama
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            'property_id' => 'required|exists:properties,id'
        ]);
        
        // Konu alanını otomatik ekle
        $validated['subject'] = 'Emlak İlanı Hakkında Bilgi Talebi';
        
        // Emlak ilanını al ve ilgili danışmanın ID'sini ekle
        $property = Property::find($validated['property_id']);
        if ($property && $property->agent_id) {
            $validated['agent_id'] = $property->agent_id;
        }
        
        // İletişim kaydı oluştur
        $contact = Contact::create($validated);
        
        // Admin mail adresine email gönder
        $adminEmail = env('ADMIN_EMAIL', 'info@remaxpupa.com');
        Mail::to($adminEmail)->send(new PropertyInquiryMail($contact, $property));
        
        // Eğer ilana ait bir danışman varsa ona da email gönder
        if ($property && $property->agent_id) {
            $agent = Agent::find($property->agent_id);
            if ($agent && $agent->email) {
                Mail::to($agent->email)->send(new PropertyInquiryMail($contact, $property));
            }
        }
        
        // Başarılı mesaj ile yönlendir
        return redirect()->back()->with('success', 'Talebiniz başarıyla iletildi. Danışmanımız en kısa sürede sizinle iletişime geçecek.');
    }
    
    /**
     * Özel iletişim formu - Danışman profil sayfasından gelen
     */
    public function agent(Request $request, $agentId)
    {
        // Form doğrulama
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);
        
        // Konu ve agent_id alanlarını otomatik ekle
        $validated['subject'] = 'Danışman İletişim Talebi';
        $validated['agent_id'] = $agentId;
        
        // İletişim kaydı oluştur
        $contact = Contact::create($validated);
        
        // Danışmanı bul
        $agent = Agent::find($agentId);
        
        // Admin mail adresine email gönder
        $adminEmail = env('ADMIN_EMAIL', 'info@remaxpupa.com');
        Mail::to($adminEmail)->send(new AgentContactMail($contact, $agent));
        
        // Danışmana email gönder
        if ($agent && $agent->email) {
            Mail::to($agent->email)->send(new AgentContactMail($contact, $agent));
        }
        
        // Başarılı mesaj ile yönlendir
        return redirect()->back()->with('success', 'Mesajınız danışmanımıza iletildi. En kısa sürede sizinle iletişime geçecektir.');
    }
}
