
function formatSn(val, row) {
    return val + '站';
}

function formatEID(val, row) {
    if (val === '0') {
        return '空';
    }
    return val;
}

function formatMID(val, row) {
    if (val === '0') {
        return '空';
    }
    return val;
}

function formatMail(val, row) {
    var reg = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$");
    if (row.bs_e_create_dt > '2019-10-01') {
        if (val === '') {
            return "<span style='color:#CCCC33;font-weight:bold;'>信息缺失!</span>";
        }
    }
    if (row.bs_e_id === '' && row.bs_m_id === '') {
        return val;
    }
    if (!reg.test(val)) {
        return '<a href="javascript:void(0)" class="easyui-tooltip" title="邮件地址不合法" style="color:#CC3333">' + val + '</a>';
    }
    return val;
}

function formatEA(val, row) {
    var s_account = '[账号:' + val + ']';
    var s_password = '[密码:' + row.bs_e_password + ']';
    return '<a href="javascript:void(0)" class="easyui-tooltip" title="' + s_account + s_password + '">详情</a>';
}

function showAddWin() {
    $('#w_add_shop').window('open');
}

function showEditWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.bs_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#w_edit_shop').window('open');
    $('#f_edit_shop').form('load', '../' + __s_c_name + '/getShopInfo?id=' + o_row.bs_id);
}

function saveAddForm() {
    $('#f_add_shop').form('submit');
}

function closeAddWin() {
    $('#w_add_shop').window('close');
}

function saveEditForm() {
    $('#f_edit_shop').form('submit');
}

function closeEditWin() {
    $('#w_edit_shop').window('close');
}

function doSearch() {
    var s_shop = $("#q_shop").val();
    var s_district = $("#q_district").val();
    var s_edt = $("#q_e_delivery_type").val();
    var s_addr = $("#q_addr").val();
    $('#dg').datagrid('load', {
        s: s_shop,
        d: s_district,
        edt: s_edt,
        a: s_addr
    });
}

$(function () {
    $('#btn_add').bind('click', function () {
        showAddWin();
    });
    $('#btn_edit').bind('click', function () {
        showEditWin();
    });
    $('#btn_search').bind('click', function () {
        doSearch();
    });
    $('#sifyj_btn_sync_info').bind('click', function () {
        doSyncInfo();
    });

    $('#f_add_shop').form({
        url: '../' + __s_c_name + '/addShopInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#w_add_shop').window('close');
            $('#dg').datagrid('reload');
        }
    });
    
    $('#f_edit_shop').form({
        url: '../' + __s_c_name + '/editShopInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#w_edit_shop').window('close');
            $('#dg').datagrid('reload');
        }
    });
})

function doSyncInfo() {
    $.messager.confirm('确认', '确认要同步当前店铺信息至石化接口吗?', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/doSyncInfo',
                type: "POST",
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                }
            });
        }
    });
}