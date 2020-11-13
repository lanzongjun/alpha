
var __a_sync_shops_pl = [];
var __i_sync_index_pl = 0;
var win_progress_pl = null;

function SyncPriceInit(){  
    $("#ebsg_dg_price_log").datagrid({     
        onClickRow: function (index, row) {
            $('#dom_log_price_msg').text(row.lspe_msg);
        }
    });
    
    $('#s_price_log_shop').combobox({url: '../AdShopInfoYJC/getShopEbIdList'});
    
    $('#btn_price_log_search').bind('click', function () {
        var s_oid = $('#s_price_log_shop').combobox('getValue');
        var s_s = $('#s_price_log_status').combobox('getValue');
        $('#ebsg_dg_price_log').datagrid('load', {eid: s_oid, ss:s_s});
    });
    
    $('#btn_price_log_clear').bind('click', function () {
        doPriceLogClear();
    });
    
    $('#btn_sync_price_diff').bind('click', function () {
        doSyncPriceDiff();
    });
    
    $('#btn_sync_price_log').bind('click',function (){ 
        $('#ebsg_w_price_log').window('open');
        $("#ebsg_dg_price_log").datagrid("options").url = '../'+__s_c_name+'/getLogPriceList/';
        $('#ebsg_dg_price_log').datagrid('load');
    });  
}

/**
 * 价格差异更新
 * @returns {undefined}
 */
function doSyncPriceDiff() {
    var s_eid = $('#s_shop').combobox('getValue');
    if (s_eid === '') {
        doSyncPriceAllDiff();
    }else{
        doSyncPriceSingleDiff();
    }
}

/**
 * 更新所有店铺价格差异
 * @returns {undefined}
 */
function doSyncPriceAllDiff(){
    var s_msg = '当前未选择任何特定店铺，将根据当前【差异价格】更新线上【所有店铺】，是否继续此操作？';    
    $.messager.confirm('差异更新-确认', s_msg, function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/getAllDiffPriceShop',
                type: "POST",
                success: function (data) {                    
                    __a_sync_shops_pl = $.parseJSON(data);
                    _doSyncPriceDiff();
                }
            });
        }
    });
}

/**
 * 更新指定店铺价格差异
 * @returns {undefined}
 */
function doSyncPriceSingleDiff(){
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    if (!s_eid || s_eid === '') {
        $.messager.alert('错误', '请选择一个店铺后，在进行此操作', 'error');
        return;
    }    
    $.messager.confirm('确认', '即将根据【站点差异价格】对[' + s_sn + '] 饿百线上价格 进行同步，是否继续此操作？', function (r) {
        if (r) {
            win_progress = $.messager.progress({
                title:'Please waiting',
                msg:'正在更新[饿了么-易捷'+s_sn+'站店]线上价格......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/syncPrice',
                type: "POST",
                data: {'eid': s_eid, 'diff':true},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    var i_suc = o_res.suc;
                    var i_fail = o_res.fail;
                    var i_pages = o_res.pages;
                    var s_shop_name = o_res.shop_name;
                    $.messager.show({
                        title:s_shop_name+' 更新结果',
                        msg:'拆分为['+i_pages+']页进行上传<br/>'+'成功:'+i_suc+'页<br/>'+'失败:'+i_fail+'页',
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#dg").datagrid('reload');
                }
            });
        }
    });
}

/**
 * 更新单店价格
 * @returns {undefined}
 */
function _doSyncPriceDiff() {
    $.messager.progress('close');
    if (__i_sync_index_pl >= __a_sync_shops_pl.length){
        __a_sync_shops_pl = [];
        __i_sync_index_pl = 0;
        win_progress_pl = null;
        return ;
    }
    var o_data = __a_sync_shops_pl[__i_sync_index_pl++];
    var s_per = __i_sync_index_pl+'/'+__a_sync_shops_pl.length;
    var s_eid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress = $.messager.progress({
        title:'Please waiting',
        msg:'正在更新[饿了么-'+s_shop_name+']线上价格['+s_per+']......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncPrice',
        type: "POST",
        data: {'eid': s_eid, 'diff': true},
        success: function (data) {
            var o_res = $.parseJSON(data);
            var i_suc = o_res.suc;
            var i_fail = o_res.fail;
            var i_pages = o_res.pages;
            var s_shop_name = o_res.shop_name;
            $.messager.show({
                title:s_shop_name+' 更新结果',
                msg:'拆分为['+i_pages+']页进行上传<br/>'+'成功:'+i_suc+'页<br/>'+'失败:'+i_fail+'页',
                timeout:5000,
                showType:'slide'
            });
            $("#dg").datagrid('reload');
            _doSyncPriceDiff();
        }
    });
}

function doPriceLogClear(){
    $.messager.confirm('确认', '此操作将删除所有日志数据，仅保留当日数据，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/keepPriceTodayLog',
                type: "POST",
                success: function (data) {
                    ajaxLoadEnd();
                    $.messager.alert('信息', "受影响记录数:"+data, 'info');
                }
            });
        }
    });    
}
