
var toolbar2 = [{
        text: '差异化更新',
        iconCls: 'icon-add',
        handler: function () {
            doDiffUpdate();
        }
    }];

$(function () {
    $('#btn_todo_input').bind('click', function () {
        $('#yjsp_win_input').window('open');
    });
    $('#btn_search').bind('click', function () {
        var s_gname = $('#s_goods').val();
        var s_barcode = $('#s_barcode').val();
        $('#dg').datagrid('load', {
            gn:s_gname,
            bc:s_barcode
        });
    });    
    //预导入CSV文件
    $('#btn_do_input').bind('click', function () {
        $("#form_input").form("submit", {
            type: 'post',
            url: '../'+__s_c_name+'/uploadInfo',
            onSubmit: function () {
                $('#yjsp_win_input').window('close');
                ajaxLoading();
            },
            success: function (data) {
                var o_response = $.parseJSON(data);
                if (o_response.state === true) {
                    $('#hid_tbn').val(o_response.tbn);
                    $("#dg2").datagrid("options").url = '../'+__s_c_name+'/loadPreview/';
                    $('#dg2').datagrid('load', {
                        tbn: $('#hid_tbn').val()
                    });
                    ajaxLoadEnd();
                    $.messager.alert('信息', '预导入完成!', 'info');
                    $("#tab_box").tabs("select", 1);
                }
            }
        });
    });
    $('#dg').datagrid({
        rowStyler: function (index, row) {
            if (row.bbp_goods_name === __temp_goods_name) {
                return 'background-color:#DDDDDD;color:#AAAAAA;';
            }
            if (row.bbp_bar_code === __temp_barcode) {
                return 'background-color:#22DDDD;color:#AAAAAA;';
            }
            __temp_goods_name = row.bbp_goods_name;
        }
    });
})

var __temp_goods_name = '';
var __temp_barcode = '';

function doDiffUpdate() {
    var s_tbn = $('#hid_tbn').val();
    if (s_tbn === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将差异化更新至正式数据，是否确认更新?', function (r) {
        if (r) {
            var s_tbn = $('#hid_tbn').val();
            ajaxLoading();
            //异步请求
            $.ajax({
                url: '../'+__s_c_name+'/doDiffUpdate/',
                type: "POST",
                data: {"tbn": s_tbn},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    if (o_response.state === true) {
                        $.messager.alert('信息', o_response.msg, 'info');                
                    } else {                
                        $.messager.alert('错误', o_response.msg, 'error');
                    }                    
                    ajaxLoadEnd();
                    //清空预览和tbn值
                    $('#hid_tbn').val('');
                    $("#dg2").datagrid("loadData", { total: 0, rows: [] });
                    //返回主界面
                    $("#tab_box").tabs("select", 0);
                    $("#dg").datagrid("reload");
                }
            })
        }
    });
}
