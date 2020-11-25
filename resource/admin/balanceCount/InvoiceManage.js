var InvoiceManage = {};

$(function () {
    $("#im_dg").datagrid("options").url = '../' + __s_c_name + '/getList/';    
    $("#im_dg").datagrid({
        onSelect:InvoiceManage.dgOnCheck,
        onLoadSuccess:InvoiceManage.onIRLoad
    });
    $("#im_dg2").datagrid("options").url = '../' + __s_c_name + '/getBRDList/';    
    $("#im_dg2").datagrid({
        onCheck:InvoiceManage.dg2OnCheck,
        onUncheck:InvoiceManage.dg2OnCheck,
        onCheckAll:InvoiceManage.dg2OnCheckAll,
        onUncheckAll:function(rows){$('#imt2_txt_count').text('0.00');},
        onLoadSuccess:InvoiceManage.onBRDLoad
    });
    $("#im_dg3").datagrid("options").url = '../' + __s_c_name + '/getCPDList/';    
    $("#im_dg3").datagrid({
        onCheck:InvoiceManage.dg3OnCheck,
        onUncheck:InvoiceManage.dg3OnCheck,
        onCheckAll:InvoiceManage.dg3OnCheckAll,
        onUncheckAll:function(rows){$('#imt3_txt_count').text('0.00');},
        onLoadSuccess:InvoiceManage.onCPDLoad
    });
    
    $('#q_imt1_btn_search').bind('click', function () {
        InvoiceManage.doSearchIRList();
    });
    $('#q_imt1_btn_new').bind('click', function () {
        InvoiceManage.showAddWin();
    });    
    $('#q_imt2_btn_search').bind('click', function () {
        $('#w_im_search_dg2').window('open');
        InvoiceManage.doSearchVRList();
    });
    $('#q_imt3_btn_search').bind('click', function () {
        $('#w_im_search_dg2').window('open');
        InvoiceManage.doSearchVRList();
    });
    $('#w_im_add_invoice').window({
       onBeforeClose:function(){ 
           $('#f_im_add_invoice').form('clear')
       }
    });
    $('#q_imt1_btn_link').bind('click', function () {
        InvoiceManage.doLink();
    });
    $('#f_im_add_invoice').form({
        url: '../' + __s_c_name + '/addInvoice',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
            $('#w_im_add_invoice').window('close');
            $('#im_dg').datagrid('reload');
        }
    });
});

InvoiceManage.formatIRVAmount = function(val, row) {
    if (val - 0 === -1) {
        row.ir_verify_amount = 0;
        return "0.00";
    }
    return val;
};

InvoiceManage.formatDiffAmount = function(val, row) {
    var d_diff = row.ir_balance_amount - row.ir_amount;
    d_diff = d_diff.toFixed(2);
    if (d_diff < 0) {
        return '<span style="color:'+COLOR_WARNING+'">'+d_diff+'</span>';
    }
    return d_diff;
};

InvoiceManage.formatVerifyState = function(val, row) {
    if (val - 0 === 1) {
        return "已核销";
    }else if (val - 0 === 0) {
        return "<span style='color:#BBBBBB'>未核销</span>";
    }
    return "未知";
};

InvoiceManage.formatType = function(val, row) {
    if (val - 0 === 1) {
        return "精确";
    }
    if (val - 0 === 0) {
        return "手工";
    }
    return "未知";
};

InvoiceManage.showAddWin = function() {
    $('#w_im_add_invoice').window('open');
};

InvoiceManage.saveAddForm = function() {
    $('#f_im_add_invoice').form('submit');
};

InvoiceManage.closeAddWin = function() {
    $('#w_im_add_invoice').window('close');
};

InvoiceManage.doSearchIRList = function() {
    var s_db = $('#q_imt1_date_begin').val();
    var s_de = $('#q_imt1_date_end').val();
    var s_sid = $('#q_imt1_shop').combobox('getValue');
    var s_d = $('#q_imt1_district').combobox('getValue');

    $('#im_dg').datagrid('load', {
        s_db: s_db,
        s_de: s_de,
        s_sid: s_sid,
        s_d: s_d
    });
    $('#im_dg2').datagrid('load', {
        s_db: s_db,
        s_de: s_de,
        s_sid: s_sid,
        s_d: s_d
    });
    $('#im_dg3').datagrid('load', {
        s_db: s_db,
        s_de: s_de,
        s_sid: s_sid,
        s_d: s_d
    });
};

InvoiceManage.doSearchVRList = function() {
    var s_db = $('#q_imt2_date_begin').val();
    var s_de = $('#q_imt2_date_end').val();
    var s_sid = $('#q_imt2_shop').combobox('getValue');
    var s_d = $('#q_imt2_district').combobox('getValue');

    $('#im_dg2').datagrid('load', {
        s_db: s_db,
        s_de: s_de,
        s_sid: s_sid,
        s_d: s_d
    });
};

