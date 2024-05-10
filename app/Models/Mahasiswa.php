<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mahasiswa extends Authenticatable
{
    use HasFactory;

    protected $table = 'mahasiswas';

    protected $primaryKey = 'nim';
    protected $fillable =
    [
        'nim',
        'nama',
        'email',
        'password',
    ];
}
