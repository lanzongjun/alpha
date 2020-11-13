function goodsSaleFormat(value, row, index) {
    if (value === '1') {
        return '可销售';
    } else if (value === '0') {
        return '不可销售';
    } else if (value === '-1') {
        return '未判断';
    } else {
        return '错误';
    }
}

function goodsStateFormat(value, row, index) {
    if (value === 'NORMAL') {
        return '正常';
    } else if (value === 'DELETE') {
        return '删除';
    } else if (value === 'NEW') {
        return '新增';
    } else {
        return '未知';
    }
}

$(function () {
    $('#btn_search').bind('click', function () {
        doSearch();
    });
    $('#btn_output').bind('click', function () {
        doOutput();
    });
    $('#btn_edit').bind('click', function () {
        doEdit();
    });
    $('#btn_batsale').bind('click', function () {
        doBatSale();
    });
    $("#dg_gi_yj").datagrid({
        onClickRow: function (index, row) {
            loadOfflineInfo(row.bgs_barcode);            
            loadOnlineInfo(row.bgs_barcode);
            loadPriceInfo(row.bgs_barcode);
        }
    });
    $('#f_edit_giyj').form({
        url: '../' + __s_c_name + '/editGoodsInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#w_edit_giyj').window('close');
            $('#dg_gi_yj').datagrid('reload');
        }
    });
    $('#f_bat_sale_giyj').form({
        url: '../' + __s_c_name + '/doChangeCanSale',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#w_bat_sale_giyj').window('close');
            $('#dg_gi_yj').datagrid('reload');
        }
    });
});

function saveBatSale(){
    $('#f_bat_sale_giyj').form('submit');
}

function closeBatSale() {
    $('#f_bat_sale_giyj').window('close');
}

function saveEditForm() {
    $('#f_edit_giyj').form('submit');
}

function closeEditWin() {
    $('#f_edit_giyj').window('close');
}

function doEdit() {
    var o_row = $("#dg_gi_yj").datagrid('getSelected');
    if (!o_row || !o_row.bgs_code) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#w_edit_giyj').window('open');
    $('#f_edit_giyj').form('load', o_row);
}

function doBatSale() {
    var a_rows = $("#dg_gi_yj").datagrid('getChecked');
    if (!a_rows || !a_rows.length) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    var s_ids = a_rows[0].ck;
    for (var i = 1; i < a_rows.length; i++) {
        s_ids += ','+a_rows[i].ck;
    }
    $('#hid_bat_sale_ids').val(s_ids);
    $('#w_bat_sale_giyj').window('open');
}

function loadPriceInfo(s_barcode) {
    $.ajax({
        url: '../' + __s_c_name + '/loadPriceInfo',
        type: "GET",
        data: {bc: s_barcode},
        success: function (data) {
            var o_response = $.parseJSON(data);
            $("#dom_yj_price").text(o_response.bbp_yj_sale_price);
            $("#dom_yj_balance").text(o_response.bbp_settlement_price);
        }
    });
}

function loadOfflineInfo(s_barcode){
    $('#dg_ext_1').datagrid('load', {
        bc: s_barcode
    });
}

function loadOnlineInfo(s_barcode){
    $('#dg_ext_2').datagrid('load', {
        bc: s_barcode
    });
}

function doSearch() {
    var s_gn = $("#q_goods_name").val();
    var s_bc = $("#q_barcode").val();
    var s_dt = $("#q_dispatching").val();
    var s_sale = $("#q_sale").val();
    var s_state = $("#q_state").val();
    $('#dg_gi_yj').datagrid('load', {
        gn: s_gn, bc: s_bc, dt: s_dt, sa: s_sale, ss: s_state
    });
}

function doOutput() {
    var s_gn = $("#q_goods_name").val();
    var s_bc = $("#q_barcode").val();
    var s_dt = $("#q_dispatching").val();
    var s_sale = $("#q_sale").val();
    var s_state = $("#q_state").val();
    $.messager.confirm('确认', '确认要导出当前数据吗?', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/doOutput',
                type: "GET",
                data: {gn: s_gn, bc: s_bc, dt: s_dt, sa: s_sale, ss: s_state},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        var s_html = '';
                        var o_msg = o_response.msg;
                        s_html = "<a href='../."+o_msg.filepath+"' style='color:#0000CD;text-decoration:underline'>"+o_msg.filename+"</a><br/>";
                        $.messager.alert('信息', s_html, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                }
            });
        }
    });
}

function showEditWindow() {
    $("#f_edit_info_giyj").form('load', {
        eod_eoi_code: s_eoi_code
    });
}
