<?php

namespace App\Models;

use App\Utils\GenererUuid;
use Illuminate\Database\Eloquent\Model;

class Marchand extends Model
{
    use GenererUuid;

    protected $table = 'marchands';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'code_marchand',
        'telephone',
    ];

    /**
     * Génère un code marchand unique au format M + 6 chiffres aléatoires
     */
    public static function genererCodeMarchand(): string
    {
        do {
            $code = 'M' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('code_marchand', $code)->exists());

        return $code;
    }
}
