<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Skymonitor
 *
 * @property $id
 * @property $created_at
 * @property $updated_at
 * @property $user_id
 * @property $city
 * @property $email
 * @property $phone
 * @property $uv_index_threshold
 * @property $precipitation_threshold
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Skymonitor extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'city', 'email', 'phone', 'uv_index_threshold', 'precipitation_threshold'];


}