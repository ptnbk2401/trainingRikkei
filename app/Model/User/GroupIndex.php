<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GroupIndex extends Model
{
    protected $table = "groups";
    protected $primaryKey = "id";
    public    $timestamps = false;

    public function getItems() {
        return GroupIndex::orderBy('id', 'DESC')->get();
    }

    public function addItem($arItem) {
        return GroupIndex::insert($arItem);
    }
    public function editItem($arItem,$id) {
        return GroupIndex::whereId($id)->update($arItem);
    }
    public function delItem($id) {
        return GroupIndex::whereId($id)->delete();
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_group','group_id', 'user_id');
    }

}
