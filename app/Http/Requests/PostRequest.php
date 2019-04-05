<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Route $route)
    {
        $required = (strpos($route->getName(),"create")) ? "required" : "";
        return [
            'name'          => 'required|max:255',
            'preview_text'  => "required|max:255",
            'cat_id'        => "required",
            'content'        => "required",
            'picture'        => $required,
        ];
    }
    public function messages()
    {
        return [
            'name.required'         => 'Nhập tên bài viết',
            'content.required'      => 'Nhập nội dung bài viết',
            'name.max'              => 'Nhập tên bài viết không quá :max ký tự',
            'preview_text.required' => 'Nhập mô tả',
            'preview_text.max'      => 'Nhập mô tả không quá :max ký tự',
            'cat_id.required'       => 'Chọn danh mục',
            'picture.required'      => 'Thêm hình ảnh',
        ];
    }
}
