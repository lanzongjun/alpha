var BalanceAccount = {};

var ENUM_ORDER_STATUS_CONFIRM = 'CONFIRM';
var ENUM_ORDER_STATUS_CANCEL = 'CANCEL';
var ENUM_ORDER_STATUS_PART_REFUND = 'PART_REFUND';
var ENUM_ORDER_STATUS_COMPLETE = 'COMPLETE';
var ENUM_ORDER_STATUS_OTHER = 'OTHER';
var ENUM_ORDER_STATUS_UNKNOW = 'UNKNOW';


function OrderStatusFormat(enum_order_state) {
    if (enum_order_state === ENUM_ORDER_STATUS_CONFIRM) {
        return '确认';
    }
    if (enum_order_state === ENUM_ORDER_STATUS_CANCEL) {
        return '取消';
    }
    if (enum_order_state === ENUM_ORDER_STATUS_PART_REFUND) {
        return '部分退款';
    }
    if (enum_order_state === ENUM_ORDER_STATUS_COMPLETE) {
        return '完成';
    }
    if (enum_order_state === ENUM_ORDER_STATUS_OTHER) {
        return '其他';
    }
    if (enum_order_state === ENUM_ORDER_STATUS_UNKNOW) {
        return '未知';
    }
}

$(function () {
    BalanceAccount.init();
});

BalanceAccount.init = function () {
    $('#ba_btn_balance').bind('click', function () {
        BalanceAccount.StageSelect('');
    });

    $('#ba_btn_balance_remove').bind('click', function () {
        BalanceAccount.removeBalance();
    });

    $("#ba_dg").datagrid({
        onClickRow: function (index, row) { //easyui封装好的时间（被单机行的索引，被单击行的值）
//            loadOnSaleAB(row.ba_id);
//            loadOnSaleYJ(row.ba_id);
//            loadDelay(row.ba_id);
//            loadErr(row.ba_id);
        }
    });
};

BalanceAccount.removeBalance = function () {
    var o_row = $("#ba_dg").datagrid('getSelected');
    if (!o_row) {
        $.messager.alert('错误', '请选择要操作的记录', 'error');
        return;
    }
    $.messager.confirm('重要', '此操作将会级联删除所有相关结算数据，是否确认继续此操作？', function (r) {
        if (r) {
            $.messager.confirm('重要', '此操作为不可逆操作，请再次确认要继续此操作？', function (r) {
                if (r) {
                    BalanceAccount.doRemoveBalance();
                }
            });
        }
    });
};

BalanceAccount.doRemoveBalance = function () {
    ajaxLoading();
    var o_row = $("#ba_dg").datagrid('getSelected');
    $.ajax({
        url: '../' + __s_c_name + '/doDelBalance',
        type: "POST",
        data: {"baid": o_row.ba_id, "batid": o_row.ba_bat_id},
        success: function (data) {
            ajaxLoadEnd();
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
            $("#ba_dg").datagrid('reload');
        }
    });
};

BalanceAccount.formatS1 = function (val, row) {
//    if (null === val) {
//        return '未完成';
//    } else {
        return '<a href="#"><img onclick="BalanceAccount.StageSelect(\'' + row.ba_bat_id + '\')" border="0" src="../../resource/admin/themes/icons/view.png"/></a>';
//    }
};

BalanceAccount.formatS2 = function (val, row) {
    if (null === val) {
        return '未完成';
    } else {
        return '<a href="#"><img onclick="BalanceAccount.StageConfirm(\'' + row.ba_bat_id + '\')" border="0" src="../../resource/admin/themes/icons/view.png"/></a>';
    }
};

BalanceAccount.formatS3 = function (val, row) {
    if (null === val) {
        return '未完成';
    } else {
        return '<a href="#"><img onclick="BalanceAccount.StageCollect(\'' + row.ba_bat_id + '\')" border="0" src="../../resource/admin/themes/icons/view.png"/></a>';
    }
};

BalanceAccount.formatS4 = function (val, row) {
    if (null === val) {
        return '未完成';
    } else {
        return '<a href="#"><img onclick="BalanceAccount.StageExport(\'' + row.ba_bat_id + '\')" border="0" src="../../resource/admin/themes/icons/view.png"/></a>';
    }
};

//选取
BalanceAccount.StageSelect = function (i_bat_id) {
    $('#ba_w_select').window({
        title: '阶段1：拉取/选定',
        modal: true,
        closed: true,
        iconCls: 'icon-add',
        width: 1280,
        height: 600,
        onOpen: function () {
            $('#ba_p_select').panel({
                cache: false,
                queryParams: {
                    bid: i_bat_id
                },
                href: '../AdBalanceStageSelectC/',
                onLoad: function () {
                    BalanceStageSelect.init();
                }
            });
        },
        onClose: function () {
            $("#ba_dg").datagrid('reload');
        }
    });
    
    $('#ba_w_select').window('open');
};

//调整确认
BalanceAccount.StageConfirm = function (i_bat_id) {
    $('#ba_w_confirm').window({
        title: '阶段2：调整/确认',
        modal: true,
        closed: true,
        iconCls: 'icon-edit',
        width: 1280,
        height: 600,
        onOpen: function () {
            $('#ba_p_confirm').panel({
                cache: false,
                queryParams: {
                    bid: i_bat_id
                },
                href: '../AdBalanceStageConfirmC/',
                onLoad: function () {
                    BalanceStageConfirm.init();
                }
            });
        },
        onClose: function () {
            $("#ba_dg").datagrid('reload');
        }
    });

    $('#ba_w_confirm').window('open');
};

//汇总
BalanceAccount.StageCollect = function (i_bat_id) {
    $('#ba_w_collect').window({
        title: '阶段3：汇总/统计',
        modal: true,
        closed: true,
        iconCls: 'icon-edit',
        width: 1280,
        height: 600,
        onOpen: function () {
            $('#ba_p_collect').panel({
                cache: false,
                queryParams: {
                    bid: i_bat_id
                },
                href: '../AdBalanceStageCollectC/',
                onLoad: function () {
                    BalanceStageCollect.init();
                }
            });
        },
        onClose: function () {
            $("#ba_dg").datagrid('reload');
        }
    });

    $('#ba_w_collect').window('open');
};

//导出
BalanceAccount.StageExport = function (i_bat_id) {
    $('#ba_w_export').window({
        title: '阶段4：导出/发送',
        modal: true,
        closed: true,
        iconCls: 'icon-edit',
        width: 1280,
        height: 600,
        onOpen: function () {
            $('#ba_p_export').panel({
                cache: false,
                queryParams: {
                    bid: i_bat_id
                },
                href: '../AdBalanceStageExportC/',
                onLoad: function () {
                    BalanceStageExport.init();
                }
            });
        },
        onClose: function () {
            $("#ba_dg").datagrid('reload');
        }
    });

    $('#ba_w_export').window('open');
};