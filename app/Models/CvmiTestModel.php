<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvmiTestModel extends Model
{
    use HasFactory;

    protected $table = 'cvmi_tests';

    protected $fillable = [
        'user_id',
        'testing_file',
        'stage',
        'description',
    ];
}
