
var __i_mtoitd_refresh_rate = 60000;
var __b_mtoitd_auto_refresh = false;

$(function () {
    init();
});

function waitFormat(value, row, index) {
    return diffDatetime(value);
}

function doConfirmOrder(s_order_id) {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.order_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    var s_order_id = o_row.order_id;
    $.ajax({
        url: '../' + __s_c_name + '/doConfirmOrder',
        type: "POST",
        data: {'oi': s_order_id},
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
            $("#dg").datagrid('reload');
        }
    });
}

function doCancelOrder() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.order_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    var s_order_id = o_row.order_id;
    $.ajax({
        url: '../' + __s_c_name + '/doCancelOrder',
        type: "POST",
        data: {'oi': s_order_id},
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
            $("#dg").datagrid('reload');
        }
    });
}

function diffDatetime(beginDate) {
    var result = "";
    var date1 = new Date(beginDate); //开始时间
    var date2 = new Date(); //结束时间
    var date3 = date2.getTime() - date1.getTime() //时间差的毫秒数
    var days=Math.floor(date3 / (24*3600*1000)) // 计算出相差天数
    result = days > 0 ? days+"天" : "";
    //计算出小时数
    var leave1 = date3 % (24*3600*1000) //计算天数后剩余的毫秒数
    var hours = Math.floor(leave1 / (3600*1000))
    result += hours > 0 ? hours+"时" : "";
    //计算出相差分钟数
    var leave2 = leave1 % (3600*1000) //计算小时数后剩余的毫秒数
    var minutes = Math.floor(leave2 / (60*1000))
    result += minutes > 0 ? minutes+"分" : "";
    //计算出相差秒数
    var leave3 = leave2 % (60*1000) //计算分钟数后剩余的毫秒数
    var seconds = Math.round(leave3 / 1000)
    result += seconds > 0 ? seconds+"秒" : "";
    return result;
}

function statusFormat(value, row, index) {
    if (value === '1') {
        return '已提交';
    }
    if (value === '2') {
        return '已推送';
    }
    if (value === '3') {
        return '已收到';
    }
    if (value === '4') {
        return '已确认';
    }
    if (value === '6') {
        return '配送中';
    }
    if (value === '7') {
        return '已送达';
    }
    if (value === '8') {
        return '已完成';
    }
    if (value === '9') {
        return '已取消';
    }
    return '未知';
}

function init() {
    $('#btn_confirm_start').linkbutton({
        disabled:true
    });
    $('#btn_confirm_pause').linkbutton({
        disabled:true
    });
    $("#dg").datagrid({
        onClickRow: function (index, row) { //easyui封装好的时间（被单机行的索引，被单击行的值）
            var p = $("#layout_room").layout("panel", "south")[0].clientWidth;            
            if (p <= 0) {
                $('#layout_room').layout('expand', 'south');
            }
            loadDetailData(row.order_id);            
        }
    });
    $('#btn_confirm_start').bind('click',function (){ startOrderConfirm(); });
    $('#btn_confirm_pause').bind('click',function (){ stopOrderConfirm(); });
    $('#btn_confirm').bind('click',function (){ doConfirmOrder(); });
    $('#btn_cancel').bind('click',function (){ doCancelOrder(); });
    isOrderAutoConfirm();
    refreshData();
}

function refreshData(){
    $('#dg').datagrid('load');
    __o_mtoitd_refresh_handler = setTimeout(refreshData,__i_mtoitd_refresh_rate);
}

function loadDetailData(s_code) {
    $("#dg2").datagrid("options").url = '../'+__s_c_name+'/loadDetailData/';
    $('#dg2').datagrid('load', {
        ocode: s_code
    });
}

function isOrderAutoConfirm() {
    $.ajax({
        url: '../' + __s_c_name + '/isOrderAutoConfirm',
        type: "POST",
        success: function (data) {
            var o_res = null;
            try {
                o_res = $.parseJSON(data);
                if (o_res.state) {
                    if (o_res.msg){
                        __b_mtoitd_auto_refresh = true;
                        $('#btn_confirm_start').linkbutton({
                            disabled:true
                        });
                        $('#btn_confirm_pause').linkbutton({
                            disabled:false
                        });
                    } else {
                        __b_mtoitd_auto_refresh = false;
                        $('#btn_confirm_start').linkbutton({
                            disabled:false
                        });
                        $('#btn_confirm_pause').linkbutton({
                            disabled:true
                        });
                    }
                }else{
                    __b_mtoitd_auto_refresh = false;
                    $('#btn_confirm_start').linkbutton({
                        disabled:true
                    });
                    $('#btn_confirm_pause').linkbutton({
                        disabled:true
                    });
                }
            }catch(error){
                __b_mtoitd_auto_refresh = false;
                $('#btn_confirm_start').linkbutton({
                    disabled:true
                });
                $('#btn_confirm_pause').linkbutton({
                    disabled:true
                });
            }
        }
    });
}

function startOrderConfirm() {
    $.ajax({
        url: '../' + __s_c_name + '/startOrderConfirm',
        type: "POST",
        success: function (data) {
            var o_res = null;
            try {
                o_res = $.parseJSON(data);
                if (o_res.state) {                    
                    $.messager.alert('成功', '自动接单启动成功!', 'info');
                } else {
                    $.messager.alert('失败', '自动接单启动失败!', 'info');                    
                }
            }catch(error){
                $.messager.alert('错误', error.message, 'error');
            }
            isOrderAutoConfirm();
        }
    });
}

function stopOrderConfirm() {
    $.ajax({
        url: '../' + __s_c_name + '/stopOrderConfirm',
        type: "POST",
        success: function (data) {
            var o_res = null;
            try {
                o_res = $.parseJSON(data);
                if (o_res.state) {
                    $.messager.alert('成功', '自动接单暂停成功!', 'info');
                } else {
                    $.messager.alert('失败', '自动接单暂停失败!', 'info');
                }
            }catch(error){
                $.messager.alert('错误', error.message, 'error');
            }            
            isOrderAutoConfirm();
        }
    });
}
