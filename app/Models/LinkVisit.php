<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkVisit extends Model
{
    use HasFactory;
    public function queryParams()
    {
        return $this->hasMany(LinkVisitQueryLog::class, 'link_visit_id', 'id');
    }
}
