<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class HighestTransactionPrice  extends Model
{
    protected $fillable = ['project_id', 'manager_department', 'highest_price'];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

