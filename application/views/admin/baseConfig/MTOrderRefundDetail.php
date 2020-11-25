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
                <th data-options="width:180,align:'center',field:'order_id'">订单号</th>
                <th data-options="width:180,align:'center',field:'wm_order_id_view'">订单展示ID</th>
                <th data-options="width:250,align:'center',field:'wm_poi_name'">门店名称</th>
                <th data-options="width:170,align:'center',field:'apply_type_desc'">申请类型</th>
                <th data-options="width:170,align:'center',field:'apply_reason'">申请退款的原因</th>
                <th data-options="width:100,align:'center',field:'money'">退款金额合计</th>
                <th data-options="width:80,align:'center',field:'refund_type_desc'">退款类型</th>
                <th data-options="width:200,align:'center',field:'res_reason'">商家处理退款时答复的内容</th>
                <th data-options="width:120,align:'center',field:'res_type_desc'">答复类型描述</th>
                <th data-options="width:180,align:'center',field:'ctime'">退款申请发起时间</th>
                <th data-options="width:180,align:'center',field:'utime'">退款申请处理时间</th>
            </tr>
            </thead>
        </table>
    </div>
    <div id="detail_room" data-options="region:'east',title:'订单详情',collapsed:true,hideCollapsedContent:false,split:true" style="width:560px;">
        <table id="dg2" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
            <thead>
            <tr>
                <th data-options="width:240,align:'center',field:'food_name'">商品名称</th>
                <th data-options="width:150,align:'center',field:'upc'">条形码</th>
                <th data-options="width:80,align:'center',field:'food_price'">单价</th>
                <th data-options="width:50,align:'center',field:'count'">数量</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/baseConfig/MTOrderInfoRefundDetail.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
