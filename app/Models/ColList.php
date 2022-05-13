<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CardList;
class ColList extends Model
{
    use HasFactory;
    protected $table='col_lists';
    protected $fillable =[
    	'title',
        'orderCol',
        'status'

    ];

public function cardlists()
{
    return $this->hasMany(CardList::class,"list_id")->orderBy("orderRow","asc")->orderBy("updated_at","desc");
}
}
