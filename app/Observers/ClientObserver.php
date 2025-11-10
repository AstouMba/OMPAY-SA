<?php

namespace App\Observers;

use App\Models\Client;
use App\Events\OtpGenerated;

class ClientObserver
{
    /**
     * Handle the Client "created" event.
     */
    public function created(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "updating" event.
     */
    public function updating(Client $client): void
    {
        // Check if OTP fields are being updated
        if ($client->isDirty(['otp_code', 'otp_expires_at'])) {
            // Fire event when OTP is generated
            if ($client->otp_code && $client->otp_expires_at) {
                event(new OtpGenerated($client));
            }
        }
    }

 
    
}
