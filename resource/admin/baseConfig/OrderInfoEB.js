var toolbar1 = [{
        text: '解析新订单',
        iconCls: 'icon-add',
        handler: function () {
            ajaxLoading();
            //异步请求
            $.ajax({
                url: '../'+__s_c_name+'/getNewOrders/',
                type: "POST",
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    var s_response = '解析成功:'+o_response.success+"条<br/>"
                            + '解析失败:'+o_response.fail+'条';
                    $.messager.alert('解析结果', s_response, 'info');
                    $("#dg").datagrid("reload");
                    ajaxLoadEnd();
                }
            });
        }
    }, {
        text:'核对订单信息',
        iconCls:'icon-add',
        handler : function () {
            checkOrders();
        }
    }];

$(function () {
    init();
});

function init() {
    $("#dg").datagrid({
        rowStyler: function (index, row) {
            if (row.eoi_repair_enum === ENUM_REPAIR_TODO) {
                return 'background-color:#FF1493;color:#000000;';
            }
            if (row.eoi_repair_enum === ENUM_REPAIR_DONE) {
                return 'background-color:#BCEE68;color:#000000;';
            }            
        },
        onClickRow: function (index, row) { //easyui封装好的时间（被单机行的索引，被单击行的值）
            var p = $("#layout_room").layout("panel", "south")[0].clientWidth;            
            if (p <= 0) {
                $('#layout_room').layout('expand', 'south');
            }
            loadDetailData(row.eoi_code);            
            loadExcpetion(row.eoi_code, row.eoi_repair_enum);
        }
    });
}

function loadDetailData(s_code) {
    $("#dg2").datagrid("options").url = '../'+__s_c_name+'/loadDetailData/';
    $('#dg2').datagrid('load', {
        ocode: s_code
    });
}

function loadExcpetion(s_code, enum_repair) {    
    if (enum_repair === ENUM_REPAIR_NORMAL) {
        $('#dg_orders_exception').datagrid('loadData',{ total:0,rows:[]});
        return;
    } 
    var p = $("#layout_room").layout("panel", "east")[0].clientWidth;            
    if (p <= 0) {
        $('#layout_room').layout('expand', 'east');
    }
    $("#dg_orders_exception").datagrid("options").url = '../'+__s_c_name+'/loadExceptionList/';
    $('#dg_orders_exception').datagrid('load', {
        oc: s_code
    });
}

function checkOrders(){
    ajaxLoading();
    //异步请求
    $.ajax({
        url: '../'+__s_c_name+'/checkOrders/',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            ajaxLoadEnd();
        }
    });
}

