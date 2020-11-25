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
    <div data-options="region:'center',title:'饿了么退款订单详情'">
        <table id="dg" toolbar="#d_eboir_toolbar" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
            <tr>
                <th data-options="width:200,align:'center',field:'order_id'">订单号</th>
                <th data-options="width:250,align:'center',field:'name'">门店名称</th>
                <th data-options="width:150,align:'center',field:'type_text'">部分退款类型</th>
                <th data-options="width:160,align:'center',field:'total_price'">部分退款后订单总金额</th>
                <th data-options="width:170,align:'center',field:'user_fee'">部分退款后用户实付金额</th>
                <th data-options="width:170,align:'center',field:'shop_fee'">部分退款后商户应收金额</th>
                <th data-options="width:100,align:'center',field:'send_fee'">配送费</th>
                <th data-options="width:160,align:'center',field:'fee'">部分退款后优惠总金额</th>
                <th data-options="width:120,align:'center',field:'commission'">部分退款后佣金</th>
                <th data-options="width:100,align:'center',field:'refund_price'">退用户总金额</th>
                <th data-options="width:100,align:'center',field:'package_fee'">包装费</th>
                <th data-options="width:200,align:'center',field:'create_time'">订单创建时间</th>
            </tr>
            </thead>
        </table>
    </div>
    <div id="detail_room" data-options="region:'east',title:'订单详情',collapsed:true,hideCollapsedContent:false,split:true" style="width:930px;">
        <table id="dg2" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
            <thead>
            <tr>
                <th data-options="width:200,align:'center',field:'p_name'">商品名称</th>
                <th data-options="width:150,align:'center',field:'upc'">条形码</th>
                <th data-options="width:150,align:'center',field:'refund_id'">退单id</th>
                <th data-options="width:180,align:'center',field:'sku_id'">商品sku码</th>
                <th data-options="width:100,align:'center',field:'total_refund'">退用户金额</th>
                <th data-options="width:80,align:'center',field:'number'">商品份数</th>
                <th data-options="width:180,align:'center',field:'status_text'">退单状态</th>
                <th data-options="width:120,align:'center',field:'shop_ele_refund'">退还给平台金额</th>
                <th data-options="width:180,align:'center',field:'apply_time'">退单申请时间</th>

            </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/baseConfig/EBOrderInfoRefundDetail.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
