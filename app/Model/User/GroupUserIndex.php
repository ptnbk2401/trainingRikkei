<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GroupUserIndex extends Model
{
    protected $table = "user_group";
    protected $primaryKey = "id";
    public    $timestamps = false;

    public function getItems() {
        return GroupUserIndex::orderBy('id', 'DESC')->get();
    }
    
    public function addItem($arItem) {
        return GroupUserIndex::insert($arItem);
    }
    public function editItem($arItem,$id) {
        return GroupUserIndex::whereId($id)->update($arItem);
    }
    public function delItem($id) {
        return GroupUserIndex::whereId($id)->delete();
    }

}
