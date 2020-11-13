
BalanceStageConfirm = {};

BalanceStageConfirm.init = function () {
    if (!__bsc_bat_id || __bsc_bat_id === '') {
        $.messager.confirm('错误', '批处理流水号缺失，请重新打开', function (r) {
            $('#ba_w_confirm').window('close');
            return;
        });
    }
    
    $('#bsc_dg_orders').datagrid({
        onClickRow: function (index, row) {
            BalanceStageConfirm.loadOrderDetail(row.oi_code);
        },
        rowStyler: function (index, row) {
            if (row.oi_order_state_enum === ENUM_ORDER_STATUS_PART_REFUND) {
                return 'background-color:#333333;color:#AAAAAA;';
            }
        }
    });
    $('#bsc_dg_orders').datagrid("options").url = '../' + __bsc_c_name + '/getOrderList/';
    $('#bsc_dg_orders').datagrid('load', {
        batid: __bsc_bat_id
    });

    $('#bsc_dg_bal_orders').datagrid({
        onClickRow: function (index, row) {
            BalanceStageConfirm.loadBalOrderDetail(row.boi_code);
        },
        rowStyler: function (index, row) {
            if (row.boi_order_state_enum === ENUM_ORDER_STATUS_PART_REFUND) {
                return 'background-color:#333333;color:#AAAAAA;';
            }
        }
    });
    $('#bsc_dg_bal_orders').datagrid("options").url = '../' + __bsc_c_name + '/getBalOrderList/';
    $('#bsc_dg_bal_orders').datagrid('load', {
        batid: __bsc_bat_id
    });
};

BalanceStageConfirm.fmtState = function (val, row) {
    return OrderStatusFormat(row.oi_order_state_enum);
};

BalanceStageConfirm.fmtBState = function (val, row) {
    return OrderStatusFormat(row.boi_order_state_enum);
};

BalanceStageConfirm.PRCount = function (val, row) {
    if (row.ord_count !== null) {
        return val + '<span style="color:#FF0000">-' + row.ord_count + '</span>';
    } else {
        return val;
    }
};

BalanceStageConfirm.EditPRCount = function (val, row) {
    if (row.bord_count !== null) {
        return val + '<span style="color:#FF0000">-' + row.bord_count + '</span>';
    } else {
        return val;
    }
};

BalanceStageConfirm.BPRCount = function (val, row) {
    if (row.bord_count !== null) {
        var i_c1 = val - 0;
        var i_c2 = row.bord_count - 0;
        return '<span style="color:#FF0000">' + (i_c1 - i_c2) + '</span>';
    } else {
        return val;
    }
};

BalanceStageConfirm.editBalOrder = function (val, row) {
    return '<button onclick="BalanceStageConfirm.showEditWin(\'' + val + '\')">编辑</button>';
};

BalanceStageConfirm.showEditWin = function (order_id) {
    $('#bsc_w_edit').window({
        onOpen: function () {
            BalanceStageConfirm.loadBalOrderInfoForm(order_id);
        },
        onClose: function () {
            $('#bsc_dg_bal_detail').datagrid('reload');
        }
    });
    $('#bsc_w_edit').window('open');
    $("#bsc_w_dg").datagrid("options").url = '../' + __bsc_c_name + '/getBalOrderDetailList/';
    $("#bsc_w_dg").datagrid({
        onOpen: function () {
            $('#bsc_w_dg').datagrid('load', {
                id: order_id
            });
        }
    });
};

BalanceStageConfirm.editWinMenu = [{
        text: '新增',
        iconCls: 'icon-add',
        handler: function () {
            BalanceStageConfirm.addOrderDetail();
        }
    }, {
        text: '修改',
        iconCls: 'icon-edit',
        handler: function () {
            BalanceStageConfirm.editOrderDetail();
        }
    }, {
        text: '删除',
        iconCls: 'icon-remove',
        handler: function () {
            BalanceStageConfirm.delOrderDetail();
        }
    }];

BalanceStageConfirm.addOrderDetail = function () {
    var pv = $('#bsc_fe_platform').val();
    var sv = $('#bsc_fe_ss').val();
    var order_id = $('#bsc_fe_order_id').val();
    $('#bsc_w_detail_add').window({
        onOpen: function () {
            $('#bsc_f_detail_add').form('load', {
                boi_code: order_id,
                boi_platform: pv,
                boi_shop_id: sv,
                boi_ba_bat_id:__bsc_bat_id
            });
        },
        onClose: function () {
            $('#bsc_f_detail_add').form('clear');
        }
    });
    $('#bsc_w_detail_add').window('open');
};

BalanceStageConfirm.getGoodsNameByBarcode = function(newValue, oldValue){
    var pv = $('#bsc_fe_platform').val();
    var sv = $('#bsc_fe_ss').val();
    
    $.ajax({
        url: '../' + __bsc_c_name + '/getGoodsNameByBarcode',
        type: "POST",
        data: {'p':pv,'s':sv,"v": newValue},
        success: function (data) {
            if (data !== ''){
                var o_response = $.parseJSON(data);
                $('#bsc_dfa_goods_name').textbox('setValue',o_response.text);
            }
        }
    });
}

BalanceStageConfirm.doAddOrderDetail = function () {
    var i_count = $('#bsc_wda_count').val();
    if (i_count - 0 < 1) {
        $.messager.alert('错误', '数量必须为正数', 'error');
        return;
    }
    $('#bsc_f_detail_add').form({
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
                $("#bsc_w_dg").datagrid('reload');
                $('#bsc_w_detail_add').window('close');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
        }
    });
    $('#bsc_f_detail_add').form('submit');
};

