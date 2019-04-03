<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Model\Post\PostIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Grids;
use HTML;

class PostIndexController extends Controller
{
    private $objmPost;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(PostIndex $objmPost){
        $this->objmPost = $objmPost;
    }

    public function index()
    {
        $cfg = [
            'src' => 'App\Model\Post\PostIndex',
            'columns' => [
                'id',
                'name',
                'preview_text',
                'created_at'
            ]
        ];
        $grid = Grids::make($cfg);
        echo $grid;
        dd($grid);

        $objItems = $this->objmPost->getItems();
        return view('public.post.index',compact('objItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('public.post.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $arItem = [
            'pname' => trim($request->name),
            'preview_text' => trim($request->preview_text),
            'cat_id' => trim($request->cat_id)
        ];
        if (!empty($request->picture)) {
            if (Input::hasFile('picture')) {
                $extension = Input::file('picture')->getClientOriginalExtension();
                $fileName = str_slug($request->name) . '-' . time() . '.' . $extension;
                $request->file('picture')->move(storage_path('app/public/media/files/posts'), $fileName);
                $arItem['picture'] = $fileName;
            }
        }
        if($this->objmPost->addItem($arItem)) {
            return redirect()->route('post.index')->with('msg','Thêm thành công');
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
        $objItem = PostIndex::find($id);
        return  view('public.post.detail',compact('objItem'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $old_item = PostIndex::find($id);
        return  view('public.post.edit',compact('old_item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request,$id)
    {
        $objItemOld = PostIndex::find($id);
        $arItem = [
            'pname' => trim($request->name),
            'preview_text' => trim($request->preview_text),
            'cat_id' => trim($request->cat_id)
        ];
        if (Input::hasFile('picture')) {
            $file =  Input::file('picture');
            $extension = $file->getClientOriginalExtension();
            // dd($extension);
            $fileName = str_slug($request->name) . '-' . time() . '.' . $extension;
            $request->file('picture')->move(storage_path('app/public/media/files/posts'), $fileName);
            $arItem['picture'] = $fileName;
        } else {
            $arItem['picture'] = $objItemOld->picture;
        }
        if($this->objmPost->editItem($arItem,$id)) {
            if ( !empty($objItemOld->picture) && isset($fileName) ) {
                Storage::delete("public/media/files/posts/" . $objItemOld->picture);
            }
            return redirect()->route('post.index')->with('msg','Sửa thành công');
        } else {
            return redirect()->back()->with('msg-er','Có lỗi xảy ra');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->objmPost->delItem($id)) {
            return redirect()->route('post.index')->with('msg','Xóa thành công');
        } else {
            return redirect()->back()->with('msg-er','Có lỗi xảy ra');
        }
    }
    public function search(Request $request)
    {
        if(!empty($request->search)) {
            $search = trim($request->search);
            $objItems = $this->objmPost->getItemsBySearch($search);
            return view('public.post.index',compact('objItems'));
        } else {
            return redirect()->route('post.index');
        }
        
    }
    public function getGrid()
    {
        // country selected filter
        $nameFilterPost = 'byPostId';
        $listPost = PostIndex::selectRaw('id, pname as label')->get()->toArray();
        $listPostSelected = [];
        if ($cityId = (int)\request()->get($nameFilterPost)) {
            /** @var PostIndex $model */
            $model = PostIndex::find($cityId);
            $listPostSelected = [
                'label' => $model->name,
                'id'    => $model->id,
            ];
        }        
        // columns
        $gridView = app(\Assurrussa\GridView\GridView::NAME);
        $gridView->column('id', '#')->setSort(true)->setFilterString('byId', '', '', 'width:60px');
        $gridView->column()->setCheckbox();
        $gridView->column('pname', 'pname')->setFilterString('byTitleLike', '', '', 'width:60px')->setSort(true);
        $gridView->column('preview_text', 'preview_text')->setScreening(true)->setHandler(function ($data) {
            /** @var \App\Post $data */
            return '<img src="' . $data->preview_text . '" alt="' . $data->pname . '" widht="60" height="60">';
        });
        $gridView->column('created_at', 'Created At')->setDateActive(true)
            ->setFilterDate('byCreatedAt', '', true, 'Y-m-d H:i')
            ->setFilterFormat('DD MMM YY');
        // column actions
        $gridView->columnActions(function ($data, $columns) {
            /**
             * @var \App\Post                           $data
             * @var \Assurrussa\GridView\Support\Column $columns
             */
            $columns->addButton()->setActionShow('post.show', [$data->id])
                ->setClass('btn btn-info btn-sm')
                ->setOptions(['target' => '_blank'])
                ->setHandler(function ($data) {
                    /** @var \App\Post $data */
                    return $data->id % 2;
                });
            $columns->addButton()->setActionEdit('post.edit', [$data->id], 'Edit')
                ->setClass('btn btn-outline-primary btn-sm')
                ->setOptions(['target' => '_blank'])
                ->setHandler(function ($data) {
                    /** @var \App\Post $data */
                    return $data->id % 2;
                });
            $columns->addButton()->setActionDelete('post.destroy', [$data->id], '')
                ->setHandler(function ($data) {
                    /** @var \App\Post $data */
                    return $data->id % 2 && !$data->deleted_at;
                });
            $columns->addButton()->setActionRestore('post.restore', [$data->id])
                ->setMethod('PUT')->setHandler(function ($data) {
                    /** @var \App\Post $data */
                    return $data->deleted_at;
                });
        });
//        // column actions
//        $gridView->columnActions(function ($data) use ($gridView) {
//            /** @var \App\Post $data */
//            $buttons = [];
//            $buttons[] = $gridView->columnAction()->setActionShow('post.show', [$data->id])
//                ->setClass('btn btn-info btn-sm')
//                ->setOptions(['target' => '_blank'])
//                ->setHandler(function ($data) {
//                    /** @var \App\Post $data */
//                    return $data->id % 2;
//                });
//            $buttons[] = $gridView->columnAction()->setActionEdit('post.edit', [$data->id], 'Edit')
//                ->setClass('btn btn-outline-primary btn-sm')
//                ->setOptions(['target' => '_blank'])
//                ->setHandler(function ($data) {
//                    /** @var \App\Post $data */
//                    return $data->id % 2;
//                });
//            $buttons[] = $gridView->columnAction()->setActionDelete('post.destroy', [$data->id], '')
//                ->setHandler(function ($data) {
//                    /** @var \App\Post $data */
//                    return $data->id % 2 && !$data->deleted_at;
//                });
//            $buttons[] = $gridView->columnAction()->setActionRestore('post.restore', [$data->id])
//                ->setMethod('PUT')->setHandler(function ($data) {
//                    /** @var \App\Post $data */
//                    return $data->deleted_at;
//                });
//            return $buttons;
//        });
        // create button
        $gridView->button()->setButtonCreate(route('post.create'));
        // create custom button
        // $gridView->button()->setButtonCheckboxAction(route('post.custom'), '?custom=');
        return $gridView;
    }
    private function _getGridView()
    {
        /** @var \Assurrussa\GridView\GridView $gridView */
        $query = $this->objmPost->newQuery();
        $gridView = app('amiGrid');
        $gridView->setQuery($query)
            ->setSearchInput(true);

        // .......
        // .......
        // .......

        return $gridView;
    }
}
