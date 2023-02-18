<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name','genere_id'];

    public function desgins(): HasMany
    {
        return $this->hasMany(DesginTag::class, 'tag_id');
    }
    public function genere():BelongsTo{
        return $this->belongsTo(TagGenre::class,'genere_id');
    }
}
