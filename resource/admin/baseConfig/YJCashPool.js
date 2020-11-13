var toolbar1 = [{
        text: '预导入',
        iconCls: 'icon-add',
        handler: function () {
            $('#cp_win_input').window('open');
        }
    }];

var toolbar2 = [{
        text: '差异更新',
        iconCls: 'icon-add',
        handler: function () {
            appendData();
        }
    }];

var __b_delete_preview = true;

$(function () {
    $('#yjcp_w_preview').window({
       onOpen : function (){
           $("#yjcp_dg_preview").datagrid({
                rowStyler: function (index, row) {
                    if (null !== row.cpd_bill_code) {
                        return 'background-color:#CCCCCC;color:#333333;';
                    }
                    if (null === row.bs_org_sn || row.bs_org_sn === '') {
                        return 'background-color:'+COLOR_WARNING+';color:#333333;';
                    }
                }
            });
       },
       onBeforeClose:function(){ 
           if (__b_delete_preview){
               deletePreviewData();
           }
       }
   });
   
    //预导入CSV文件
    $('#btn_do_input').bind('click', function () {
        $("#form_input").form("submit", {
            type: 'post',
            url: '../'+__s_c_name+'/uploadInfo',
            onSubmit: function () {
                $('#cp_win_input').window('close');
                $.messager.progress({
                    title:'Please waiting',
                    msg:'正在导入数据......'
                });
            },
            success: function (data) {
                var o_response = $.parseJSON(data);
                $.messager.progress('close');
                if (o_response.state === true) {
                    $('#yjcp_hid_preview').val(o_response.tbn);
                    $("#yjcp_dg_preview").datagrid("options").url = '../'+__s_c_name+'/loadPreview/';
                    $('#yjcp_dg_preview').datagrid('load', {
                        tbn: $('#yjcp_hid_preview').val()
                    });
                    $('#yjcp_w_preview').window('open');
                }
            }
        });
    });
});

function deletePreviewData(){
    var s_tbn = $('#yjcp_hid_preview').val();
    $.ajax({
        url: '../' + __s_c_name + '/deletePreviewData',
        type: "POST",
        data: {'tbn': s_tbn},
        success: function (data) {
            
        }
    });
}

function appendData() {
    var s_tbn = $('#yjcp_hid_preview').val();
    if (s_tbn === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将追加至正式数据，是否确认追加导入?', function (r) {
        if (r) {
            var s_tbn = $('#yjcp_hid_preview').val();
            //异步请求
            $.ajax({
                url: '../'+__s_c_name+'/appendData/',
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
                    $('#yjcp_hid_preview').val('');
                    $("#yjcp_dg_preview").datagrid("loadData", { total: 0, rows: [] });
                    $("#yjcp_dg").datagrid("reload");
                    $('#yjcp_w_preview').window('close');
                }
            })
        }
    });
}
