<?php 

namespace Sdisauth\Console;

use Illuminate\Console\Command;

class InstallAuthPackageCommand extends Command
{
    protected $signature = 'sdisauth:install';
    protected $description = 'Installe Laravel Breeze et configure Laravel Permission';

    public function handle()
    {
        $this->info('Début de l\'installation et de la publication des ressources pour Sdisauth.');

        // Installation de Laravel Breeze
        if ($this->confirm('Souhaitez-vous installer Laravel Breeze ?')) {
            $this->info('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
            $this->info('+++++++++++++++++++ Installation de Laravel Breeze +++++++++++++++');
            $this->info('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');

            // Installation de Breeze via composer
            exec('composer require laravel/breeze --dev', $output, $status);
            if ($status !== 0) {
                $this->error("Erreur lors de l'installation de Laravel Breeze !");
                foreach ($output as $line) {
                    $this->error($line);
                }
                exit(1);
            } else {
                $this->info("Laravel Breeze installé avec succès.");
                // Installation de Breeze
                $this->info("Instruction suivante");
            }
            
            exec('php artisan breeze:install', $output, $status);
            if ($status !== 0) {
                $this->error('Erreur lors de la configuration de Laravel Breeze.');
                foreach ($output as $line) {
                    $this->error($line);
                }
                exit(1);
            } else {
                $this->info('Laravel Breeze configuré avec succès.');
            }
            // $this->info('Laravel Breeze installé avec succès.');

            
            // $this->info('Laravel Breeze configuré avec succès.');
        }

        // Installation de Laravel Permission
        if ($this->confirm('Souhaitez-vous installer Spatie Laravel Permission ?')) {
            $this->info('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
            $this->info('+++++++++++++++ Installation de Laravel Permission +++++++++++++++');
            $this->info('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');

            // exec('php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"', $output, $status);
            // if ($status !== 0) {
            //     $this->error('Erreur lors de la publication des fichiers de Laravel Permission.');
            //     foreach ($output as $line) {
            //         $this->error($line);
            //     }
            //     exit(1);
            // } else {
            //     $this->info('Fichiers de configuration de Laravel Permission publiés avec succès.');//ok
            // }

            // $this->info('Spatie Laravel Permission installé avec succès.');

            $this->call('vendor:publish', ['--provider' => 'Spatie\Permission\PermissionServiceProvider']);
            $this->info('Fichiers de configuration de Laravel Permission publiés avec succès.');
        }

        // Publication des ressources
        $this->publishResources('vues', 'sdisauth');
        $this->publishResources('contrôleurs', 'sdisauth-controllers');
        $this->publishResources('migrations', 'sdisauth-migrations');
        $this->publishResources('configuration', 'config');
        $this->publishResources('assets', 'sdisauth-assets');
        $this->publishResources('routes', 'sdisauth-routes');

        // Exécution des migrations après la publication des routes
        $this->info('Lancement de la migration...');
        $this->call('migrate');
        $this->info('Migration effectuée avec succès ✅✅.');

        $this->info('Installation terminée ✅🏆 FPM DEV TEAM => SDIS 🏆');
    }

    /**
     * Méthode pour publier les ressources avec logs détaillés.
     */
    private function publishResources($type, $tag)
    {
        if ($this->confirm("Souhaitez-vous publier les {$type} ?")) {
            $this->info('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
            $this->info("+++++++++++++++++++ Publication des {$type}... +++++++++++++++");
            $this->info('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++');
            $this->call('vendor:publish', ['--tag' => $tag]);

            $this->info("++++++++++++++++++++ Publication des {$type} terminée ✅. ++++++++++++++++++++");
        }
    }
}
