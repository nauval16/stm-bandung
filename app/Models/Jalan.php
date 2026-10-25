<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jalan extends Model
{
    protected $fillable = ['nama_jalan', 'kecamatan'];

    public function cctvs()
    {
        return $this->hasMany(Cctv::class);
    }
}
