<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesginTag extends Model
{
    use HasFactory;
    protected $fillable = [
        'desgin_id',
        'tag_id',
    ];
    public function desgin(): BelongsTo
    {
        return $this->belongsTo(Desgin::class, 'desgin_id');
    }
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
    public function format(){
        return $this->tag;
    }
}
