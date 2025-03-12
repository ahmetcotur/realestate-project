<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {name?} {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Admin kullanıcısı oluşturur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?? $this->ask('Admin adı?', 'Admin');
        $email = $this->argument('email') ?? $this->ask('Admin e-posta adresi?', 'admin@remax-pupa.com');
        $password = $this->argument('password') ?? $this->secret('Admin şifresi?') ?? 'admin123';

        // E-posta adresi kontrolü
        if (User::where('email', $email)->exists()) {
            $this->error('Bu e-posta adresi zaten kullanılıyor!');
            return 1;
        }

        // Admin kullanıcısı oluştur
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->info("Admin kullanıcısı başarıyla oluşturuldu!");
        $this->table(
            ['ID', 'Ad', 'E-posta', 'Rol'],
            [[$user->id, $user->name, $user->email, $user->role]]
        );

        return 0;
    }
}
