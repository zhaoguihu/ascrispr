<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneInfoHS extends Model
{
    protected $primaryKey = 'gene_id';
    protected $guarded = [];
    protected $table = 'gene_info_hs';
}
