
var __a_sync_shops = [];
var __i_sync_index = 0;
var win_progress = null;

function SyncStockInit() {
    $("#ebsg_dg_stock_log").datagrid({     
        onClickRow: function (index, row) {
            $('#dom_log_msg').text(row.lue_msg);
        }
    });
    
    $('#s_stock_log_shop').combobox({url: '../AdShopInfoYJC/getShopEbIdList'});
    
    $('#btn_stock_log_search').bind('click', function () {
        var s_oid = $('#s_stock_log_shop').combobox('getValue');
        var s_s = $('#s_stock_log_status').combobox('getValue');
        $('#ebsg_dg_stock_log').datagrid('load', {eid: s_oid, ss:s_s});
    });
    
    $('#btn_stock_log_clear').bind('click', function () {
        doLogClear();
    });
     
    $('#btn_sync_online').bind('click', function () {
        doSyncOnline();
    });
    
    $('#btn_sync_online_diff').bind('click', function () {
        doSyncOnlineDiff();
    });
    
    $('#btn_sync_log').bind('click',function (){ 
        $('#ebsg_w_stock_log').window('open');
        $("#ebsg_dg_stock_log").datagrid("options").url = '../'+__s_c_name+'/getLogStockList/';
        $('#ebsg_dg_stock_log').datagrid('load');
    });    
}

function doSyncOnline() {
    var s_eid = $('#s_shop').combobox('getValue');
    if (s_eid === '') {
        doSyncAll();
    }else{
        doSyncSingle();
    }
}

function doSyncOnlineDiff() {
    var s_eid = $('#s_shop').combobox('getValue');
    if (s_eid === '') {
        doSyncAllDiff();
    }else{
        doSyncSingleDiff();
    }
}

function doSyncSingle() {
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    if (!s_eid || s_eid === '') {
        $.messager.alert('错误', '请选择一个店铺后，在进行此操作', 'error');
        return;
    }    
    $.messager.confirm('确认', '即将根据【站点整体库存】对[' + s_sn + '] 饿百线上库存 进行同步，是否继续此操作？', function (r) {
        if (r) {
            win_progress = $.messager.progress({
                title:'Please waiting',
                msg:'正在更新[饿了么-易捷'+s_sn+'站店]线上库存......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/syncOnlineStorage',
                type: "POST",
                data: {'eid': s_eid},
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

function doSyncSingleDiff() {
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    if (!s_eid || s_eid === '') {
        $.messager.alert('错误', '请选择一个店铺后，在进行此操作', 'error');
        return;
    }    
    $.messager.confirm('确认', '即将根据【站点差异库存】对[' + s_sn + '] 饿百线上库存 进行同步，是否继续此操作？', function (r) {
        if (r) {
            win_progress = $.messager.progress({
                title:'Please waiting',
                msg:'正在更新[饿了么-易捷'+s_sn+'站店]线上库存......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/syncOnlineStorage',
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

function doSyncAll(){
    var s_msg = '当前未选择任何特定店铺，将根据当前【整体库存】更新线上【所有店铺】，是否继续此操作？';    
    $.messager.confirm('常规更新-确认', s_msg, function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/getAllSyncShop',
                type: "POST",
                success: function (data) {                    
                    __a_sync_shops = $.parseJSON(data);
                    doSyncShop();
                }
            });
        }
    });
}

function doSyncAllDiff() {
    var s_msg = '当前未选择任何特定店铺，将根据当前【差异库存】更新线上【所有店铺】，是否继续此操作？';    
    $.messager.confirm('差异更新-确认', s_msg, function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/getAllDiffStorageShop',
                type: "POST",
                success: function (data) {                    
                    __a_sync_shops = $.parseJSON(data);
                    doSyncShopDiff();
                }
            });
        }
    });
}

function doSyncShop() {
    $.messager.progress('close');
    if (__i_sync_index >= __a_sync_shops.length){
        $("#dg").datagrid('reload');
        return ;
    }
    var o_data = __a_sync_shops[__i_sync_index++];
    var s_per = __i_sync_index+'/'+__a_sync_shops.length;
    var s_eid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress = $.messager.progress({
        title:'Please waiting',
        msg:'正在更新[饿了么-'+s_shop_name+']线上库存['+s_per+']......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncOnlineStorage',
        type: "POST",
        data: {'eid': s_eid},
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
            doSyncShop();
        }
    });
}

function doSyncShopDiff() {
    $.messager.progress('close');
    if (__i_sync_index >= __a_sync_shops.length){
        __a_sync_shops = [];
        __i_sync_index = 0;
        win_progress = null;
        $("#dg").datagrid('reload');
        return ;
    }
    var o_data = __a_sync_shops[__i_sync_index++];
    var s_per = __i_sync_index+'/'+__a_sync_shops.length;
    var s_eid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress = $.messager.progress({
        title:'Please waiting',
        msg:'正在更新[饿了么-'+s_shop_name+']线上库存['+s_per+']......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncOnlineStorage',
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
            doSyncShopDiff();
        }
    });
}

function doLogClear(){
    $.messager.confirm('确认', '此操作将删除所有日志数据，仅保留当日数据，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/keepUpdateTodayLog',
                type: "POST",
                success: function (data) {
                    ajaxLoadEnd();
                    $.messager.alert('信息', "受影响记录数:"+data, 'info');
                }
            });
        }
    });    
}
