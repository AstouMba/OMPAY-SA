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
}
