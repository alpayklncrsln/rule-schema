<?php

namespace Alpayklncrsln\RuleSchema\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RuleSchemaCommand extends Command
{
    protected $signature = 'make:rule-schema {name}';

    protected $description = 'RuleSchema request creation';

    public function handle(): int
    {
        $name = $this->argument('name');

        // Paketteki stub dosyasının yolu
        $stubPath = __DIR__.'/../stubs/rule-schema.stub';

        // Laravel projesinde oluşturulacak dosyanın yolu
        $namespace = 'App\\Http\\Requests';
        $targetPath = base_path("app/Http/Requests/{$name}.php");

        // Eğer stub dosyası yoksa hata ver
        if (! File::exists($stubPath)) {
            $this->error("Stub dosyası bulunamadı: {$stubPath}");

            return self::FAILURE;
        }

        // Stub dosyasını oku ve değişkenleri değiştir
        $stubContent = File::get($stubPath);
        $stubContent = str_replace(
            ['{{namespace}}', '{{class}}'],
            [$namespace, $name],
            $stubContent
        );

        // Hedef dizini oluştur
        File::ensureDirectoryExists(dirname($targetPath));

        // Dosyayı oluştur
        File::put($targetPath, $stubContent);

        $this->info("Request sınıfı oluşturuldu: {$targetPath}");

        return self::SUCCESS;
    }
}
