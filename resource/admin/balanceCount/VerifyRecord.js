
$(function () {
    $('#q_vrt1_btn_search').bind('click', function () {
        doSearchList();
    });
    $('#q_vrt1_btn_remove').bind('click', function () {
        doRemove();
    });
    $("#vr_dg3").datagrid({
        onClickRow: function (index, row) {
            $('#vr_dg2').datagrid('load', {
                vrid: row.vr_id
            });
            $('#vr_dg1').datagrid('load', {
                vrid: row.vr_id
            });
        }
    });

});

function formatType(val, row) {
    if (val - 0 === 1) {
        return "精确";
    }
    if (val - 0 === 0) {
        return "手工";
    }
    return "未知";
}

function doRemove() {
    var o_row = $("#vr_dg3").datagrid('getSelected');
    if (!o_row || !o_row.vr_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将删除此核销记录，并重置结算记录及资金流水，确定继续此操作吗?', function (r) {
        if (r) {
            $.messager.progress({
                title: 'Please waiting',
                msg: '正在删除核销记录......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/doRemove',
                type: "POST",
                data: {'vrid': o_row.vr_id},
                success: function (data) {
                    $.messager.progress('close');
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $("#vr_dg3").datagrid('reload');
                    $("#vr_dg2").datagrid('reload');
                    $("#vr_dg1").datagrid('reload');
                }
            });
        }
    });
}

function doSearchList() {
    var s_db = $('#q_vrt1_date_begin').val();
    var s_de = $('#q_vrt1_date_end').val();
    var s_sid = $('#q_vrt1_shop').combobox('getValue');
    var s_vrid = $('#q_vrt1_vr_id').val();

    $('#vr_dg3').datagrid('load', {
        s_db: s_db,
        s_de: s_de,
        s_sid: s_sid,
        s_vrid: s_vrid
    });
}

