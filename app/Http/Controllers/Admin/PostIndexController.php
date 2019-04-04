<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Model\Post\PostIndex;
use Grids;
use HTML;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\Components\ColumnHeadersRow;
use Nayjest\Grids\Components\ColumnsHider;
use Nayjest\Grids\Components\CsvExport;
use Nayjest\Grids\Components\ExcelExport;
use Nayjest\Grids\Components\Filters\DateRangePicker;
use Nayjest\Grids\Components\FiltersRow;
use Nayjest\Grids\Components\HtmlTag;
use Nayjest\Grids\Components\Laravel5\Pager;
use Nayjest\Grids\Components\OneCellRow;
use Nayjest\Grids\Components\RecordsPerPage;
use Nayjest\Grids\Components\RenderFunc;
use Nayjest\Grids\Components\ShowingRecords;
use Nayjest\Grids\Components\TFoot;
use Nayjest\Grids\Components\THead;
use Nayjest\Grids\Components\TotalsRow;
use Nayjest\Grids\DbalDataProvider;
use Nayjest\Grids\EloquentDataProvider;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Grid;
use Nayjest\Grids\GridConfig;

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
        $query = \DB::getDoctrineConnection()->createQueryBuilder();
        $query
            ->select([
                'p.id as pid',
                'title',
                'picture',
                'name',
                'preview_text',
                'status',
            ])
            ->from("posts","p")
            ->join("p","post_category","pc","p.id = pc.post_id")        
            ->join("pc","categories","c","pc.cat_id = c.id")
            ->groupby('p.id');        
        $grid =  $this->getGrid($query);
        $objItems = $this->objmPost->getItems();
        return view('admin.post.index',compact('objItems','grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('admin.post.add');
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
            'title' => trim($request->name),
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
        return  view('admin.post.detail',compact('objItem'));
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
        return  view('admin.post.edit',compact('old_item'));
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
            'title' => trim($request->name),
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
    public function getGrid($query)
    {
        $query;
        // dd($query);
        $cfg = (new GridConfig())
            ->setDataProvider(
                new DbalDataProvider($query)
            )
            ->setPageSize(10)
            ->setColumns([
                (new FieldConfig)
                    ->setName('pid')
                    ->setLabel('ID')
                    ->setSortable(true)
                    ->setSorting(Grid::SORT_ASC)
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('p.id')
                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )

                ,
                (new FieldConfig)
                    ->setName('title')
                    ->setLabel('Bài viết')
                    ->setSortable(true)
                    ->setWidth('300px')
                    ->setSorting(Grid::SORT_ASC)
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('title')
                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )
                ,
                
                (new FieldConfig)
                    ->setName('pid')
                    ->setLabel('Danh mục')
                    ->setCallback(function ($val) {
                        $cat = $this->getCategory($val);
                        return '<p>'.$cat.'</p>';
                    })
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('name')
                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )                    
                ,
                (new FieldConfig)
                    ->setName('picture')
                    ->setLabel('Hình ảnh')
                    ->setCallback(function ($val) {
                        return "<img src='".asset('/storage/media/files/posts/' .$val)."' style='width: 110px; height: 80px'>";
                    })                    
                ,
                (new FieldConfig)
                    ->setName('preview_text')
                    ->setLabel('Mô tả')
                    ->addFilter(
                        (new FilterConfig)
                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )
                ,
                (new FieldConfig)
                    ->setName('status')
                    ->setWidth('130px')
                    ->setLabel('Trạng thái')
                    ->setSortable(true)
                    ->setSorting(Grid::SORT_ASC)
                    ->setCallback(function ($val) {
                        $html = !empty($val)? '<a href="javascript:void(0)"><i class="fa fa-fw fa-check-circle" style="font-size: 20px; color: green"></i></a>' : '<a href="javascript:void(0)"><i class="fa fa-fw fa-times-circle" style="font-size: 20px; color: red"></i></a>';
                        return $html;
                    })  
                ,
                (new FieldConfig)
                    ->setName('pid')
                    ->setLabel('Action')
                    ->setWidth('130px')
                    ->setCallback(function ($val) {
                        $html = '<a href=" '.route('post.edit',$val).' " class="btn btn-info" ><i class="fa fa-fw fa-pencil-square"></i></a>
                            <a class="btn btn-danger" href="javascript:void(0)"  onclick="deletePost('.$val.')"><i class="fa fa-fw fa-trash"></i></a>';
                        return $html;
                    }) 
                ,
                
            ])

            ->setComponents([
                (new THead)
                    ->getComponentByName(FiltersRow::NAME)
                    ->getParent()
                    ->setComponents([
                        (new ColumnHeadersRow),
                        (new FiltersRow),
                        (new OneCellRow)
                            ->setRenderSection(RenderableRegistry::SECTION_END)
                            ->setComponents([
                                (new RecordsPerPage)
                                    ->setVariants([10,20,50,100])
                                ,
                                (new HtmlTag)
                                    ->setContent('<span class="glyphicon glyphicon-refresh"></span> Filter ')
                                    ->setTagName('button')
                                    ->setRenderSection(RenderableRegistry::SECTION_END)
                                    ->setAttributes([
                                        'class' => 'btn btn-success ',
                                        'style' => 'margin: 0 10px'
                                    ]), 
                                (new HtmlTag)
                                    ->setContent(' <span class="glyphicon glyphicon-plus"></span> Add ')
                                    ->setTagName('button')
                                    ->setRenderSection(RenderableRegistry::SECTION_END)
                                    ->setAttributes([
                                        'type' => 'submit',
                                        'form' => 'form-create',
                                        'class' => 'btn btn-info',
                                        'style' => 'margin: 0 10px'
                                    ]),
                            ])
                    ])
                ,
                
                (new TFoot)
                ->setComponents([
                    (new OneCellRow)
                        ->setComponents([
                            new Pager,
                            (new HtmlTag)
                                ->setAttributes(['class' => 'pull-right'])
                                ->addComponent(new ShowingRecords)
                            ,
                        ])
                ])
            
            ]);
        $grid = (new Grid($cfg))->render();
        return $grid;
    }
    
    public function getCategory($post_id){
        $post = PostIndex::find($post_id);
        foreach ($post->categories as $category) {
            $arCat[] = $category->name;
        }
        return !empty($arCat)? implode(',', $arCat) : ''; 
    }
}
