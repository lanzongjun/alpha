
var toolbar2 = [{
        text: '覆盖导入',
        iconCls: 'icon-add',
        handler: function () {
            coverData();
        }
    }];

$(function () {
    $('#btn_todo_input').bind('click', function () {
        $('#tyje_win_input').window('open');
    });
    
    $('#btn_search').bind('click', function () {
        doSearch();
    });
    
    $('#btn_onsale').bind('click', function () {
        appendOnSale();
    });
    
    //预导入CSV文件
    $('#btn_do_input').bind('click', function () {
        $("#form_input").form("submit", {
            type: 'post',
            url: '../' + __s_c_name + '/uploadInfo',
            onSubmit: function () {
                $('#tyje_win_input').window('close');
                ajaxLoading();
            },
            success: function (data) {
                var o_response = $.parseJSON(data);
                if (o_response.state === true) {
                    $('#hid_tbn').val(o_response.tbn);
                    $("#dg2").datagrid("options").url = '../' + __s_c_name + '/loadPreview/';
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
});

function appendOnSale() {
    var row = $('#dg').datagrid('getSelected');
    if (!row.bgs_barcode) {$.messager.alert('info', '条码缺失，不可加入促销！');return;}
    var s_id = row.gey_id;
    if (s_id !== '') {
        $.ajax({
        url: '../AdYJOnSaleC/appendOnSaleByExpire/',
        type: "GET",
        data: {"id": s_id},
        success: function (data) {
            var o_response = $.parseJSON(data);
            $.messager.alert('info', o_response.msg);
        }
    });
    }else{
        $.messager.alert('info', '未选中任何数据，不可进行此操作!');
    }    
}

function doSearch() {
    var s_expire = $("#s_expire").val();
    var s_shop = $("#s_shop").val();
    var s_count = $("#s_count").val();    
    var s_goods = $("#s_goods").val();
    $.ajax({
        url: '../' + __s_c_name + '/getList',
        type: "GET",
        data: {"e": s_expire, "s":s_shop, "c":s_count, "g":s_goods},
        success: function (data) {
            var o_response = $.parseJSON(data);
            $("#dg").datagrid("loadData",o_response);
        }
    })
}

function coverData() {
    var s_tbn = $('#hid_tbn').val();
    if (s_tbn === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将完全覆盖正式数据，是否确认覆盖导入?', function (r) {
        if (r) {
            var s_tbn = $('#hid_tbn').val();
            //异步请求
            $.ajax({
                url: '../' + __s_c_name + '/coverData/',
                type: "POST",
                data: {"tbn": s_tbn},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    if (o_response.state === true) {
                        $.messager.alert('成功', '数据导入成功!', 'info');
                    } else {
                        $.messager.alert('失败', '数据导入失败!请重试', 'error');
                    }
                    //清空预览和tbn值
                    $('#hid_tbn').val('');
                    $("#dg2").datagrid("loadData", {total: 0, rows: []});
                    //返回主界面
                    $("#tab_box").tabs("select", 0);
                    $("#dg").datagrid("reload");
                }
            });
        }
    });
}
