<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facilite extends Model
{
    use HasFactory;
    
    protected $table = "facilites";

    protected $fillable=['name','status'];
    // public function 
}
