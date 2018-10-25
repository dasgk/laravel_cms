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
						<input type="hidden" value={{$info['exhibit_id'] or  ""}} name='exhibit_id'/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">测试文案</label>
                            <div class="col-sm-4">
										<input placeholder="时间" class="form-control layer-date laydate-icon" id="title" type="text" name="title"	 value="{{$info['title'] or ''}}"     style="width: 140px;" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">音频文件</label>
                            <div class="col-sm-4">
                                <input type="text" name="file_path" value="{{$info['file_path'] or ""}}"  id="file_path" class="form-control"
                                                       style="width:400px;float: left"/>
                                                <button type="button" onclick="upload_resource('音频文件','FT_ONE_MP3','file_path',2);" class="btn btn-white">文件上传</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">列表图</label>
                            <div class="col-sm-4">
                                <input type="text" name="list_img" value="{{$info['list_img'] or ''}}" class="form-control" maxlength="10" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">详细内容</label>
                            <div class="col-sm-4">
                                 <div class="webuploader-pick" onclick="upload_resource('详细内容','FT_MORE_RESOURCE','content',1,'content',1);"
                                             style=" float: left; display: inline-block; width: auto;">点击上传图片(可多张)
                                        </div>
                                    </div>
                            </div>
                        <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-4" style="overflow: auto;width: 80%;">
                                    <div id="content">
                                        <ul id="sortable-content" style="list-style-type: none; margin: 0; padding: 0; width: 60%;">
                                                    @if(isset($info['content'])&&is_array($info['content'] ))
                                                    @foreach($info['content'] as $kk=>$gg)
                                                        <div class="img-div">
                                                            <img src="{{get_file_url($gg)}}">
                                                            <span onclick="del_img($(this))">×</span>
                                                            <input type="hidden" name="content[]" value="{{$gg}}">
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