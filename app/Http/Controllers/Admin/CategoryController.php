<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct(Category $objCatIndex)
    {
        $this->objCatIndex = $objCatIndex;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objItems = $this->objCatIndex->getItems();
        return view('admin.cat.index',compact('objItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cat.add');
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
            'name' => 'required|unique:categories|max:255',
        ],[
            'name.required' =>'Nhập tên danh mục',
            'name.max'      =>'Nhập tên danh mục không quá :max ký tự',
            'name.unique'   => 'Tên danh mục đã tồn tại',
        ]);

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        $arItem = [
            'name' => trim($request->name),
        ];
        
        if($this->objCatIndex->addItem($arItem)) {
            return redirect()->route('cat.index')->with('msg','Thêm thành công');
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
        $old_item = Category::find($id);
        return  view('admin.cat.edit',compact('old_item'));
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
        $old_item = Category::find($id);
        $unique = ( $old_item->name != trim($request->name) )? '|unique:categories' : '';
        $v = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ],[
            'name.required' =>'Nhập tên danh mục',
            'name.max'      =>'Nhập tên danh mục không quá :max ký tự',
            'name.unique'   => 'Tên danh mục đã tồn tại',
        ]);

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        $arItem = [
            'name' => trim($request->name),
        ];
        if($this->objCatIndex->editItem($arItem,$id)) {
            return redirect()->route('cat.index')->with('msg','Sửa thành công');
        } 
        return redirect()->route('cat.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int    $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $count = $category->posts->count();
        if (!$count) {
            if($this->objCatIndex->delItem($id)) {
                return redirect()->route('cat.index')->with('msg','Xóa thành công');
            } else {
                return redirect()->back()->with('msg-er','Có lỗi xảy ra');
            }
        }
        else {
            return redirect()->route('cat.index')->with('msg-er','Danh mục cón chứa bài viết! Cần xóa bài viết trước..');
        }
        // $this->objCatIndex->delItem($id)
    }
    public function search(Request $request)
    {
        if(!empty($request->search)) {
            $search = trim($request->search);
            $objItems = $this->objCatIndex->getItemsBySearch($search);
            return view('admin.cat.index',compact('objItems'));
        } else {
            return redirect()->route('post.index');
        }
        
    }
}
