
var __i_mtoir_refresh_rate = 30000;
var __b_mtoir_auto_refresh = false;

function isAppealFormat(value, row, index) {
    if (value === '0') {
        return '否';
    }
    if (value === '1') {
        return '是';
    }
    return '未知';
}

function notifyTypeFormat(value, row, index) {
    if (value === 'apply') {
        return '发起退款';
    }
    if (value === 'agree') {
        return '确认退款';
    }
    if (value === 'reject') {
        return '驳回退款';
    }
    if (value === 'cancelRefund') {
        return '用户取消退款申请';
    }
    if (value === 'cancelRefundComplaint') {
        return '用户取消退款申诉';
    }
    return '未知';
}

function resTypeFormat(value, row, index) {
    if (value === '0') {
        return '等待处理中';
    }
    if (value === '1') {
        return '商家驳回退款请求';
    }
    if (value === '2') {
        return '商家同意退款';
    }
    if (value === '3') {
        return '客服驳回退款请求';
    }
    if (value === '4') {
        return '客服帮商家同意退款';
    }
    if (value === '5') {
        return '超时未处理系统自动同意';
    }
    if (value === '6') {
        return '系统自动确认';
    }
    if (value === '7') {
        return '用户取消退款申请';
    }
    if (value === '8') {
        return '用户取消退款申诉';
    }
    return '未知';
}

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
        }
    });
    $('#btn_refund_agree').bind('click', function () {
        doRefundAgree();
    });
    $('#btn_refund_reject').bind('click', function () {
        doRefundReject();
    });
    $('#btn_pull_phone').bind('click', function () {
        doPullPhone();
    });
    refreshData();
}

function doRefundAgree() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.order_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
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
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
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

function doPullPhone() {
    $.ajax({
        url: '../' + __s_c_name + '/doPullPhone',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
        }
    });
}

function refreshData() {
    $('#dg').datagrid('load');
    __o_mtoir_refresh_handler = setTimeout(refreshData, __i_mtoir_refresh_rate);
}

function loadDetailData(s_code) {
    $("#dg2").datagrid("options").url = '../' + __s_c_name + '/loadDetailData/';
    $('#dg2').datagrid('load', {
        ocode: s_code
    });
}

function loadRefundDetail(s_code) {
    $("#dg3").datagrid("options").url = '../' + __s_c_name + '/loadRefundDetail/';
    $('#dg3').datagrid('load', {
        ocode: s_code
    });
}
