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
            <div data-options="region:'center',title:'饿百订单信息'">
                <table id="dg" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../AdEBOrderInfoC/getList/',toolbar:toolbar1,pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                    <thead>
                        <tr>
                            <th data-options="width:200,align:'center',field:'eoi_order_create'">下单时间</th>
                            <th data-options="width:50,align:'center',field:'eoi_sn'">序号</th>
                            <th data-options="width:210,align:'center',field:'eoi_shop_name'">商户名称</th>
                            <th data-options="width:80,align:'center',field:'eoi_order_state'">订单状态</th>
                            <th data-options="width:80,align:'center',field:'eoi_goods_price'">商品费用</th>
                            <th data-options="width:95,align:'center',field:'eoi_cus_pay'">用户实付金额</th>
                            <th data-options="width:90,align:'center',field:'eoi_shop_receive_amount'">商户应收金额</th>
                            <th data-options="width:80,align:'center',field:'eoi_commission'">平台佣金</th>                            
                            <th data-options="width:80,align:'center',field:'eoi_deliver_fee'">配送费</th>
                            <th data-options="width:80,align:'center',field:'eoi_package_fee'">包装费</th>
                            <th data-options="width:90,align:'center',field:'eoi_subsidy_shop_promotion'">商家活动补贴</th>
                            <th data-options="width:90,align:'center',field:'eoi_subsidy_shop_ticket'">商家券补贴</th>
                            <th data-options="width:90,align:'center',field:'eoi_subsidy_shop_packet'">商家红包补贴</th>
                            <th data-options="width:110,align:'center',field:'eoi_subsidy_shop_deliver'">商家配送费补贴</th>
                            <th data-options="width:90,align:'center',field:'eoi_subsidy_shop_gift'">商家礼金补贴</th>
                            <th data-options="width:90,align:'center',field:'eoi_subsidy_platform_promotion'">平台活动补贴</th>
                            <th data-options="width:110,align:'center',field:'eoi_subsidy_platform_ticket'">平台商家券补贴</th>
                            <th data-options="width:95,align:'center',field:'eoi_subsidy_platform_packet'">平台红包补贴</th>
                            <th data-options="width:110,align:'center',field:'eoi_subsidy_platform_deliver'">平台配送费补贴</th>
                            <th data-options="width:90,align:'center',field:'eoi_subsidy_platform_gift'">平台礼金补贴</th>
                            <th data-options="width:90,align:'center',field:'eoi_subsidy_cus_ticket'">顾客买券补贴</th>
                            <th data-options="width:80,align:'center',field:'eoi_deliver_free'">免配送费</th>
                            <th data-options="width:120,align:'center',field:'eoi_is_present'">是否参加满赠活动</th>
                            <th data-options="width:80,align:'center',field:'eoi_refund_state'">退款状态</th>
                            <th data-options="width:95,align:'center',field:'eoi_order_invalid'">订单无效理由</th>
                            <th data-options="width:95,align:'center',field:'eoi_arrive_time'">预计送达时间</th>
                            <th data-options="width:95,align:'center',field:'eoi_shop_receive'">商户接单时间</th>
                            <th data-options="width:95,align:'center',field:'eoi_order_finish'">订单完成时间</th>
                            <th data-options="width:90,align:'center',field:'eoi_order_amount'">订单总金额</th>
                            <th data-options="width:80,align:'center',field:'eoi_claim_settlement'">索赔状态</th>
                            <th data-options="width:80,align:'center',field:'eoi_deliver'">配送方式</th>
                            <th data-options="width:80,align:'center',field:'eoi_from'">订单来源</th>
                            <th data-options="width:180,align:'center',field:'eoi_code'">订单编号</th>
                            <th data-options="width:150,align:'center',field:'eoi_e_code'">饿了么订单编号</th>
                            <th data-options="width:80,align:'center',field:'eoi_city'">城市</th>
                            <th data-options="width:120,align:'center',field:'eoi_shop_id'">商户ID</th>
                            <th data-options="width:150,align:'center',field:'eoi_memo'">备注</th>
                            <th data-options="width:80,align:'center',field:'eoi_express_cmp'">快递公司</th>
                            <th data-options="width:80,align:'center',field:'eoi_express_code'">快递单号</th>
                            <th data-options="width:180,align:'center',field:'eoi_update_date'">更新时间</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="exception_room" data-options="region:'east',title:'异常信息',hideCollapsedContent:false,collapsed:true,split:true" style="width:380px;">
                <table id="dg_orders_exception" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
                    <thead>
                        <tr>
                            <th data-options="width:160,align:'center',field:'eoe_exception_memo'">异常描述</th>
                            <th data-options="width:90,align:'center',field:'eoe_append_dt'">追加时间</th>
                            <th data-options="width:80,align:'center',field:'eoe_exception_enum'">异常代码</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="detail_room" data-options="region:'south',title:'详情',collapsed:true,split:true" style="height:300px;">
                <table id="dg2" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
                    <thead>
                        <tr>
                            <th data-options="width:300,align:'center',field:'eod_goods_name'">商品名称</th>
                            <th data-options="width:150,align:'center',field:'sge_barcode'">条形码</th>
                            <th data-options="width:80,align:'center',field:'eod_onsale_before'">优惠前单价</th>
                            <th data-options="width:80,align:'center',field:'eod_onsale_after'">优惠后单价</th>
                            <th data-options="width:80,align:'center',field:'eod_buy_count'">购买数量</th>
                            <th data-options="width:80,align:'center',field:'bbp_settlement_price'">结算价</th>
                            <th data-options="width:80,align:'center',field:'bbp_yj_sale_price'">线下售价</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <style type="text/css">
            .datagrid-header-rownumber, .datagrid-cell-rownumber {
                width: 40px;
            }
        </style>
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/OrderInfoEB.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
