<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'owner_id',
        'name',
        'desc',
    ];
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function desgins(): HasMany
    {
        return $this->hasMany(Desgin::class, 'group_id');
    }
    public function format(){
        return [
            "name" =>$this->name,
            "desc" =>$this->desc,
            "desgins" => $this->desgins->map->format()
        ];
    }
}
