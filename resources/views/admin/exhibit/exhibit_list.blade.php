@extends('layouts.public')

@section('head')
    <link rel="stylesheet" href="{{cdn('css/add/exhibit.css')}}">
@endsection

@section('body')

    <div class="wrapper wrapper-content">

        <div class="row m-b">
            <div class="col-sm-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="{{route('admin.exhibit.index')}}">展品列表</a></li>
                        <li><a href="{{route('admin.exhibit.edit',array('id'=>'add'))}}">添加展品</a></li>
                    </ul>                    
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <table class="table table-striped table-new table-hover infoTables-example infoTable">
                            <thead>
                            <tr role="row">
								<th>测试文案</th>
								<th>音频文件</th>
								<th>列表图</th>
								<th>详细内容</th>
								<th>操作</th>

                            </tr>
                            </thead>
                            @foreach($list as $k=>$v)
                                <tr class="gradeA" >
								<td>{{$v['title']}}</td>

								<td>{{$v['file_path']}}</td>

								<td>{{$v['list_img']}}</td>

								<td>{{$v['content']}}</td>

								<td><a href="{{route('admin.exhibit.edit',array('id'=>$v['exhibit_id']))}}">编辑</a>|<a class="ajaxBtn btn-delete" href="javascript:void(0);" uri="{{route('admin.exhibit.delete' ,array('id'=>$v['exhibit_id']))}}" msg="是否删除该展品？">删除</a></td>

                                </tr>
                            @endforeach
                        </table>
                        <div class="row recordpage">
                            <div class="col-sm-12">
                                {!! $list->links() !!}
                                <span>共 {{ $list->total() }} 条记录</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection