function newStorageFormat(value, row, index) {
    var storage_old = row.sgm_count - 0;
    var storage_new = value - 0;
    if (storage_new === storage_old) {
        return storage_new;
    } else if (storage_new > storage_old) {
        return "<span style='color:#EE5C42;font-weight:bolder'>" + storage_new + "↑</span>";
    } else {
        return "<span style='color:#9ACD32;font-weight:bolder'>" + storage_new + "↓</span>";
    }
}

function upFormat(value, row, index) {
    if (value === '0') {
        return "上架";
    } else if (value === '1') {
        return "下架";
    } else {
        return "未知";
    }
}

function shopNameFormat(value, row, index) {
    return value;
}

function updateErrNoFormat(value, row, index) {
    return value==='0' ? '成功':'<span style="font-weight:bold;color:#B22222">失败</span>';
}

function opFormat(value, row, index) {
    return '<a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="doFreezeStorage(\''+row.sgm_barcode+'\')">冻结</a>';                                
}

$(function () {
    init();
    SyncStockInit();
    SyncSKUInit();
});

function init() {
    $('#btn_sync_log').bind('click',function (){ 
        $('#mtsg_w_stock_log').window('open');
        $("#mtsg_dg_stock_log").datagrid("options").url = '../'+__s_c_name+'/getLogStockList/';
        $('#mtsg_dg_stock_log').datagrid('load');
    });
    $('#btn_sync_sku_log').bind('click',function (){ 
        $('#mtsg_w_sku_log').window('open'); 
        $("#mtsg_dg_sku_log").datagrid("options").url = '../'+__s_c_name+'/getLogSKUList/';
        $('#mtsg_dg_sku_log').datagrid('load');
    });
    $("#mtsg_dg_new_goods").datagrid({
        rowStyler: function (index, row) {
            if (row.csgm_sale_price-0 <= 0 || row.csgm_settlement_price-0 <= 0) {
                return 'background-color:#F47983;color:#FFFFFF;';
            }
        }
    });
    $("#mtsg_dg_sku_log").datagrid({     
        onClickRow: function (index, row) {
            $('#mtsg_msg_sku_log').text(row.lsm_msg);
        }
    });
    $('#btn_sku_log_search').bind('click', function () {
        var s_oid = $('#s_sku_log_shop').combobox('getValue');
        var s_s = $('#s_sku_log_status').combobox('getValue');
        $('#mtsg_dg_sku_log').datagrid('load', {sid: s_oid, ss:s_s});
    });    
    $('#btn_sku_log_clear').bind('click', function () {
        doSKULogClear();
    });
    
    $("#mtsg_dg_stock_log").datagrid({     
        onClickRow: function (index, row) {
            $('#mtsg_msg_stock_log').text(row.lum_msg);
        }
    });
    $('#btn_stock_log_search').bind('click', function () {
        var s_oid = $('#s_stock_log_shop').combobox('getValue');
        var s_s = $('#s_stock_log_status').combobox('getValue');
        $('#mtsg_dg_stock_log').datagrid('load', {sid: s_oid, ss:s_s});
    });    
    $('#btn_stock_log_clear').bind('click', function () {
        doStockLogClear();
    });
    
    $('#mtsg_new_goods').window({
        onOpen: function () {
            var s_oid = $('#s_shop').combobox('getValue');
            var s_name = $('#s_shop').combobox('getText');
            s_name = s_name === null || s_name === '' ? '所有' : s_name;
            $('#mtsg_new_goods').window('setTitle','未上线商品-'+s_name);
            $("#mtsg_dg_new_goods").datagrid("options").url = '../' + __s_c_name + '/getNewGoods/';
            $("#mtsg_dg_new_goods").datagrid("load",{
                'sid': s_oid
            });
            $("#tb_ng_dl").attr('href','../' + __s_c_name + '/outputNewGoods/?sid='+s_oid);
        }
    });
    $('#btn_show_new_goods').bind('click', function () {
        $('#mtsg_new_goods').window('open');
    });
    $("#dg_goods_new").datagrid({
        rowStyler: function (index, row) {
            if (row.bbp_settlement_price === null) {
                return 'background-color:#FF1493;color:#000000;';
            }
        }
    });
    
    $("#mtsg_dg").datagrid({
        rowStyler: function (index, row) {
            if (row.sgm_is_freeze === '1') {
                return 'background-color:#CCCCCC;color:#333333;';
            }
        }
    });
    $('#s_shop').combobox({
        url: '../AdShopInfoYJC/getShopMtIdList',
        onLoadSuccess: function () {
            var s_oid = $('#s_shop').combobox('getValue');
            $('#mtsg_dg').datagrid({url: '../' + __s_c_name + '/getList/'});
            $('#mtsg_dg').datagrid('load', {
                oid: s_oid
            });
        }
    });
    
    $('#btn_search').bind('click', function () {
        var s_oid = $('#s_shop').combobox('getValue');
        var s_gname = $('#s_goods').val();
        var s_barcode = $('#s_barcode').val();
        var s_filter_storage = $('#s_filter_storage').combobox('getValue');
        var s_filter_up = $('#s_filter_up').combobox('getValue');
        $('#mtsg_dg').datagrid('load', {
            oid: s_oid,
            gn: s_gname,
            bc: s_barcode,
            fs: s_filter_storage,
            fu: s_filter_up
        });
    });
    
    $('#btn_refresh_storage').bind('click', function () {
        doRefreshStorage();
    });

    $('#btn_refresh_price').bind('click', function () {
        doRefreshPrice();
    });

    $('#btn_update_storage').bind('click', function () {
        doUpdateStorage();
    });
    
    $('#btn_freeze_storage').bind('click', function () {
        doFreezeStorage();
    });
    
    $('#btn_unfreeze_storage').bind('click', function () {
        doUnfreezeStorage();
    });
}

