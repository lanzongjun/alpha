
var BalanceStageSelect = {};

BalanceStageSelect.orderCheckFormat = function(value, row, index) {
    if (row.oi_ba_bat_id-0 === -1) {
        return '未结算';
    } else {
        $("#bss_dg_orders").datagrid('uncheckRow', index);
        return '已结算';
    }
};

BalanceStageSelect.orderStateFormat = function(value, row, index) {
    return OrderStatusFormat(row.oi_order_state_enum);
};

BalanceStageSelect.tb_balance = ['-', {
        text: '选取',
        iconCls: 'icon-add',
        handler: function () {
            BalanceStageSelect.doSelect();
        }        
    }, {
        text: '移除',
        iconCls: 'icon-remove',
        handler: function () {
            $.messager.confirm('确认', '确认移除此订单不进行结算吗?', function (r) {
                if (r) {
                    BalanceStageSelect.bal_doDelete();
                }
            });
        }
    }, '-'/*, {
        text: '结算',
        iconCls: 'icon-sum',
        handler: function () {
            BalanceStageSelect.doBalance();
        }
    }, '-'*/];

BalanceStageSelect.init = function () {
    $('#bss_btn_search').bind('click', function () {
        BalanceStageSelect.doSearch();
    });
    $('#bss_btn_pull_order').bind('click', function () {
        BalanceStageSelect.doPullOrder();
    });
    $('#btn_select').bind('click', function () {
        BalanceStageSelect.doSelect();
    });
    $("#bss_dg_orders").datagrid({
        onLoadSuccess: function (data) {
            if (data) {
                $.each(data.rows, function (index, row) {
                    if (row.oi_order_state_enum === ENUM_ORDER_STATUS_COMPLETE || 
                            row.oi_order_state_enum === ENUM_ORDER_STATUS_PART_REFUND) {
                        $("#bss_dg_orders").datagrid('checkRow', index);
                        if (row.oi_ba_bat_id - 0 !== 0) {
                            $("#bss_dg_orders").datagrid('uncheckRow', index);
                        }
                    } else {
                        $("#bss_dg_orders").datagrid('uncheckRow', index);
                    }
                });
            }
        },
        rowStyler: function (index, row) {
            if (row.oi_order_state_enum === 'CANCEL') {
                return 'background-color:#DDDDDD;color:#AAAAAA;';
            }
        }
    });
    if (__bss_bat_id === '') {
        BalanceStageSelect.initSearch();
    } else {
        BalanceStageSelect.loadSelectedInfo();
        BalanceStageSelect.loadSearchInfo();
    }
};

BalanceStageSelect.loadSearchInfo = function () {
    $.ajax({
        url: '../' + __bss_c_name + '/getSearchInfo',
        type: "POST",
        data: {"batid":__bss_bat_id},
        success: function (data) {
            var o_res = $.parseJSON(data);
            if (o_res.state) {
                $("#bss_q_date_begin").datebox('setValue', o_res.date_begin);
                $("#bss_q_date_end").datebox('setValue', o_res.date_end);
                BalanceStageSelect.doSearch();
            }
        }
    });
};
BalanceStageSelect.loadSelectedInfo = function (){
    $("#bss_dg_balance").datagrid("options").url = '../' + __bss_c_name + '/getSelectedList/';
    $('#bss_dg_balance').datagrid('load', {
        batid: __bss_bat_id
    });
};

BalanceStageSelect.initSearch = function () {
    var now = new Date();
    var yesterday = myformatter(new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1));
    $("#bss_q_date_begin").datebox('setValue', yesterday);
    $("#bss_q_date_end").datebox('setValue', yesterday);
    BalanceStageSelect.doSearch();
}

BalanceStageSelect.bal_doDelete = function () {
    var o_row = $("#bss_dg_balance").datagrid('getSelected');
    var a_rows = $("#bss_dg_balance").datagrid('getRows');
    for (var i = 0; i < a_rows.length; i++) {
        if (a_rows[i].boi_code === o_row.boi_code) {
            $("#bss_dg_balance").datagrid('deleteRow', i);
            break;
        }
    }
};

BalanceStageSelect.doSelect = function () {
    var a_rows = $("#bss_dg_orders").datagrid('getChecked');
    var a_rows_balance = $("#bss_dg_balance").datagrid('getRows');
    var b_is_same = false;
    
    for (var i = 0; i < a_rows.length; i++) {
        if (a_rows[i].oi_order_state_enum === ENUM_ORDER_STATUS_CANCEL){
            continue;
        }
        if (a_rows[i].oi_order_state_enum !== ENUM_ORDER_STATUS_PART_REFUND 
                && a_rows[i].oi_order_state_enum !== ENUM_ORDER_STATUS_COMPLETE) {
            $.messager.alert('错误', '将要选取的订单中存在异常订单，请重新选择', 'error');
            return;
        }
        
        for (var j = 0; j < a_rows_balance.length; j++) {
            
            if (a_rows[i].oi_code === a_rows_balance[j].boi_code) {
                b_is_same = true;
                break;
            }
        }
        if (b_is_same) {
            b_is_same = false;
            //$.messager.alert('错误', '存在相同订单', 'error');
            continue;
        }
        $('#bss_dg_balance').datagrid('appendRow', {
            boi_code:a_rows[i].oi_code,
            boi_platform: a_rows[i].oi_platform,
            boi_create_date: a_rows[i].oi_create_date,
            boi_create_time: a_rows[i].oi_create_time,
            boi_shop_name: a_rows[i].oi_shop_name,
            boi_total_fee: a_rows[i].oi_total_fee,
            boi_order_state_enum:a_rows[i].oi_order_state_enum,
            boi_ba_bat_id : a_rows[i].oi_ba_bat_id
        });
    }
};

