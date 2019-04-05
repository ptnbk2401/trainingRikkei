<?php

namespace App\Model\Tags;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TagsIndex extends Model
{
    protected $table = "tags";
    protected $primaryKey = "id";
    public    $timestamps = false;

    public function posts() {
        return $this->belongsToMany('App\Model\Post\PostIndex', 'post_tag','tag_id', 'post_id');
    }

    public function getItems() {
        return TagsIndex::orderBy('id', 'DESC')
            ->get();
    }
    public function getItemsIndex() {
        return TagsIndex::orderBy('id', 'DESC')
            ->paginate(10);
    }
    public function getItem($tag) {
        return TagsIndex::where('tag',$tag)->first();
    }
    public function findOrCreate($tag) {
        if(!empty( $this->getItem($tag) )){
            return $this->getItem($tag)->id;
        } else {
            return $this->addItem(['tag'=>$tag]);
        }
    }
    public function addItem($arItem) {
        return TagsIndex::insertGetId($arItem);
    }
    public function editItem($arItem,$id) {
        return TagsIndex::whereId($id)->update($arItem);
    }
    public function delItem($id) {
        return TagsIndex::whereId($id)->delete();
    }

    public function getItemsBySearch($search) {
        return TagsIndex::where('tag','like','%'.$search.'%')
            ->orderBy('id', 'DESC')
            ->paginate(10);
    }


}
