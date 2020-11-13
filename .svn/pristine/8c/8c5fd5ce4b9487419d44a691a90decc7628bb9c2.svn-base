
var BalanceStageExport = {};

BalanceStageExport.tb_shops = ['-',{
        text: '批量生成',
        iconCls: 'icon-play',
        handler: function () {
            BalanceStageExport.ExportAllShops();
        }
    }, '-',{
        text: '打包下载',
        iconCls: 'icon-down',
        handler: function () {
            BalanceStageExport.ExportZipPackage();
        }
    }, '-',{
        text: '导出总结算表',
        iconCls: 'icon-down',
        handler: function () {
            $.ajax({
                url: '../AdBalanceStageExportC/downloadGoods',
                type: "POST",
                data: {"batid":__bse_bat_id},
                success: function (data) {
                    var a = document.createElement('a');
                    a.href = '../../'+data;
                    $("body").append(a);  // 修复firefox中无法触发click
                    a.click();
                    $(a).remove();
                }
            });
        }
    }, '-'];

BalanceStageExport.init = function () {
    if (!__bse_bat_id || __bse_bat_id === '') {
        $.messager.confirm('错误', '批处理流水号缺失，请重新打开', function (r) {
            $('#ba_w_export').window('close');
            return;
        });
    }
    $('#bse_dg_shop').datagrid({
        onClickRow: function (index, row) {
        },
        rowStyler: function (index, row) {
        }
    });
    $('#bse_dg_shop').datagrid("options").url = '../' + __bse_c_name + '/getCollectShops/';
    $('#bse_dg_shop').datagrid('load', {
        batid: __bse_bat_id
    });
    $('#bse_dg_goods').datagrid("options").url = '../' + __bse_c_name + '/getCollectGoods/';
    $('#bse_dg_goods').datagrid('load', {
        batid: __bse_bat_id
    });
};

BalanceStageExport.FormatSendState = function(val, row) {
    if (val === 'todo') {
        return '<span style="color:#55CC55"><b>未发</b></span>';
    }
    if (val === 'success') {
        return '<span style="color:#FFFFFF"><b>成功</b></span>';
    }
    if (val === 'fail') {
        return '<span style="color:#CC5555"><b>失败</b></span>';
    }
    return '未知';
};

BalanceStageExport.FormatExcel = function(val, row) {
    if (val === null  || val === '') {
//        return '<button onclick="BalanceStageExport.ExportShops(\''+
//                row.bas_bs_org_sn+'\')">生成</button>';
        return '<a href="../../'+val+'"><img border="0" src="../../resource/admin/themes/icons/hammer.png" /></a>';
    } else {
        return '<a href="../../'+val+'"><img border="0" src="../../resource/admin/themes/icons/down.png" /></a>';
    }
    return '未知';
};

BalanceStageExport.ExportShops = function (s_shop_id) {
    $.ajax({
        url: '../AdBalanceStageExportC/exportCollect',
        type: "POST",
        data: {"batid":__bse_bat_id,'sid':s_shop_id},
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
                $('#bse_dg_shop').datagrid('reload');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
        }
    });
};
BalanceStageExport.ExportZipPackage = function (){
    var a_rows = $("#bse_dg_shop").datagrid('getChecked');
    if (null === a_rows || a_rows.length === 0){
        $.messager.alert('错误', '未选择任何记录', 'error');
        return;
    }
    var a_ids = [];
    for (var i=0; i<a_rows.length; i++) {
        if (null === a_rows[i].bas_file_path || a_rows[i].bas_file_path === ''){
            $.messager.alert('错误', a_rows[i].bas_bs_shop_name+' 未进行表格生成', 'error');
            return;
        }
        a_ids[i] = a_rows[i].bas_id;
    }
    var s_ids = JSON.stringify(a_ids);
    var s_url = 'AdBalanceStageExportC/exportZipPackage?ids='+s_ids;
    var a = document.createElement('a');
    a.href = '../'+s_url;
    $("body").append(a);  
    a.click();
    $(a).remove();    
};

BalanceStageExport.ExportShopsID = [];
BalanceStageExport.ExportShopsIndex = 0;

BalanceStageExport.ExportAllShops = function () {
    BalanceStageExport.ExportShopsID = [];
    BalanceStageExport.ExportShopsIndex = 0;
    var a_rows = $("#bse_dg_shop").datagrid('getChecked');
    if (null === a_rows || a_rows.length === 0){
        $.messager.alert('错误', '未选择任何记录', 'error');
        return;
    }
    for (var i=0; i<a_rows.length; i++) {
        BalanceStageExport.ExportShopsID[i] = {};
        BalanceStageExport.ExportShopsID[i].id = a_rows[i].bas_bs_org_sn;
        BalanceStageExport.ExportShopsID[i].title = a_rows[i].bas_bs_shop_name;
    }
    BalanceStageExport._ExportAllShop();
};

BalanceStageExport._ExportAllShop = function () {
    var s_shop_id = BalanceStageExport.ExportShopsID[
        BalanceStageExport.ExportShopsIndex].id;
    var s_shop_name = BalanceStageExport.ExportShopsID[
                    BalanceStageExport.ExportShopsIndex].title;
    $.messager.progress({
        title:'Please waiting',
        msg:'正在生成['+s_shop_name+']表格文件......'
    });
    $.ajax({
        url: '../AdBalanceStageExportC/exportCollect',
        type: "POST",
        data: {"batid":__bse_bat_id,'sid':s_shop_id},
        success: function (data) {
            $.messager.progress('close');
            var o_response = $.parseJSON(data);
            var s_msg = s_shop_name + ':' + o_response.msg;
            $.messager.show({
                title:o_response.state?'信息':'错误',
                msg:s_msg,
                timeout:2000,
                showType:'slide'
            });
            BalanceStageExport.ExportShopsIndex++;
            if (BalanceStageExport.ExportShopsIndex 
                    < BalanceStageExport.ExportShopsID.length){
                BalanceStageExport._ExportAllShop();
            } else {
                $("#bse_dg_shop").datagrid('reload');
            }
        }
    });
};