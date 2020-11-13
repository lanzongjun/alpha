
var BalanceStageCollect = {};

BalanceStageCollect.init = function () {
    if (!__bsco_bat_id || __bsco_bat_id === '') {
        $.messager.confirm('错误', '批处理流水号缺失，请重新打开', function (r) {
            $('#ba_w_collect').window('close');
            return;
        });
    }
    $("#bsco_dg_shop").datagrid({
        onClickRow: function (index, row) {
            BalanceStageCollect.getCollectListByShop(row.bas_ba_bat_id,
                    row.bas_bs_org_sn);
        },
        rowStyler: function (index, row) {
            if (row.bas_state === 'ABNORMAL') {
                return 'background-color:#F47983;color:#FFFFFF;';
            }
        }
    });
    $("#bsco_dg_shop_c").datagrid({
        rowStyler: function (index, row) {
            if (row.bac_settlement_price-0 <= 0) {
                return 'background-color:#F47983;color:#FFFFFF;';
            }
        }
    });
    $("#bsco_dg_goods").datagrid({
        rowStyler: function (index, row) {
            if (row.bag_settlement_price-0 <= 0) {
                return 'background-color:#F47983;color:#FFFFFF;';
            }
        }
    });
    $("#bsco_dg_shop").datagrid("options").url = '../' + __bsco_c_name + '/getCollectShopList/';
    $('#bsco_dg_shop').datagrid('load', {
        batid: __bsco_bat_id
    });
    $("#bsco_dg_goods").datagrid("options").url = '../' + __bsco_c_name + '/getCollectGoodsList/';
    $('#bsco_dg_goods').datagrid('load', {
        batid: __bsco_bat_id
    });
    
};

BalanceStageCollect.getCollectListByShop = function (batid, sid) {
    $("#bsco_dg_shop_c").datagrid("options").url = '../' + __bsco_c_name + '/getCollectListByShop/';
    $('#bsco_dg_shop_c').datagrid('load', {
        batid: batid,
        sid: sid
    });
};

BalanceStageCollect.doCollect = function () {
    $.messager.confirm('确认', '确认要对当前结算信息进行汇总统计吗?', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __bsco_c_name + '/doCollect',
                type: "POST",
                data: {"batid": __bsco_bat_id},
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
                        $("#bsco_dg_shop").datagrid('reload');
                        $("#bsco_dg_goods").datagrid('reload');
//                        $.messager.confirm('提示', '汇总统计操作完成，可以进入下一阶段，是否继续？', function(r){
//                            if (r){
//                                //下一阶段
//                                BalanceStageCollect.toNextStage();
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

BalanceStageCollect.toNextStage = function () {
    //是否已经
    $.ajax({
        url: '../AdBalanceAccountC/getStageTime',
        type: "POST",
        data: {'batid':__bsco_bat_id},
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state){
                if (null === o_response.data.ba_stage3_time) {
                    $.messager.confirm('提示', '当前阶段尚未进行统计操作，是否先进行统计操作？', function(r){
                        if (r){
                            BalanceStageCollect.doCollect();
                        }
                    });
                } else {
                    BalanceAccount.StageExport(__bsco_bat_id);
                    $('#ba_w_collect').window('close');
                }
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }            
        }
    });
};
