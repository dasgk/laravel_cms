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
                     <li><a href="{{route('admin.test.index')}}">111列表</a></li>
                     <li class='active'><a href="javascript:void(0)">111编辑</a></li>
                      </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <form action="{{route('admin.test.save')}}" method="post" class="form-horizontal ajaxForm">
						{{csrf_field()}}
						<input type="hidden" value="{{$info['test_id'] or  'add'}}" name='test_id'/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">展品编号</label>
                            <div class="col-sm-4">
                                 <div class="webuploader-pick" onclick="upload_resource('展品编号','FT_ONE_RESOURCE','test',1,'test',1);"
                                             style=" float: left; display: inline-block; width: auto;">点击上传图片
                                        </div>
                                    </div>
                            </div>                           
                            
                            
                        <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-4" style="overflow: auto;width: 80%;">
                                    <div id="test">@if($info && $info['test'])
								 <div class="img-div">
                                                    <img src="{{get_file_url($info['test'])}}">
                                                    <span onclick="del_img($(this))">×</span>
                                                    <input type="hidden" name="test" value="{{$info['test']}}">
                                                </div>
                                                              
                               
                                 @endif
                                 </div>
                                 
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
                            <label class="col-sm-2 control-label">是否轮播({{$g['name']}})</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="ewa_{{$k}}">
                                    <option value="1" @if($info && $info['language'][$k]['ewa']==1) selected @endif>轮播</option>
                                    <option value="2" @if($info && $info['language'][$k]['ewa']==2) selected @endif>不轮播</option>
                                  </select>
							</div>
						</div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">音频文件({{$g['name']}})</label>
                            <div class="col-sm-4">
                                <input type="text" name="upload_file_{{$k}}" value="{{$info['language'][$k]['upload_file'] or ""}}"  id="upload_file_{{$k}}" class="form-control"
                                                       style="width:400px;float: left"/>
                                <button type="button" onclick="upload_resource('音频文件','FT_ONE_MP3','upload_file_{{$k}}',2);" class="btn btn-white">文件上传</button>
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
    
@endsection