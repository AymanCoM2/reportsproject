<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempUd extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable  = [
        'isUsed',
        'isForSavingNewPivot',
        'user_id',
        'query_id',
        'pivotCode',
        'sqlQuery',
        'dbName' , 
        'original'
    ];
}
