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
                     <li><a href="{{route('admin.data.exhibit')}}">展品列表</a></li>
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
                                <select class="form-control" name="test>
                                    <option value="1" @if($info && $info['test']==1) selected @endif>哈哈</option>
                                    <option value="2" @if($info && $info['test']==2) selected @endif>da</option>
                                  </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">测试变哈</label>
                            <div class="col-sm-4">
                                <input type="number" name="HAH" value="{{$info['exhibit_num'] or 12}}" class="form-control" required/>
                            </div>
                        </div>