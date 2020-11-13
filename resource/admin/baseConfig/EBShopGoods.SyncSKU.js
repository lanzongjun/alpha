
var __a_sync_shops_sl = [];
var __i_sync_index_sl = 0;
var win_progress_sl = null;

function SyncSKUInit(){
    $("#ebsg_dg_sku_log").datagrid({     
        onClickRow: function (index, row) {
            $('#ebsg_msg_sku_log').text(row.lse_msg);
        }
    });
    
    $('#s_sku_log_shop').combobox({url: '../AdShopInfoYJC/getShopEbIdList'});

    $('#btn_sku_log_search').bind('click', function () {
        var s_oid = $('#s_sku_log_shop').combobox('getValue');
        var s_s = $('#s_sku_log_status').combobox('getValue');
        $('#ebsg_dg_sku_log').datagrid('load', {eid: s_oid, ss:s_s});
    });
    
    $('#btn_sku_log_clear').bind('click', function () {
        doSKULogClear();
    });
    
    $('#btn_sync_eb_sku_list').bind('click', function () {
        doSyncEBSkuList();
    });
    
    $('#btn_sync_eb_sku_diff').bind('click', function () {
        doSyncSkuDiff();
    });
    
    $('#btn_sync_sku_log').bind('click',function (){ 
        $('#ebsg_w_sku_log').window('open'); 
        $("#ebsg_dg_sku_log").datagrid("options").url = '../'+__s_c_name+'/getLogSKUList/';
        $('#ebsg_dg_sku_log').datagrid('load');
    });
}

/**
 * 更新所有店铺价格差异
 * @returns {undefined}
 */
function doSyncSkuDiff(){
    var s_msg = '将根据 存在差异的饿百店铺商品信息 对 部分本地商品信息 进行同步，是否继续此操作？';    
    $.messager.confirm('差异下载-确认', s_msg, function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/getAllDiffShop',
                type: "POST",
                success: function (data) {                    
                    __a_sync_shops_sl = $.parseJSON(data);
                    _doSyncSkuDiff();
                }
            });
        }
    });
}

/**
 * 更新单店价格
 * @returns {undefined}
 */
function _doSyncSkuDiff() {
    $.messager.progress('close');
    if (__i_sync_index_sl >= __a_sync_shops_sl.length){
        __a_sync_shops_sl = [];
        __i_sync_index_sl = 0;
        win_progress_sl = null;
        return ;
    }
    var o_data = __a_sync_shops_sl[__i_sync_index_sl++];
    var s_per = __i_sync_index_sl+'/'+__a_sync_shops_sl.length;
    var s_eid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress = $.messager.progress({
        title:'Please waiting',
        msg:'正在同步[饿了么-'+s_shop_name+']本地商品信息['+s_per+']......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncSkuList',
        type: "POST",
        data: {'eid': s_eid},
        success: function (data) {
            var o_res = $.parseJSON(data);
            $.messager.show({
                title:'[饿了么-'+s_shop_name+'] 同步结果',
                msg:o_res.msg,
                timeout:5000,
                showType:'slide'
            });
            $("#dg").datagrid('reload');
            _doSyncSkuDiff();
        }
    });
}

function doSyncEBSkuList() {
    var s_eid = $('#s_shop').combobox('getValue');
    if (s_eid === '') {
        doSyncEBSkuListAll();
    }else{
        doSyncEBSkuListSingle();
    }
}

function doSyncEBSkuListSingle(){
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    if (!s_eid || s_eid === '') {
        $.messager.alert('错误', '请选择一个店铺后，在进行此操作', 'error');
        return;
    }    
    $.messager.confirm('确认', '即将根据 线上饿百店铺商品信息 对[' + s_sn + '] 本地商品信息 进行同步，是否继续此操作？', function (r) {
        if (r) {
            win_progress_sl = $.messager.progress({
                title:'Please waiting',
                msg:'正在同步[饿了么-'+s_sn+']本地商品信息......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/syncSkuList',
                type: "POST",
                data: {'eid': s_eid},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    $.messager.show({
                        title:s_sn+' 同步结果',
                        msg:o_res.msg,
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#dg").datagrid('reload');
                }
            });
        }
    });
}

function doSyncEBSkuListAll(){
    var s_msg = '当前未选择任何特定店铺，将根据 线上饿百店铺商品信息 对 所有本地商品信息 进行同步，是否继续此操作？';    
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/getAllSyncShop',
                type: "POST",
                success: function (data) {                    
                    __a_sync_shops_sl = $.parseJSON(data);
                    doSyncESkuList();
                }
            });
        }
    });
}

function doSyncESkuList() {
    $.messager.progress('close');
    if (__i_sync_index_sl >= __a_sync_shops_sl.length){
        __a_sync_shops_sl = [];
        __i_sync_index_sl = 0;
        win_progress_sl = null;
        return ;
    }
    var o_data = __a_sync_shops_sl[__i_sync_index_sl++];
    var s_per = __i_sync_index_sl+'/'+__a_sync_shops_sl.length;
    var s_eid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress_sl = $.messager.progress({
        title:'Please waiting',
        msg:'正在同步[饿了么-'+s_shop_name+']本地商品信息['+s_per+']......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncSkuList',
        type: "POST",
        data: {'eid': s_eid},
        success: function (data) {
            var o_res = $.parseJSON(data);
            $.messager.show({
                title:'[饿了么-'+s_shop_name+'] 同步结果',
                msg:o_res.msg,
                timeout:5000,
                showType:'slide'
            });
            $("#dg").datagrid('reload');
            doSyncESkuList();
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
