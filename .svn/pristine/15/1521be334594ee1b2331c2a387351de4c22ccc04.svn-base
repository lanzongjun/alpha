
var toolbar1 = [{
        text: '覆盖导入',
        iconCls: 'icon-add',
        handler: function () {
            coverData();
        }
    }];

var __b_delete_preview = true;

$(function () {
    $('#btn_upload').bind('click',function (){ $('#yjss_win_input').window('open'); });
    $('#btn_update_base').bind('click',function (){ updateBaseGoods(); });
    $('#btn_update_cache').bind('click',function (){ updateCacheGoods(); });
    $('#btn_search_win').bind('click',function (){ $('#yjss_win_search').window('open'); });
    $('#btn_sync').bind('click',function (){ getRealtimeStorage(); });
    $('#btn_sync_sku').bind('click',function (){ getRealtimeSku(); });
    $('#btn_storage_start').bind('click',function (){ startStorageAutoUpdate(); });
    $('#btn_storage_pause').bind('click',function (){ stopStorageAutoUpdate(); });
//    isStorageAutoUpdate();
//    getStorageUpdateState();
    
    $('#yjss_w_preview').window({
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
                $('#yjss_win_input').window('close');
                ajaxLoading();
            },
            success: function (data) {
                var o_response = $.parseJSON(data);
                ajaxLoadEnd();
                if (o_response.state === true) {
                    $('#hid_tbn_preview').val(o_response.tbn);
                    //TODO：数据量太大，可能会导致浏览器崩溃,后期应改成分页
                    $("#yjss_dg_preview").datagrid("options").url = '../' + __s_c_name + '/loadPreview/';
                    $('#yjss_dg_preview').datagrid('load', {
                        tbn: $('#hid_tbn_preview').val()
                    });
                    $('#yjss_w_preview').window('open');
                }
            }
        });
    });
    $('#dg').datagrid({
        url: '../AdYJShopStorageC/getList/'
    });
})

function doSearch() {
    var q_org_code = $("#q_bssy_org_code").val();
    var q_org_name = $("#q_bssy_org_name").val();
    var q_yj_code = $("#q_bssy_yj_code").val();
    var q_goods_name = $("#q_bssy_goods_name").val();
    var q_barcode = $("#q_bssy_barcode").val();
    var q_count_type = $("#q_bssy_count_type").val();
    var q_count = $("#q_bssy_count").val();
    var q_supplier_code = $("#q_bssy_supplier_code").val();
    var q_supplier_name = $("#q_bssy_supplier_name").val();
    
    $('#dg').datagrid('load', {
        oc: q_org_code, on: q_org_name, yjc: q_yj_code, gn: q_goods_name, 
        bc: q_barcode, ct:q_count_type, c:q_count, sc:q_supplier_code, sn:q_supplier_name
    });
    
    $('#yjss_f_search').form('clear');
    $('#yjss_win_search').window('close');
}

function closeSWin(){
    $('#yjss_win_search').window('close');
}

function getStorageUpdateState(){
    $('#btn_storage_start').linkbutton({
        disabled:true
    });
    $('#btn_storage_pause').linkbutton({
        disabled:true
    });
    $.ajax({
        url: '../' + __s_c_name + '/getStorageUpdateState',
        type: "POST",
        success: function (data) {
            var o_res = null;            
            try {
                o_res = $.parseJSON(data);
                if (o_res.state) {
                    if (o_res.msg === 4) {
                        $('#btn_storage_start').linkbutton({
                            disabled:true
                        });
                        $('#btn_storage_pause').linkbutton({
                            disabled:false
                        });
                    } else if(o_res.msg === 5) {
                        $('#btn_storage_start').linkbutton({
                            disabled:false
                        });
                        $('#btn_storage_pause').linkbutton({
                            disabled:true
                        });
                    }
                }
            }catch(error){
                
            }
        }
    });
}

function isStorageAutoUpdate() {
    $.ajax({
        url: '../' + __s_c_name + '/isStorageAutoUpdate',
        type: "POST",
        success: function (data) {
            var o_res = null;
            try {
                o_res = $.parseJSON(data);
                if (o_res.state && o_res.msg) {
                    $('#btn_storage_auto').linkbutton({
                        iconCls:"icon-power-on"
                    });
                } else {
                    $('#btn_storage_auto').linkbutton({
                        iconCls:"icon-power-off"
                    });
                }
            }catch(error){
                $('#btn_storage_auto').linkbutton({
                    iconCls:"icon-power-off"
                });
            }
        }
    });
}

function startStorageAutoUpdate() {
    $.ajax({
        url: '../' + __s_c_name + '/startStorageAutoUpdate',
        type: "POST",
        success: function (data) {
            getStorageUpdateState();
            var o_res = null;
            try {
                o_res = $.parseJSON(data);
                if (o_res.state) {                    
                    $.messager.alert('成功', '自动更新启动成功!', 'info');
                } else {
                    $.messager.alert('失败', '自动更新启动失败!', 'info');                    
                }
            }catch(error){
                $.messager.alert('错误', error.message, 'error');
            }
        }
    });
}

function stopStorageAutoUpdate() {
    $.ajax({
        url: '../' + __s_c_name + '/stopStorageAutoUpdate',
        type: "POST",
        success: function (data) {
            getStorageUpdateState();
            var o_res = null;
            try {
                o_res = $.parseJSON(data);
                if (o_res.state) {
                    $.messager.alert('成功', '自动更新暂停成功!', 'info');
                } else {
                    $.messager.alert('失败', '自动更新暂停失败!', 'info');
                }
            }catch(error){
                $.messager.alert('错误', error.message, 'error');
            }
        }
    });
}

