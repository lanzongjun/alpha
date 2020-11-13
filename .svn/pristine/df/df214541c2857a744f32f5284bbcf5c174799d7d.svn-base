<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/default/easyui.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/icon.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/demo.css") ?>">
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.min.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.easyui.min.js") ?>"></script>
    </head>
    <body>
        <div id="layout_room" class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',title:'饿了么退款订单信息'">
                <table id="dg" toolbar="#d_eboir_toolbar" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/'">
                    <thead>
                        <tr>
                            <th data-options="width:100,align:'center',field:'order_id'">订单号</th>
                            <th data-options="width:150,align:'center',field:'name'">门店名称</th>
                            <th data-options="width:60,align:'center',field:'refund_type'">退款类型</th>
                            <th data-options="width:150,align:'center',field:'reason'">退款原因</th>
                            <th data-options="width:120,align:'center',field:'addition_reason'">额外原因</th>
                            <th data-options="width:70,align:'center',field:'refund_price'">退款金额</th>
                            <th data-options="width:150,align:'center',field:'status_desc'">状态</th>
                            <th data-options="width:170,align:'center',field:'update_dt'">时间</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="d_eboir_toolbar">
                <div>
                    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                    <a id="btn_refund_agree" iconCls='icon-ok' href="#" class="easyui-linkbutton">同意</a>
                    <a id="btn_refund_reject" iconCls='icon-no' href="#" class="easyui-linkbutton">拒绝</a>
                    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>            
                </div>
            </div>
            <div id="products_room" data-options="region:'south',hideCollapsedContent:false,title:'部分退款详情',collapsed:true,split:true" style="height:220px;">
                <table id="dg2" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
                    <thead>
                        <tr>
                            <th data-options="width:300,align:'center',field:'p_name'">商品名称</th>
                            <th data-options="width:150,align:'center',field:'upc'">条形码</th>
                            <th data-options="width:80,align:'center',field:'number'">数量</th>
                            <th data-options="width:80,align:'center',field:'total_refund'">退款金额</th>
                            <th data-options="width:80,align:'center',field:'shop_ele_refund'">退还补贴</th>
                        </tr>
                    </thead>
                </table>
            </div>            
            <div id="detail_room" data-options="region:'east',title:'订单详情',collapsed:true,hideCollapsedContent:false,split:true" style="width:370px;">
                <table id="dg3" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
                    <thead>
                        <tr>
                            <th data-options="width:150,align:'center',field:'product_name'">商品名称</th>
                            <th data-options="width:70,align:'center',field:'upc'">条形码</th>
                            <th data-options="width:60,align:'center',field:'product_price'">单价</th>
                            <th data-options="width:50,align:'center',field:'product_amount'">数量</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/EBOrderInfoRefund.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
