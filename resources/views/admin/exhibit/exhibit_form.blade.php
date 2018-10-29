@extends('layouts.public')
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
    <script src="{{cdn('js/plugins/jquery-ui.min.js') }}"></script>
@endsection
@section('bodyattr')@endsection
@section('body')
    <div class="wrapper wrapper-content">
        <div class="row m-b">
            <div class="col-sm-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                     <li><a href="{{route('admin.exhibit.index')}}">展品列表</a></li>
                     <li class='active'><a href="javascript:void(0)">展品编辑</a></li>
                      </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <form action="{{route('admin.exhibit.save')}}" method="post" class="form-horizontal ajaxForm">
						{{csrf_field()}}
						<input type="hidden" value="{{$info['exhibit_id'] or  'add'}}" name='exhibit_id'/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">展品编号</label>
                            <div class="col-sm-4">
                                <input type="number" name="exhibit_num" value="{{$info['exhibit_num'] or ''}}" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">列表图</label>
                            <div class="col-sm-4">
                                 <div class="webuploader-pick" onclick="upload_resource('列表图','FT_ONE_RESOURCE','list_img',1,'list_img',1);"
                                             style=" float: left; display: inline-block; width: auto;">点击上传图片
                                        </div>
                                    </div>
                            </div>                           
                            
                            
                        <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-4" style="overflow: auto;width: 80%;">
                                    <div id="list_img">@if($info && $info['list_img'])
								 <div class="img-div">
                                                    <img src="{{get_file_url($info['list_img'])}}">
                                                    <span onclick="del_img($(this))">×</span>
                                                    <input type="hidden" name="list_img" value="{{$info['list_img']}}">
                                                </div>
                                                              
                               
                                 @endif
                                 </div>
                                 
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">展品类型</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="type">
                                    <option value="1" @if($info && $info['type']==1) selected @endif>精品文物</option>
                                    <option value="2" @if($info && $info['type']==2) selected @endif>普通文物</option>
                                  </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">详细内容</label>
                            <div class="col-sm-4">
								<script type="text/plain" id="content" name="content">{{$info["content"]  or ""}}</script>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">详情图</label>
                            <div class="col-sm-4">
                                 <div class="webuploader-pick" onclick="upload_resource('详情图','FT_MORE_RESOURCE','mutiple_imgs',1,'mutiple_imgs',1);"
                                             style=" float: left; display: inline-block; width: auto;">点击上传图片(可多张)
                                        </div>
                                    </div>
                            </div>
                        <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-4" style="overflow: auto;width: 80%;">
                                    <div id="mutiple_imgs">
                                        <ul id="sortable-mutiple_imgs" style="list-style-type: none; margin: 0; padding: 0; width: 60%;">
                                                    @if(isset($info['mutiple_imgs'])&&is_array($info['mutiple_imgs'] ))
                                                    @foreach($info['mutiple_imgs'] as $kk=>$gg)
                                                        <div class="img-div">
                                                            <img src="{{get_file_url($gg)}}">
                                                            <span onclick="del_img($(this))">×</span>
                                                            <input type="hidden" name="mutiple_imgs[]" value="{{$gg}}">
                                                        </div>
                                                    @endforeach
                                                    @endif
                                         </ul>                                         
                                                
                                        </div>
                            </div>
                        </div> 
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


@endsection
@section('script')
				
	<script src="{{cdn('js/plugins/ueditor/ueditor.config.js')}}"></script>
    <script src="{{cdn('js/plugins/ueditor/ueditor.all.min.js')}}"></script>
    <script src="{{cdn('js/plugins/ueditor/lang/zh-cn/zh-cn.js')}}"></script>
    <script>
        layui.use('element', function () {
            var $ = layui.jquery; //Tab的切换功能，切换事件监听等，需要依赖element模块
        });
    </script>
    
			<script>
			//编辑器路径定义
        	var initialWidth = $(window).width() > 1366 ? 950 : 705;
        	var initialHeight = $(window).width() > 1366 ? 350 : 200;
        	editor_content= new baidu.editor.ui.Editor({
            pasteplain: true,
            initialFrameWidth: 950,
            initialFrameHeight: 300,
            wordCount: false,
            elementPathEnabled: false,
            autoHeightEnabled: false,
            initialStyle: 'img{width:20%;}',
            toolbars: [[
                'fullscreen', 'source', '|', 'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                'directionalityltr', 'directionalityrtl', 'indent', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                'simpleupload', 'emotion', '|',
                'horizontal', 'date', 'time', 'spechars', 'wordimage', '|',
                'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts'
            ]]
          });
          editor_content.render('content');
          editor_content.ready(function () {
          editor_content.execCommand('serverparam', {
                '_token': '{{csrf_token()}}',
                'filetype': 'FT_EXHIBIT_ONE',
                'itemid': 0
            });
          });
		  </script>	
@endsection