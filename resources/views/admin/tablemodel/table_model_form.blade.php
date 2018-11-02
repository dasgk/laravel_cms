@extends('layouts.public')

@section('head')
    <link rel="stylesheet" href="{{cdn('js/plugins/webuploader/single.css')}}">
@endsection

@section('bodyattr')class="gray-bg"

@endsection

@section('body')
    <div class="wrapper wrapper-content">
        <div class="row m-b">
            <div class="col-sm-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a href="{{route('admin.table.index')}}">模块列表</a></li>
                        <li class="active"><a href="javascript:void(0)">添加模块</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <form action="{{route('admin.table.save')}}" method="post" class="form-horizontal ajaxForm">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$info['id'] or 0}}"/>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">数据库表名称</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="table_name"
                                           value="{{$info['table_name'] or ''}}" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">模块名称</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="model_name"
                                           value="{{$info['model_name'] or ''}}" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">数据库表注释</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="table_comment"
                                           value="{{$info['table_comment'] or ''}}" autocomplete="off"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否支持时间戳</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="timestamps">
                                        <option @if($info && $info['timestamps'] ==1) selected @endif value="1">支持
                                        </option>
                                        <option @if($info && $info['timestamps'] ==0) selected @endif  value="0">不支持
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否生成migration文件</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="generate_migration">
                                        <option @if($info && $info['generate_migration'] ==1) selected
                                                @endif  value="1">生成
                                        </option>
                                        <option @if($info && $info['generate_migration'] ==0) selected @endif value="0">
                                            不生成
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否执行migrate</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="execute_migration">
                                        <option @if($info && $info['execute_migration'] ==1) selected @endif  value="1">
                                            执行
                                        </option>
                                        <option @if($info && $info['execute_migration'] ==0) selected @endif value="0">
                                            不执行
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否生成model文件</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="generate_model">
                                        <option value="1" @if($info && $info['generate_model'] ==1) selected @endif >
                                            生成
                                        </option>
                                        <option value="0" @if($info && $info['generate_model'] ==0) selected @endif >
                                            不生成
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否后台管理</label>
                                <div class="col-sm-3">
                                    <select class="form-control" name="is_backup_control">
                                        <option value="1"
                                                @if($info && $info['is_backup_control'] ==1) selected @endif >管理
                                        </option>
                                        <option value="0"
                                                @if($info && $info['is_backup_control'] ==0) selected @endif >不管理
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">表结构</label>
                                <div class="col-sm-3" style="width:80%">
                                    <table class="table">
                                        <thead>
                                        <td style="width:10%">字段名称</td>
                                        <td style="width:10%">数据类型</td>
                                        <td style="width:10%">是否可以为空</td>
                                        <td style="width:10%">注释</td>
                                        <td style="width:10%">默认值</td>
                                        <td style="width:10%">是否是多语种字段</td>
                                        <td style="width:10%">前端类型</td>
                                        <td style="width:10%">前端文案</td>
                                        <td style="width:10%">前端值（目前只对select有效1#打开2#关闭）</td>
                                        <td style="width:10%"> 操作</td>
                                        </thead>
                                        <tbody id="filed_list">
                                        @if($table_struct)
                                            <tr>
                                            @foreach($table_struct as $item)
                                                <!--字段名称-->
                                                    <td>
                                                        <input class='form-control' value="{{$item['field_name']}}"
                                                               style='width: 100%' name='field_name[]' type='text'/>
                                                    </td>
                                                    <!--数据类型-->
                                                    <td>
                                                        <select class='form-control' style='width: 100%' name='field_type[]' >
                                                            <option @if($item['field_type'] == 'integer') selected @endif value='integer'>整型</option>
                                                            <option @if($item['field_type'] == 'string') selected @endif  value='string'>字符串类型</option>
                                                            <option @if($item['field_type'] == 'tinyInteger') selected @endif  value='tinyInteger'>tinyInteger</option>
                                                            <option  @if($item['field_type'] == 'datetime') selected @endif  value='datetime'>时间类型</option>
                                                            <option   @if($item['field_type'] == 'text') selected @endif value='text'>text</option>
                                                            <option @if($item['field_type'] == 'longtext') selected @endif value='longtext'>长文本类型</option>
                                                        </select>
                                                    </td>
                                                    <!--是否可以为空-->
                                                    <td>
                                                        <select class='form-control' style='width: 100%' name='can_null[]' >
                                                            <option @if($item['can_null'] == 1) selected @endif value=1>可以为空</option>
                                                            <option  @if($item['can_null'] == 0) selected @endif value=0>不可以为空</option>
                                                        </select>
                                                    </td>
                                                    <!--注释名称-->
                                                    <td>
                                                        <input class='form-control' value="{{$item['comment']}}"
                                                               style='width: 100%' name='comment[]' type='text'/>
                                                    </td>
                                                    <!--默认值-->
                                                    <td>
                                                        <input class='form-control' value="{{$item['default_value']}}"
                                                               style='width: 100%' name='default_value[]' type='text'/>
                                                    </td>
                                                    <!--是否是多语种-->
                                                    <td>
                                                        <select class='form-control' style='width: 100%' name='is_mutiple_lan[]' >
                                                            <option @if($item['is_mutiple_lan'] == 1) selected @endif value=1>是</option>
                                                            <option  @if($item['is_mutiple_lan'] == 0) selected @endif value=0>不是</option>
                                                        </select>
                                                    </td>
                                                <!--前端类型g-->
                                                <td>
                                                    <select class='form-control' style='width: 100%' name='front_type[]' >
                                                        <option @if($item['front_type'] == '') selected @endif value=''>无</option>
                                                        <option @if($item['front_type'] == 'select') selected @endif value='select'>选择框</option>
                                                        <option @if($item['front_type'] == 'datetime') selected @endif value='datetime'>时间选择</option>
                                                        <option @if($item['front_type'] == 'text') selected @endif value='text'>文本域</option>
                                                        <option @if($item['front_type'] == 'textarea') selected @endif value='textarea'>多行文本域</option>
                                                        <option @if($item['front_type'] == 'number') selected @endif value='number'>数字类型</option>
                                                        <option @if($item['front_type'] == 'rich_text') selected @endif  value='rich_text'>富文本</option>
                                                        <option @if($item['front_type'] == 'single_file') selected @endif  value='single_file'>单文件</option>
                                                        <option @if($item['front_type'] == 'single_image') selected @endif  value='single_image'>单图</option>
                                                        <option  @if($item['front_type'] == 'mutiple_image') selected @endif  value='mutiple_image'>多图</option>
                                                    </select>
                                                </td>
                                                    <!--前端文案-->
                                                    <td>
                                                        <input class='form-control' style='width: 100%' value="{{$item['front_text'] or ''}}"
                                                               style='width: 100%' name='front_text[]' type='text'/>
                                                    </td>
                                                    <!--前端值-->
                                                    <td>
                                                        <input class='form-control' style='width: 100%' value="{{$item['front_value'] or ''}}"
                                                               style='width: 100%' name='front_value[]' type='text'/>
                                                    </td>
                                                    <td>
                                                        <button type="button" class ="btn btn-white" onclick="del(this)"> 删除当前行 </button>
                                                    </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="button" onclick="add_new_field()">添加新字段
                                    </button>
                                    <button class="btn btn-primary" type="submit">保存</button>
                                    <button class="btn btn-white" type="button" id="backBtn">返回</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{cdn('js/plugins/webuploader/webuploader.nolog.min.js')}}"></script>
    <script src="{{cdn('js/plugins/webuploader/webuploader_public.js')}}"></script>
    <script type="text/javascript">
        jQuery(function ($) {
            singleUpload({
                _token: '{{csrf_token()}}',
                type_key: 'FT_AVATAR',
                item_id: '{{$user->uid or 0}}',
                pick: 'avatar_picker',
                boxid: 'avatar_box',
                file_path: 'avatar',
                file_id: 'avatar_file_id'
            });
            $('#avatar_box').find('.img-div>span').click(function () {
                sUploadDel($(this), 'avatar');
            });
        });

        function del(nowtd) {
            $(nowtd).parents('tr').remove();
        }

        function add_new_field() {
            //
            sample = "<tr>"
            //添加字段名称
            sample += "<td>"
            sample += "<input class='form-control' style='width: 100%' name='field_name[]' type='text'/>"
            sample += "</td>"
            //说明字段类型
            sample += "<td>"
            sample += "<select class='form-control' style='width: 100%' name='field_type[]' >"
            sample += "<option value='integer'>整型</option>"
            sample += "<option value='string'>字符串类型</option>"
            sample += "<option value='tinyInteger'>tinyInteger</option>"
            sample += "<option value='datetime'>时间类型</option>"
            sample += "<option value='text'>text</option>"
            sample += "<option value='longtext'>长文本类型</option>"
            sample += "</select>"
            sample += "</td>"
            //是否可以为空
            sample += "<td>"
            sample += "<select class='form-control' style='width: 100%' name='can_null[]' >"
            sample += "<option value=1>可以为空</option>"
            sample += "<option value=0>不可以为空</option>"
            sample += "</select>"
            sample += "</td>"
            //注释
            sample += "<td>"
            sample += "<input class='form-control' style='width: 100%' name='comment[]' type='text'/>"
            sample += "</td>"
            //默认值
            sample += "<td>"
            sample += "<input class='form-control' style='width: 100%' name='default_value[]' type='text'/>"
            sample += "</td>"
            //是否多语种
            sample += "<td>"
            sample += "<select class='form-control' style='width: 100%' name='is_mutiple_lan[]' >"
            sample += "<option value=0>不是</option>"
            sample += "<option value=1>是</option>"
            sample += "</select>"
            sample += "</td>"
            //前端类型
            sample += "<td>"
            sample += "<select class='form-control' style='width: 100%' name='front_type[]' >"
            sample += "<option value=''>无</option>"
            sample += "<option value='select'>选择框</option>"
            sample += "<option value='text'>文本域</option>"
            sample += "<option value='textarea'>多行文本域</option>"
            sample += "<option value='number'>数字类型</option>"
            sample += "<option value='datetime'>时间控件</option>"
            sample += "<option value='rich_text'>富文本</option>"
            sample += "<option value='single_image'>单图</option>"
            sample += "<option value='mutiple_image'>多图</option>"
            sample += "<option value='single_file'>单文件</option>"
            sample += "<option value='datetime'>时间类型</option>"
            sample += "</select>"
            sample += "</td>"
            //前端文案
            sample += "<td>"
            sample += "<input class='form-control' style='width: 100%' name='front_text[]' type='text'/>"
            sample +="</td>"
            //前端值
            sample += "<td>"
            sample += "<input class='form-control' style='width: 100%' name='front_value[]' type='text'/>"
            sample +="</td>"
            sample += " <td>";
            sample += '<button type="button" class ="btn btn-white" onclick="del(this)"> 删除当前行 </button>'
            sample += "</td>"
            sample += "</tr>"
            $("#filed_list").append(sample)
        }
    </script>
@endsection