<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TagGenre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class, 'genere_id');
    }

    public function format()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tags' => $this->tags,
        ];
    }
}
