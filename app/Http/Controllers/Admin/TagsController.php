<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PostTag\PostTagIndex;
use App\Model\Tags\TagsIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagsController extends Controller
{
    public function __construct(TagsIndex $objTagsIndex)
    {
        $this->objTagsIndex = $objTagsIndex;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objItems = $this->objTagsIndex->getItemsIndex();
        return view('admin.tags.index',compact('objItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'tag' => 'required|unique:tags|max:255',
        ],[
            'tag.required' =>'Nhập Tags',
            'tag.max'      =>'Nhập Tags không quá :max ký tự',
            'tag.unique'   => 'Tên Tags đã tồn tại',
        ]);

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        $arItem = [
            'tag' => trim($request->tag),
        ];
        
        if($this->objTagsIndex->addItem($arItem)) {
            return redirect()->route('tags.index')->with('msg','Thêm thành công');
        } else {
            return redirect()->back()->with('msg-er','Có lỗi xảy ra');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $old_item = TagsIndex::find($id);
        return view('admin.tags.edit',compact('old_item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $old_item = TagsIndex::find($id);
        $unique = ( $old_item->tag != trim($request->tag) )? '|unique:tags' : '';
        $v = Validator::make($request->all(), [
            'tag' => 'required|max:255'.$unique,
        ],[
            'tag.required' =>'Nhập Tags',
            'tag.max'      =>'Nhập Tags không quá :max ký tự',
            'tag.unique'   => 'Tên Tags đã tồn tại',
        ]);

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        $arItem = [
            'tag' => trim($request->tag),
        ];
        if($this->objTagsIndex->editItem($arItem,$id)) {
            return redirect()->route('tags.index')->with('msg','Sửa thành công');
        } 
        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = TagsIndex::find($id);
        $count = $tag->posts->count();
        if (!$count) {
            if($this->objTagsIndex->delItem($id)) {
                return redirect()->route('tags.index')->with('msg','Xóa thành công');
            } else {
                return redirect()->back()->with('msg-er','Có lỗi xảy ra');
            }
        }
        else {
            $this->objTagsIndex->delItem($id);
            $objmPostTagIndex = new PostTagIndex();
            $objmPostTagIndex->delItemByTag($id);
            return redirect()->route('tags.index')->with('msg','Xóa thành công');
        }
    }
    /**
     * @param  Request
     * @return [type]
     */
    public function search(Request $request)
    {
        if(!empty($request->tags_search)) {
            $search = trim($request->tags_search);
            $objItems = $this->objTagsIndex->getItemsBySearch($search);
            return view('admin.tags.index',compact('objItems'));
        } else {
            return redirect()->route('tags.index');
        }
        
    }
}
