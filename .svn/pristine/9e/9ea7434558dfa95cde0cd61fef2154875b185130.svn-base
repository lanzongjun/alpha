
function confirmTypeFormat(value, row, index) {
    var v = value - 0;
    if (v === 1){
        return "门店";
    }
    if (v === 2){
        return "CVS手动";
    }
    if (v === 3){
        return "<span style='color:#FF0000;'>云端</span>";
    }
    return "未知";
}

function statusFormat(value, row, index) {
    if (value === '1') {
            return '已提交';
    }
    if (value === '2') {
        return '已推送';
    }
    if (value === '4') {
        return '已确认';
    }
    if (value === '5') {
        return '已拣货';
    }
    if (value === '8') {
        return '已完成';
    }
    if (value === '9') {
        return '已取消';
    }
    return '未知';
}

$(function () {
    init();
});

function init() {
    $('#btn_search').bind('click',function (){ doSearch(); });
    $('#btn_sync_load').bind('click',function (){ doSyncLoad(); });
    $('#btn_sync_reload').bind('click',function (){ doSyncReload(); });
    
    $("#dg").datagrid({
        onClickRow: function (index, row) { //easyui封装好的时间（被单机行的索引，被单击行的值）
            var p = $("#layout_room").layout("panel", "south")[0].clientWidth;            
            if (p <= 0) {
                $('#layout_room').layout('expand', 'south');
            }
            loadDetailData(row.order_id);            
        }
    });
}

function doSearch(){
    var s_db = $('#q_date_begin').val();
    var s_de = $('#q_date_end').val();
    var s_sid = $('#q_shop').combobox('getValue');
    
    $('#dg').datagrid('load', {
        s_db: s_db,
        s_de: s_de,
        s_sid: s_sid
    });
}

function loadDetailData(s_code) {
    $("#dg2").datagrid("options").url = '../'+__s_c_name+'/loadDetailData/';
    $('#dg2').datagrid('load', {
        ocode: s_code
    });
}

function doSyncLoad(){
    $.messager.prompt('加载新订单信息', '请输入订单号：', function(r){
        if (r){
            $.ajax({
                url: '../' + __s_c_name + '/getOrderInfo',
                type: "POST",
                data: {'oid': r},
                success: function (data) {
                    var o_res = $.parseJSON(data);
                    $.messager.show({
                        title:'['+r+'] 同步结果',
                        msg:o_res.msg,
                        timeout:5000,
                        showType:'slide'
                    });            
                    _doSyncReload();
                }
            });
        }
    });
}

function doSyncReload(){
    var a_rows = $("#dg").datagrid('getChecked');
    if (a_rows.length < 1) {
        $.messager.alert('信息','未选中任何订单信息，不可进行此操作','info');
        return;
    }
    $.messager.confirm('重新加载订单信息', '是否重新加载当前选中的['+a_rows.length+']条订单信息?', function(r){
        if (r){
            for (var i=0; i<a_rows.length; i++) {
                __a_sync_orders_mtoi[i] = a_rows[i].order_id;
            }
            __i_sync_index_mtoi = 0;
            _doSyncReload();
        }
    });
}

var __a_sync_orders_mtoi = [];
var __i_sync_index_mtoi = 0;
var win_progress_mtoi = null;

function _doSyncReload(){
    $.messager.progress('close');
    if (__i_sync_index_mtoi >= __a_sync_orders_mtoi.length){
        __a_sync_orders_mtoi = [];
        __i_sync_index_mtoi = 0;
        win_progress_mtoi = null;        
        $("#dg").datagrid('reload');
        return ;
    }
    var o_data = __a_sync_orders_mtoi[__i_sync_index_mtoi++];
    var s_per = __i_sync_index_mtoi+'/'+__a_sync_orders_mtoi.length;
    win_progress_mtoi = $.messager.progress({
        title:'Please waiting',
        msg:'正在重载订单['+o_data+']的信息 ['+s_per+']......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/getOrderInfo',
        type: "POST",
        data: {'oid': o_data},
        success: function (data) {
            var o_res = $.parseJSON(data);
            $.messager.show({
                title:'['+o_data+'] 同步结果',
                msg:o_res.msg,
                timeout:5000,
                showType:'slide'
            });            
            _doSyncReload();
        }
    });
}