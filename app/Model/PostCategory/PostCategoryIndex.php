<?php

namespace App\Model\PostCategory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostCategoryIndex extends Model
{
    protected $table = "post_category";
    protected $primaryKey = "id";
    // public    $timestamps = false;

    public function getItems() {
        return PostCategoryIndex::orderBy('id', 'DESC')
            ->get();
    }
    public function addItem($arCat,$post_id) {
        foreach ($arCat as $cid) {
            PostCategoryIndex::insert(['cat_id'=>$cid,'post_id'=>$post_id]);
        }
        return 1;
    }
    public function delItem($post_id) {
        return PostCategoryIndex::where('post_id',$post_id)->delete();
    }

}
