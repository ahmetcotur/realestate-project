<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Uygulama dilini değiştirir
     *
     * @param string $locale Ayarlanacak dil kodu (tr, en)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLanguage($locale)
    {
        // Desteklenen diller
        $supported_locales = ['tr', 'en'];
        
        // Eğer desteklenen bir dil ise işlem yap
        if (in_array($locale, $supported_locales)) {
            // Session'a dili kaydet
            Session::put('locale', $locale);
            
            // Uygulama dilini ayarla
            App::setLocale($locale);
        }
        
        // Bir önceki sayfaya yönlendir
        return redirect()->back();
    }
} 