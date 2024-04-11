<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['project_id', 'price'];

    // 定義與 Project 模型的反向關聯
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