function doStockLogClear(){
    $.messager.confirm('确认', '此操作将删除所有[库存更新]日志数据，仅保留当日数据，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/keepStockTodayLog',
                type: "POST",
                success: function (data) {
                    ajaxLoadEnd();
                    $.messager.alert('信息', "受影响记录数:"+data, 'info');
                }
            });
        }
    });    
}

function doSKULogClear(){
    $.messager.confirm('确认', '此操作将删除[SKU更新]所有日志数据，仅保留当日数据，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/keepSKUTodayLog',
                type: "POST",
                success: function (data) {
                    ajaxLoadEnd();
                    $.messager.alert('信息', "受影响记录数:"+data, 'info');
                }
            });
        }
    });    
}

function doFreezeStorage(){
    var o_row = $("#mtsg_dg").datagrid('getSelected');
    if (!o_row || !o_row.sgm_barcode) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    var s_barcode = o_row.sgm_barcode;
    var s_gname = o_row.sgm_gname;
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
                    $("#mtsg_dg").datagrid('reload');
                }
            });
        }
    });
}

 function doUnfreezeStorage() {
    var o_row = $("#mtsg_dg").datagrid('getSelected');
    if (!o_row || !o_row.sgm_barcode) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    var s_barcode = o_row.sgm_barcode;
    var s_gname = o_row.sgm_gname;
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
                    $("#mtsg_dg").datagrid('reload');
                }
            });
        }
    }); 
 }

function doRefreshStorage() {
    var s_mid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    var s_msg = '';
    if (s_mid === '') {
        s_msg = '当前未选择任何特定店铺，将根据站点库存刷新所有美团库存，是否继续此操作？';
    } else {
        s_msg = '即将根据站点库存对' + s_sn + '美团库存进行刷新，是否继续此操作？';
    }
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/refreshStorage',
                type: "POST",
                data: {'oid': s_mid},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $("#mtsg_dg").datagrid("reload");
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
        s_msg = '当前未选择任何特定店铺，将根据站点库存刷新所有美团零售价，是否继续此操作？';
    } else {
        s_msg = '即将根据站点库存对[' + s_sn + ']美团零售价进行刷新，是否继续此操作？';
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
                    $("#mtsg_dg").datagrid("reload");
                }
            });
        }
    });
}

function doUpdateStorage() {
    var s_mid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    var s_msg = '';
    if (s_mid === '') {
        s_msg = '当前未选择任何特定店铺，将更新所有美团店铺库存，是否继续此操作？';
    } else {
        s_msg = '即将对' + s_sn + '美团库存进行更新，是否继续此操作？';
    }
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/updateStorage',
                type: "POST",
                data: {'oid': s_mid},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $("#mtsg_dg").datagrid("reload");
                }
            });
        }
    });
}
