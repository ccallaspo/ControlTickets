<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestDigitalOceanSpaces extends Command
{
    protected $signature = 'test:spaces';
    protected $description = 'Test DigitalOcean Spaces configuration';

    public function handle()
    {
        try {
            $this->info('Testing DigitalOcean Spaces configuration...');
            
            // Verificar que podemos acceder al bucket
            $exists = Storage::disk('digitalocean')->exists('');
            $this->info('Bucket access: ' . ($exists ? 'OK' : 'Failed'));
            
            // Crear un archivo de prueba
            $testContent = 'This is a test file created at ' . now();
            $testPath = 'test_' . time() . '.txt';
            
            $this->info('Attempting to upload test file...');
            $uploaded = Storage::disk('digitalocean')->put($testPath, $testContent);
            
            if ($uploaded) {
                $this->info('File uploaded successfully!');
                $this->info('File path: ' . $testPath);
                
                // Verificar que podemos leer el archivo
                $content = Storage::disk('digitalocean')->get($testPath);
                $this->info('File content: ' . $content);
                
                // Limpiar: eliminar el archivo de prueba
                Storage::disk('digitalocean')->delete($testPath);
                $this->info('Test file deleted.');
            } else {
                $this->error('Failed to upload test file.');
            }
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
} 