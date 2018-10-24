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
    <script src='http://127.0.0.1:8826/js/plugins/jquery-ui.min.js'></script>
@endsection
@section('bodyattr')@endsection
@section('body')
    <div class="wrapper wrapper-content">
        <div class="row m-b">
            <div class="col-sm-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                     <li><a href="javascript:void(0)">展品列表</a></li>
                      </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <form action="" method="post" class="form-horizontal ajaxForm">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">测试文案</label>
                            <div class="col-sm-4">
										<input placeholder="时间" class="form-control layer-date laydate-icon" id="title" type="text" name="title"	 value="{{$info['title'] or ''}}"     style="width: 140px;" autocomplete="off">
                            </div>
                        </div>
						 <div class="layui-tab">
                            <ul class="layui-tab-title">
                                @foreach(config('language') as $k=>$g)
                                    <li @if($k==1) class="layui-this" @endif>{{$g['name']}}</li>
                                @endforeach
                            </ul>
                            <div class="layui-tab-content">
                                @foreach(config('language') as $k=>$g)
                                    <div class="layui-tab-item @if($k==1) layui-show @endif">
                                 
                        <div class="form-group">
                            <label class="col-sm-2 control-label">音频文件({{$g['name']}})</label>
                            <div class="col-sm-4">
                                <input type="text" name="file_path_{{$k}}" value="{{$info['language'][$k]['file_path'] or ""}}"  id="file_path_{{$k}}" class="form-control"
                                                       style="width:400px;float: left"/>
                                <button type="button" onclick="upload_resource('音频文件','FT_ONE_MP3','file_path_{{$k}}',2);" class="btn btn-white">文件上传</button>
							</div>
						</div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">列表图({{$g['name']}})</label>
                            <div class="col-sm-4">
                                <input type="text" name="list_img_{{$k}}" value="{{$info['language'][$k]['list_img'] or ''}}" class="form-control" maxlength="10" required/>
							</div>
						</div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">详细内容({{$g['name']}})</label>
                            <div class="col-sm-4">
                                <input type="text" name="content_{{$k}}" value="{{$info['language'][$k]['content'] or ""}}"  id="content_{{$k}}" class="form-control"
                                                       style="width:400px;float: left"/>
                                <button type="button" onclick="upload_resource('详细内容','FT_ONE_MP3','content_{{$k}}',2);" class="btn btn-white">文件上传</button>
							</div>
						</div>
        						</div>
                                @endforeach
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
    
		    <script src="{{cdn('js/jquery-1.12.4.min.js')}}"></script>
    		<script src="{{cdn('js/plugins/laydate/laydate.js')}}"></script>
				<script type="text/javascript">
        var title = {
            elem: "#title", format: "YYYY-MM-DD", 
            isclear: false,
            istoday: false,
            issure: false,
            choose: function (datas) {                         
            }
        };
 laydate(title);
 </script>
@endsection