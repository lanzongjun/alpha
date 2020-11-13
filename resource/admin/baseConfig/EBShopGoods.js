function newStorageFormat(value, row, index) {
    var storage_old = row.sge_count - 0;
    var storage_new = value - 0;
    if (storage_new === storage_old) {
        return storage_new;
    } else if (storage_new > storage_old) {
        return "<span style='color:#EE5C42;font-weight:bolder'>" + storage_new + "↑</span>";
    } else {
        return "<span style='color:#9ACD32;font-weight:bolder'>" + storage_new + "↓</span>";
    }
}

function newPriceFormat(value, row, index) {
    var price_old = row.sge_price - 0;
    var price_new = value - 0;
    if (price_new <= 0) {
        return "<span style='color:#333333;font-weight:bolder'>" + price_new + "</span>";
    }
    if (price_new === price_old) {
        return price_new;
    } else if (price_new > price_old) {
        return "<span style='color:#EE5C42;font-weight:bolder'>" + price_new + "↑</span>";
    } else {
        return "<span style='color:#9ACD32;font-weight:bolder'>" + price_new + "↓</span>";
    }
}

function upFormat(value, row, index) {
    if (value === '1') {
        return "上架";
    } else {
        return "下架";
    }
}

function opfURLFormat(value, row, index) {
    var s_html = "<a href='../.." + value + "' style='font-weight:bolder;color:#0000CD;text-decoration:underline'>下载</a>";
    return s_html;
}

function shopNameFormat(value, row, index) {
    return value;
}

function updateErrNoFormat(value, row, index) {
    return value==='0' ? '成功':'<span style="font-weight:bold;color:#B22222">失败</span>';
}

var toolbar2 = [{
        text: '覆盖导入',
        iconCls: 'icon-add',
        handler: function () {
            coverData();
        }
    }];

function deletePreviewData(){
    var s_tbn = $('#hid_tbn').val();
    $.ajax({
        url: '../' + __s_c_name + '/deletePreviewData',
        type: "POST",
        data: {'tbn': s_tbn},
        success: function (data) {
            
        }
    });
}

var __b_delete_preview = true;

$(function () {
    init();
    SyncStockInit();
    SyncSKUInit();
    SyncPriceInit();
    
    $('#ebsg_w_update_preview').window({
       onBeforeClose:function(){ 
           if (__b_delete_preview){
               deletePreviewData();
           }
       }
   });
    //预导入CSV文件
    $('#btn_do_input').bind('click', function () {        
        __b_delete_preview = true;
        $("#form_input").form("submit", {
            type: 'post',
            url: '../' + __s_c_name + '/uploadInfo',
            onSubmit: function () {
                $('#ebsg_win_input').window('close');
                ajaxLoading();
            },
            success: function (data) {
                var o_response = $.parseJSON(data);
                ajaxLoadEnd();
                if (o_response.state === true) {
                    $('#hid_tbn').val(o_response.tbn);
                    $("#dg2").datagrid("options").url = '../' + __s_c_name + '/loadPreview/';
                    $('#dg2').datagrid('load', {
                        tbn: $('#hid_tbn').val()
                    });
                    $('#ebsg_w_update_preview').window('open');
                }
            }
        });
    });
});

function init() {
    $("#ebsg_dg").datagrid({
        rowStyler: function (index, row) {
            if (row.sge_is_freeze === '1') {
                return 'background-color:#CCCCCC;color:#333333;';
            }
        }
    });
    $("#ebsg_dg_new_goods").datagrid({
        rowStyler: function (index, row) {
            if (row.csge_sale_price-0 <= 0 || row.csge_settlement_price-0 <= 0) {
                return 'background-color:#F47983;color:#FFFFFF;';
            }
        }
    });
    $('#s_shop').combobox({
        url: '../AdShopInfoYJC/getShopEbIdList',
        onLoadSuccess: function () {
            var s_oid = $('#s_shop').combobox('getValue');
            $('#ebsg_dg').datagrid("options").url = '../' + __s_c_name + '/getList/';
            $('#ebsg_dg').datagrid('load', {
                oid: s_oid
            });
        }
    });
    
    $('#ebsg_win_input').window({
        onOpen: function () {
            $('#dom_shop_id').combobox({
                url: '../AdShopInfoYJC/getShopEbIdList',
                onLoadSuccess: function () {
                    var s_oid = $('#s_shop').combobox('getValue');
                    $('#dom_shop_id').combobox('setValue', s_oid);
                },
                onChange: function (newValue, oldValue) {
                    $('#s_shop').combobox('setValue', newValue);
                }
            });
        }
    });
    
    $('#ebsg_new_goods').window({
        onOpen: function () {
            var s_oid = $('#s_shop').combobox('getValue');
            var s_name = $('#s_shop').combobox('getText');
            s_name = s_name === null || s_name === '' ? '所有' : s_name;
            $('#ebsg_new_goods').window('setTitle','未上线商品-'+s_name);
            $("#ebsg_dg_new_goods").datagrid("options").url = '../' + __s_c_name + '/getNewGoods/';
            $("#ebsg_dg_new_goods").datagrid("load",{
                'sid': s_oid
            });
            $("#tb_ng_dl").attr('href','../' + __s_c_name + '/outputNewGoods/?sid='+s_oid);
        }
    });
    
    $('#btn_show_new_goods').bind('click', function () {
        $('#ebsg_new_goods').window('open');
    });
    
    $('#btn_todo_input').bind('click', function () {
        $('#ebsg_win_input').window('open');
    });

    $('#btn_search').bind('click', function () {
        var s_oid = $('#s_shop').combobox('getValue');
        var s_gname = $('#s_goods').val();
        var s_barcode = $('#s_barcode').val();
        var s_filter_storage = $('#s_filter_storage').combobox('getValue');
        var s_filter_price = $('#s_filter_price').combobox('getValue');
        var s_filter_up = $('#s_filter_up').combobox('getValue');
        $('#ebsg_dg').datagrid('load', {
            oid: s_oid,
            gn: s_gname,
            bc: s_barcode,
            fs: s_filter_storage,
            fp: s_filter_price,
            fu: s_filter_up
        });
    });
        
    $('#btn_out_pf_csv_win').bind('click', function () {
        $('#ebsg_w_update_file_download').window('open');
    });
    
    $('#btn_out_pf_csv').bind('click', function () {
        doOutPfCSV();
    });

    $('#btn_refresh_storage').bind('click', function () {
        doRefreshStorage();
    });
    
    $('#btn_refresh_price').bind('click', function () {
        doRefreshPrice();
    });
   
    $('#btn_freeze_storage').bind('click', function () {
        doFreezeStorage();
    });
    
    $('#btn_unfreeze_storage').bind('click', function () {
        doUnfreezeStorage();
    });
    
}

