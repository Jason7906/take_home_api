<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'manager_department'];

    

    // 定義與 Transaction 模型的一對多關係
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
}