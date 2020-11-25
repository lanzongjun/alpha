
$(function () {
    init();
});

function init() {
    $("#dg").datagrid({
        url: '../' + __s_c_name + '/getList',
        onClickRow: function (index, row) {
            var p = $("#layout_room").layout("panel", "east")[0].clientWidth;
            if (p <= 0) {
                $('#layout_room').layout('expand', 'east');
            }

            loadRefundDetail(row.order_id);
        },
        // rowStyler: function (index, row) {
        //     if (row.status-0 !== 10) {
        //         return 'color:#AAAAAA;';
        //     }
        // }
    });
}

function loadRefundDetail(order_id) {
    $("#dg2").datagrid("options").url = '../' + __s_c_name + '/loadRefundDetail/';
    $('#dg2').datagrid('load', {
        order_id: order_id
    });
}
