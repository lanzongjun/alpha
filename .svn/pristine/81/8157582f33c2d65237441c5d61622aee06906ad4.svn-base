
var __i_eboir_refresh_rate = 30000;
var __b_eboir_auto_refresh = false;

$(function () {
    init();
});

function init() {
    $("#dg").datagrid({
        onClickRow: function (index, row) {
            var p = $("#layout_room").layout("panel", "south")[0].clientWidth;
            if (p <= 0) {
                $('#layout_room').layout('expand', 'south');
            }
            var p2 = $("#layout_room").layout("panel", "east")[0].clientWidth;
            if (p2 <= 0) {
                $('#layout_room').layout('expand', 'east');
            }
            
            loadDetailData(row.order_id);
            loadRefundDetail(row.order_id);
        },
        rowStyler: function (index, row) {
            if (row.status-0 !== 10) {
                return 'color:#AAAAAA;';
            }
        }
    });
    $('#btn_refund_agree').bind('click', function () {
        doRefundAgree();
    });
    $('#btn_refund_reject').bind('click', function () {
        doRefundReject();
    });
    refreshData();
}

function doRefundAgree() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.order_id) {
        $.messager.alert('错误', '请选择一条记录后，再进行此操作', 'error');
        return;
    }
    if (o_row.status-0 !== 10) {
        $.messager.alert('错误', '当前订单状态不可进行此操作', 'error');
        return ;
    }
    var s_order_id = o_row.order_id;
    $.ajax({
        url: '../' + __s_c_name + '/doRefundAgree',
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

function doRefundReject() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.order_id) {
        $.messager.alert('错误', '请选择一条记录后，再进行此操作', 'error');
        return;
    }
    if (o_row.status-0 !== 10) {
        $.messager.alert('错误', '当前订单状态不可进行此操作', 'error');
        return ;
    }
    var s_order_id = o_row.order_id;
    $.ajax({
        url: '../' + __s_c_name + '/doRefundReject',
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

function refreshData() {
    $('#dg').datagrid('load');
    __o_eboir_refresh_handler = setTimeout(refreshData, __i_eboir_refresh_rate);
}

function loadDetailData(s_code) {
    $("#dg3").datagrid("options").url = '../AdEBOrderInfoC/loadDetailData/';
    $('#dg3').datagrid('load', {
        ocode: s_code
    });
}

function loadRefundDetail(s_code) {
    $("#dg2").datagrid("options").url = '../' + __s_c_name + '/loadRefundDetail/';
    $('#dg2').datagrid('load', {
        ocode: s_code
    });    
}
