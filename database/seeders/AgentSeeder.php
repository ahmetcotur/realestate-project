<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agents = [
            [
                'name' => 'Ahmet Yılmaz',
                'title' => 'Kıdemli Gayrimenkul Danışmanı',
                'email' => 'ahmet@remaxpupa.com',
                'phone' => '+90 555 123 4567',
                'bio' => 'Ahmet Bey 10 yıllık gayrimenkul deneyimiyle Kaş ve Kalkan bölgesinin uzmanıdır. Özellikle lüks villalar konusunda uzmanlaşmıştır.',
                'photo' => 'agents/ahmet.jpg',
                'slug' => 'ahmet-yilmaz',
                'is_active' => true,
            ],
            [
                'name' => 'Ayşe Kaya',
                'title' => 'Gayrimenkul Danışmanı',
                'email' => 'ayse@remaxpupa.com',
                'phone' => '+90 555 987 6543',
                'bio' => 'Ayşe Hanım deniz manzaralı mülkler konusunda uzmanlaşmış, 5 yıllık deneyime sahip bir gayrimenkul danışmanıdır.',
                'photo' => 'agents/ayse.jpg',
                'slug' => 'ayse-kaya',
                'is_active' => true,
            ],
            [
                'name' => 'Mehmet Çelik',
                'title' => 'Ticari Gayrimenkul Uzmanı',
                'email' => 'mehmet@remaxpupa.com',
                'phone' => '+90 555 789 0123',
                'bio' => 'Mehmet Bey ticari gayrimenkul alanında uzmanlaşmış, işyerleri ve otel satışlarında geniş bir port
                föye sahiptir.',
                'photo' => 'agents/mehmet.jpg',
                'slug' => 'mehmet-celik',
                'is_active' => true,
            ],
        ];

        foreach ($agents as $agent) {
            Agent::create($agent);
        }
    }
}
