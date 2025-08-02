<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Admin extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super',
        'status',
        'profile_image'
    ];

     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_super' => 'boolean',
        'status' => 'boolean',
    ];

     /**
     * Get the files uploaded by this admin
     */
    public function uploadedFiles(): HasMany
    {
        return $this->hasMany(File::class, 'uploaded_by');
    }

    /**
     * Get active files uploaded by this admin
     */
    public function activeFiles(): HasMany
    {
        return $this->hasMany(File::class, 'uploaded_by')->where('is_active', true);
    }
}
