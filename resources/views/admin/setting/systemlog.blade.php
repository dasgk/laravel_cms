@extends('layouts.public')

@section('bodyattr')class="gray-bg"@endsection

@section('head')
    <style type="text/css">
        .treeview span.indent {
            margin-left: 10px;
            margin-right: 10px;
        }

        .treeview span.icon {
            width: 12px;
            margin-right: 5px;
        }
    </style>
@endsection

@section('body')
    <div class="wrapper wrapper-content">
        <div class="row m-b">
            <div class="col-sm-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="{{route('admin.setting.systemlog')}}">系统日志</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="col-sm-12 treeview">
                            <ul class="list-group">
                                <li class="list-group-item node-tree">
                                    {{storage_path('logs')}}
                                </li>
                                @foreach($dirlist as $dir)
                                    <li class="list-group-item node-tree">
                                        @if($dir['type'] == 'dir')
                                            <span class="icon glyphicon glyphicon-folder-close"></span>
                                            {{$dir['name']}}
                                            <a href="javascript:void(0);" id="logview">展开</a>
                                        @else
                                            <span class="icon glyphicon glyphicon-file"></span>
                                            {{$dir['name']}}
                                            <a class="chakan" href="javascript:;" _href="{{route('admin.setting.systemlog.view')}}?path={{urlencode($dir['path'])}}">查看</a>
                                        @endif
                                        <input type="hidden" name="path" value="{{$dir['path']}}">
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="row"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(function ($) {
            $(document).on('click', 'a[id^=logview]', function () {
                if ($(this).text() == '展开') {
                    $(this).text('收起');

                    var p = $(this).parent();
                    p.find('.icon').prop('class', 'icon glyphicon glyphicon-folder-open');
                    var path = p.find('[name=path]').val();
                    var layer = p.find('.indent').length;
                    var layerhtml = '';
                    for (var i = 0; i < layer + 1; i++) {
                        layerhtml += '<span class="indent"></span>';
                    }
                    $.getJSON('{{route('admin.setting.systemlog.getdir')}}', {path: path}, function (data) {
                        var e = p;
                        var ehtml, ehref;
                        data.reverse();
                        for (var i in data) {
                            if (data[i].type == 'dir') {
                                ehtml = '<span class="icon glyphicon glyphicon-folder-close"></span> ';
                                ehref = '<a href="javascript:void(0);" id="logview">展开</a>';
                            } else {
                                ehtml = '<span class="icon glyphicon glyphicon-file"></span> ';
                                ehref = '<a class="chakan" href="javascript:;" _href="{{route('admin.setting.systemlog.view')}}?path=' + encodeURI(data[i].path) + '">查看</a>';
                            }
                            e.after(['<li class="list-group-item node-tree">' + layerhtml + ehtml + data[i].name +
                            '<input type="hidden" name="path" value="' + data[i].path + '"> ' + ehref + '</li>'].join(""));
                        }
                    })
                } else {
                    $(this).text('展开');

                    var p = $(this).parent();
                    p.find('.icon').prop('class', 'icon glyphicon glyphicon-folder-close');
                    var layer = p.find('.indent').length;

                    while (true) {
                        if (p.next().find('.indent').length > layer) {
                            p.next().remove();
                        } else {
                            break;
                        }
                    }
                }
            });

            $(document).on('click', 'a[class^=chakan]', function () {
                var that = $(this);
                var url=that.attr('_href');
                var index = layer.open({
                    type: 2,
                    content: url,
                    maxmin: false,
                    move: false,
                    resize: false,
                    zIndex: 1,
                });
                layer.full(index);
            });
        });
    </script>
@endsection