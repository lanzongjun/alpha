var toolbar1 = [{
        text: '预导入',
        iconCls: 'icon-add',
        handler: function () {
            $('#mtbi_win_input').window('open');
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

var MTBillInfo = {};
MTBillInfo.amountFormat = function (value, row, index){
    if (null === value || value-0 === 0){
        return "<span style='color:#333333'>"+value+'</span>';
    }
    return value;
};

$(function () {
    $('#mtbi_w_preview').window({
       onOpen : function (){
           $("#mtbi_dg_preview").datagrid({
                rowStyler: function (index, row) {
                    if (row.bbim_is_exist-0 === 1) {
                        return 'background-color:#CCCCCC;color:#333333;';
                    }
                    if (null === row.bbim_org_sn || row.bbim_org_sn === '') {
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
    $('#mtbi_btn_do_input').bind('click', function () {
        $("#mtbi_form_input").form("submit", {
            type: 'post',
            url: '../'+__s_c_name+'/uploadInfo',
            onSubmit: function () {
                $('#mtbi_win_input').window('close');
                $.messager.progress({
                    title:'Please waiting',
                    msg:'正在导入数据......'
                });
            },
            success: function (data) {
                var o_response = $.parseJSON(data);
                $.messager.progress('close');
                if (o_response.state === true) {
                    $('#mtbi_hid_preview').val(o_response.tbn);
                    $("#mtbi_dg_preview").datagrid("options").url = '../'+__s_c_name+'/loadPreview/';
                    $('#mtbi_dg_preview').datagrid('load', {
                        tbn: $('#mtbi_hid_preview').val()
                    });
                    $('#mtbi_w_preview').window('open');
                }
            }
        });
    });
});

function deletePreviewData(){
    var s_tbn = $('#mtbi_hid_preview').val();
    $.ajax({
        url: '../' + __s_c_name + '/deletePreviewData',
        type: "POST",
        data: {'tbn': s_tbn},
        success: function (data) {
            
        }
    });
}

function appendData() {
    var s_tbn = $('#mtbi_hid_preview').val();
    if (s_tbn === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将追加至正式数据，是否确认追加导入?', function (r) {
        if (r) {
            var s_tbn = $('#mtbi_hid_preview').val();
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
                    $('#mtbi_hid_preview').val('');
                    $("#mtbi_dg_preview").datagrid("loadData", { total: 0, rows: [] });
                    $("#mtbi_dg").datagrid("reload");
                    $('#mtbi_w_preview').window('close');
                }
            })
        }
    });
}
