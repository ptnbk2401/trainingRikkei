<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Model\Category\Category;
use App\Model\PostCategory\PostCategoryIndex;
use App\Model\PostTag\PostTagIndex;
use App\Model\Post\PostIndex;
use App\Model\Tags\TagsIndex;
use Grids;
use HTML;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\Components\ColumnHeadersRow;
use Nayjest\Grids\Components\ColumnsHider;
use Nayjest\Grids\Components\CsvExport;
use Nayjest\Grids\Components\ExcelExport;
use Nayjest\Grids\Components\FiltersRow;
use Nayjest\Grids\Components\Filters\DateRangePicker;
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
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Grid;
use Nayjest\Grids\GridConfig;
use Nayjest\Grids\ObjectDataRow;

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
                'c.name as cname',
                'preview_text',
                'status',
                'tag',
                'u.name as uname',
            ])
            ->from("posts","p")
            ->leftjoin("p","post_category","pc","p.id = pc.post_id")        
            ->leftjoin("pc","categories","c","pc.cat_id = c.id")
            ->leftjoin("p","post_tag","pt","pt.post_id = p.id")
            ->leftjoin("pt","tags","t","pt.tag_id = t.id")
            ->join("p","users","u","p.user_id = u.id")
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
    public function create(Category $objCatIndex,TagsIndex $objmTagsIndex)
    {
        $objCatItems = $objCatIndex->getItems();
        $objTagsItems = $objmTagsIndex->getItems();
        return  view('admin.post.add',compact('objCatItems','objTagsItems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request,PostCategoryIndex $objmPostCategoryIndex,PostTagIndex $objmPostTagsIndex)
    {

        $arCatID = $request->cat_id;
        $arTagsID = $request->tags;
        $arItem = [
            'title'         => trim($request->name),
            'status'        => trim($request->status),
            'preview_text'  => trim($request->preview_text),
            'content'       => trim($request->content),
            'user_id'       => Auth::id(),
        ];
        if (!empty($request->picture)) {
            if (Input::hasFile('picture')) {
                $extension = Input::file('picture')->getClientOriginalExtension();
                $fileName = str_slug($request->name) . '-' . time() . '.' . $extension;
                $request->file('picture')->move(storage_path('app/public/media/files/posts'), $fileName);
                $arItem['picture'] = $fileName;
            }
        }
        if( $id = $this->objmPost->addItem($arItem)) {
            $objmPostCategoryIndex->addItem($arCatID,$id);
            $objmPostTagsIndex->addItem($arTagsID,$id);
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
    public function edit($id,Category $objCatIndex,TagsIndex $objmTagsIndex)
    {
        $objCatItems = $objCatIndex->getItems();
        $objTagsItems = $objmTagsIndex->getItems();
        $old_item = PostIndex::find($id);
        $old_arCat = [];
        $old_arTags = [];
        foreach ($old_item->categories as $category) {
            $old_arCat[] = $category->id;
        }
        foreach ($old_item->tags as $tag) {
            $old_arTags[] = $tag->id;
        }
        return  view('admin.post.edit',compact('old_item','objCatItems','old_arCat','old_arTags','objTagsItems'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request,$id,PostCategoryIndex $objmPostCategoryIndex,PostTagIndex $objmPostTagsIndex)
    {
        $objItemOld = PostIndex::find($id);
        $arCatID = $request->cat_id;
        $arTagsID = $request->tags;
        $arItem = [
            'title'         => trim($request->name),
            'status'        => trim($request->status),
            'preview_text'  => trim($request->preview_text),
            'content'       => trim($request->content),
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
            $objmPostCategoryIndex->delItem($id);
            $objmPostCategoryIndex->addItem($arCatID,$id);
            $objmPostTagsIndex->delItem($id);
            $objmPostTagsIndex->addItem($arTagsID,$id);
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
    public function status(Request $request)
    {
        $post_id = $request->post_id;
        $post = PostIndex::find($post_id);
        if(!empty($post)){
            $status = $post->status;
            if( !empty($status) ) {
                $this->objmPost->changeStatus($post_id,0);
                return '<i class="fa fa-fw fa-times-circle" style="font-size: 20px; color: red"></i>';
            } else {
                $this->objmPost->changeStatus($post_id,1);
                return '<i class="fa fa-fw fa-check-circle" style="font-size: 20px; color: green"></i>';
            }
        }        
    }
    public function searchTags(Request $request,TagsIndex $objmTagsIndex)
    {
        $search = $request->search;
        $Items =  $objmTagsIndex->getItemsBySearch($search);
        return response()->json(['items'=>$Items]);
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
                    ->setSorting(Grid::SORT_DESC)
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
                    ->setSorting(Grid::SORT_ASC)
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('title')
                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )
                ,
                
                (new FieldConfig)
                    ->setName('cname')
                    ->setLabel('Danh mục')
                    ->setCallback(function ($val,ObjectDataRow $row) {
                        $data = $row->getSrc();  
                        $cat = $this->getCategory($data->pid);
                        return '<p>'.$cat.'</p>';
                    })
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('c.name')
                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )                    
                ,
                (new FieldConfig)
                    ->setName('tag')
                    ->setLabel('Tags')
                    ->setSortable(true)
                    ->setSorting(Grid::SORT_ASC)
                    ->setCallback(function ($val,ObjectDataRow $row) {
                        $data = $row->getSrc();
                        $tags = $this->getTagsPost($data->pid);
                        return '<p>'.$tags.'</p>';
                    })
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('tag')
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
                    ->setName('uname')
                    ->setLabel('Editor')
                    ->addFilter(
                        (new FilterConfig)
                            ->setName('u.name')
                            ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )
                ,
                (new FieldConfig)
                    ->setName('status')
                    ->setLabel('Trạng thái')
                    ->setSortable(true)
                    ->setSorting(Grid::SORT_ASC)
                    ->setCallback(function ($val, ObjectDataRow $row) {
                        $data = $row->getSrc();  
                        $html = !empty($val)? '<a href="javascript:void(0)" onclick="changeStatus('.$data->pid.')" id="stt'.$data->pid.'"><i class="fa fa-fw fa-check-circle" style="font-size: 20px; color: green"></i></a>' : '<a href="javascript:void(0)" onclick="changeStatus('.$data->pid.')" id="stt'.$data->pid.'"><i class="fa fa-fw fa-times-circle" style="font-size: 20px; color: red"></i></a>';
                        return $html;
                    })  
                ,
                (new FieldConfig)
                    ->setName('pid')
                    ->setLabel('Action')
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
        $html = '';
        foreach ($post->categories as $category) {
            $arCat[] = $category->name;
            $c = $category->name;
            $html .= '<span class="label label-primary" title="'.$c.'">'.str_limit($c,20).'</span><br>';
        }
        // return !empty($arCat)? implode(', ', $arCat) : ''; 
        return !empty($html)? $html : ''; 
    }    
    public function getTagsPost($post_id){
        $post = PostIndex::find($post_id);
        $html = '';
        foreach ($post->tags as $tag) {
            $arTags[] = $tag->tag;
            $t = $tag->tag;
            $html .= '<span class="label label-success" title="'.$t.'">'.str_limit($t,10).'</span> ';
        }
        // return !empty($arTags)? implode(', ', $arTags) : ''; 
        return !empty($html)? $html : ''; 
    }
}
