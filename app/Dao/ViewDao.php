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

	/**
	 * 创建单个输入框无关乎语种
	 * @param $v
	 * @return string
	 */
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
                                <textarea class="form-control" name="'.$v['field_name'];
			if(empty($v['can_null'])) {
				$content .= '" required>';
			}else{
				$content .= '" >';
			}
			$content .='{{$info[\'exhibit_num\'] or \''.$v['default_value'].'\'}}</textarea>  ';
            return $content;
        }
        //创建数字类型的前端
        if($v['front_type'] == 'number'){
            $content = '
                                <input type="number" name="'.$v['field_name'].'" value="{{$info[\''.$v['field_name'].'\'] or \''.$v['default_value'].'\'}}" class="form-control" ';
            if($v['can_null']){
                $content .= 'required/>';
            }else{
                $content .= '/>';
            }
            return $content;
        }
        //创建选择框类型的前端
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
		//创建可以上传单个文件
        if($v['front_type'] == 'single_file'){
            $content = '
                                <input type="text" name="'.$v['field_name'].'" value="{{$info[\''.$v['field_name'].'\'] or ""}}"  id="'.$v['field_name'].'" class="form-control"
                                                       style="width:400px;float: left"/>
                                                <button type="button" onclick="upload_resource(\''.$v['front_text'].'\',\'FT_ONE_MP3\',\''.$v['field_name'].'\',2);" class="btn btn-white">文件上传</button>';
            return $content;
        }
        //可以上传单个图片
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
		//可以上传多个图片
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
		//可以实现富文本
        if($v['front_type'] == 'rich_text'){
			$content = '
								<script type="text/plain" id="'.$v['field_name'].'" name="'.$v['field_name'].'">{{$info["'.$v['field_name'].'"]  or "'.$v['default_value'].'"}}</script>';
			return $content;
		}
		//可以实现时间控件
		if($v['front_type'] == 'datetime'){
			$content ='
			<input placeholder="时间" class="form-control layer-date laydate-icon" id="'.$v['field_name'].'" type="text" name="'.$v['field_name'].'"	 value="{{$info[\''.$v['field_name'].'\'] or \'\'}}"     style="width: 140px;" autocomplete="off">';
			return $content;
		}
    }

	/**
	 * 创建单个输入框（在语种下）
	 * @param $v
	 * @return string
	 */
    public static function generateInputHtmlLanguage($v){
		//创建文本域类型的前端
		if($v['front_type'] == 'text'){
			$content = '
                                <input type="text" name="'.$v['field_name'].'_'.'{{$k}}'.'" value="{{$info[\'language\'][$k][\''.$v['field_name'].'\'] or \'\'}}" class="form-control" maxlength="10" ';
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
                                <textarea class="form-control" name="'.$v['field_name'].'_{{$k}}';
			if(empty($v['can_null'])) {
				$content .= '" required>';
			}else{
				$content .= '" >';
			}
			$content .='{{$info[\'language\'][$k][\''.$v['field_name'].'\'] or \''.$v['default_value'].'\'}}</textarea>  ';
			return $content;
		}
		//创建数字类型的前端
		if($v['front_type'] == 'number'){
			$content = '
                                <input type="number" name="'.$v['field_name'].'_'.'{{$k}}'.'" value="{{$info[\'language\'][$k][\''.$v['field_name'].'\'] or \''.$v['default_value'].'\'}}" class="form-control" ';
			if($v['can_null']){
				$content .= 'required/>';
			}else{
				$content .= '/>';
			}
			return $content;
		}
		//创建选择框类型的前端
		if($v['front_type'] == 'select'){

			$content = '
                                <select class="form-control" name="'.$v['field_name'].'_{{$k}}">';
			//开始处理option
			$front_value = $v['front_value'];
			$front_value = explode('#',$front_value);
			if($front_value){
				foreach ($front_value as $k=>$text){
					if($k %2 == 0){
						//表示 value
						$content .= '
                                    <option value="'.$text."\"";
						$content .= ' @if($info && $info[\'language\'][$k][\''.$v['field_name'].'\']=='.$text.') selected @endif';
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
		//创建可以上传单个文件
		if($v['front_type'] == 'single_file'){
			$content = '
                                <input type="text" name="'.$v['field_name'].'_'.'{{$k}}'.'" value="{{$info[\'language\'][$k][\''.$v['field_name'].'\'] or ""}}"  id="'.$v['field_name'].'_'.'{{$k}}'.'" class="form-control"
                                                       style="width:400px;float: left"/>
                                <button type="button" onclick="upload_resource(\''.$v['front_text'].'\',\'FT_ONE_MP3\',\''.$v['field_name'].''.'_'.'{{$k}}'.'\',2);" class="btn btn-white">文件上传</button>';
			return $content;
		}
		//可以上传单个图片
		if($v['front_type'] == 'single_image'){
			$content = '
                                 <div class="webuploader-pick" onclick="upload_resource(\''.$v['front_text'].'\',\'FT_ONE_RESOURCE\',\''.$v['field_name'].'_{{$k}}\',1,\''
				.$v['field_name'].'_{{$k}}\',1);"
                                             style=" float: left; display: inline-block; width: auto;">点击上传图片
                                        </div>
                                    </div>
                            </div>
            ';
			$content .= '            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-4" style="overflow: auto;width: 80%;">
                                    <div id="'.$v['field_name'].'_{{$k}}">';
			$content .= '
                                        <ul id="sortable-'.$v['field_name'].'" style="list-style-type: none; margin: 0; padding: 0; width: 60%;">
                                                    @if(isset($info[\''.$v['field_name'].'\'])&&is_array($info[\''.$v['field_name'].'\'] ))
                                                    @foreach($info[\'language\'][$k][\''.$v['field_name'].'\'] as $kk=>$gg)
                                                        <div class="img-div">
                                                            <img src="{{get_file_url($gg)}}">
                                                            <span onclick="del_img($(this))">×</span>
                                                            <input type="hidden" name="'.$v['field_name'].'_{{$k}}'.'" value="{{$gg}}">
                                                        </div>
                                                    @endforeach
                                                    @endif
                                         </ul>
                                 </div>';
			return $content;
		}
		//可以上传多个图片
		if($v['front_type'] == 'mutiple_image'){
			$content = '
                                 <div class="webuploader-pick" onclick="upload_resource(\''.$v['front_text'].'\',\'FT_MORE_RESOURCE\',\''.$v['field_name'].'_{{$k}}\',1,\''
				.$v['field_name'].'_{{$k}}\',1);"
                                             style=" float: left; display: inline-block; width: auto;">点击上传图片(可多张)
                                        </div>
                                    </div>
                            </div>
            ';
			$content .= '            <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-4" style="overflow: auto;width: 80%;">
                                    <div id="'.$v['field_name'].'_{{$k}}">';
			$content .= '
                                        <ul id="sortable-'.$v['field_name'].'" style="list-style-type: none; margin: 0; padding: 0; width: 60%;">
                                                    @if(isset($info[\''.$v['field_name'].'\'])&&is_array($info[\''.$v['field_name'].'\'] ))
                                                    @foreach($info[\'language\'][$k][\''.$v['field_name'].'\'] as $kk=>$gg)
                                                        <div class="img-div">
                                                            <img src="{{get_file_url($gg)}}">
                                                            <span onclick="del_img($(this))">×</span>
                                                            <input type="hidden" name="'.$v['field_name'].'_{{$k}}[]'.'" value="{{$gg}}">
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
		//可以实现富文本
		if($v['front_type'] == 'rich_text'){
			$content = '
								<script type="text/plain" id="'.$v['field_name'].'_{{$k}}" name="'.$v['field_name'].'_'.'{{$k}}'.'">{{$info[\'language\'][$k]["'.$v['field_name'].'"]  or "'.$v['default_value'].'"}}</script>';
			return $content;
		}
	}

	/**
	 * 渲染出ueditor的js文件
	 * @param $header
	 * @return string
	 */
	private static function ui_editor($header){
		$header.= '
				
	<script src="{{cdn(\'js/plugins/ueditor/ueditor.config.js\')}}"></script>
    <script src="{{cdn(\'js/plugins/ueditor/ueditor.all.min.js\')}}"></script>
    <script src="{{cdn(\'js/plugins/ueditor/lang/zh-cn/zh-cn.js\')}}"></script>
    <script>
        layui.use(\'element\', function () {
            var $ = layui.jquery; //Tab的切换功能，切换事件监听等，需要依赖element模块
        });
    </script>
    ';
		return $header;
	}


	/**
	 *
	 */
	private static function laydate_js($header){
		$header .= '
		    <script src="{{cdn(\'js/jquery-1.12.4.min.js\')}}"></script>
    		<script src="{{cdn(\'js/plugins/laydate/laydate.js\')}}"></script>';
		return $header;
	}

    /**
     * 生成formview文件
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
		$is_datetime = 0;
        foreach ($table_struct as $v){
            if(empty($v['front_type'])){
                continue;//如果该字段的前端类型是空，则不展示
            }
            //非多语种字段
			if($v['front_type'] == 'datetime'){
            	$is_datetime = 1;
			}
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
        	// 渲染 支持语种的layui渲染
			$header .= '
						 <div class="layui-tab">
                            <ul class="layui-tab-title">
                                @foreach(config(\'language\') as $k=>$g)
                                    <li @if($k==1) class="layui-this" @endif>{{$g[\'name\']}}</li>
                                @endforeach
                            </ul>
                            <div class="layui-tab-content">
                                @foreach(config(\'language\') as $k=>$g)
                                    <div class="layui-tab-item @if($k==1) layui-show @endif">
                                 ';
            foreach ($table_struct as $v) {
                if (empty($v['front_type']) || empty($v['is_mutiple_lan'])) {
                    continue;//如果该字段的前端类型是空，则不展示
                }
                //开始进行输出lable
				$header .= '
                        <div class="form-group">
                            <label class="col-sm-2 control-label">';
				$header .= $v['front_text'].'('.'{{$g[\'name\']}}'.')</label>
                            <div class="col-sm-4">';
                //开始处理多语种
				$header .= self::generateInputHtmlLanguage($v);
				$header .= '
							</div>
						</div>';
            }
            // 渲染语种的结束符
			$header .= '
        						</div>
                                @endforeach
                            </div>
                        </div>';
        }
		//使得html进行闭合
		$header .= ' 
 						<div class="form-group">
                            <div class="col-sm-6 col-md-offset-2">
                                <button class="btn btn-primary" type="submit">保存</button>
                                <button class="btn btn-white" type="button" onclick="window.history.back()">返回</button>
                            </div>
                        </div>
                    </form>
               </div>
            </div>
        </div>
    </div>


@endsection';


		//处理完多语种之后，重新遍历$table_struct 是否需要进行js渲染
		$header .= '
@section(\'script\')';
        if($is_mutiple_language){
        	//如果有多语种，则渲染出 layui
			$header = self::ui_editor($header);

		}
		//渲染时间控件的js
		if($is_datetime){
        	$header = self::laydate_js($header);
		}
		foreach ($table_struct as $v) {
			//处理js ，只有 富文本和时间空间是需要js渲染的
			if($v['front_type'] == 'rich_text'){

				if($v['is_mutiple_lan']){
					//如果是多语种的
					$header .= '
			<script>
			//编辑器路径定义
        	var initialWidth = $(window).width() > 1366 ? 950 : 705;
        	var initialHeight = $(window).width() > 1366 ? 350 : 200;
        	@foreach(config(\'language\') as $k=>$v)
        	editor_'.$v['field_name'].'_{{$k}}= new baidu.editor.ui.Editor({
            pasteplain: true,
            initialFrameWidth: 950,
            initialFrameHeight: 300,
            wordCount: false,
            elementPathEnabled: false,
            autoHeightEnabled: false,
            initialStyle: \'img{width:20%;}\',
            toolbars: [[
                \'fullscreen\', \'source\', \'|\', \'undo\', \'redo\', \'|\',
                \'bold\', \'italic\', \'underline\', \'fontborder\', \'strikethrough\', \'superscript\', \'subscript\', \'removeformat\', \'formatmatch\', \'autotypeset\', \'blockquote\', \'pasteplain\', \'|\', \'forecolor\', \'backcolor\', \'insertorderedlist\', \'insertunorderedlist\', \'selectall\', \'cleardoc\', \'|\',
                \'rowspacingtop\', \'rowspacingbottom\', \'lineheight\', \'|\',
                \'customstyle\', \'paragraph\', \'fontfamily\', \'fontsize\', \'|\',
                \'directionalityltr\', \'directionalityrtl\', \'indent\', \'|\',
                \'justifyleft\', \'justifycenter\', \'justifyright\', \'justifyjustify\', \'|\', \'touppercase\', \'tolowercase\', \'|\',
                \'simpleupload\', \'emotion\', \'|\',
                \'horizontal\', \'date\', \'time\', \'spechars\', \'wordimage\', \'|\',
                \'inserttable\', \'deletetable\', \'insertparagraphbeforetable\', \'insertrow\', \'deleterow\', \'insertcol\', \'deletecol\', \'mergecells\', \'mergeright\', \'mergedown\', \'splittocells\', \'splittorows\', \'splittocols\', \'charts\'
            ]]
          });
          editor_'.$v['field_name'].'_{{$k}}.render(\''.$v['field_name'].'_{{$k}}\');
          editor_'.$v['field_name'].'_{{$k}}.ready(function () {
          editor_'.$v['field_name'].'_{{$k}}.execCommand(\'serverparam\', {
                \'_token\': \'{{csrf_token()}}\',
                \'filetype\': \'FT_EXHIBIT_ONE\',
                \'itemid\': 0
            });
          });
          @endforeach
		  </script>	';
				}else{

					//如果不是多语种
					$header .= '
			<script>
			//编辑器路径定义
        	var initialWidth = $(window).width() > 1366 ? 950 : 705;
        	var initialHeight = $(window).width() > 1366 ? 350 : 200;
        	editor_'.$v['field_name'].'= new baidu.editor.ui.Editor({
            pasteplain: true,
            initialFrameWidth: 950,
            initialFrameHeight: 300,
            wordCount: false,
            elementPathEnabled: false,
            autoHeightEnabled: false,
            initialStyle: \'img{width:20%;}\',
            toolbars: [[
                \'fullscreen\', \'source\', \'|\', \'undo\', \'redo\', \'|\',
                \'bold\', \'italic\', \'underline\', \'fontborder\', \'strikethrough\', \'superscript\', \'subscript\', \'removeformat\', \'formatmatch\', \'autotypeset\', \'blockquote\', \'pasteplain\', \'|\', \'forecolor\', \'backcolor\', \'insertorderedlist\', \'insertunorderedlist\', \'selectall\', \'cleardoc\', \'|\',
                \'rowspacingtop\', \'rowspacingbottom\', \'lineheight\', \'|\',
                \'customstyle\', \'paragraph\', \'fontfamily\', \'fontsize\', \'|\',
                \'directionalityltr\', \'directionalityrtl\', \'indent\', \'|\',
                \'justifyleft\', \'justifycenter\', \'justifyright\', \'justifyjustify\', \'|\', \'touppercase\', \'tolowercase\', \'|\',
                \'simpleupload\', \'emotion\', \'|\',
                \'horizontal\', \'date\', \'time\', \'spechars\', \'wordimage\', \'|\',
                \'inserttable\', \'deletetable\', \'insertparagraphbeforetable\', \'insertrow\', \'deleterow\', \'insertcol\', \'deletecol\', \'mergecells\', \'mergeright\', \'mergedown\', \'splittocells\', \'splittorows\', \'splittocols\', \'charts\'
            ]]
          });
          editor_'.$v['field_name'].'.render(\''.$v['field_name'].'\');
          editor_'.$v['field_name'].'.ready(function () {
          editor_'.$v['field_name'].'.execCommand(\'serverparam\', {
                \'_token\': \'{{csrf_token()}}\',
                \'filetype\': \'FT_EXHIBIT_ONE\',
                \'itemid\': 0
            });
          });
		  </script>	';
				}
			}
			//渲染时间控件，时间控件没有多语种
			if($v['front_type'] == 'datetime'){
				$header .= '
				<script type="text/javascript">
        var '.$v['field_name'].' = {
            elem: "#'.$v['field_name'].'", format: "YYYY-MM-DD", 
            isclear: false,
            istoday: false,
            issure: false,
            choose: function (datas) {                         
            }
        };
 laydate('.$v['field_name'].');
 </script>';
			}
		}
		//闭合 @section('script')
		$header .= '
@endsection';
        file_put_contents($table_name, $header);
    }
}

