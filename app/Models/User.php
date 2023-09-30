<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property array $payload
 * */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity, SoftDeletes;

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid = Str::uuid()->toString();
        });

        static::saving(function ($model) {
            unset($model->attributes['password_confirmation']);
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'payload' => 'array'
    ];

    // ************
    //   Eloquent
    // ************

    /**
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * It has many establishments (one to many)
     */
    public function establishments()
    {
        return $this->hasMany(Establishment::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'user_package')
                        ->withPivot('remaining')
                        ->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_user')
                        ->withTimestamps();
    }

    /**
     * It has one social media (one to many)
     */
    public function socialMedia()
    {
        return $this->hasMany(SocialMedia::class);
    }

    // **********
    //   Scopes
    // **********

    // *************
    //   Mutators
    // *************

    /**
     * @param $value
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // *************
    //   Accessors
    // *************

    // **********
    //   Traits
    // **********

    // ***********
    //   Helpers
    // ***********

    // ******************
    //  Private Methods
    // ******************

    // ******************
    //  Dependency Methods
    // ******************

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('Users');
    }

    /**
     * Determine if the user is banned from a given establishment.
     *
     * @param  int  $establishmentId
     * @return bool
     */
    public function isBannedFrom($establishmentId)
    {
        return $this->bannedEstablishments()->where('establishment_id', $establishmentId)->exists();
    }

    /**
     * Get the establishments where the user is banned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bannedEstablishments()
    {
        return $this->belongsToMany(UserEstablishment::class, 'user_establishment_banned');
    }


    /**
     * Get the establishments assigned to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignedEstablishments()
    {
        return $this->belongsToMany(Establishment::class, 'establishment_user', 'user_id', 'establishment_id');
    }

}
