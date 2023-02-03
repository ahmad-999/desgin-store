<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Desgin extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'name',
        'url',
        'video_url',
        'desc',
    ];
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function tags(): HasMany
    {
        return $this->hasMany(DesginTag::class, 'desgin_id');
    }



    public function format()
    {
        return [
            "name" => $this->name,
            "desc" => $this->desc,
            "url" => $this->url,
            "video" => $this->video_url,
            "created_at" => $this->created_at->diffForHumans(),
            "tags" => $this->tags->map->format()
        ];
    }
    public function formatWithOwner()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "desc" => $this->desc,
            "url" => $this->url,
            "video" => $this->video_url,
            "created_at" => $this->created_at->diffForHumans(),
            "owner" => $this->group->owner != null ? $this->group->owner->formatUser() : null,
            "tags" => $this->tags->map->format()
        ];
    }
}
