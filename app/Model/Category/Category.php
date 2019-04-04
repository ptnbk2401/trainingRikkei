<?php

namespace App\Model\Category;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $table = "categories";
    protected $primaryKey = "id";
    public    $timestamps = false;

    public function posts() {
        return $this->belongsToMany('App\Model\Post\PostIndex', 'post_category','cat_id', 'post_id');;
    }

    public function getItems() {
        return Category::orderBy('id', 'DESC')
            ->paginate(10);
    }
    public function addItem($arItem) {
        return Category::insert($arItem);
    }
    public function editItem($arItem,$id) {
        return Category::whereId($id)->update($arItem);
    }
    public function delItem($id) {
        return Category::whereId($id)->delete();
    }

}
