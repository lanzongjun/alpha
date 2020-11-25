
var toolbar1 = [{
        text: '预导入',
        iconCls: 'icon-add',
        handler: function () {
            $('#br_win_input').window('open');
        }
    }];

var toolbar2 = [{
        text: '差异更新',
        iconCls: 'icon-add',
        handler: function () {
            appendData();
        }
    }, {
        text: '覆盖更新',
        iconCls: 'icon-add',
        handler: function () {
            coverData();
        }
    }];

var v_menu_sidemenu_data = [{
        text: '精确核销',
        iconCls: 'icon-aim',
        state: 'open',
        children: [{
                text: '自动关联',
                fname: 'aim_link'
            }, {
                text: '确认核销',
                fname: 'aim_verify'
            }]
    }, {
        text: '手动核销',
        iconCls: 'icon-man',
        state: 'open',
        children: [{
                text: '确认关联',
                fname: 'cus_link'
            }, {
                text: '确认核销',
                fname: 'cus_verify'
            }]
    }];

$(function () {
    $("#br_dg").datagrid({
        onCheck:dg1OnCheck,
        onUncheck:dg1OnCheck,
        onSelectAll:dg1OnCheckAll,
        onUnselectAll:function(rows){$('#brt1_txt_count').text('0.00');}
    });
    $("#br_dg2").datagrid({
        onCheck:dg2OnCheck,
        onUncheck:dg2OnCheck,
        onSelectAll:dg2OnCheckAll,
        onUnselectAll:function(rows){$('#brt2_txt_count').text('0.00');}
    });
    $('#q_brt1_btn_search').bind('click', function () {
        doSearchList();
    });
    $('#q_brt2_btn_search').bind('click', function () {
        doSearchBalList();
    });
});

function v_sidemenu_handler(item) {
    if (item.fname) {
        if (item.fname === 'aim_link') {
            doAimLink();
        } else if (item.fname === 'aim_verify') {
            doAimVerify();
        } else if (item.fname === 'cus_link') {
            doCusLink();
        } else if (item.fname === 'cus_verify') {
            doCusVerify();
        }
    }
}

function dg1OnCheck(index,row){
    var a_row_br = $("#br_dg").datagrid('getChecked');
    var i_count = 0;
    for (var i=0; i<a_row_br.length; i++){
        i_count += a_row_br[i].brd_balance_amount-0;
    }
    $('#brt1_txt_count').text(i_count.toFixed(2));
}

function dg1OnCheckAll(rows){
    var i_count = 0;
    for (var i=0; i<rows.length; i++){
        i_count += rows[i].brd_balance_amount-0;
    }
    $('#brt1_txt_count').text(i_count.toFixed(2));
}

function dg2OnCheck(index,row){
    var a_row_cpd = $("#br_dg2").datagrid('getChecked');
    var i_count = 0;
    for (var i=0; i<a_row_cpd.length; i++){
        i_count += a_row_cpd[i].cpd_amount-0;
    }
    $('#brt2_txt_count').text(i_count.toFixed(2));
}

function dg2OnCheckAll(rows){
    var i_count = 0;
    for (var i=0; i<rows.length; i++){
        i_count += rows[i].cpd_amount-0;
    }
    $('#brt2_txt_count').text(i_count.toFixed(2));
}

function doSearchList() {
    var s_db = $('#q_brt1_date_begin').val();
    var s_de = $('#q_brt1_date_end').val();
    var s_sid = $('#q_brt1_shop').combobox('getValue');

    $('#br_dg').datagrid('load', {
        s_db: s_db,
        s_de: s_de,
        s_sid: s_sid
    });
}

function doSearchBalList() {
    var s_db = $('#q_brt2_date_begin').val();
    var s_de = $('#q_brt2_date_end').val();
    var s_sid = $('#q_brt2_shop').combobox('getValue');

    $('#br_dg2').datagrid('load', {
        s_db: s_db,
        s_de: s_de,
        s_sid: s_sid
    });
}

function doCusLink(){
    var a_row_br = $("#br_dg").datagrid('getChecked');
    var a_row_cpd = $("#br_dg2").datagrid('getChecked');
    
    if (!checkSameShop(a_row_br,a_row_cpd)){return;}
    var big_row,small_row;
    if (a_row_br.length > a_row_cpd.length) {
        big_row = a_row_br;
        small_row = a_row_cpd;
    } else {
        small_row = a_row_br;
        big_row = a_row_cpd;
    }
    var f_step = big_row.length / small_row.length;
    var i_step = Math.floor(f_step);
    
    //自动平衡分配
    var d_diff = f_step-i_step;
    var d_diff_temp = d_diff / (small_row.length-1);
    var b_is_balance = d_diff+d_diff_temp >= 1;
    i_step = b_is_balance ? i_step+1 : i_step;
    var i_small_index = b_is_balance ? 1 : 0;
    var i_big_index = b_is_balance ? 1 : 0;
    if (b_is_balance) {
        o_data = $.extend({}, big_row[0], small_row[0]);
        big_row[0] = o_data;
    }
    
    //生成核销号
    var i_timestamp = new Date().getTime();
    big_row[0].vr_id = "C_"+i_timestamp;//.toString(16).toUpperCase();//转十六进制
    
    var o_big,o_small,o_data;
    for (var i=i_small_index; i<small_row.length; i++){
        o_big = big_row[i_step*i-i_big_index];
        o_small = small_row[i];
        //合并对象
        o_data = $.extend({}, o_big, o_small);
        o_data.vr_unique = 0;
        big_row[i_step*i-i_big_index] = o_data;
    }
    $("#br_dg3").datagrid('loadData', big_row);
    doCusMergeCells();
}

