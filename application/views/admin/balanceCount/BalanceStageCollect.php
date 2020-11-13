<script type="text/javascript">
    var __bsco_c_name = '<?php echo $c_name; ?>';
    var __bsco_bat_id = '<?php echo $bat_id; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/balanceCount/BalanceStageCollect.js?" . rand()) ?>" type="text/javascript"></script>
<div class="easyui-layout" data-options="border:false,fit:true">
    <div title="门店汇总" data-options="border:true,region:'west',split:true,collapsible:false" style="width:370px;">
        <table id="bsco_dg_shop" class="easyui-datagrid" data-options="rownumbers:true,border:false,fit:true,singleSelect:true,method:'POST'">
            <thead>
                <tr>
                    <th data-options="width:130,align:'center',field:'bas_bs_shop_name'">商户名称</th>
                    <!--<th data-options="width:60,align:'center',field:'bas_order_count'">订单量</th>-->
                    <th data-options="width:80,align:'center',field:'bas_order_amount'">结算金额</th>
                    <th data-options="width:95,align:'center',field:'bas_balance_time'">结算日期</th>
                </tr>
            </thead>
        </table>
    </div>
    <div title="门店汇总详情" data-options="border:true,region:'center',split:true,collapsible:false">
        <table id="bsco_dg_shop_c" class="easyui-datagrid" data-options="rownumbers:true,border:false,fit:true,singleSelect:true,method:'POST'">
            <thead>
                <tr>
                    <th data-options="width:220,align:'center',field:'bac_name'">品名</th>
                    <th data-options="width:50,align:'center',field:'bac_count'">数量</th>
                    <th data-options="width:60,align:'center',field:'bac_settlement_price'">结算价</th>
                    <th data-options="width:80,align:'center',field:'bac_amount'">金额</th>
                    <th data-options="width:100,align:'center',field:'bac_barcode'">条码</th>
                </tr>
            </thead>
        </table>
    </div>
    <div title="商品汇总" data-options="border:true,region:'east',split:true,collapsible:false" style="width:450px;">
        <table id="bsco_dg_goods" class="easyui-datagrid" data-options="rownumbers:true,border:false,fit:true,singleSelect:true,method:'POST'">
            <thead>
                <tr>
                    <th data-options="width:220,align:'center',field:'bag_goods_name'">品名</th>
                    <th data-options="width:50,align:'center',field:'bag_count'">数量</th>
                    <th data-options="width:50,align:'center',field:'bag_settlement_price'">结算价</th>
                    <th data-options="width:80,align:'center',field:'bag_amount'">金额</th>
                    <th data-options="width:100,align:'center',field:'bag_barcode'">条码</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