BalanceStageConfirm.cancelAddOrderDetail = function () {
    $('#bsc_w_detail_add').window('close');
};

BalanceStageConfirm.editOrderDetail = function () {
    var o_row = $("#bsc_w_dg").datagrid('getSelected');
    if (!o_row || !o_row.bod_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }    
    var s_bod_id = o_row.bod_id;
    var s_bod_name = o_row.bod_name;
    var s_bod_count = o_row.bod_count - (o_row.bord_count !== null ? o_row.bord_count-0 : 0);
    var s_bod_modify_memo = o_row.bod_modify_memo;
    $('#bsc_w_detail_edit').window({
        onOpen: function () {
            $('#bsc_f_detail_edit').form('load', {
                bod_id: s_bod_id,
                bod_name: s_bod_name,
                bod_count: s_bod_count,
                bod_modify_memo: s_bod_modify_memo
            });
        },
        onClose: function () {
            $('#bsc_f_detail_edit').form('clear');
        }
    });
    $('#bsc_w_detail_edit').window('open');
};

BalanceStageConfirm.doEditOrderDetail = function () {
    var i_count = $('#bsc_wde_bod_count').val();
    if (i_count - 0 < 1) {
        $.messager.alert('错误', '数量必须为正数', 'error');
        return;
    }
    $('#bsc_f_detail_edit').form({
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
                $("#bsc_w_dg").datagrid('reload');
                $('#bsc_w_detail_edit').window('close');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
        }
    });
    $('#bsc_f_detail_edit').form('submit');
};

BalanceStageConfirm.cancelEditOrderDetail = function () {
    $('#bsc_w_detail_edit').window('close');
};

BalanceStageConfirm.delOrderDetail = function () {
    var o_row = $("#bsc_w_dg").datagrid('getSelected');
    if (!o_row || !o_row.bod_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    var s_bod_id = o_row.bod_id;
    var s_bod_name = o_row.bod_name;
    var s_bod_count = o_row.bod_count - 0;
    var s_bod_modify_memo = o_row.bod_modify_memo;
    $('#bsc_w_detail_del').window({
        onOpen: function () {
            $('#bsc_f_detail_del').form('load', {
                bod_id: s_bod_id,
                bod_name: s_bod_name,
                bod_count: s_bod_count
            });
        },
        onClose: function () {
            $('#bsc_f_detail_del').form('clear');
        }
    });
    $('#bsc_w_detail_del').window('open');
};

BalanceStageConfirm.doDelOrderDetail = function () {
    $('#bsc_f_detail_del').form({
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
                $("#bsc_w_dg").datagrid('reload');
                $('#bsc_w_detail_del').window('close');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
        }
    });
    $('#bsc_f_detail_del').form('submit');
};

BalanceStageConfirm.cancelDelOrderDetail = function () {
    $('#bsc_w_detail_del').window('close');
};

BalanceStageConfirm.loadBalOrderInfoForm = function (order_id) {
    $.ajax({
        url: '../' + __bsc_c_name + '/getBalOrderInfo',
        type: "GET",
        data: {"oid": order_id},
        success: function (data) {
            var o_response = $.parseJSON(data);
            $('#bsc_f_edit').form('load', o_response);
        }
    });
};

BalanceStageConfirm.loadOrderDetail = function (s_code) {
    $("#bsc_dg_detail").datagrid("options").url = '../' + __bsc_c_name + '/getOrderDetail/';
    $('#bsc_dg_detail').datagrid('load', {
        id: s_code
    });
};

BalanceStageConfirm.loadBalOrderDetail = function (s_code) {
    $("#bsc_dg_bal_detail").datagrid("options").url = '../' + __bsc_c_name + '/getBalOrderDetailList/';
    $('#bsc_dg_bal_detail').datagrid('load', {
        id: s_code
    });
};

BalanceStageConfirm.doConfirm = function () {
    $.messager.confirm('确认', '此操作将根据当前待结算订单的状态进行重新保存，是否继续?', function (r) {
        if (r) {
            $.messager.confirm('确认', '请再次确认当前待结算订单已经为最终状态?', function (r) {
                if (r) {
                    ajaxLoading();
                    $.ajax({
                        url: '../' + __bsc_c_name + '/doConfirm',
                        type: "POST",
                        data: {"batid": __bsc_bat_id},
                        success: function (data) {
                            ajaxLoadEnd();
                            var o_response = $.parseJSON(data);
                            if (o_response.state) {
                                $.messager.show({
                                    title:'信息',
                                    msg:o_response.msg,
                                    timeout:2000,
                                    showType:'slide'
                                });
//                                $.messager.confirm('提示', '确认操作完成，可以进入下一阶段，是否继续？', function(r){
//                                    if (r){
//                                        //下一阶段
//                                        BalanceStageConfirm.toNextStage();
//                                    }
//                                });
                            } else {
                                $.messager.alert('错误', o_response.msg, 'error');
                            }
                        }
                    });
                }
            });
        }
    });
};

BalanceStageConfirm.toNextStage = function (){
    //是否已经确认
    $.ajax({
        url: '../AdBalanceAccountC/getStageTime',
        type: "POST",
        data: {'batid':__bsc_bat_id},
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state){
                if (null === o_response.data.ba_stage2_time) {
                    $.messager.confirm('提示', '当前阶段尚未进行确认操作，是否先进行确认操作？', function(r){
                        if (r){
                            BalanceStageConfirm.doConfirm();
                        }
                    });
                } else {
                    BalanceAccount.StageCollect(__bsc_bat_id);
                    $('#ba_w_confirm').window('close');
                }
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }            
        }
    });    
};