function deletePreviewData(){
    var s_tbn = $('#hid_tbn_preview').val();
    $.ajax({
        url: '../' + __s_c_name + '/deletePreviewData',
        type: "POST",
        data: {'tbn': s_tbn},
        success: function (data) {
            
        }
    });
}

function getRealtimeSku() {
    var s_eid = $('#q_shop').combobox('getValue');
    $.ajax({
        url: '../' + __s_c_name + '/doSyncSKUTest',
        type: "POST",
        data: {'eid': s_eid},
        success: function (data) {
            
        }
    });
}

function getRealtimeStorage() {
    $.ajax({
        url: '../' + __s_c_name + '/doSyncTest',
        type: "POST",
        data: {'eid': 's_eid'},
        success: function (data) {
            
        }
    });
}

/*
function doSyncOnline() {
    var s_eid = $('#q_shop').combobox('getValue');
    if (s_eid === '') {
        doSyncAll();
    }else{
        doSyncSingle();
    }
}

function doSyncSingle() {
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    if (!s_eid || s_eid === '') {
        $.messager.alert('错误', '请选择一个店铺后，在进行此操作', 'error');
        return;
    }    
    $.messager.confirm('确认', '即将根据 站点库存 对[' + s_sn + '] 饿百线上库存 进行同步，是否继续此操作？', function (r) {
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
                    var s_shop_sn = o_res.shop_sn;
                    $.messager.show({
                        title:'易捷('+s_shop_sn+'站店) 更新结果',
                        msg:'拆分为['+i_pages+']页进行上传<br/>'+'成功:'+i_suc+'页<br/>'+'失败:'+i_fail+'页',
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#tab_box").tabs("select", 2);
                    $("#dg_log").datagrid('reload');
                }
            });
        }
    });
}

var __a_sync_shops = [];
var __i_sync_index = 0;

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

var win_progress = null;

function doSyncShop() {
    $.messager.progress('close');
    if (__i_sync_index >= __a_sync_shops.length){return ;}
    var o_data = __a_sync_shops[__i_sync_index++];
    var s_eid = o_data.bs_e_id;
    var s_shop_name = o_data.bs_shop_sn;
    win_progress = $.messager.progress({
        title:'Please waiting',
        msg:'正在获取[饿了么-易捷'+s_shop_name+'站店]实时库存......'
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
            var s_shop_sn = o_res.shop_sn;
            $.messager.show({
                title:'易捷('+s_shop_sn+'站店) 更新结果',
                msg:'拆分为['+i_pages+']页进行上传<br/>'+'成功:'+i_suc+'页<br/>'+'失败:'+i_fail+'页',
                timeout:5000,
                showType:'slide'
            });
            $("#dg_log").datagrid('reload');
            doSyncShop();
        }
    });
}
*/

function updateCacheGoodsEle() {
    $.messager.progress({
        title: 'Please waiting',
        msg: '正在更新[饿了么商品缓存]相关信息......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/updateCacheGoodsEle/',
        type: "POST",
        success: function (data) {
            $.messager.progress('close');
            var o_response = $.parseJSON(data);
            $.messager.show({
                title: '[饿了么商品缓存]相关信息 更新结果',
                msg: o_response.msg,
                timeout: 2000,
                showType: 'slide'
            });
            updateCacheGoodsMT();
        }
    });
}

function updateCacheGoodsMT() {
    $.messager.progress({
        title: 'Please waiting',
        msg: '正在更新[美团商品缓存]相关信息......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/updateCacheGoodsMT/',
        type: "POST",
        success: function (data) {
            $.messager.progress('close');
            var o_response = $.parseJSON(data);
            $.messager.show({
                title: '[美团商品缓存]相关信息 更新结果',
                msg: o_response.msg,
                timeout: 2000,
                showType: 'slide'
            });
        }
    });
}

function updateCacheGoods() {
    $.messager.progress({
        title: 'Please waiting',
        msg: '正在更新[可售商品缓存]相关信息......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/updateCacheGoods/',
        type: "POST",
        success: function (data) {
            $.messager.progress('close');
            var o_response = $.parseJSON(data);
            $.messager.show({
                title: '[可售商品缓存]相关信息 更新结果',
                msg: o_response.msg,
                timeout: 2000,
                showType: 'slide'
            });
            updateCacheGoodsEle();
        }
    });
}

function updateBaseGoods() {
    $.messager.confirm('确认', '是否根据当前库存数据，更新总商品库?', function (r) {
        if (r) {
            $.messager.progress({
                title:'Please waiting',
                msg:'正在更新[总商品库]相关信息......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/updateBaseGoods/',
                type: "POST",
                success: function (data) {
                    $.messager.progress('close');
                    var o_response = $.parseJSON(data);
                    var s_html = "<div>新增商品:"+o_response.new_count+"</div>";
                    s_html += "<div>删除商品:"+o_response.del_count+"</div>";
                    s_html += "<div>恢复商品:"+o_response.re_count+"</div>";
                    $.messager.show({
                        title:'[总商品库]相关信息 更新结果',
                        msg:s_html,
                        timeout:2000,
                        showType:'slide'
                    });
                    updateCacheGoods();
                }
            });
        }
    });
}

function coverData() {
    var s_tbn = $('#hid_tbn_preview').val();
    if (s_tbn === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将完全覆盖正式数据，是否确认覆盖导入?', function (r) {
        if (r) {
            var s_tbn = $('#hid_tbn_preview').val();
            __b_delete_preview = false;
            //关闭窗口
            $('#yjss_w_preview').window('close');
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
                    $('#hid_tbn_preview').val('');
                    $("#yjss_dg_preview").datagrid("loadData", {total: 0, rows: []});                    
                    //返回主界面
//                    $("#tab_box").tabs("select", 0);
                    $("#dg").datagrid("reload");                    
                    updateBaseGoods();
                }
            });
        }
    });
}
