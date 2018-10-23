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
    <script src='http://127.0.0.1:81/js/plugins/jquery-ui.min.js'></script>
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
                                    <div id="test">
                                        <ul id="sortable-test" style="list-style-type: none; margin: 0; padding: 0; width: 60%;">
                                                    @if(isset($info['test'])&&is_array($info['test'] ))
                                                    @foreach($info['test'] as $kk=>$gg)
                                                        <div class="img-div">
                                                            <img src="{{get_file_url($gg)}}">
                                                            <span onclick="del_img($(this))">×</span>
                                                            <input type="hidden" name="{{$g['key']}}[]" value="{{$gg}}">
                                                        </div>
                                                    @endforeach
                                                    @endif
                                         </ul>
                                 </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">测试变哈</label>
                            <div class="col-sm-4">
                                 <div class="webuploader-pick" onclick="upload_resource('测试变哈','FT_MORE_RESOURCE','HAH',1,'HAH',1);"
                                             style=" float: left; display: inline-block; width: auto;">点击上传图片(可多张)
                                        </div>
                                    </div>
                            </div>
                        <div class="form-group">
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-4" style="overflow: auto;width: 80%;">
                                    <div id="HAH">
                                        <ul id="sortable-HAH" style="list-style-type: none; margin: 0; padding: 0; width: 60%;">
                                                    @if(isset($info['HAH'])&&is_array($info['HAH'] ))
                                                    @foreach($info['HAH'] as $kk=>$gg)
                                                        <div class="img-div">
                                                            <img src="{{get_file_url($gg)}}">
                                                            <span onclick="del_img($(this))">×</span>
                                                            <input type="hidden" name="{{$g['key']}}[]" value="{{$gg}}">
                                                        </div>
                                                    @endforeach
                                                    @endif
                                         </ul>
                                          
                                                <script>
                                                    $(function () {
                                                        $("#sortable-HAH").sortable();
                                                    });
                                                </script>
                                        </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否轮播</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="ewa">
                                    <option value="1" @if($info && $info['ewa']==1) selected @endif>轮播</option>
                                    <option value="2" @if($info && $info['ewa']==2) selected @endif>不轮播</option>
                                  </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">音频文件</label>
                            <div class="col-sm-4">
                                <input type="text" name="upload_file" value="{{$info['upload_file'] or ""}}"  id="upload_file" class="form-control"
                                                       style="width:400px;float: left"/>
                                                <button type="button" onclick="upload_resource('音频文件','FT_ONE_MP3','upload_file',2);" class="btn btn-white">文件上传</button>
                            </div>
                        </div>