function doFreezeStorage(){
    var o_row = $("#ebsg_dg").datagrid('getSelected');
    if (!o_row || !o_row.sge_barcode) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    var s_barcode = o_row.sge_barcode;
    var s_gname = o_row.sge_gname;
    $.messager.confirm('确认', '是否冻结所有店铺['+s_barcode+':'+s_gname+']商品的库存？', function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/doFreezeStorage',
                type: "POST",
                data: {'bc': s_barcode, 'gn': s_gname},
                success: function (data) {
                    var o_res = $.parseJSON(data);
                    $.messager.show({
                        title:'库存冻结结果',
                        msg:'受影响记录数:'+o_res.msg,
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#ebsg_dg").datagrid('reload');
                }
            });
        }
    });
}

 function doUnfreezeStorage() {
    var o_row = $("#ebsg_dg").datagrid('getSelected');
    if (!o_row || !o_row.sge_barcode) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    var s_barcode = o_row.sge_barcode;
    var s_gname = o_row.sge_gname;
    $.messager.confirm('确认', '是否解冻所有店铺['+s_barcode+':'+s_gname+']商品的库存？', function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/doUnfreezeStorage',
                type: "POST",
                data: {'bc': s_barcode},
                success: function (data) {
                    var o_res = $.parseJSON(data);
                    $.messager.show({
                        title:'库存解冻结果',
                        msg:'受影响记录数:'+o_res.msg,
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#ebsg_dg").datagrid('reload');
                }
            });
        }
    }); 
}

function doRefreshPrice() {
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    var s_msg = '';
    if (s_eid === '') {
        s_msg = '当前未选择任何特定店铺，将根据站点库存刷新所有饿百零售价，是否继续此操作？';
    } else {
        s_msg = '即将根据站点库存对[' + s_sn + ']饿百零售价进行刷新，是否继续此操作？';
    }
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/refreshPrice',
                type: "POST",
                data: {'oid': s_eid},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $("#ebsg_dg").datagrid("reload");
                }
            });
        }
    });
}

function doRefreshStorage() {
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    var s_msg = '';
    if (s_eid === '') {
        s_msg = '当前未选择任何特定店铺，将根据站点库存刷新所有饿百库存，是否继续此操作？';
    } else {
        s_msg = '即将根据站点库存对[' + s_sn + ']饿百库存进行刷新，是否继续此操作？';
    }
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/refreshStorage',
                type: "POST",
                data: {'oid': s_eid},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $("#ebsg_dg").datagrid("reload");
                }
            });
        }
    });
}

function doOutPfCSV() {
    var s_oid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    var s_msg = '';
    if (s_oid === '') {
        s_msg = '当前未选择任何特定店铺，将导出所有饿百店铺库存平台，是否继续此操作？';
    } else {
        s_msg = '即将导出[' + s_sn + ']饿百库存平台表，是否继续此操作？';
    }
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/outputPlatformCSV',
                type: "POST",
                data: {'oid': s_oid, 'sn': s_sn},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        var s_html = '';
                        var o_msg = [];
                        for (var i = 0; i < o_response.msg.length; i++) {
                            o_msg = o_response.msg[i];
                            s_html += "<a href='../." + o_msg.filepath + "' style='color:#0000CD;text-decoration:underline'>" + o_msg.filename + "</a><br/>";
                        }
                        $.messager.alert('信息', s_html, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $('#dg_opfl').datagrid('reload');
                }
            });
        }
    });
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
            __b_delete_preview = false;
            //关闭窗口
            $('#ebsg_w_update_preview').window('close');            
            ajaxLoading();
            //异步请求
            $.ajax({
                url: '../' + __s_c_name + '/coverData/',
                type: "POST",
                data: {"tbn": s_tbn},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    ajaxLoadEnd();
                    if (o_response.state === true) {
                        $.messager.alert('成功', '数据导入成功!', 'info');
                    } else {
                        $.messager.alert('失败', '数据导入失败!请重试', 'error');
                    }
                    //清空预览和tbn值
                    $('#hid_tbn').val('');
                    $("#dg2").datagrid("loadData", {total: 0, rows: []});
                    $("#ebsg_dg").datagrid("reload");
                }
            });
        }
    });
}

