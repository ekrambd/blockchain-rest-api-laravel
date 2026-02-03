<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Wallet extends Model
{
    use HasFactory;


    protected $fillable = [
        'wallet_name',
        'wallet_address',
        'existing_wallet_address',
        'import_by',
        'private_key',
        'mnemonic',
        'status',
    ];


    public function transactions()
    {
    	return $this->hasMany(Transaction::class);
    }
}
