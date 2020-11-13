function smoOpFormat(value, row, index) {
    var s_html1 = "<a href='../." + row.sui_filepath + "' class='mylink'>下载</a>";
    var s_html2 = "<a class='mylink' href=\"javascript:doRebuild('" + value + "')\">重建</a>";
    var s_html3 = "<a class='mylink' href=\"javascript:doDelete('" + value + "')\">删除</a>";
    return s_html1 + '|' + s_html2+ '|' + s_html3;
}

function smoOWFormat(value, row, index) {
    if (row.sui_sow_id==='0') {
        return "<button onclick=\"doInputCSV('" + row.sui_id + "','" + row.sui_filename + "')\">入库</button>";        
    } else {        
        return value;
    }
}

var toolbar2 = [{
        text: '追加导入',
        iconCls: 'icon-add',
        handler: function () {
            appendData();
        }
    },'-',{
        text: '解密',
        iconCls: 'icon-edit',
        handler: function () {
            doDecodeEle();
        }
    },'-'];

$(function () {
    init();
});

function init() {
    $('#btn_search').bind('click', function () {
        var s_shop = $('#s_shop').val();
        var s_gname = $('#s_goods').val();
        $('#dg').datagrid('load', {
            ss: s_shop,
            gn: s_gname
        });
    });
    $('#btn_showall').bind('click', function () {
        $('#dg').datagrid('load', {
            ss: '',
            gn: '',
            suid: ''
        });
    });
    $("#dg_smo_file").datagrid({
        onClickRow: function (index, row) { //easyui封装好的时间（被单机行的索引，被单击行的值）
            $("#dg").datagrid('load', {
                suid: row.sui_id
            });
        }
    });
    $("#dg_smo_file").datagrid('getPager').pagination({
        displayMsg: "{from}-{to} of {total}"
    });
}

function doRebuild(i_id) {
    if (i_id === '') {
        $.messager.alert('错误', '未找到指定数据!', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将重建此CSV实体文件，是否确认重建?', function (r) {
        if (r) {
            ajaxLoading();
            //异步请求
            $.ajax({
                url: '../' + __s_c_name + '/doRebuildCSV/',
                type: "POST",
                data: {"id": i_id},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    if (o_response.state === true) {
                        $.messager.alert('成功', '重建此CSV实体文件成功!', 'info');
                    } else {
                        $.messager.alert('失败', '重建此CSV实体文件失败!请重试', 'error');
                    }
                    $("#dg").datagrid("reload");
                    $("#dg_smo_file").datagrid("reload");
                    ajaxLoadEnd();
                }
            });
        }
    });
}

function doDelete(i_id) {
    if (i_id === '') {
        $.messager.alert('错误', '未找到指定数据!', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将删除此实体文件，并级联删除相关元数据，是否确认删除?', function (r) {
        if (r) {
            $.messager.confirm('确认', '此操作为不可逆操作，仍确认删除?', function (r) {
                if (r) {
                    ajaxLoading();
                    //异步请求
                    $.ajax({
                        url: '../' + __s_c_name + '/doDeleteCSV/',
                        type: "POST",
                        data: {"id": i_id},
                        success: function (data) {
                            var o_response = $.parseJSON(data);
                            if (o_response.state === true) {
                                $.messager.alert('成功', '数据删除成功!', 'info');
                            } else {
                                $.messager.alert('失败', '数据删除失败!请重试', 'error');
                            }
                            $("#dg").datagrid("reload");
                            $("#dg_smo_file").datagrid("reload");
                            ajaxLoadEnd();
                        }
                    });
                }
            });
        }
    });

}

function doInputCSV(i_id, s_filename) {
    $('#hid_sui_id').val(i_id);
    var ow_id = $('#q_ow_list').val();
    if (!ow_id) {
        $.messager.alert('错误', '请先选择将要导入的数据仓', 'error');
        return;
    }
    $('#hid_sow_id').val(ow_id);
    var ow_name = $('#q_ow_list').combo('getText');
    $.messager.confirm('确认', '当前CSV将导入['+ow_name+']数据仓，确认导入?', function (r) {
        if (r) {
            ajaxLoading();
            //异步请求
            $.ajax({
                url: '../' + __s_c_name + '/doInputCSV/',
                type: "POST",
                data: {"s": s_filename, 'sid': i_id, 'owid':ow_id},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    $.messager.alert('成功', '解析成功：' + o_response.success
                            + '<br/>解析失败：' + o_response.fail
                            + '<br/>重复数据：' + o_response.repeat, 'info');

                    //清空预览和tbn值
                    $('#hid_tbn').val(o_response.tbn);
                    $("#dg2").datagrid("options").url = '../' + __s_c_name + '/loadPreview/';
                    $('#dg2').datagrid('load', {
                        tbn: $('#hid_tbn').val()
                    });
                    ajaxLoadEnd();
                    $("#tab_box").tabs("select", 1);
                }
            });
        }
    });

}

function appendData() {
    var s_tbn = $('#hid_tbn').val();
    var i_id = $('#hid_sui_id').val();
    var i_sow_id = $('#hid_sow_id').val();

    if (s_tbn === '' || i_id === ''|| i_sow_id === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将追加至正式数据，是否确认导入?', function (r) {
        if (r) {
            var s_tbn = $('#hid_tbn').val();
            var i_id = $('#hid_sui_id').val();
            var i_sow_id = $('#hid_sow_id').val();
            //异步请求
            $.ajax({
                url: '../' + __s_c_name + '/appendData/',
                type: "POST",
                data: {"tbn": s_tbn, "id": i_id, 'owid':i_sow_id},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    if (o_response.state === true) {
                        $.messager.alert('成功', '数据导入成功!', 'info');
                    } else {
                        $.messager.alert('失败', '数据导入失败!请重试', 'error');
                    }
                    //清空预览和tbn值
                    $('#hid_tbn').val('');
                    $('#hid_sui_id').val('');
                    $("#dg2").datagrid("loadData", {total: 0, rows: []});
                    //返回主界面
                    $("#tab_box").tabs("select", 0);
                    $("#dg").datagrid("reload");
                    $("#dg_smo_file").datagrid("reload");
                }
            });
        }
    });
}

function doDecodeEle() {
    var s_tbn = $('#hid_tbn').val();
    var i_id = $('#hid_sui_id').val();

    if (s_tbn === '' || i_id === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '将对预导入数据的单价和销量列进行解密（餐饮），是否确认解密?', function (r) {
        if (r) {
            var s_tbn = $('#hid_tbn').val();
            var i_id = $('#hid_sui_id').val();
            //异步请求
            $.ajax({
                url: '../' + __s_c_name + '/decodeData/',
                type: "POST",
                data: {"tbn": s_tbn, "id": i_id},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    if (o_response.state === true) {
                        $.messager.alert('成功', '数据解密完成!', 'info');
                    } else {
                        $.messager.alert('失败', '数据解密失败!', 'error');
                    }
                    $("#dg2").datagrid("reload");
                }
            });
        }
    });
}

