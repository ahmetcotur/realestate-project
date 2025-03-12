<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class PropertyImageController extends Controller
{
    /**
     * Belirli bir emlak ilanına ait görselleri görüntüler.
     */
    public function index(Property $property)
    {
        $images = $property->images()->orderBy('sort_order')->get();
        return view('admin.property-images.index', compact('property', 'images'));
    }

    /**
     * Yeni bir görsel yükleme formu gösterir.
     */
    public function create(Property $property)
    {
        return view('admin.property-images.create', compact('property'));
    }

    /**
     * Yeni bir görsel kaydeder.
     */
    public function store(Request $request, Property $property)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp',
            'alt_text' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ]);

        $savedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                // Görsel boyutunu kontrol et (2MB = 2 * 1024 * 1024 = 2097152 bytes)
                $maxSize = 2 * 1024 * 1024; // 2MB
                
                if ($image->getSize() > $maxSize) {
                    // Görsel 2MB'ı aşıyorsa, yeniden boyutlandır
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($image);
                    
                    // Görselin en boy oranını koruyarak boyutlandır
                    $width = $img->width();
                    $height = $img->height();
                    
                    // Başlangıç kalitesi
                    $quality = 90;
                    
                    // Görseli hafızada tutma
                    $targetFilename = uniqid('property_') . '.' . $image->getClientOriginalExtension();
                    $targetPath = 'properties/' . $property->id . '/' . $targetFilename;
                    
                    // Görsel boyutu 2MB'dan küçük olana kadar sıkıştır
                    while (true) {
                        // Görseli belirtilen kalitede hazırla
                        $img = $manager->read($image);
                        
                        // Büyük görselleri küçült (genellikle 1920px max genişlik iyi bir seçimdir)
                        if ($width > 1920) {
                            $img->scale(width: 1920);
                        }
                        
                        // JPEG'e dönüştür ve sıkıştır
                        $encodedImage = $img->toJpeg($quality);
                        
                        // Dosya boyutunu kontrol et
                        if ($encodedImage->size() <= $maxSize || $quality <= 50) {
                            break;
                        }
                        
                        // Kalite yeterli değilse azalt ve tekrar dene
                        $quality -= 10;
                    }
                    
                    // Dosyayı kaydet
                    Storage::disk('public')->put($targetPath, $encodedImage);
                    $path = $targetPath;
                } else {
                    // Görsel 2MB'dan küçükse normal kaydet
                    $path = $image->store('properties/' . $property->id, 'public');
                }
                
                // Eğer öne çıkarılan resim ise, diğerlerini öne çıkarma özelliğini kaldır
                if ($request->has('is_featured') && $request->is_featured == 1) {
                    $property->images()->update(['is_featured' => false]);
                    $isFeatured = true;
                } else {
                    // Hiç öne çıkan resim yoksa, ilk resmi öne çıkar
                    $isFeatured = $property->images()->where('is_featured', true)->count() === 0;
                }

                // Son sıra numarasını bul ve bir sonraki sıra için artır
                $lastOrder = $property->images()->max('sort_order') ?? 0;
                
                $savedImages[] = $property->images()->create([
                    'image_path' => $path,
                    'alt_text' => $request->alt_text ?? $property->title_tr,
                    'sort_order' => $lastOrder + 1,
                    'is_featured' => $isFeatured,
                ]);
            }
        }

        return redirect()->route('admin.properties.images.index', $property->id)
            ->with('success', count($savedImages) . ' görsel başarıyla yüklendi.');
    }

    /**
     * Görseli düzenleme formunu gösterir.
     */
    public function edit(Property $property, PropertyImage $image)
    {
        return view('admin.property-images.edit', compact('property', 'image'));
    }

    /**
     * Görselin bilgilerini günceller.
     */
    public function update(Request $request, Property $property, PropertyImage $image)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_featured' => 'boolean',
        ]);

        // Eğer öne çıkarılan resim ise, diğerlerini öne çıkarma özelliğini kaldır
        if ($request->has('is_featured') && $request->is_featured == 1) {
            $property->images()->where('id', '!=', $image->id)->update(['is_featured' => false]);
        }

        $image->update([
            'alt_text' => $request->alt_text,
            'sort_order' => $request->sort_order,
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.properties.images.index', $property->id)
            ->with('success', 'Görsel bilgileri başarıyla güncellendi.');
    }

    /**
     * Görsel sıralama işlemini gerçekleştirir.
     */
    public function reorder(Request $request, Property $property)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'integer|exists:property_images,id',
        ]);

        foreach ($request->images as $index => $id) {
            PropertyImage::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Görseli ana görsel olarak işaretler.
     */
    public function setFeatured(Property $property, PropertyImage $image)
    {
        $property->images()->update(['is_featured' => false]);
        $image->update(['is_featured' => true]);

        return redirect()->route('admin.properties.images.index', $property->id)
            ->with('success', 'Ana görsel başarıyla değiştirildi.');
    }

    /**
     * Görseli siler.
     */
    public function destroy(Property $property, PropertyImage $image)
    {
        // Eğer dosya varsa fiziksel olarak sil
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        // Eğer silinen resim ana resimse ve başka resimler varsa, ilk resmi ana resim yap
        if ($image->is_featured) {
            $firstImage = $property->images()->first();
            if ($firstImage) {
                $firstImage->update(['is_featured' => true]);
            }
        }

        return redirect()->route('admin.properties.images.index', $property->id)
            ->with('success', 'Görsel başarıyla silindi.');
    }
    
    /**
     * Parçalı görsel yükleme için bir parçayı kaydeder.
     */
    public function uploadChunk(Request $request, Property $property)
    {
        $request->validate([
            'chunk' => 'required', // Base64 kodlanmış parça verisi
            'index' => 'required|integer', // Parça indeksi
            'totalChunks' => 'required|integer', // Toplam parça sayısı
            'filename' => 'required|string', // Dosya adı
            'fileId' => 'required|string', // Yükleme için benzersiz ID
        ]);
        
        // Geçici dizin oluştur
        $tempDir = storage_path('app/chunks/' . $request->fileId);
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }
        
        // Base64'ten çöz ve parçayı kaydet
        $chunkData = base64_decode($request->chunk);
        $chunkFile = $tempDir . '/' . $request->index;
        file_put_contents($chunkFile, $chunkData);
        
        return response()->json([
            'success' => true,
            'message' => 'Parça başarıyla yüklendi',
            'index' => $request->index
        ]);
    }
    
    /**
     * Parçaları birleştirip görsel yükleme işlemini tamamlar.
     */
    public function completeUpload(Request $request, Property $property)
    {
        $request->validate([
            'fileId' => 'required|string', // Yükleme için benzersiz ID
            'filename' => 'required|string', // Orijinal dosya adı
            'totalChunks' => 'required|integer', // Toplam parça sayısı
            'alt_text' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ]);
        
        $fileId = $request->fileId;
        $filename = $request->filename;
        $totalChunks = $request->totalChunks;
        
        // Parçaların bulunduğu dizin
        $tempDir = storage_path('app/chunks/' . $fileId);
        
        // Birleştirilmiş dosya için geçici dosya yolu
        $tempFilePath = storage_path('app/chunks/' . $fileId . '_complete');
        $targetFp = fopen($tempFilePath, 'wb');
        
        // Tüm parçaları sırayla birleştir
        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkFile = $tempDir . '/' . $i;
            if (file_exists($chunkFile)) {
                $chunkData = file_get_contents($chunkFile);
                fwrite($targetFp, $chunkData);
                unlink($chunkFile); // Parçayı sil
            }
        }
        
        fclose($targetFp);
        
        // Dosya tipini kontrol et
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tempFilePath);
        finfo_close($finfo);
        
        // Sadece görsel dosyalarını kabul et
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($mimeType, $allowedTypes)) {
            // Geçici dosyaları temizle
            @unlink($tempFilePath);
            @rmdir($tempDir);
            
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz dosya tipi. Sadece JPEG, PNG ve WEBP dosyalarına izin verilir.'
            ], 400);
        }
        
        // Optimize etme ve kaydetme işlemi
        $maxSize = 2 * 1024 * 1024; // 2MB
        $fileSize = filesize($tempFilePath);
        
        $manager = new ImageManager(new Driver());
        $img = $manager->read($tempFilePath);
        
        // Benzersiz dosya adı oluştur
        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
        if (empty($fileExtension) || !in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $fileExtension = 'jpg'; // Varsayılan uzantı
        }
        
        $targetFilename = uniqid('property_') . '.' . $fileExtension;
        $targetPath = 'properties/' . $property->id . '/' . $targetFilename;
        
        // Büyük görselleri yeniden boyutlandır
        $width = $img->width();
        if ($width > 1920) {
            $img->scale(width: 1920);
        }
        
        // Dosya boyutu 2MB'ı aşıyorsa kaliteyi düşür
        $quality = 90;
        if ($fileSize > $maxSize) {
            while (true) {
                $encodedImage = $img->toJpeg($quality);
                
                if ($encodedImage->size() <= $maxSize || $quality <= 50) {
                    break;
                }
                
                $quality -= 10;
            }
            
            Storage::disk('public')->put($targetPath, $encodedImage);
        } else {
            // Dosya boyutu zaten küçükse doğrudan kaydet
            Storage::disk('public')->put($targetPath, file_get_contents($tempFilePath));
        }
        
        // Geçici dosyaları temizle
        @unlink($tempFilePath);
        @rmdir($tempDir);
        
        // Öne çıkan görsel ayarı
        $isFeatured = false;
        if ($request->has('is_featured') && $request->is_featured) {
            $property->images()->update(['is_featured' => false]);
            $isFeatured = true;
        } else {
            // Hiç öne çıkan resim yoksa, ilk resmi öne çıkar
            $isFeatured = $property->images()->where('is_featured', true)->count() === 0;
        }
        
        // Son sıra numarasını bul ve bir sonraki sıra için artır
        $lastOrder = $property->images()->max('sort_order') ?? 0;
        
        // Görseli veritabanına kaydet
        $image = $property->images()->create([
            'image_path' => $targetPath,
            'alt_text' => $request->alt_text ?? $property->title_tr,
            'sort_order' => $lastOrder + 1,
            'is_featured' => $isFeatured,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Görsel başarıyla yüklendi',
            'image' => [
                'id' => $image->id,
                'url' => asset('storage/' . $image->image_path),
                'alt_text' => $image->alt_text,
                'is_featured' => $image->is_featured
            ]
        ]);
    }
}
