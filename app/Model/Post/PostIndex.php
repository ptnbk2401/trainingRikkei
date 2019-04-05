<?php

namespace App\Model\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostIndex extends Model
{
    protected $table = "posts";
    protected $primaryKey = "id";
    // public    $timestamps = false;


    public function categories() {
        return $this->belongsToMany('App\Model\Category\Category', 'post_category','post_id', 'cat_id');
    }
    public function tags() {
        return $this->belongsToMany('App\Model\Tags\TagsIndex', 'post_tag','post_id', 'tag_id');
    }
    public function getItems() {
        return PostIndex::orderBy('id', 'DESC')
            ->paginate(10);
    }
    public function getItemsBySearch($search) {
        return PostIndex::where('title','like','%'.$search.'%')
        	->orWhere('preview_text','like','%'.$search.'%')
        	->orderBy('id', 'DESC')
            ->paginate(10);
    }
    public function addItem($arItem) {
        return PostIndex::insertGetId($arItem);
    }
    public function editItem($arItem,$id) {
        return PostIndex::whereId($id)->update($arItem);
    }
    public function delItem($id) {
        DB::table('post_tag')->where('post_id',$id)->delete();
        return PostIndex::whereId($id)->delete();
    }
    public function changeStatus($id,$stt) {
        return PostIndex::whereId($id)->update(['status'=>$stt]);
    }

}