function checkSameShop(a_row_br, a_row_cpd){
    if (a_row_br.length < 1 || a_row_cpd.length < 1){
        $.messager.alert('错误', '未选择[结算记录]或者[资金流水]', 'error');
        return false;
    }
    var s_org_sn = a_row_br[0].brd_org_sn;
    for (var i=0; i<a_row_br.length; i++){
        if (s_org_sn !== a_row_br[i].brd_org_sn){
            $.messager.alert('错误', '只可选择[同店铺]数据进行核销', 'error');
            return false;
        }
    }
    for (var j=0; j<a_row_cpd.length; j++){
        if (s_org_sn !== a_row_cpd[j].cpd_bs_org_sn) {
            $.messager.alert('错误', '只可选择[同店铺]数据进行核销', 'error');
            return false;
        }
    }
    return true;
}

function doCusMergeCells(){
    var a_cols = ['brd_date_begin','brd_date_end','brd_shop_name',
        'brd_balance_amount','vr_id','brd_org_sn','cpd_date','cpd_shop',
        'cpd_amount','cpd_remaining_sum','cpd_biz_type','cpd_trade_state'];
    var a_rows = $("#br_dg3").datagrid('getRows');
    var o_row;
    var i_row_data = 0;
    
    for (var i = 0; i < a_cols.length; i++) {
        i_row_data = 0;
        for (var j = 1; j < a_rows.length; j++) {
            o_row = a_rows[j];
            if (o_row[a_cols[i]]) {
                //合并上一个单元格
                $("#br_dg3").datagrid("mergeCells", {
                    index: i_row_data,
                    field: a_cols[i], //合并字段
                    rowspan: j - i_row_data,
                    colspan: null
                });
                i_row_data = j;
            } else if (j === a_rows.length - 1) {
                $("#br_dg3").datagrid("mergeCells", {
                    index: i_row_data,
                    field: a_cols[i], //合并字段
                    rowspan: j - i_row_data + 1,
                    colspan: null
                });
            }
        }
    }
}

function doCusVerify() {
    var a_row = $("#br_dg3").datagrid('getRows');
    if (!a_row || a_row.length < 1) {
        $.messager.alert('错误', '请选择要操作的记录', 'error');
        return;
    }
    var b_vr_unique = a_row[0].vr_unique-0 === 1;
    if (b_vr_unique){
        $.messager.alert('错误', '当前列表为精确关联，请使用精确核销进行确认', 'error');
        return;
    }
    $.messager.confirm('确认', '是否对当前选中的 ' + a_row.length + ' 条记录进行核销?', function (r) {
        if (r) {
            var a_cpd = [];
            var a_br = [];
            var s_vr_id = "";
            var o_row = {};
            for (var i = 0; i < a_row.length; i++) {
                o_row = a_row[i];
                if (o_row.vr_id){s_vr_id = o_row.vr_id;}
                if (o_row.cpd_bill_code){a_cpd.push(o_row.cpd_bill_code);}
                if (o_row.brd_id) {a_br.push(o_row.brd_id);}
            }
            var s_cpd = JSON.stringify(a_cpd);
            var s_br = JSON.stringify(a_br);
            $.messager.progress({
                title: 'Please waiting',
                msg: '正在进行手工核销......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/doCusVerify',
                type: "POST",
                data: {'cpd': s_cpd,'br':s_br,'vr':s_vr_id},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    if (o_res.state) {
                        $.messager.alert('信息', o_res.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_res.msg, 'error');
                    }
                    $('#br_dg').datagrid('reload');
                    $('#br_dg2').datagrid('reload');
                }
            });
        }
    });
}

function doAimLink() {
    $.messager.confirm('确认', '系统将对所有未核销的结算记录和资金流水记录进行自动关联，是否继续此操作?', function (r) {
        if (r) {
            $.messager.progress({
                title: 'Please waiting',
                msg: '正在建立精确关联......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/doAutoLink',
                type: "POST",
                data: {'ids': 'a_ids'},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    $("#br_dg3").datagrid('loadData', o_res);
                }
            });
        }
    });
}

function doAimVerify() {
    var a_row = $("#br_dg3").datagrid('getChecked');
    if (!a_row || a_row.length < 1) {
        $.messager.alert('错误', '请选择要操作的记录', 'error');
        return;
    }
    var b_vr_unique = a_row[0].vr_unique-0 === 1;
    if (!b_vr_unique){
        $.messager.alert('错误', '当前列表为手工关联，请使用手工核销进行确认', 'error');
        return;
    }
    $.messager.confirm('确认', '是否对当前选中的 ' + a_row.length + ' 条记录进行核销?', function (r) {
        if (r) {
            var a_data = [];
            var o_data = {};
            var o_row = {};
            for (var i = 0; i < a_row.length; i++) {
                o_row = a_row[i];
                if (!o_row.vr_id || !o_row.cpd_bill_code || !o_row.brd_id) {
                    $.messager.alert('错误', '缺少关键数据', 'error');
                    return;
                }
                o_data = {
                    vr_id: o_row.vr_id,
                    cpd_bill_code: o_row.cpd_bill_code,
                    brd_id: o_row.brd_id
                };
                a_data.push(o_data);
            }
            var s_data = JSON.stringify(a_data);
            $.messager.progress({
                title: 'Please waiting',
                msg: '正在进行精确核销......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/doAimVerify',
                type: "POST",
                data: {'ads': s_data},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    if (o_res.state) {
                        $.messager.alert('信息', o_res.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_res.msg, 'error');
                    }
                    $('#br_dg').datagrid('reload');
                    $('#br_dg2').datagrid('reload');
                }
            });
        }
    });
}
