<?php

namespace App\Model\PostTag;

use App\Model\Tags\TagsIndex;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostTagIndex extends Model
{
    protected $table = "post_tag";
    protected $primaryKey = "id";
    // public    $timestamps = false;

    public function getItems() {
        return PostTagIndex::orderBy('id', 'DESC')
            ->get();
    }
    public function addItem($arTags,$post_id) {
        $objTags = new TagsIndex();
        foreach ($arTags as $tag) {
            $idtag = $objTags->findOrCreate($tag);
            PostTagIndex::insert(['tag_id'=>$idtag,'post_id'=>$post_id]);
        }
        return 1;
    }
    public function delItem($post_id) {
        return PostTagIndex::where('post_id',$post_id)->delete();
    }
    public function delItemByTag($tag_id) {
        return PostTagIndex::where('tag_id',$tag_id)->delete();
    }

}
