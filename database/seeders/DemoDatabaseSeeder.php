<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;
use App\Models\User;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\PropertyImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DemoDatabaseSeeder extends Seeder
{
    /**
     * Demo verilerini ekleyen seeder.
     */
    public function run(): void
    {
        // Mevcut verileri temizle
        PropertyImage::query()->delete();
        Property::query()->delete();
        User::where('role', 'agent')->delete();
        Agent::query()->delete();
        PropertyType::query()->delete();

        // Emlak tipleri ekleme
        $propertyTypes = [
            ['name_tr' => 'Daire', 'name_en' => 'Apartment', 'slug' => 'daire'],
            ['name_tr' => 'Villa', 'name_en' => 'Villa', 'slug' => 'villa'],
            ['name_tr' => 'Müstakil Ev', 'name_en' => 'Detached House', 'slug' => 'mustakil-ev'],
            ['name_tr' => 'Yazlık', 'name_en' => 'Summer House', 'slug' => 'yazlik'],
            ['name_tr' => 'Ticari', 'name_en' => 'Commercial', 'slug' => 'ticari'],
            ['name_tr' => 'Arsa', 'name_en' => 'Land', 'slug' => 'arsa']
        ];

        $createdPropertyTypes = [];
        foreach ($propertyTypes as $type) {
            $propertyType = PropertyType::create($type);
            $createdPropertyTypes[$type['slug']] = $propertyType;
        }

        // Demo danışmanlar ekleme
        $agents = [
            [
                'name' => 'Ahmet Yılmaz',
                'title' => 'Kıdemli Emlak Danışmanı',
                'email' => 'ahmet@pupaemlak.com',
                'phone' => '05321234567',
                'bio' => '10 yıllık emlak deneyimim ile İstanbul\'un en değerli bölgelerinde müşterilerime en iyi hizmeti sunuyorum. Gayrimenkul yatırımlarınızda doğru adımları atmanız için yanınızdayım.',
                'photo' => 'default-agent.jpg',
                'slug' => 'ahmet-yilmaz',
                'is_active' => true
            ],
            [
                'name' => 'Ayşe Demir',
                'title' => 'Lüks Konut Uzmanı',
                'email' => 'ayse@pupaemlak.com',
                'phone' => '05332345678',
                'bio' => 'Lüks konut sektöründe 8 yıllık deneyimim ile müşterilerimin beklentilerini en üst düzeyde karşılıyorum. Bodrum ve İstanbul\'daki premium gayrimenkul piyasasını yakından takip ediyorum.',
                'photo' => 'default-agent.jpg',
                'slug' => 'ayse-demir',
                'is_active' => true
            ],
            [
                'name' => 'Mehmet Kaya',
                'title' => 'Ticari Gayrimenkul Danışmanı',
                'email' => 'mehmet@pupaemlak.com',
                'phone' => '05343456789',
                'bio' => 'Ticari gayrimenkul alanında uzmanlaşmış bir danışman olarak, işletmeler için en doğru lokasyonları bulma konusunda uzmanım. 12 yıllık sektör tecrübem ile yatırımcılar ve işletmeler için katma değer yaratıyorum.',
                'photo' => 'default-agent.jpg',
                'slug' => 'mehmet-kaya',
                'is_active' => true
            ],
            [
                'name' => 'Zeynep Şahin',
                'title' => 'Yeni Projeler Uzmanı',
                'email' => 'zeynep@pupaemlak.com',
                'phone' => '05354567890',
                'bio' => 'İstanbul\'un gelişmekte olan bölgelerindeki yeni konut projelerinde uzmanlaştım. Yatırımcılar için yüksek kazanç potansiyeli taşıyan fırsatları değerlendiriyorum.',
                'photo' => 'default-agent.jpg',
                'slug' => 'zeynep-sahin',
                'is_active' => true
            ]
        ];

        $createdAgents = [];
        // Danışmanları oluştur ve her biri için kullanıcı oluştur
        foreach ($agents as $agentData) {
            $agent = Agent::create($agentData);
            $createdAgents[$agentData['slug']] = $agent;
            
            // Her danışman için bir kullanıcı oluştur
            User::create([
                'name' => $agent->name,
                'email' => $agent->email,
                'password' => Hash::make('123456'),
                'role' => 'agent',
                'agent_id' => $agent->id,
                'is_active' => true,
            ]);
        }

        // Admin kullanıcısı oluştur (eğer yoksa)
        if (!User::where('email', 'admin@pupaemlak.com')->exists()) {
            User::create([
                'name' => 'Admin Kullanıcı',
                'email' => 'admin@pupaemlak.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'is_active' => true,
            ]);
        }

        // Emlak ilanları ekleme
        $properties = [
            // Satılık İlanlar
            [
                'title_tr' => 'Levent\'te Lüks Rezidans Daire',
                'title_en' => 'Luxury Residence Apartment in Levent',
                'description_tr' => 'Levent merkezinde, 5 yıldızlı otel konforunda, 24 saat güvenlikli, kapalı otoparklı, fitness center, yüzme havuzu ve sosyal alanları ile lüks bir yaşam sunan rezidans dairemiz satılıktır. Metro ve alışveriş merkezlerine yürüme mesafesindedir. İç mekanlar İtalyan tasarımcılar tarafından dizayn edilmiş olup, akıllı ev sistemleri ile donatılmıştır.',
                'description_en' => 'Our luxurious residence apartment in the center of Levent, offering a luxurious life with 5-star hotel comfort, 24-hour security, covered parking, fitness center, swimming pool and social areas, is for sale. It is within walking distance to metro and shopping centers. The interiors are designed by Italian designers and equipped with smart home systems.',
                'location' => 'Levent, İstanbul',
                'price' => 12500000,
                'currency' => 'TRY',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 180,
                'property_type_id' => $createdPropertyTypes['daire']->id,
                'agent_id' => $createdAgents['ayse-demir']->id,
                'features' => ['Kapalı Otopark', 'Güvenlik', 'Yüzme Havuzu', 'Fitness Salonu', 'Akıllı Ev Sistemleri', 'Merkezi Konum', 'Metro Yakın'],
                'slug' => 'leventte-luks-rezidans-daire',
                'is_featured' => true,
                'status' => 'sale',
            ],
            [
                'title_tr' => 'Bodrum Yalıkavak\'ta Deniz Manzaralı Villa',
                'title_en' => 'Sea View Villa in Bodrum Yalıkavak',
                'description_tr' => 'Bodrum Yalıkavak\'ta eşsiz deniz manzarasına sahip, özel havuzlu, 5+1 müstakil villa. Geniş bahçesi, barbekü alanı ve terasları ile yazın keyfini çıkarabileceğiniz bu villa, marina ve plajlara yakın konumda yer almaktadır. Modern mimarisi ve lüks iç dizaynı ile hayallerinizin ötesinde bir yaşam sunuyor.',
                'description_en' => 'A 5+1 detached villa with a unique sea view and private pool in Bodrum Yalıkavak. With its large garden, barbecue area and terraces, this villa, which you can enjoy in summer, is located close to marinas and beaches. It offers a life beyond your dreams with its modern architecture and luxurious interior design.',
                'location' => 'Yalıkavak, Bodrum',
                'price' => 25000000,
                'currency' => 'TRY',
                'bedrooms' => 5,
                'bathrooms' => 3,
                'area' => 350,
                'property_type_id' => $createdPropertyTypes['villa']->id,
                'agent_id' => $createdAgents['ayse-demir']->id,
                'features' => ['Deniz Manzarası', 'Özel Havuz', 'Geniş Bahçe', 'Barbekü Alanı', 'Marina Yakın', 'Güvenlikli Site', 'Akıllı Ev Sistemleri'],
                'slug' => 'bodrum-yalikavakta-deniz-manzarali-villa',
                'is_featured' => true,
                'status' => 'sale',
            ],
            [
                'title_tr' => 'Beşiktaş\'ta Boğaz Manzaralı Tarihi Yalı',
                'title_en' => 'Historic Waterfront Mansion with Bosphorus View in Beşiktaş',
                'description_tr' => 'İstanbul Boğazı\'nın en değerli lokasyonunda, tarihi dokusunu koruyan, tamamen restore edilmiş, 8+2 yalı. Özel iskelesi, yüzme havuzu ve Boğaz\'ın eşsiz manzarasına sahip terasları ile ayrıcalıklı bir yaşam sunuyor. 19. yüzyıldan kalma orijinal mimari detaylar modern konfor ile harmanlanmıştır.',
                'description_en' => 'A completely restored 8+2 mansion in the most valuable location of the Bosphorus, preserving its historical texture. It offers a privileged life with its private pier, swimming pool and terraces with a unique view of the Bosphorus. Original architectural details from the 19th century are blended with modern comfort.',
                'location' => 'Arnavutköy, Beşiktaş, İstanbul',
                'price' => 95000000,
                'currency' => 'TRY',
                'bedrooms' => 8,
                'bathrooms' => 5,
                'area' => 650,
                'property_type_id' => $createdPropertyTypes['mustakil-ev']->id,
                'agent_id' => $createdAgents['ahmet-yilmaz']->id,
                'features' => ['Boğaz Manzarası', 'Özel İskele', 'Tarihi Yapı', 'Yüzme Havuzu', 'Özel Güvenlik', 'Restorasyon', 'Geniş Bahçe'],
                'slug' => 'besiktasta-bogaz-manzarali-tarihi-yali',
                'is_featured' => true,
                'status' => 'sale',
            ],
            [
                'title_tr' => 'Kadıköy Bağdat Caddesi\'nde Yenilenmiş Daire',
                'title_en' => 'Renovated Apartment on Bağdat Avenue in Kadıköy',
                'description_tr' => 'Bağdat Caddesi\'ne paralel sokakta, tamamen yenilenmiş, ferah ve aydınlık 3+1 daire. Yüksek tavanlı, geniş balkonlu ve özel tasarım mutfağı ile konforlu bir yaşam sunuyor. Yürüme mesafesinde alışveriş imkanları, restoranlar ve denize yakın konumu ile İstanbul\'un en gözde semtlerinden birinde yaşama fırsatı.',
                'description_en' => 'Completely renovated, spacious and bright 3+1 apartment on a street parallel to Bağdat Avenue. It offers a comfortable life with its high ceilings, large balcony and custom-designed kitchen. An opportunity to live in one of Istanbul\'s most popular districts with shopping opportunities, restaurants and proximity to the sea within walking distance.',
                'location' => 'Göztepe, Kadıköy, İstanbul',
                'price' => 9500000,
                'currency' => 'TRY',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 165,
                'property_type_id' => $createdPropertyTypes['daire']->id,
                'agent_id' => $createdAgents['ahmet-yilmaz']->id,
                'features' => ['Yenilenmiş', 'Geniş Balkon', 'Özel Tasarım', 'Caddeye Yakın', 'Denize Yakın', 'Asansör', 'Çift Cephe'],
                'slug' => 'kadikoy-bagdat-caddesinde-yenilenmis-daire',
                'is_featured' => false,
                'status' => 'sale',
            ],
            [
                'title_tr' => 'Ankara Çankaya\'da Prestijli Sitede Daire',
                'title_en' => 'Apartment in Prestigious Complex in Çankaya, Ankara',
                'description_tr' => 'Çankaya\'nın en prestijli sitelerinden birinde, şehir manzaralı, geniş teraslı 4+1 daire. Site içerisinde spor tesisleri, yüzme havuzu, çocuk oyun alanları ve 24 saat güvenlik hizmeti bulunmaktadır. Ankara\'nın en değerli bölgesinde, diplomatlara komşu olabileceğiniz, yüksek standartlarda bir yaşam alanı.',
                'description_en' => 'A 4+1 apartment with city view and large terrace in one of the most prestigious sites in Çankaya. There are sports facilities, swimming pool, children\'s playgrounds and 24-hour security service on the site. A high-standard living space in the most valuable region of Ankara, where you can be neighbors with diplomats.',
                'location' => 'Çankaya, Ankara',
                'price' => 8750000,
                'currency' => 'TRY',
                'bedrooms' => 4,
                'bathrooms' => 2,
                'area' => 210,
                'property_type_id' => $createdPropertyTypes['daire']->id,
                'agent_id' => $createdAgents['zeynep-sahin']->id,
                'features' => ['Site İçerisinde', 'Şehir Manzarası', 'Geniş Teras', 'Yüzme Havuzu', 'Spor Tesisleri', 'Çocuk Oyun Alanı', '24 Saat Güvenlik'],
                'slug' => 'ankara-cankayada-prestijli-sitede-daire',
                'is_featured' => false,
                'status' => 'sale',
            ],
            [
                'title_tr' => 'İzmir Çeşme\'de Denize Sıfır Yazlık',
                'title_en' => 'Beachfront Summer House in Çeşme, İzmir',
                'description_tr' => 'Çeşme\'nin kristal berraklığındaki koyunda, denize sıfır konumda, özel plajlı yazlık. 4+1 müstakil yazlık, geniş bahçesi ve açık hava alanları ile yaz tatilleriniz için mükemmel bir seçim. Alaçatı merkeze arabayla 10 dakika mesafede olan yazlığımız, yaz-kış kullanıma uygundur.',
                'description_en' => 'A summer house with a private beach in a beachfront location in the crystal clear bay of Çeşme. A 4+1 detached summer house is a perfect choice for your summer holidays with its large garden and outdoor areas. Our summer house, which is 10 minutes by car from Alaçatı center, is suitable for use in summer and winter.',
                'location' => 'Alaçatı, Çeşme, İzmir',
                'price' => 18500000,
                'currency' => 'TRY',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area' => 220,
                'property_type_id' => $createdPropertyTypes['yazlik']->id,
                'agent_id' => $createdAgents['ahmet-yilmaz']->id,
                'features' => ['Denize Sıfır', 'Özel Plaj', 'Geniş Bahçe', 'Mavi Bayraklı Koy', 'Alaçatı\'ya Yakın', 'Açık Mutfak', 'Deniz Manzarası'],
                'slug' => 'izmir-cesmede-denize-sifir-yazlik',
                'is_featured' => true,
                'status' => 'sale',
            ],
            
            // Kiralık İlanlar
            [
                'title_tr' => 'Nişantaşı\'nda Şık Mobilyalı Daire',
                'title_en' => 'Stylishly Furnished Apartment in Nişantaşı',
                'description_tr' => 'Nişantaşı\'nın kalbinde, tamamen mobilyalı, lüks 2+1 daire. Abdi İpekçi Caddesi\'ne yürüme mesafesinde bulunan dairemiz, seçkin restoranlara, kafelere ve butiklere yakın konumdadır. İç mimari tasarımı özenle yapılmış, kaliteli mobilyalar ile döşenmiş bu daire uzun dönem kiralamalar için uygundur.',
                'description_en' => 'A fully furnished, luxury 2+1 apartment in the heart of Nişantaşı. Located within walking distance of Abdi İpekçi Street, our apartment is close to select restaurants, cafes and boutiques. This apartment, carefully designed with interior architecture and furnished with quality furniture, is suitable for long-term rentals.',
                'location' => 'Nişantaşı, Şişli, İstanbul',
                'price' => 35000,
                'currency' => 'TRY',
                'bedrooms' => 2,
                'bathrooms' => 1,
                'area' => 120,
                'property_type_id' => $createdPropertyTypes['daire']->id,
                'agent_id' => $createdAgents['ayse-demir']->id,
                'features' => ['Mobilyalı', 'Merkezi Konum', 'Asansörlü', 'Güvenlikli', 'Kombili', 'Ankastre Mutfak', 'Çift Cephe'],
                'slug' => 'nisantasinda-sik-mobilyali-daire',
                'is_featured' => true,
                'status' => 'rent',
            ],
            [
                'title_tr' => 'Etiler\'de Bahçeli Müstakil Ev',
                'title_en' => 'Detached House with Garden in Etiler',
                'description_tr' => 'Etiler\'in sakin bir sokağında, bahçeli, müstakil 4+2 ev. Geniş bahçesi, barbekü alanı ve açık havuz seçeneği ile yazın keyfini doyasıya çıkarabileceğiniz bu ev, şehrin gürültüsünden uzak huzurlu bir yaşam sunuyor. Akmerkez alışveriş merkezine 5 dakika yürüme mesafesinde.',
                'description_en' => 'A 4+2 detached house with garden on a quiet street in Etiler. This house, where you can enjoy the summer with its large garden, barbecue area and outdoor pool option, offers a peaceful life away from the noise of the city. 5 minutes walking distance to Akmerkez shopping center.',
                'location' => 'Etiler, Beşiktaş, İstanbul',
                'price' => 75000,
                'currency' => 'TRY',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area' => 280,
                'property_type_id' => $createdPropertyTypes['mustakil-ev']->id,
                'agent_id' => $createdAgents['ahmet-yilmaz']->id,
                'features' => ['Bahçeli', 'Müstakil', 'Açık Havuz', 'Barbekü Alanı', 'Otopark', 'Çocuk Oyun Alanı', 'Akıllı Ev Sistemleri'],
                'slug' => 'etilerde-bahceli-mustakil-ev',
                'is_featured' => true,
                'status' => 'rent',
            ],
            [
                'title_tr' => 'Kemerburgaz\'da Orman Manzaralı Villa',
                'title_en' => 'Forest View Villa in Kemerburgaz',
                'description_tr' => 'Kemerburgaz\'da, Belgrad Ormanı manzaralı, güvenlikli sitede 5+1 villa. Doğa ile iç içe, şehrin stresinden uzak bir yaşam için ideal. Kapalı ve açık yüzme havuzu, fitness merkezi, tenis kortu gibi sosyal imkanlarla dolu site içerisinde yer almaktadır.',
                'description_en' => 'A 5+1 villa in a secure site with a view of Belgrade Forest in Kemerburgaz. Ideal for a life intertwined with nature, away from the stress of the city. It is located in a site full of social facilities such as indoor and outdoor swimming pool, fitness center, tennis court.',
                'location' => 'Kemerburgaz, Eyüp, İstanbul',
                'price' => 85000,
                'currency' => 'TRY',
                'bedrooms' => 5,
                'bathrooms' => 3,
                'area' => 350,
                'property_type_id' => $createdPropertyTypes['villa']->id,
                'agent_id' => $createdAgents['ahmet-yilmaz']->id,
                'features' => ['Orman Manzarası', 'Güvenlikli Site', 'Yüzme Havuzu', 'Fitness Merkezi', 'Tenis Kortu', 'Çocuk Oyun Alanı', 'Kapalı Otopark'],
                'slug' => 'kemburgaz-orman-manzarali-villa',
                'is_featured' => false,
                'status' => 'rent',
            ],
            [
                'title_tr' => 'Levent\'te Plaza Katı - Ofis',
                'title_en' => 'Office Floor in Levent Plaza',
                'description_tr' => 'Levent\'in prestijli iş merkezinde, 500 m² açık ofis katı. Metrobüs ve metro istasyonlarına yürüme mesafesinde, şehir manzaralı, modern tasarımlı ofis katı kurumsal şirketler için uygundur. 24 saat güvenlik, kapalı otopark, toplantı salonları ve restoran hizmetleri bulunan plaza, iş dünyasının merkezinde yer almaktadır.',
                'description_en' => '500 m² open office floor in the prestigious business center of Levent. Within walking distance of metrobus and metro stations, with city view, modern designed office floor is suitable for corporate companies. The plaza, which has 24-hour security, closed parking, meeting rooms and restaurant services, is located in the center of the business world.',
                'location' => 'Levent, Beşiktaş, İstanbul',
                'price' => 180000,
                'currency' => 'TRY',
                'bedrooms' => 0,
                'bathrooms' => 2,
                'area' => 500,
                'property_type_id' => $createdPropertyTypes['ticari']->id,
                'agent_id' => $createdAgents['mehmet-kaya']->id,
                'features' => ['Plaza', 'Açık Ofis', 'Şehir Manzarası', 'Metro Yakın', '24 Saat Güvenlik', 'Toplantı Salonu', 'Kapalı Otopark'],
                'slug' => 'leventte-plaza-kati-ofis',
                'is_featured' => true,
                'status' => 'rent',
            ],
            [
                'title_tr' => 'Beşiktaş Çarşıda Dükkan',
                'title_en' => 'Shop in Beşiktaş Market',
                'description_tr' => 'Beşiktaş çarşının en işlek caddesinde, yüksek cirolu, 80 m² kullanım alanına sahip dükkan. Büyük vitrin cephesi ve depo alanı bulunan dükkanımız her türlü ticari faaliyet için uygundur. Yaya trafiğinin yoğun olduğu bölgede, iskele ve otobüs duraklarına çok yakın konumda bulunmaktadır.',
                'description_en' => 'A shop with a usage area of 80 m² with high turnover on the busiest street of Beşiktaş bazaar. Our shop with a large showcase front and storage area is suitable for all kinds of commercial activities. It is located very close to the pier and bus stops in an area with heavy pedestrian traffic.',
                'location' => 'Beşiktaş, İstanbul',
                'price' => 45000,
                'currency' => 'TRY',
                'bedrooms' => 0,
                'bathrooms' => 1,
                'area' => 80,
                'property_type_id' => $createdPropertyTypes['ticari']->id,
                'agent_id' => $createdAgents['mehmet-kaya']->id,
                'features' => ['İşlek Cadde', 'Vitrin Cepheli', 'Depo Alanı', 'Yüksek Yaya Trafiği', 'İskeleye Yakın', 'WC', 'Doğalgaz'],
                'slug' => 'besiktas-carsida-dukkan',
                'is_featured' => false,
                'status' => 'rent',
            ],
            [
                'title_tr' => 'Maslak\'ta Hazır Ofis',
                'title_en' => 'Ready Office in Maslak',
                'description_tr' => 'Maslak\'ın en yeni ve modern iş merkezinde, tamamen döşenmiş, hemen taşınmaya hazır, 250 m² ofis alanı. Profesyonel resepsiyon hizmeti, toplantı odaları, ortak alanlar ve teknik destek hizmetleri ile işinize odaklanmanız için gereken tüm imkanlar sunulmaktadır. Metrobüs ve metro istasyonlarına yakın konumdadır.',
                'description_en' => '250 m² office space, fully furnished and ready to move in at the newest and most modern business center in Maslak. All the facilities you need to focus on your business are provided with professional reception service, meeting rooms, common areas and technical support services. It is located close to metrobus and metro stations.',
                'location' => 'Maslak, Sarıyer, İstanbul',
                'price' => 95000,
                'currency' => 'TRY',
                'bedrooms' => 0,
                'bathrooms' => 2,
                'area' => 250,
                'property_type_id' => $createdPropertyTypes['ticari']->id,
                'agent_id' => $createdAgents['mehmet-kaya']->id,
                'features' => ['Hazır Ofis', 'Resepsiyon Hizmeti', 'Toplantı Odaları', 'Teknik Destek', 'Metro Yakın', 'Kapalı Otopark', 'Yemekhane'],
                'slug' => 'maslakta-hazir-ofis',
                'is_featured' => true,
                'status' => 'rent',
            ],
            
            // Arsa İlanları
            [
                'title_tr' => 'Çeşme Alaçatı\'da Deniz Manzaralı Arsa',
                'title_en' => 'Sea View Land in Alaçatı, Çeşme',
                'description_tr' => 'Alaçatı\'da, deniz manzaralı, villa imarlı, 1.000 m² arsa. Alaçatı merkeze 5 dk, plajlara 10 dk mesafede yer alan arsamız, eşsiz konumu ile dikkat çekiyor. İmar durumu 2 kat, TAKS: 0.15, KAKS: 0.30 olan arsada müstakil villa veya ikiz villalar inşa edilebilir.',
                'description_en' => 'Sea view, villa zoned, 1,000 m² land in Alaçatı. Our land, which is 5 minutes from Alaçatı center and 10 minutes from beaches, stands out with its unique location. A detached villa or twin villas can be built on the land with a zoning status of 2 floors, TAKS: 0.15, KAKS: 0.30.',
                'location' => 'Alaçatı, Çeşme, İzmir',
                'price' => 15000000,
                'currency' => 'TRY',
                'bedrooms' => 0,
                'bathrooms' => 0,
                'area' => 1000,
                'property_type_id' => $createdPropertyTypes['arsa']->id,
                'agent_id' => $createdAgents['zeynep-sahin']->id,
                'features' => ['Deniz Manzarası', 'Villa İmarlı', 'Yola Cepheli', 'Elektrik', 'Su', 'Alaçatı\'ya Yakın', 'Plaja Yakın'],
                'slug' => 'cesme-alacatida-deniz-manzarali-arsa',
                'is_featured' => true,
                'status' => 'sale',
            ],
            [
                'title_tr' => 'Bodrum Gümüşlük\'te Yatırımlık Arsa',
                'title_en' => 'Investment Land in Gümüşlük, Bodrum',
                'description_tr' => 'Gümüşlük\'te, gelişmekte olan bölgede, 1.500 m² konut imarlı arsa. Denize 800 metre mesafedeki arsamız, yüksek konumu sayesinde Gümüşlük Koyu ve Adaları manzarasına sahiptir. İmar durumu 2 kat olup, arazi üzerinde 450 m² taban alanlı yapı inşa edilebilir.',
                'description_en' => 'A 1,500 m² residential zoned land in the developing region of Gümüşlük. Our land, which is 800 meters from the sea, has a view of Gümüşlük Bay and Islands thanks to its high location. The zoning status is 2 floors and a building with a base area of 450 m² can be built on the land.',
                'location' => 'Gümüşlük, Bodrum, Muğla',
                'price' => 12000000,
                'currency' => 'TRY',
                'bedrooms' => 0,
                'bathrooms' => 0,
                'area' => 1500,
                'property_type_id' => $createdPropertyTypes['arsa']->id,
                'agent_id' => $createdAgents['zeynep-sahin']->id,
                'features' => ['Deniz Manzarası', 'Konut İmarlı', 'Yola Cepheli', 'Elektrik', 'Su', 'Denize Yakın', 'Yatırımlık'],
                'slug' => 'bodrum-gumusluk-yatirimlik-arsa',
                'is_featured' => false,
                'status' => 'sale',
            ],
            [
                'title_tr' => 'İstanbul Silivri\'de Sanayi İmarlı Arsa',
                'title_en' => 'Industrial Zoned Land in Silivri, Istanbul',
                'description_tr' => 'Silivri\'de, E-5 karayoluna cepheli, 10.000 m² sanayi imarlı arsa. TEM Otoyoluna 5 dakika, limana 15 dakika mesafede stratejik konuma sahip arsamız her türlü sanayi yatırımı için uygundur. Altyapısı tamamlanmış olup, elektrik, su ve doğalgaz hatları arsaya kadar getirilmiştir.',
                'description_en' => 'A 10,000 m² industrial zoned land facing the E-5 highway in Silivri. Our land, which has a strategic location 5 minutes from the TEM Highway and 15 minutes from the port, is suitable for all kinds of industrial investments. Infrastructure has been completed and electricity, water and natural gas lines have been brought to the land.',
                'location' => 'Silivri, İstanbul',
                'price' => 35000000,
                'currency' => 'TRY',
                'bedrooms' => 0,
                'bathrooms' => 0,
                'area' => 10000,
                'property_type_id' => $createdPropertyTypes['arsa']->id,
                'agent_id' => $createdAgents['mehmet-kaya']->id,
                'features' => ['Sanayi İmarlı', 'E-5 Cepheli', 'TEM\'e Yakın', 'Limana Yakın', 'Altyapı Hazır', 'Elektrik', 'Doğalgaz'],
                'slug' => 'istanbul-silivride-sanayi-imarli-arsa',
                'is_featured' => true,
                'status' => 'sale',
            ],
        ];

        // Emlak ilanlarını oluştur
        foreach ($properties as $propertyData) {
            Property::create($propertyData);
        }

        $this->command->info('Demo veriler başarıyla eklendi! Emlak danışmanları, ilanlar ve kullanıcılar sisteme tanımlandı.');
    }
}