BalanceStageSelect.doPullOrder = function (){
    var s_db = $("#bss_q_date_begin").val();
    var s_de = $("#bss_q_date_end").val();
    var s_f = $("#bss_q_from").val();
    if (s_db === '' || s_de === '') {
        $.messager.alert('错误', '起止日期不可为空', 'error');
        return;
    }
    if (s_f === '') {
        $.messager.alert('错误', '订单来源不可为空', 'error');
        return;
    }
    if (s_f === 'ALL') {
        $.messager.alert('错误', '订单来源不可为所有', 'error');
        return;
    }
    $.messager.progress({
        title:'Please waiting',
        msg:'正在拉取订单......'
    });
    $.ajax({
        url: '../' + __bss_c_name + '/doPullOrder',
        type: "GET",
        data: {"db": s_db, "de": s_de, "f": s_f},
        success: function (data) {
            $.messager.progress('close');
            var o_res = $.parseJSON(data);
            var i_suc = o_res.suc;
            var i_fail = o_res.fail;
            var b_result = o_res.result;
            var s_html = "<div>成功拉取:"+i_suc+"</div>";
            s_html += "<div>已存在:"+i_fail+"</div>";
            var s_title = b_result ? '成功' : '失败';
            var s_type = b_result ? 'info' : 'error';
            $.messager.alert(s_title, s_html, s_type);
            BalanceStageSelect.doSearch();
        }
    });
};

BalanceStageSelect.doSearch = function () {
    var s_db = $("#bss_q_date_begin").val();
    var s_de = $("#bss_q_date_end").val();
    var s_bs = $("#bss_q_b_state").val();
    var s_f = $("#bss_q_from").val();
    if (s_db === '' || s_de === '') {
        $.messager.alert('错误', '起止日期不可为空', 'error');
        return;
    }
    $('#bss_dg_orders').datagrid('load', {
        db: s_db,
        de: s_de,
        bs:s_bs, 
        f: s_f
    });
};

BalanceStageSelect.doBalance = function () {
    var a_rows = $('#bss_dg_balance').datagrid('getRows');
    
    var a_codes = [];
    if (a_rows.length === 0) {
        $.messager.alert('错误', '结算目标为空', 'error');
        return;
    }

    var s_date_begin = a_rows[0].boi_create_date;
    var s_date_end = a_rows[0].boi_create_date;
    for (var i=0; i<a_rows.length; i++) {
        //如果存在已经结算过的订单，则退出
        if (__bss_bat_id === '' && a_rows[i].boi_ba_bat_id-0 > 0) {
            $.messager.alert('错误1', '订单['+a_rows[i].boi_code+']为已结算订单，请重新选择', 'error');
            return;
        }
        if ( a_rows[i].boi_ba_bat_id-0 > 0 
                && __bss_bat_id-0 !== a_rows[i].boi_ba_bat_id-0){
            $.messager.alert('错误2', '订单['+a_rows[i].boi_code+']为已结算订单，请重新选择', 'error');
            return;
        }
        //计算待结算订单起止日期
        if (a_rows[i].boi_create_date < s_date_begin) {
            s_date_begin = a_rows[i].boi_create_date;
        } else if (a_rows[i].boi_create_date > s_date_end) {
            s_date_end = a_rows[i].boi_create_date;
        }
    }
    
    for (var i = 0; i < a_rows.length; i++) {
        a_codes.push(a_rows[i].boi_code);
    }
    
    $.messager.confirm('确认', '确认要对' + s_date_begin + '至' + s_date_end + '的' + a_codes.length + '个订单进行结算吗?', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __bss_c_name + '/doSelected',
                type: "POST",
                data: {'db':s_date_begin,'de':s_date_end,'codes': a_codes,'batid':__bss_bat_id},
                success: function (data) {
                    ajaxLoadEnd();
                    $('#dg').datagrid('reload');
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.show({
                            title:'信息',
                            msg:'订单选定结果:'+o_response.msg,
                            timeout:2000,
                            showType:'slide'
                        });
                        __bss_bat_id = o_response.bat_id;
//                        $.messager.confirm('提示', '选定操作完成，可以进入下一阶段，是否继续？', function(r){
//                            if (r){
//                                //下一阶段
//                                BalanceStageSelect.toNextStage();
//                            }
//                        });
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                }
            });
        }
    });
};

BalanceStageSelect.toNextStage = function (){
    //是否已经选中
    $.ajax({
        url: '../AdBalanceAccountC/getStageTime',
        type: "POST",
        data: {'batid':__bss_bat_id},
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state){
                if (null === o_response.data.ba_stage1_time) {
                    $.messager.confirm('提示', '当前阶段尚未进行选定操作，是否先进行选定操作？', function(r){
                        if (r){
                            BalanceStageSelect.doBalance();
                        }
                    });
                } else {
                    BalanceAccount.StageConfirm(__bss_bat_id);
                    $('#ba_w_select').window('close');
                }
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }            
        }
    });
};
