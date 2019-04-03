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
                'p.id',
                'pname',
                'cname',
                'picture',
                'cat_id',
                'preview_text',
            ])
            ->from("posts","p")
            ->join("p","cats","c","p.cat_id = c.cid");
        $cfg = (new GridConfig())
            ->setDataProvider(
                new DbalDataProvider($query)
            )
            ->setPageSize(5)
            ->setColumns([
                (new FieldConfig)
                    ->setName('id')
                    ->setLabel('ID')
                    ->setSortable(true)
                    ->setSorting(Grid::SORT_ASC)
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('id')
                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )

                ,
                (new FieldConfig)
                    ->setName('pname')
                    ->setLabel('Bài viết')
                    ->setSortable(true)
                    ->setSorting(Grid::SORT_ASC)
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('pname')
                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )
                ,
                (new FieldConfig)
                    ->setName('cname')
                    ->setLabel('Danh mục')
                    ->setSortable(true)
                    ->setSorting(Grid::SORT_ASC)
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('cname')
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
                // (new FieldConfig)
                //     ->setName('created_at')
                //     ->setLabel('Ngày tạo')
                //     ->setSortable(true)
                //     ->setSorting(Grid::SORT_ASC)
                //     ->setCallback(function ($val) {
                //         return date('M, d-Y',strtotime($val));
                //     })  
                // ,
                (new FieldConfig)
                    ->setName('id')
                    ->setLabel('Action')
                    ->setCallback(function ($val) {
                        $html = '<form action=" '.route('post.edit',$val).' " method="get" style="display: inline;" >
                            <button class="btn btn-primary" type="submit" ><i style="font-size:20px" class="material-icons">edit</i></button>
                        </form>
                        <form action="'. route('post.destroy',$val).'" method="post" style="display: inline;">
                            '.csrf_field().'
                            <input type="hidden" name="_method" value="DELETE"> 
                            <button class="btn btn-danger" type="submit" onclick="return confirm(\'Bạn có chắc muốn xóa?\') "><i style="font-size:20px" class="material-icons">delete</i></button>
                        </form>';
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
                                // new RecordsPerPage,
                                (new HtmlTag)
                                    ->setContent('<span class="glyphicon glyphicon-refresh"></span> Filter ')
                                    ->setTagName('button')
                                    ->setRenderSection(RenderableRegistry::SECTION_END)
                                    ->setAttributes([
                                        'class' => 'btn btn-success '
                                    ]), 
                                (new HtmlTag)
                                    ->setContent(' <span class="glyphicon glyphicon-plus"></span> Add ')
                                    ->setTagName('button')
                                    ->setRenderSection(RenderableRegistry::SECTION_END)
                                    ->setAttributes([
                                        'type' => 'submit',
                                        'form' => 'form-create',
                                        'class' => 'btn btn-info btn-add '
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
    public function getGrid($query)
    {
        $cfg = (new GridConfig())
            ->setDataProvider(
                new DbalDataProvider($query)
            )
            ->setPageSize(5)
            ->setColumns([
                (new FieldConfig)
                    ->setName('p.id')
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
                    ->setName('pname')
                    ->setLabel('Bài viết')
                    ->setSortable(true)
                    ->setSorting(Grid::SORT_ASC)
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('pname')
                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )
                ,
                (new FieldConfig)
                    ->setName('cname')
                    ->setLabel('Danh mục')
                    ->setSortable(true)
                    ->setSorting(Grid::SORT_ASC)
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('cname')
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
                // (new FieldConfig)
                //     ->setName('created_at')
                //     ->setLabel('Ngày tạo')
                //     ->setSortable(true)
                //     ->setSorting(Grid::SORT_ASC)
                //     ->setCallback(function ($val) {
                //         return date('M, d-Y',strtotime($val));
                //     })  
                // ,
                (new FieldConfig)
                    ->setName('p.id')
                    ->setLabel('Action')
                    ->setCallback(function ($val) {
                        $html = '<form action=" '.route('post.edit',$val).' " method="get" style="display: inline;" >
                            <button class="btn btn-primary" type="submit" ><i style="font-size:20px" class="material-icons">edit</i></button>
                        </form>
                        <form action="'. route('post.destroy',$val).'" method="post" style="display: inline;">
                            '.csrf_field().'
                            <input type="hidden" name="_method" value="DELETE"> 
                            <button class="btn btn-danger" type="submit" onclick="return confirm(\'Bạn có chắc muốn xóa?\') "><i style="font-size:20px" class="material-icons">delete</i></button>
                        </form>';
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
                                // new RecordsPerPage,
                                (new HtmlTag)
                                    ->setContent('<span class="glyphicon glyphicon-refresh"></span> Filter ')
                                    ->setTagName('button')
                                    ->setRenderSection(RenderableRegistry::SECTION_END)
                                    ->setAttributes([
                                        'class' => 'btn btn-success '
                                    ]), 
                                (new HtmlTag)
                                    ->setContent(' <span class="glyphicon glyphicon-plus"></span> Add ')
                                    ->setTagName('button')
                                    ->setRenderSection(RenderableRegistry::SECTION_END)
                                    ->setAttributes([
                                        'type' => 'submit',
                                        'form' => 'form-create',
                                        'class' => 'btn btn-info btn-add '
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
    
}
