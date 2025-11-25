<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class UpdateClientEmailsSeeder extends Seeder
{
    public function run(): void
    {
        // Liste des clients avec leurs emails corrects
        $clientEmails = [
            '+221781157773' => 'astou.odc@gmail.com',
            // Vous pouvez ajouter d'autres clients ici si nécessaire
            // '+221XXXXXXXXX' => 'email@domain.com',
        ];

        foreach ($clientEmails as $telephone => $email) {
            $client = Client::where('telephone', $telephone)->first();
            
            if ($client) {
                $oldEmail = $client->email;
                $client->update(['email' => $email]);
                
                $this->command->info("Client {$telephone}:");
                $this->command->info("  Ancien email: " . ($oldEmail ?? 'Aucun'));
                $this->command->info("  Nouveau email: {$email}");
                $this->command->info("  ✅ Mis à jour avec succès\n");
            } else {
                $this->command->warn("Client {$telephone} non trouvé dans la base de données");
            }
        }

        $this->command->info('Mise à jour des emails des clients terminée !');
    }
}