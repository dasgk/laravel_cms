@extends('layouts.public')

@section('bodyattr')class="gray-bg"@endsection

@section('body')
    <div class="wrapper wrapper-content">
        <div class="row m-b">
            <div class="col-sm-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="{{route('admin.table.index')}}">模块列表</a></li>
                        <li><a href="{{route('admin.table.edit')."?id=add"}}">添加新模块</a></li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <table class="table table-striped table-bordered table-hover dataTables-example dataTable">
                            <thead>
                            <tr role="row">
                                <th>表名称</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            @foreach($list as $user)
                                <tr class="gradeA">
                                    <td>{{$user['table_name']}}</td>
                                    <td>
                                        <a href="{{route('admin.table.edit')."?id=".$user->id}}">编辑</a>
                                        | <a class="ajaxBtn" href="javascript:void(0);" uri="{{route('admin.table.delete').'?id='.$user->id}}" msg="是否删除该模块？">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="row">
                            <div class="col-sm-12">
                                <div>共 {{ $list->total() }} 条记录</div>
                                {!! $list->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            laydate.render({
                elem: '#created_at',
                range: '~',
                max: 0
            });
        });
    </script>
@endsection
