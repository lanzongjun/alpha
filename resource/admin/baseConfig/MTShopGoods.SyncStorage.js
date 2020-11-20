
var __a_sync_shops = [];
var __i_sync_index = 0;
var win_progress = null;

function SyncStockInit() {
    $('#btn_sync_online').bind('click', function () {
        doSyncOnline();
    });

    $('#btn_sync_online_diff').bind('click', function () {
        doSyncOnlineDiff();
    });
}

function doSyncOnline() {
    var s_mid = $('#s_shop').combobox('getValue');
    if (s_mid === '') {
        doSyncAll();
    }else{
        doSyncSingle();
    }
}

function doSyncShop() {
    $.messager.progress('close');
    if (__i_sync_index >= __a_sync_shops.length){return ;}
    var o_data = __a_sync_shops[__i_sync_index++];
    var s_mid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress = $.messager.progress({
        title:'Please waiting',
        msg:'正在更新[美团-'+s_shop_name+']线上库存......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncOnlineStorage',
        type: "POST",
        data: {'mid': s_mid},
        success: function (data) {
            $.messager.progress('close');
            var o_res = $.parseJSON(data);
            var o_res_update = o_res.update;
            var o_res_status_up = o_res.status_up;
            var o_res_status_down = o_res.status_down;
            var i_suc = o_res_update.suc;
            var i_fail = o_res_update.fail;
            var i_pages = o_res_update.pages;
            var s_shop_name = o_res.shop_name;
            $.messager.show({
                height:260,
                title:s_shop_name+' 更新结果',
                msg:'拆分为['+i_pages+']页进行上传<br/>'+'成功:'+i_suc+'页<br/>'+'失败:'+i_fail+'页<br/>'
                +'[自动上架]拆分为['+o_res_status_up.pages+'页进行上传<br/>'+'成功:'+o_res_status_up.suc+'页<br/>'+'失败:'+o_res_status_up.fail+'页<br/>'
                +'[自动下架]拆分为['+o_res_status_down.pages+'页进行上传<br/>'+'成功:'+o_res_status_down.suc+'页<br/>'+'失败:'+o_res_status_down.fail+'页<br/>',
                timeout:5000,
                showType:'slide'
            });
            doSyncShop();
        }
    });
}

function doSyncAll(){
    var s_msg = '当前未选择任何特定店铺，将根据当前整体库存更新线上所有店铺，是否继续此操作？';
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/getAllSyncShop',
                type: "POST",
                success: function (data) {
                    __a_sync_shops = $.parseJSON(data);
                    $("#tab_box").tabs("select", 2);
                    doSyncShop();
                }
            });
        }
    });
}

function doSyncSingle() {
    var s_mid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    if (!s_mid || s_mid === '') {
        $.messager.alert('错误', '请选择一个店铺后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '即将根据 站点库存 对[' + s_sn + '] 美团线上库存 进行同步，是否继续此操作？', function (r) {
        if (r) {
            win_progress = $.messager.progress({
                title:'Please waiting',
                msg:'正在更新[美团-'+s_sn+']线上库存......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/syncOnlineStorage',
                type: "POST",
                data: {'mid': s_mid},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    var o_res_update = o_res.update;
                    var o_res_status_up = o_res.status_up;
                    var o_res_status_down = o_res.status_down;
                    var i_suc = o_res_update.suc;
                    var i_fail = o_res_update.fail;
                    var i_pages = o_res_update.pages;
                    var s_shop_name = o_res.shop_name;
                    $.messager.show({
                        height:260,
                        title:s_shop_name+' 更新结果',
                        msg:'拆分为['+i_pages+']页进行上传<br/>'+'成功:'+i_suc+'页<br/>'+'失败:'+i_fail+'页<br/>'
                        +'[自动上架]拆分为['+o_res_status_up.pages+'页进行上传<br/>'+'成功:'+o_res_status_up.suc+'页<br/>'+'失败:'+o_res_status_up.fail+'页<br/>'
                        +'[自动下架]拆分为['+o_res_status_down.pages+'页进行上传<br/>'+'成功:'+o_res_status_down.suc+'页<br/>'+'失败:'+o_res_status_down.fail+'页<br/>',
                        timeout:5000,
                        showType:'slide'
                    });
                }
            });
        }
    });
}

function doSyncOnlineDiff() {
    var s_mid = $('#s_shop').combobox('getValue');
    if (s_mid === '') {
        doSyncAllDiff();
    }else{
        doSyncSingleDiff();
    }
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
    var s_mid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress = $.messager.progress({
        title:'Please waiting',
        msg:'正在更新[美团-'+s_shop_name+']线上库存['+s_per+']......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncOnlineStorage',
        type: "POST",
        data: {'mid': s_mid, 'diff': true},
        success: function (data) {
            var o_res = $.parseJSON(data);
            var o_res_update = o_res.update;
            var o_res_status_up = o_res.status_up;
            var o_res_status_down = o_res.status_down;
            var i_suc = o_res_update.suc;
            var i_fail = o_res_update.fail;
            var i_pages = o_res_update.pages;
            var s_shop_name = o_res.shop_name;
            $.messager.show({
                height:260,
                title:s_shop_name+' 更新结果',
                msg:'拆分为['+i_pages+']页进行上传<br/>'+'成功:'+i_suc+'页<br/>'+'失败:'+i_fail+'页<br/>'
                +'[自动上架]拆分为['+o_res_status_up.pages+'页进行上传<br/>'+'成功:'+o_res_status_up.suc+'页<br/>'+'失败:'+o_res_status_up.fail+'页<br/>'
                +'[自动下架]拆分为['+o_res_status_down.pages+'页进行上传<br/>'+'成功:'+o_res_status_down.suc+'页<br/>'+'失败:'+o_res_status_down.fail+'页<br/>',
                timeout:5000,
                showType:'slide'
            });
            doSyncShopDiff();
        }
    });
}

function doSyncSingleDiff() {
    var s_mid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    if (!s_mid || s_mid === '') {
        $.messager.alert('错误', '请选择一个店铺后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '即将根据【站点差异库存】对[' + s_sn + '] 美团线上库存 进行同步，是否继续此操作？', function (r) {
        if (r) {
            win_progress = $.messager.progress({
                title:'Please waiting',
                msg:'正在更新[美团-易捷'+s_sn+'站店]线上库存......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/syncOnlineStorage',
                type: "POST",
                data: {'mid': s_mid, 'diff':true},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    var o_res_update = o_res.update;
                    var o_res_status_up = o_res.status_up;
                    var o_res_status_down = o_res.status_down;
                    var i_suc = o_res_update.suc;
                    var i_fail = o_res_update.fail;
                    var i_pages = o_res_update.pages;
                    var s_shop_name = o_res.shop_name;
                    $.messager.show({
                        height:260,
                        title:s_shop_name+' 更新结果',
                        msg:'拆分为['+i_pages+']页进行上传<br/>'+'成功:'+i_suc+'页<br/>'+'失败:'+i_fail+'页<br/>'
                        +'[自动上架]拆分为['+o_res_status_up.pages+'页进行上传<br/>'+'成功:'+o_res_status_up.suc+'页<br/>'+'失败:'+o_res_status_up.fail+'页<br/>'
                        +'[自动下架]拆分为['+o_res_status_down.pages+'页进行上传<br/>'+'成功:'+o_res_status_down.suc+'页<br/>'+'失败:'+o_res_status_down.fail+'页<br/>',
                        timeout:5000,
                        showType:'slide'
                    });
                }
            });
        }
    });
}