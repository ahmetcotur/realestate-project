<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propertyTypes = [
            [
                'name_tr' => 'Villa',
                'name_en' => 'Villa',
                'slug' => 'villa',
            ],
            [
                'name_tr' => 'Apartman Dairesi',
                'name_en' => 'Apartment',
                'slug' => 'apartman-dairesi',
            ],
            [
                'name_tr' => 'Müstakil Ev',
                'name_en' => 'Detached House',
                'slug' => 'mustakil-ev',
            ],
            [
                'name_tr' => 'Arsa',
                'name_en' => 'Land',
                'slug' => 'arsa',
            ],
            [
                'name_tr' => 'Ticari Gayrimenkul',
                'name_en' => 'Commercial Property',
                'slug' => 'ticari-gayrimenkul',
            ],
        ];

        foreach ($propertyTypes as $type) {
            PropertyType::create($type);
        }
    }
}
