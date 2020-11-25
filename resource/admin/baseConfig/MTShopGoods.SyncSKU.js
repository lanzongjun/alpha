
var __a_sync_shops_sl = [];
var __i_sync_index_sl = 0;
var win_progress_sl = null;

function SyncSKUInit(){
    $('#btn_sync_mt_sku_list').bind('click', function () {
        doSyncSkuList();
    });

    $('#btn_sync_mt_sku_diff').bind('click', function () {
        doSyncSkuDiff();
    });
}

function doSyncSkuList() {
    var s_m_id = $('#s_shop').combobox('getValue');
    if (s_m_id === '') {
        doSyncSkuListAll();
    }else{
        doSyncSkuListSingle();
    }
}

function doSyncSkuListAll(){
    var s_msg = '当前未选择任何特定店铺，将根据 线上美团店铺商品信息 对 所有本地商品信息 进行同步，是否继续此操作？';
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/getAllSyncShop',
                type: "POST",
                success: function (data) {
                    console.log(11111);
                    __a_sync_shops_sl = $.parseJSON(data);
                    $("#tab_box").tabs("select", 0);
                    doSyncMtSkuList();
                }
            });
        }
    });
}

function doSyncMtSkuList() {
    $.messager.progress('close');
    if (__i_sync_index_sl >= __a_sync_shops_sl.length){
        __a_sync_shops_sl = [];
        __i_sync_index_sl = 0;
        win_progress_sl = null;
        return ;
    }
    var o_data = __a_sync_shops_sl[__i_sync_index_sl++];
    var s_per = __i_sync_index_sl+'/'+__a_sync_shops_sl.length;
    var s_mid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress_sl = $.messager.progress({
        title:'Please waiting',
        msg:'正在同步[美团-'+s_shop_name+']本地商品信息['+s_per+']......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncSkuList',
        type: "POST",
        data: {'mid': s_mid},
        success: function (data) {
            var o_res = $.parseJSON(data);
            $.messager.show({
                title:'[美团-'+s_shop_name+'] 同步结果',
                msg:o_res.msg,
                timeout:5000,
                showType:'slide'
            });
            $("#mtsg_dg").datagrid('reload');
            doSyncMtSkuList();
        }
    });
}

function doSyncSkuListSingle(){
    var s_mid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    if (!s_mid || s_mid === '') {
        $.messager.alert('错误', '请选择一个店铺后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '即将根据 线上美团店铺商品信息 对[' + s_sn + '] 本地商品信息 进行同步，是否继续此操作？', function (r) {
        if (r) {
            win_progress_sl = $.messager.progress({
                title:'Please waiting',
                msg:'正在同步[美团-'+s_sn+']本地商品信息......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/syncSkuList',
                type: "POST",
                data: {'mid': s_mid},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    $.messager.show({
                        title:s_sn+' 同步结果',
                        msg:o_res.msg,
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#mtsg_dg").datagrid('reload');
                }
            });
        }
    });
}

/**
 * 更新所有店铺价格差异
 * @returns {undefined}
 */
function doSyncSkuDiff(){
    var s_msg = '将根据 存在差异的美团店铺商品信息 对 部分本地商品信息 进行同步，是否继续此操作？';
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
    var s_mid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress = $.messager.progress({
        title:'Please waiting',
        msg:'正在同步[美团-'+s_shop_name+']本地商品信息['+s_per+']......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncSkuList',
        type: "POST",
        data: {'mid': s_mid},
        success: function (data) {
            var o_res = $.parseJSON(data);
            $.messager.show({
                title:'[美团-'+s_shop_name+'] 同步结果',
                msg:o_res.msg,
                timeout:5000,
                showType:'slide'
            });
            $("#mtsg_dg").datagrid('reload');
            _doSyncSkuDiff();
        }
    });
}