<?php

namespace App\Dao;

use Illuminate\Database\Eloquent\Model;

class ViewDao
{
    /**
     * 生成view文件的name
     */
    public static function getFormFileName($table_name){
        $dir = resource_path('views'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.$table_name.DIRECTORY_SEPARATOR);
        if(!file_exists($dir)){
            mkdir ($dir,0777,true);
        }else{

        }
        $fileName = $dir.$table_name."_form.blade.php";
        return $fileName;
    }

    /**
     * 获得form页面的header
     * @return string
     */
    public static function generateFormHeaderContent(){
        $header = "@extends('layouts.public')
@section('head')
    <style>
        #position {
            width: 500px;
            height: 245px;
            overflow: hidden;
            border-radius: 3px;
            border: 1px solid #dcdcdc;
            background-color: #fbf8f1;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
            cursor: default;
        }

        #sortable {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 80%;
        }
    </style>
    <script src='".cdn('js/plugins/jquery-ui.min.js') ."'></script>
@endsection
@section('bodyattr')@endsection";

        $header .= "
@section('body')
    <div class=\"wrapper wrapper-content\">
        <div class=\"row m-b\">
            <div class=\"col-sm-12\">
                <div class=\"tabs-container\">
                    <ul class=\"nav nav-tabs\">
                     <li><a href=\"javascript:void(0)\">展品列表</a></li>
                      </ul>
                </div>
            </div>
        </div>
        ";
        return $header;
    }

    public static function generateInputHtmlOutLanguage($v){
        //创建文本域类型的前端

        if($v['front_type'] == 'text'){
           $content = '
                                <input type="text" name="'.$v['field_name'].'" value="{{$info[\'exhibit_num\'] or \'\'}}" class="form-control" maxlength="10" ';
           if($v['can_null']){
               $content .= 'required/>';
           }else{
               $content .= '/>';
           }
           return $content;
        }
        //创建textarea类型的前端
        if($v['front_type'] == 'textarea'){
            $content = '
                                <textarea class="form-control" name="'.$v['field_name'].'">{{$info[\'exhibit_num\'] or \''.$v['default_value'].'\'}}</textarea>  ';
            if($v['can_null']){
                $content .= 'required/>';
            }else{
                $content .= '/>';
            }
            return $content;
        }
        //创建数字类型的前端
        if($v['front_type'] == 'number'){
            $content = '
                                <input type="number" name="'.$v['field_name'].'" value="{{$info[\''.$v['field_name'].'\'] or '.$v['default_value'].'}}" class="form-control" ';
            if($v['can_null']){
                $content .= 'required/>';
            }else{
                $content .= '/>';
            }
            return $content;
        }
        if($v['front_type'] == 'select'){

            $content = '
                                <select class="form-control" name="'.$v['field_name'].'">';
            //开始处理option
            $front_value = $v['front_value'];
            $front_value = explode('#',$front_value);
            if($front_value){
                foreach ($front_value as $k=>$text){
                    if($k %2 == 0){
                        //表示 value
                        $content .= '
                                    <option value="'.$text."\"";
                        $content .= ' @if($info && $info[\''.$v['field_name'].'\']=='.$text.') selected @endif';
                        $content .='>';
                    }else{
                        //表示展示的值
                        $content .= $text.'</option>';
                    }
                }
                $content .= '
                                  </select>';

            }
            return $content;


        }
        if($v['front_type'] == 'single_file'){
            $content = '
                                <input type="text" name="'.$v['field_name'].'" value="{{$info[\''.$v['field_name'].'\'] or ""}}"  id="'.$v['field_name'].'" class="form-control"
                                                       style="width:400px;float: left"/>
                                                <button type="button" onclick="upload_resource(\''.$v['front_text'].'\',\'FT_ONE_MP3\',\''.$v['field_name'].'\',2);" class="btn btn-white">文件上传</button>';
            return $content;
        }
        if($v['front_type'] == 'single_image'){
            $content = '
                                 <div class="webuploader-pick" onclick="upload_resource(\''.$v['front_text'].'\',\'FT_ONE_RESOURCE\',\''.$v['field_name'].'\',1,\''
                .$v['field_name'].'\',1);"
                                             style=" float: left; display: inline-block; width: auto;">点击上传图片
                                        </div>
                                    </div>
                            </div>
            ';
            $content .= '            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-4" style="overflow: auto;width: 80%;">
                                    <div id="'.$v['field_name'].'">';
            $content .= '
                                        <ul id="sortable-'.$v['field_name'].'" style="list-style-type: none; margin: 0; padding: 0; width: 60%;">
                                                    @if(isset($info[\''.$v['field_name'].'\'])&&is_array($info[\''.$v['field_name'].'\'] ))
                                                    @foreach($info[\''.$v['field_name'].'\'] as $kk=>$gg)
                                                        <div class="img-div">
                                                            <img src="{{get_file_url($gg)}}">
                                                            <span onclick="del_img($(this))">×</span>
                                                            <input type="hidden" name="{{$g[\'key\']}}[]" value="{{$gg}}">
                                                        </div>
                                                    @endforeach
                                                    @endif
                                         </ul>
                                 </div>';
            return $content;
        }

        if($v['front_type'] == 'mutiple_image'){
            $content = '
                                 <div class="webuploader-pick" onclick="upload_resource(\''.$v['front_text'].'\',\'FT_MORE_RESOURCE\',\''.$v['field_name'].'\',1,\''
                .$v['field_name'].'\',1);"
                                             style=" float: left; display: inline-block; width: auto;">点击上传图片(可多张)
                                        </div>
                                    </div>
                            </div>
            ';
            $content .= '            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-4" style="overflow: auto;width: 80%;">
                                    <div id="'.$v['field_name'].'">';
            $content .= '
                                        <ul id="sortable-'.$v['field_name'].'" style="list-style-type: none; margin: 0; padding: 0; width: 60%;">
                                                    @if(isset($info[\''.$v['field_name'].'\'])&&is_array($info[\''.$v['field_name'].'\'] ))
                                                    @foreach($info[\''.$v['field_name'].'\'] as $kk=>$gg)
                                                        <div class="img-div">
                                                            <img src="{{get_file_url($gg)}}">
                                                            <span onclick="del_img($(this))">×</span>
                                                            <input type="hidden" name="{{$g[\'key\']}}[]" value="{{$gg}}">
                                                        </div>
                                                    @endforeach
                                                    @endif
                                         </ul>
                                          
                                                <script>
                                                    $(function () {
                                                        $("#sortable-'.$v['field_name'].'").sortable();
                                                    });
                                                </script>
                                        </div>';
            return $content;
        }
    }

    /**
     * 生成view文件
     * @param $model
     */
    public static function makeFormView($model){
        $table_name = self::getFormFileName($model->table_name);
        $table_struct = \json_decode($model->table_struct, true);
        $header = self::generateFormHeaderContent();
        $header .= '<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <form action="" method="post" class="form-horizontal ajaxForm">';
        $is_mutiple_language = 0;

        foreach ($table_struct as $v){
            if(empty($v['front_type'])){
                continue;//如果该字段的前端类型是空，则不展示
            }
            //非多语种字段
            if(empty($v['is_mutiple_lan'])){
                //添加展示的label
                $header .= '
                        <div class="form-group">
                            <label class="col-sm-2 control-label">';
                $header .= $v['front_text'].'</label>
                            <div class="col-sm-4">';
                //根据选择不同的前端类型渲染出不同的内容
                $header .= self::generateInputHtmlOutLanguage($v);
                $header .= '
                            </div>
                        </div>';
            }
            //多语种字段,稍后一起处理
            else{
                $is_mutiple_language = 1;
                continue;
            }
        }
        //开始处理多语种字段
        if($is_mutiple_language) {
            foreach ($table_struct as $v) {
                if (empty($v['front_type']) || empty($v['is_mutiple_lan'])) {
                    continue;//如果该字段的前端类型是空，则不展示
                }
                //开始处理多语种
            }
        }

        file_put_contents($table_name, $header);
    }
}

