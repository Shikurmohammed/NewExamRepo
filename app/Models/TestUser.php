<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestUser extends Model
{
    use HasFactory;
    protected $table = "tests_users";
    protected $primaryKey = 'id';  // Assuming the primary key is id
}
