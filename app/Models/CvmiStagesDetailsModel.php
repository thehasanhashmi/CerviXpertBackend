<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvmiStagesDetailsModel extends Model
{
    use HasFactory;

    protected $table = 'cvmi_stages_details';

    protected $fillable = [
        'stage_name',
        'stage_file',
        'stage_descrption',
        'more_detailed_files',
    ];
}
