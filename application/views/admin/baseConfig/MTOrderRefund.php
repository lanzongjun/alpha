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
            <div data-options="region:'center',title:'美团退款订单信息'">
                <table id="dg" toolbar="#d_mtoir_toolbar" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/'">
                    <thead>
                        <tr>
                            <th data-options="width:120,align:'center',field:'wm_order_id_view'">订单号</th>
                            <th data-options="width:150,align:'center',field:'wm_poi_name'">门店名称</th>
                            <th data-options="width:80,align:'center',field:'refund_type'">退款类型</th>
                            <th data-options="width:80,align:'center',field:'notify_type',formatter:notifyTypeFormat">通知类型</th>
                            <th data-options="width:200,align:'center',field:'reason'">退款原因</th>
                            <th data-options="width:80,align:'center',field:'money'">部分退款</th>
                            <th data-options="width:150,align:'center',field:'res_type',formatter:resTypeFormat">退款状态</th>
                            <th data-options="width:70,align:'center',field:'is_appeal',formatter:isAppealFormat">申诉退款</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="d_mtoir_toolbar">
                <div>
                    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                    <a id="btn_refund_agree" iconCls='icon-ok' href="#" class="easyui-linkbutton">同意</a>
                    <a id="btn_refund_reject" iconCls='icon-no' href="#" class="easyui-linkbutton">拒绝</a>
                    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>                
                    <a id="btn_pull_phone" iconCls='icon-reload' href="#" class="easyui-linkbutton">拉取骑手电话</a>
                    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>                
                </div>
            </div>
            <div id="part_room" data-options="region:'south',hideCollapsedContent:false,title:'部分退款详情',collapsed:true,split:true" style="height:220px;">
                <table id="dg3" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
                    <thead>
                        <tr>
                            <th data-options="width:300,align:'center',field:'food_name'">商品名称</th>
                            <th data-options="width:150,align:'center',field:'upc'">条形码</th>
                            <th data-options="width:80,align:'center',field:'count'">数量</th>
                            <th data-options="width:80,align:'center',field:'food_price'">实付价</th>
                            <th data-options="width:80,align:'center',field:'origin_food_price'">原价</th>
                            <th data-options="width:80,align:'center',field:'refund_price'">退款价格</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="detail_room" data-options="region:'east',title:'订单详情',collapsed:true,hideCollapsedContent:false,split:true" style="width:370px;">
                <table id="dg2" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
                    <thead>
                        <tr>
                            <th data-options="width:150,align:'center',field:'food_name'">商品名称</th>
                            <th data-options="width:70,align:'center',field:'upc'">条形码</th>
                            <th data-options="width:60,align:'center',field:'price'">单价</th>
                            <th data-options="width:50,align:'center',field:'quantity'">数量</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/MTOrderInfoRefund.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
