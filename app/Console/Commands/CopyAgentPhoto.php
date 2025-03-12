<?php

namespace App\Console\Commands;

use App\Models\Agent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CopyAgentPhoto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:copy-agent-photo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ayşe Demir\'in fotoğrafını tüm danışmanlara uygular';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Ayşe Demir profil fotoğrafını tüm danışmanlara uygulama işlemi başlatılıyor...');

        // Ayşe Demir'i bul
        $sourceAgent = Agent::where('slug', 'ayse-demir')->orWhere('name', 'Ayşe Demir')->first();
        
        if (!$sourceAgent) {
            $this->error('Ayşe Demir adlı danışman bulunamadı!');
            return 1;
        }
        
        if (!$sourceAgent->photo || str_starts_with($sourceAgent->photo, 'default')) {
            $this->error('Ayşe Demir için geçerli bir fotoğraf bulunamadı!');
            return 1;
        }
        
        $this->info('Kaynak fotoğraf: ' . $sourceAgent->photo);
        
        // Tüm diğer danışmanları bul
        $agents = Agent::where('id', '!=', $sourceAgent->id)->get();
        
        if ($agents->isEmpty()) {
            $this->warn('Güncelleme yapılacak başka danışman bulunamadı.');
            return 0;
        }
        
        $this->info('Toplam ' . $agents->count() . ' danışman fotoğrafı güncellenecek...');
        
        $bar = $this->output->createProgressBar($agents->count());
        $bar->start();
        
        $photoField = $sourceAgent->photo;
        
        // Doğrudan fotoğraf alanını güncelle, böylece model üzerindeki diğer alanları değiştirmiyoruz
        foreach ($agents as $agent) {
            DB::table('agents')
                ->where('id', $agent->id)
                ->update(['photo' => $photoField]);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info('İşlem tamamlandı! Tüm danışmanların fotoğrafı Ayşe Demir\'in fotoğrafı ile değiştirildi.');
        return 0;
    }
}