InvoiceManage.doLink = function(){
    var o_invoice = $('#im_dg').datagrid('getSelected');
    var a_brd = $('#im_dg2').datagrid('getChecked');
    if (!o_invoice || !o_invoice.ir_no) {
        $.messager.alert('错误', '请选择一条发票信息后，再进行此操作', 'error');
        return;
    }
    if (!a_brd || a_brd.length<1) {
        $.messager.alert('错误', '请选择至少一条结算信息后，再进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '是否对当前选中的发票信息和核销信息进行关联?', function (r) {
        if (r) {
            var a_brd_id = [];
            for (var i=0; i<a_brd.length; i++){
                a_brd_id[i] = a_brd[i].brd_id;
            }
            var s_brd_id = JSON.stringify(a_brd_id);
            $.messager.progress({
                title: 'Please waiting',
                msg: '正在进行关联......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/doInvoiceLink',
                type: "POST",
                data: {'ids': s_brd_id,'irno':o_invoice.ir_no},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    if (o_res.state) {
                        $.messager.alert('信息', o_res.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_res.msg, 'error');
                    }
                    $('#im_dg').datagrid('reload');
                    $('#im_dg2').datagrid('reload');
                }
            });
        }
    });
};

InvoiceManage.onIRLoad = function(data) {
    InvoiceManage.doIRLocalCount();
};

InvoiceManage.onBRDLoad = function(data) {
    var a_rows = $('#im_dg2').datagrid('getRows');
    var i_count = 0;
    for (var i=0; i<a_rows.length; i++){
        i_count += a_rows[i].brd_balance_amount-0;
    }
    $('#imt2_txt_count').text(i_count.toFixed(2));
    $('#imt2_txt_diff').text('0.00');
};

InvoiceManage.onCPDLoad = function(data) {
    var a_rows = $('#im_dg3').datagrid('getRows');
    var i_count = 0;
    for (var i=0; i<a_rows.length; i++){
        i_count += a_rows[i].cpd_amount-0;
    }
    $('#imt3_txt_count').text(i_count.toFixed(2));
    $('#imt3_txt_diff').text('0.00');
};

InvoiceManage.doIRLocalCount = function() {
    var a_rows = $('#im_dg').datagrid('getRows');
    var i_count = 0;
    for (var i=0; i<a_rows.length; i++){
        i_count += a_rows[i].ir_amount-0;
    }
    $('#imt1_txt_count').text(i_count.toFixed(2));
};

InvoiceManage.doBRLocalCount = function(data) {
    var a_rows = $('#im_dg2').datagrid('getRows');
    var i_count = 0;
    for (var i=0; i<a_rows.length; i++){
        i_count += a_rows[i].brd_balance_amount-0;
    }
    $('#imt2_txt_count').text(i_count.toFixed(2));
};

InvoiceManage.dgOnCheck = function(index,row){
    var i_count = row.ir_amount-0;
    $('#imt1_txt_count').text(i_count.toFixed(2));
    InvoiceManage.dg2OnCheck();
    InvoiceManage.dg3OnCheck();
};

InvoiceManage.dg2OnCheck = function(index,row){
    var a_row = $("#im_dg2").datagrid('getChecked');
    var i_count = 0;
    for (var i=0; i<a_row.length; i++){
        i_count += a_row[i].brd_balance_amount-0;
    }
    var d_ir_amount = $('#imt1_txt_count').text();
    var d_diff = i_count-d_ir_amount;
    $('#imt2_txt_count').text(i_count.toFixed(2));
    $('#imt2_txt_diff').text(d_diff.toFixed(2));
};

InvoiceManage.dg2OnCheckAll = function(rows){
    var i_count = 0;
    for (var i=0; i<rows.length; i++){
        i_count += rows[i].brd_balance_amount-0;
    }
    var d_ir_amount = $('#imt1_txt_count').text();
    var d_diff = i_count-d_ir_amount;
    $('#imt2_txt_count').text(i_count.toFixed(2));
    $('#imt2_txt_diff').text(d_diff.toFixed(2));
};

InvoiceManage.dg3OnCheck = function(index,row){
    var a_row = $("#im_dg3").datagrid('getChecked');
    var i_count = 0;
    for (var i=0; i<a_row.length; i++){
        i_count += a_row[i].cpd_amount-0;
    }
    var d_ir_amount = $('#imt1_txt_count').text();
    var d_diff = i_count-d_ir_amount;
    $('#imt3_txt_count').text(i_count.toFixed(2));
    $('#imt3_txt_diff').text(d_diff.toFixed(2));
};

InvoiceManage.dg3OnCheckAll = function(rows){
    var i_count = 0;
    for (var i=0; i<rows.length; i++){
        i_count += rows[i].cpd_amount-0;
    }
    var d_ir_amount = $('#imt1_txt_count').text();
    var d_diff = i_count-d_ir_amount;
    $('#imt3_txt_count').text(i_count.toFixed(2));
    $('#imt3_txt_diff').text(d_diff.toFixed(2));
};