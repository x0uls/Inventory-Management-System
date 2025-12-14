<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $table = 'user_groups';
    
    protected $primaryKey = 'group_id';

    protected $fillable = [
        'group_name',
        'description',
    ];

    public $timestamps = true;

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'group_id', 'group_id');
    }
}
