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

var __b_delete_preview = true;

var BalanceRecord = {};
BalanceRecord.amountFormat = function (value, row, index) {
    if (null === value || value - 0 === 0) {
        return "<span style='color:#333333'>" + value + '</span>';
    }
    return value;
};

$(function () {
    $('#q_brt1_btn_input').bind('click', function () {
        $('#br_win_input').window('open');
    });
    $('#q_brt2_btn_append').bind('click', function () {
        doDaliy2BalList();
    });
    
    $('#q_brt1_btn_search').bind('click', function () {
        doSearchList();
    });
    $('#q_brt2_btn_search').bind('click', function () {
        doSearchBalList();
    });

    $('#br_w_preview').window({
        onOpen: function () {
            $("#br_dg_preview").datagrid({
                rowStyler: function (index, row) {
                    if (row.brd_is_exist - 0 === 1) {
                        return 'background-color:#CCCCCC;color:#333333;';
                    }
                    if (null === row.brd_org_sn || row.brd_org_sn === '') {
                        return 'background-color:' + COLOR_WARNING + ';color:#333333;';
                    }
                }
            });
        },
        onBeforeClose: function () {
            if (__b_delete_preview) {
                deletePreviewData();
            }
        }
    });

    //预导入CSV文件
    $('#br_btn_do_input').bind('click', function () {
        $("#br_form_input").form("submit", {
            type: 'post',
            url: '../' + __s_c_name + '/uploadInfo',
            onSubmit: function () {
                $('#br_win_input').window('close');
                $.messager.progress({
                    title: 'Please waiting',
                    msg: '正在导入数据......'
                });
            },
            success: function (data) {
                var o_response = $.parseJSON(data);
                $.messager.progress('close');
                if (o_response.state === true) {
                    $('#br_hid_preview').val(o_response.tbn);
                    $("#br_dg_preview").datagrid("options").url = '../' + __s_c_name + '/loadPreview/';
                    $('#br_dg_preview').datagrid('load', {
                        tbn: $('#br_hid_preview').val()
                    });
                    __b_delete_preview = true;
                    $('#br_w_preview').window('open');
                }
            }
        });
    });
});

function onBalListLoad(data){
    var a_bal_rows = data.rows;
    var a_rows = $("#br_dg").datagrid('getRows');
    var b_is_exist = false;
    var o_bal_row;
    var o_row;
    for (var i=0; i<a_bal_rows.length; i++) {
        o_bal_row = a_bal_rows[i];
        b_is_exist = false;
        for (var j=0; j<a_rows.length; j++) {
            o_row = a_rows[j];
            if (o_row.brd_date_begin === o_bal_row.ba_balance_date_begin &&
                o_row.brd_date_end === o_bal_row.ba_balance_date_end &&
                o_row.brd_org_sn === o_bal_row.bas_bs_org_sn &&
                o_row.brd_balance_amount === o_bal_row.bas_order_amount){
                b_is_exist = true;
                break;
            }
        }
        if (!b_is_exist){
            $("#br_dg2").datagrid('checkRow',i);
        }
    }
}

function doDaliy2BalList(){    
    var a_bal_rows = $("#br_dg2").datagrid('getChecked');
    var a_ids = [];
    var a_rows = $("#br_dg").datagrid('getRows');
    var o_bal_row,o_row;
    for (var i=0; i<a_bal_rows.length; i++){
        o_bal_row = a_bal_rows[i];
        for (var j=0; j<a_rows.length; j++) {
            o_row = a_rows[j];
            if (o_row.brd_date_begin === o_bal_row.ba_balance_date_begin &&
                o_row.brd_date_end === o_bal_row.ba_balance_date_end &&
                o_row.brd_org_sn === o_bal_row.bas_bs_org_sn){
                var msg = '<div>[开始日期]' + o_bal_row.ba_balance_date_begin+'<div/>'
                        + '<div>[结束日期]' + o_bal_row.ba_balance_date_end+'<div/>'
                        + '<div>[门店]&nbsp;' + o_bal_row.bas_bs_shop_name+'<div/>';
                $.messager.alert('错误', '存在相同的结算记录，请重新选择'+msg, 'error');
                return;
            }
        }
        a_ids[i] = o_bal_row.ck;
    }
    $.messager.confirm('确认', '是否将当前选中的'+a_ids.length
            +'条结算信息更新至正式结算记录中?', function (r) {
        if (r) {
            $.messager.progress({
                title:'Please waiting',
                msg:'正在将选中信息更新至结算记录中......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/doDaliy2BalList',
                type: "POST",
                data: {'ids': a_ids},
                success: function (data) {
                    var o_res = $.parseJSON(data);
                    $.messager.show({
                        title:'更新结果',
                        msg:o_res.msg,
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#br_dg").datagrid('reload');
                }
            });
        }
    });
    
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

function deletePreviewData() {
    var s_tbn = $('#br_hid_preview').val();
    $.ajax({
        url: '../' + __s_c_name + '/deletePreviewData',
        type: "POST",
        data: {'tbn': s_tbn},
        success: function (data) {
            $('#br_hid_preview').val('');
        }
    });
}

function appendData() {
    var s_tbn = $('#br_hid_preview').val();
    if (s_tbn === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将追加至正式数据，是否确认追加导入?', function (r) {
        if (r) {
            var s_tbn = $('#br_hid_preview').val();
            //异步请求
            $.ajax({
                url: '../' + __s_c_name + '/appendData/',
                type: "POST",
                data: {"tbn": s_tbn},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    if (o_response.state === true) {
                        $.messager.alert('成功', '数据导入成功!', 'info');
                    } else {
                        $.messager.alert('失败', '数据导入失败!请重试', 'error');
                    }
                    //清空预览和tbn值
                    __b_delete_preview = false;
                    $("#br_dg_preview").datagrid("loadData", {total: 0, rows: []});
                    $("#br_dg").datagrid("reload");
                    $('#br_w_preview').window('close');
                }
            })
        }
    });
}

function coverData() {
    var s_tbn = $('#br_hid_preview').val();
    if (s_tbn === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将覆盖为正式数据，是否确认覆盖导入?', function (r) {
        if (r) {
            $.messager.confirm('确认', '此操作将清空原有数据，并替换为当前预导入数据，请再次确认此操作?', function (r) {
                if (r) {
                    var s_tbn = $('#br_hid_preview').val();
                    //异步请求
                    $.ajax({
                        url: '../' + __s_c_name + '/coverData/',
                        type: "POST",
                        data: {"tbn": s_tbn},
                        success: function (data) {
                            var o_response = $.parseJSON(data);
                            if (o_response.state === true) {
                                $.messager.alert('成功', '数据覆盖成功!', 'info');
                            } else {
                                $.messager.alert('失败', '数据覆盖失败!请重试', 'error');
                            }
                            //清空预览和tbn值
                            __b_delete_preview = false;
                            $("#br_dg_preview").datagrid("loadData", {total: 0, rows: []});
                            $("#br_dg").datagrid("reload");
                            $('#br_w_preview').window('close');
                        }
                    });
                }
            });
        }
    });
}
