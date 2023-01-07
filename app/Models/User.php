<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'password',
        'avatar_url',
        'type',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function group(): HasOne
    {
        return $this->hasOne(Group::class, 'owner_id');
    }

    public function formatUser(){
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'avatar' =>$this->avatar_url,
            "group" => $this->group->format(),
            'created_at' =>$this->created_at->diffForHumans()
        ];
    }
    public function format(){
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'avatar' =>$this->avatar_url,
            'created_at' =>$this->created_at->diffForHumans(),
            "group" => $this->group->format(),
            "type" => $this->type
        ];
    }
}
