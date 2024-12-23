<?php

namespace Workdo\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillAttechment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'file_name',
        'file_path',
        'file_size',
    ];

 
}
