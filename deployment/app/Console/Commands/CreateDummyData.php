<?php

namespace App\Console\Commands;

use App\Models\Agent;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-dummy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Veritabanını temizleyip yeni dummy veriler oluşturur (admin bilgileri hariç)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dummy veri oluşturma işlemi başlatılıyor...');
        
        // Onay adımını atlıyoruz
        // if (!$this->confirm('Bu işlem admin bilgileri hariç tüm verileri silecek. Devam etmek istiyor musunuz?', true)) {
        //     $this->info('İşlem iptal edildi.');
        //     return;
        // }
        
        // Veritabanını temizle (admin bilgileri hariç)
        $this->cleanDatabase();
        
        // Emlak tiplerini kontrol et, yoksa oluştur
        $this->ensurePropertyTypes();
        
        // Danışmanlar için dummy veri oluştur
        $agents = $this->createDummyAgents();
        
        // Emlaklar için dummy veri oluştur
        $this->createDummyProperties($agents);
        
        $this->info('Dummy veri oluşturma işlemi tamamlandı!');
    }
    
    /**
     * Veritabanını temizler (admin bilgileri korunur)
     */
    private function cleanDatabase()
    {
        $this->info('Veritabanı temizleniyor...');
        
        // Admin kullanıcıları bul
        $adminUserIds = User::where('is_admin', true)->pluck('id')->toArray();
        
        // Admin kullanıcılarla ilişkili olmayan ajanları sil
        $this->info('Emlak görselleri siliniyor...');
        PropertyImage::whereNotIn('property_id', function ($query) use ($adminUserIds) {
            $query->select('id')
                ->from('properties')
                ->whereIn('agent_id', function ($subQuery) use ($adminUserIds) {
                    $subQuery->select('id')
                        ->from('agents')
                        ->whereIn('id', function ($subSubQuery) use ($adminUserIds) {
                            $subSubQuery->select('agent_id')
                                ->from('users')
                                ->whereIn('id', $adminUserIds);
                        });
                });
        })->delete();
        
        $this->info('Emlaklar siliniyor...');
        Property::whereNotIn('agent_id', function ($query) use ($adminUserIds) {
            $query->select('id')
                ->from('agents')
                ->whereIn('id', function ($subQuery) use ($adminUserIds) {
                    $subQuery->select('agent_id')
                        ->from('users')
                        ->whereIn('id', $adminUserIds);
                });
        })->delete();
        
        $this->info('Danışmanlar siliniyor...');
        Agent::whereNotIn('id', function ($query) use ($adminUserIds) {
            $query->select('agent_id')
                ->from('users')
                ->whereIn('id', $adminUserIds);
        })->delete();
        
        // Klasörleri temizle
        $this->info('Dosya sistemi temizleniyor...');
        $this->cleanStorageDirectories();
    }
    
    /**
     * Storage klasörlerini temizler
     */
    private function cleanStorageDirectories()
    {
        // Danışman fotoğrafları
        if (Storage::disk('public')->exists('agents')) {
            Storage::disk('public')->deleteDirectory('agents');
            Storage::disk('public')->makeDirectory('agents');
        }
        
        // Emlak görselleri (property_id klasörlerini koru)
        $propertyIds = Property::pluck('id')->toArray();
        if (Storage::disk('public')->exists('properties')) {
            $directories = Storage::disk('public')->directories('properties');
            
            foreach ($directories as $directory) {
                $dirName = basename($directory);
                if (!in_array($dirName, $propertyIds)) {
                    Storage::disk('public')->deleteDirectory($directory);
                }
            }
            
            if (!Storage::disk('public')->exists('properties')) {
                Storage::disk('public')->makeDirectory('properties');
            }
        }
    }
    
    /**
     * Emlak tipleri
     */
    private function ensurePropertyTypes()
    {
        $this->info('Emlak tipleri kontrol ediliyor...');
        
        $types = [
            ['name_tr' => 'Villa', 'name_en' => 'Villa'],
            ['name_tr' => 'Daire', 'name_en' => 'Apartment'],
            ['name_tr' => 'Müstakil Ev', 'name_en' => 'Detached House'],
            ['name_tr' => 'Arsa', 'name_en' => 'Land'],
            ['name_tr' => 'Ticari', 'name_en' => 'Commercial']
        ];
        
        foreach ($types as $type) {
            PropertyType::firstOrCreate(
                ['name_tr' => $type['name_tr']],
                ['name_en' => $type['name_en']]
            );
        }
    }
    
    /**
     * Danışmanlar için dummy veri oluşturur
     */
    private function createDummyAgents()
    {
        $this->info('Danışmanlar oluşturuluyor...');
        
        $agents = [];
        $agentData = [
            [
                'name' => 'Ayşe Demir',
                'title' => 'Kıdemli Emlak Danışmanı',
                'email' => 'ayse.demir@remax-pupa.com',
                'phone' => '+90 532 123 4567',
                'bio' => 'Ayşe Demir, 10 yılı aşkın emlak sektörü deneyimiyle Kaş ve Kalkan bölgesinin en tanınmış danışmanlarından biridir. Müşterilerine en iyi hizmeti sunmak için çalışan Ayşe Hanım, bölgedeki tüm gayrimenkul bilgilerine hakimdir.',
                'slug' => 'ayse-demir'
            ],
            [
                'name' => 'Mehmet Kaya',
                'title' => 'Lüks Emlak Uzmanı',
                'email' => 'mehmet.kaya@remax-pupa.com',
                'phone' => '+90 532 987 6543',
                'bio' => 'Mehmet Kaya, özellikle Kaş ve çevresindeki lüks villalar konusunda derin bilgi ve deneyime sahiptir. Müşteri odaklı çalışma prensibiyle her zaman en doğru emlak yatırımını bulmanızda yardımcı olur.',
                'slug' => 'mehmet-kaya'
            ],
            [
                'name' => 'Zeynep Yılmaz',
                'title' => 'Emlak Danışmanı',
                'email' => 'zeynep.yilmaz@remax-pupa.com',
                'phone' => '+90 532 456 7890',
                'bio' => 'Zeynep Yılmaz, Kalkan bölgesinin uzmanı olarak müşterilerine en uygun gayrimenkul seçeneklerini sunmaktadır. Profesyonel ve güler yüzlü yaklaşımıyla emlak alım-satım süreçlerinizde size rehberlik etmekten mutluluk duyar.',
                'slug' => 'zeynep-yilmaz'
            ]
        ];
        
        // Kaynak fotoğraf bul
        $photoPath = $this->getAgentPhoto();
        
        foreach ($agentData as $data) {
            $agent = Agent::create([
                'name' => $data['name'],
                'title' => $data['title'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'bio' => $data['bio'],
                'slug' => $data['slug'],
                'photo' => $photoPath,
                'is_active' => true
            ]);
            
            $agents[] = $agent;
            $this->info("- {$agent->name} oluşturuldu");
        }
        
        return $agents;
    }
    
    /**
     * Emlaklar için dummy veri oluşturur
     */
    private function createDummyProperties($agents)
    {
        $this->info('Emlaklar oluşturuluyor...');
        
        $propertyTypes = PropertyType::all();
        
        // Her danışman için emlak ekle
        foreach ($agents as $agent) {
            // Satılık emlaklar (2 adet)
            for ($i = 1; $i <= 2; $i++) {
                $property = $this->createProperty($agent, 'sale', $propertyTypes->random());
                $this->info("- {$property->title_tr} oluşturuldu (Satılık)");
            }
            
            // Kiralık emlaklar (2 adet)
            for ($i = 1; $i <= 2; $i++) {
                $property = $this->createProperty($agent, 'rent', $propertyTypes->random());
                $this->info("- {$property->title_tr} oluşturuldu (Kiralık)");
            }
        }
    }
    
    /**
     * Emlak oluşturur
     */
    private function createProperty($agent, $status, $type)
    {
        $locations = ['Kaş Merkez', 'Kalkan', 'Kaş Çukurbağ', 'Kaş Limanağzı', 'Kaş Bayındır'];
        $location = $locations[array_rand($locations)];
        
        $isVilla = str_contains(strtolower($type->name_tr), 'villa');
        $isApartment = str_contains(strtolower($type->name_tr), 'daire');
        $isLand = str_contains(strtolower($type->name_tr), 'arsa');
        
        $bedrooms = $isLand ? 0 : ($isVilla ? rand(3, 6) : rand(1, 4));
        $bathrooms = $isLand ? 0 : ($isVilla ? rand(2, 5) : rand(1, 3));
        $area = $isLand ? rand(500, 2000) : ($isVilla ? rand(150, 400) : rand(60, 150));
        
        $price = 0;
        if ($status == 'sale') {
            if ($isLand) {
                $price = rand(2000, 5000) * 1000;
            } elseif ($isVilla) {
                $price = rand(300, 900) * 1000;
            } else {
                $price = rand(100, 250) * 1000;
            }
        } else {
            if ($isVilla) {
                $price = rand(1500, 4000) * 10;
            } else {
                $price = rand(700, 1500) * 10;
            }
        }
        
        $propertyName = '';
        if ($isVilla) {
            $villaNames = ['Deniz', 'Akdeniz', 'Manzara', 'Turkuaz', 'Kaş', 'Kalkan', 'Güneş', 'Gökkuşağı', 'Cennet'];
            $propertyName = $villaNames[array_rand($villaNames)] . ' Villa';
        } elseif ($isApartment) {
            $propertyName = $location . ' Dairesi';
        } elseif ($isLand) {
            $propertyName = $location . ' Arsası';
        } else {
            $propertyName = $type->name_tr . ' - ' . $location;
        }
        
        $titleTr = $propertyName . ' - ' . $bedrooms . ' Yatak ' . $bathrooms . ' Banyo - ' . $area . 'm²';
        $titleEn = $propertyName . ' - ' . $bedrooms . ' Bedroom ' . $bathrooms . ' Bathroom - ' . $area . 'm²';
        
        $descriptionTr = 'Bu muhteşem ' . $type->name_tr . ', ' . $location . ' bölgesinde yer almaktadır. ' . 
            $bedrooms . ' yatak odası ve ' . $bathrooms . ' banyosu bulunan, ' . $area . 
            'm² alana sahip bu gayrimenkul, Kaş\'ın eşsiz manzarasına sahiptir. ' .
            'Denize yakın konumuyla, huzurlu bir yaşam vaat ediyor.';
            
        $descriptionEn = 'This magnificent ' . $type->name_en . ' is located in ' . $location . '. ' .
            'With ' . $bedrooms . ' bedrooms and ' . $bathrooms . ' bathrooms, this ' . $area . 
            'm² property offers stunning views of Kaş. ' .
            'Its proximity to the sea promises a peaceful lifestyle.';
        
        $property = Property::create([
            'title_tr' => $titleTr,
            'title_en' => $titleEn,
            'description_tr' => $descriptionTr,
            'description_en' => $descriptionEn,
            'price' => $price,
            'currency' => 'EUR',
            'status' => $status,
            'location' => $location,
            'address' => $location . ', Antalya, Turkey',
            'latitude' => 36.2 + (rand(-100, 100) / 1000),
            'longitude' => 29.6 + (rand(-100, 100) / 1000),
            'bedrooms' => $bedrooms,
            'bathrooms' => $bathrooms,
            'area' => $area,
            'agent_id' => $agent->id,
            'property_type_id' => $type->id,
            'is_featured' => rand(0, 1) === 1,
            'is_active' => true,
            'slug' => Str::slug($titleTr),
        ]);
        
        // Görseller ekle
        $imageCount = rand(2, 5);
        for ($i = 1; $i <= $imageCount; $i++) {
            $this->createPropertyImage($property, $i === 1);
        }
        
        return $property;
    }
    
    /**
     * Emlak görseli oluşturur
     */
    private function createPropertyImage($property, $isFeatured = false)
    {
        // Mevcut bir emlak görselini bul
        $imageFiles = $this->getAllPropertyImages();
        
        if (empty($imageFiles)) {
            $this->warn('Hiç emlak görseli bulunamadı, varsayılan görseller kullanılacak.');
            return null;
        }
        
        $randomImage = $imageFiles[array_rand($imageFiles)];
        $path = 'properties/' . $property->id;
        
        // Klasör yoksa oluştur
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }
        
        // Dosyayı kopyala
        $extension = pathinfo($randomImage, PATHINFO_EXTENSION);
        $newFilename = 'property_' . uniqid() . '.' . $extension;
        $newPath = $path . '/' . $newFilename;
        
        $sourceContent = file_get_contents($randomImage);
        Storage::disk('public')->put($newPath, $sourceContent);
        
        // Veritabanına kaydet
        PropertyImage::create([
            'property_id' => $property->id,
            'image_path' => $newPath,
            'alt_text' => $property->title_tr,
            'is_featured' => $isFeatured,
            'sort_order' => 0
        ]);
    }
    
    /**
     * Danışman fotoğrafını alır
     */
    private function getAgentPhoto()
    {
        // Mevcut bir danışman fotoğrafı bul
        $existingAgent = Agent::whereNotNull('photo')
            ->where('photo', '!=', '')
            ->where('photo', 'not like', 'default%')
            ->first();
            
        if ($existingAgent && $existingAgent->photo) {
            return $existingAgent->photo;
        }
        
        return 'default-agent.jpg';
    }
    
    /**
     * Tüm emlak görsellerini alır
     */
    private function getAllPropertyImages()
    {
        $files = [];
        
        // Public dizinindeki emlak görselleri
        $publicPath = public_path('storage/properties');
        if (File::exists($publicPath)) {
            $directories = File::directories($publicPath);
            
            foreach ($directories as $directory) {
                $imageFiles = File::files($directory);
                foreach ($imageFiles as $file) {
                    $files[] = $file->getPathname();
                }
            }
        }
        
        // Eğer hiç dosya yoksa varsayılan görselleri kullan
        if (empty($files)) {
            $this->info('Mevcut emlak görseli bulunamadı, varsayılan görseller kullanılacak.');
            
            // Varsayılan görselleri ekle
            $defaultImages = [
                public_path('images/property-1.jpg'),
                public_path('images/property-2.jpg'),
                public_path('images/property-3.jpg'),
                public_path('images/property-4.jpg'),
                public_path('images/property-5.jpg'),
                public_path('images/property-6.jpg'),
            ];
            
            foreach ($defaultImages as $image) {
                if (File::exists($image)) {
                    $files[] = $image;
                }
            }
            
            // Yine de bulunamazsa, public/images klasöründeki tüm görselleri dene
            if (empty($files)) {
                $publicImagePath = public_path('images');
                if (File::exists($publicImagePath)) {
                    $imageFiles = File::files($publicImagePath);
                    foreach ($imageFiles as $file) {
                        $extension = strtolower($file->getExtension());
                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                            $files[] = $file->getPathname();
                        }
                    }
                }
            }
            
            // Hala bulunamazsa, storage/app/public klasöründeki tüm görselleri dene
            if (empty($files)) {
                $storagePath = storage_path('app/public');
                if (File::exists($storagePath)) {
                    $this->searchImagesRecursively($storagePath, $files);
                }
            }
        }
        
        if (empty($files)) {
            // Son çare: Örnek bir görsel oluştur
            $this->warn('Hiç görsel bulunamadı, örnek bir görsel oluşturulacak.');
            $placeholderPath = storage_path('app/public/placeholder.jpg');
            
            // Eğer placeholder.jpg yoksa, oluştur
            if (!File::exists($placeholderPath)) {
                $img = imagecreatetruecolor(800, 600);
                $bgColor = imagecolorallocate($img, 200, 200, 200);
                $textColor = imagecolorallocate($img, 50, 50, 50);
                
                imagefill($img, 0, 0, $bgColor);
                imagestring($img, 5, 300, 280, 'Placeholder Image', $textColor);
                
                imagejpeg($img, $placeholderPath, 90);
                imagedestroy($img);
            }
            
            $files[] = $placeholderPath;
        }
        
        return $files;
    }
    
    /**
     * Klasörde ve alt klasörlerinde görsel dosyaları arar
     */
    private function searchImagesRecursively($directory, &$files)
    {
        $items = File::files($directory);
        foreach ($items as $item) {
            $extension = strtolower($item->getExtension());
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $files[] = $item->getPathname();
            }
        }
        
        $directories = File::directories($directory);
        foreach ($directories as $dir) {
            $this->searchImagesRecursively($dir, $files);
        }
    }
}
