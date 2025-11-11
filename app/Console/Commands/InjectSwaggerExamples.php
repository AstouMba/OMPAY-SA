<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Client;

class InjectSwaggerExamples extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swagger:inject-examples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Injecte des exemples réels depuis la base dans le JSON swagger généré';

    public function handle()
    {
        $path = storage_path('api-docs/api-docs.json');

        if (! file_exists($path)) {
            $this->error('Fichier de documentation introuvable : ' . $path);
            return 1;
        }

        $json = json_decode(file_get_contents($path), true);
        if (! is_array($json)) {
            $this->error('Impossible de parser le JSON swagger.');
            return 1;
        }

        // Récupère le premier admin et premier client pour exemples
        $admin = User::where('role', 'admin')->first();
        $client = Client::first();

        // Construire un exemple de réponse de login admin
        if ($admin) {
            $loginExample = [
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => [
                    'user' => [
                        'id' => $admin->id ?? null,
                        'name' => $admin->name,
                        'email' => $admin->email,
                        'role' => $admin->role,
                    ],
                    'access_token' => 'eyJhbGci...example',
                    'refresh_token' => 'refresh_token_example',
                    'token_type' => 'Bearer',
                ],
            ];

            // Injecter exemple dans components.schemas.AuthResponse.example
            if (isset($json['components']['schemas']['AuthResponse'])) {
                $json['components']['schemas']['AuthResponse']['example'] = $loginExample;
            }

            // Injecter dans path /v1/admin/login response example
            if (isset($json['paths']['/v1/admin/login']['post']['responses']['200']['content']['application/json'])) {
                $json['paths']['/v1/admin/login']['post']['responses']['200']['content']['application/json']['examples']['loginExample'] = ['value' => $loginExample];
            }
        }

        // Exemple client (pour CreateClientResponse and client user response)
        if ($client) {
            $clientExample = [
                'success' => true,
                'message' => 'Client récupéré',
                'data' => [
                    'id' => $client->id ?? null,
                    'nom' => $client->nom,
                    'prenom' => $client->prenom,
                    'telephone' => $client->telephone,
                    'nci' => $client->nci,
                ],
            ];

            // Injecter dans CreateClientResponse
            if (isset($json['components']['schemas']['CreateClientResponse'])) {
                $json['components']['schemas']['CreateClientResponse']['example'] = $clientExample;
            }

            // Injecter dans /v1/admin/clients response example if present
            if (isset($json['paths']['/v1/admin/clients']['post']['responses']['200']['content']['application/json'])) {
                $json['paths']['/v1/admin/clients']['post']['responses']['200']['content']['application/json']['examples']['createClientExample'] = ['value' => $clientExample];
            }
        }

        // Ecrire le JSON modifié
        file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info('Exemples injectés dans ' . $path);
        return 0;
    }
}
