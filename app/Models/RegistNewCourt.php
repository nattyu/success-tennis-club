<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistNewCourt extends Model
{
    use HasFactory;

    protected $fillable = [
        'court_name',
        'address'
    ];

    public function postCourts() {
        return $this->hasMany(PostCourt::class);
    }
}
