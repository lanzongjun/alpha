<script type="text/javascript">
    var __bsc_c_name = '<?php echo $c_name; ?>';
    var __bsc_bat_id = '<?php echo $bat_id; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/balanceCount/BalanceStageConfirm.js?" . rand()) ?>" type="text/javascript"></script>
<div class="easyui-layout" data-options="border:false,fit:true">
    <div data-options="border:true,region:'west',split:true,width:640">
        <div class="easyui-layout" data-options="border:false,fit:true">
            <div data-options="border:false,region:'center',split:true">
                <table id="bsc_dg_orders" class="easyui-datagrid" title="原始订单" data-options="singleSelect:true,border:false,fit:true,method:'post',url: ''">
                    <thead>
                        <tr>
                            <th data-options="width:65,align:'center',field:'oi_platform'">订单来源</th>
                            <th data-options="width:90,align:'center',field:'oi_create_time'">订单日期</th>
                            <th data-options="width:220,align:'center',field:'oi_shop_name'">商户名称</th>
                            <th data-options="width:80,align:'center',field:'oi_total_fee'">总金额</th>
                            <th data-options="width:120,align:'center',field:'oi_order_state_enum',formatter:BalanceStageConfirm.fmtState">订单状态</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="border:false,split:true,region:'south',height:200">
                <table id="bsc_dg_detail" class="easyui-datagrid" data-options="border:false,fit:true,method:'post'">
                    <thead>
                        <tr>
                            <th data-options="width:130,align:'center',field:'od_barcode'">条码</th>
                            <th data-options="width:210,align:'center',field:'od_name'">品名</th>
                            <th data-options="width:70,align:'center',field:'od_count',formatter:BalanceStageConfirm.PRCount">数量</th>
                            <th data-options="width:70,align:'center',field:'od_price'">单价</th>
                            <th data-options="width:70,align:'center',field:'od_fee'">总价</th>
                            <th data-options="width:70,align:'center',field:'od_discount_fee'">优惠</th>
                        </tr>
                    </thead>
                </table>   
            </div>
        </div>
    </div>
    <div data-options="border:true,region:'center',split:true">
        <div class="easyui-layout" data-options="border:false,fit:true">
            <div data-options="border:false,region:'center',split:true">
                <table id="bsc_dg_bal_orders" class="easyui-datagrid" title="结算订单" data-options="singleSelect:true,border:false,fit:true,method:'post',url: ''">
                    <thead>
                        <tr>
                            <th data-options="width:65,align:'center',field:'boi_platform'">订单来源</th>
                            <th data-options="width:90,align:'center',field:'boi_create_time'">订单日期</th>
                            <th data-options="width:220,align:'center',field:'boi_shop_name'">商户名称</th>
                            <th data-options="width:80,align:'center',field:'boi_total_fee'">总金额</th>
                            <th data-options="width:100,align:'center',field:'boi_order_state_enum',formatter:BalanceStageConfirm.fmtBState">订单状态</th>
                            <th data-options="width:60,align:'center',field:'boi_code',formatter:BalanceStageConfirm.editBalOrder">操作</th>
                        </tr>
                    </thead>
                </table>   
            </div>
            <div data-options="border:false,region:'south',split:true,height:200">
                <table id="bsc_dg_bal_detail" class="easyui-datagrid" data-options="border:false,fit:true,method:'post'">
                    <thead>
                        <tr>
                            <th data-options="width:130,align:'center',field:'bod_barcode'">条码</th>
                            <th data-options="width:210,align:'center',field:'bod_name'">品名</th>
                            <th data-options="width:70,align:'center',field:'bod_count',formatter:BalanceStageConfirm.BPRCount">数量</th>
                            <th data-options="width:70,align:'center',field:'bod_price'">单价</th>
                            <th data-options="width:70,align:'center',field:'bod_fee'">总价</th>
                            <th data-options="width:70,align:'center',field:'bod_discount_fee'">优惠</th>
                        </tr>
                    </thead>
                </table>   
            </div>
        </div>
    </div>
</div>
<div id="bsc_w_edit" class="easyui-window" title="订单编辑" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:750px;height:600px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="border:false,region:'north'" style="height:180px">
            <form id="bsc_f_edit" method="post">
                <div style="padding:5px;">                    
                    <input class="easyui-textbox" name="boi_code" id="bsc_fe_order_id" data-options="label:'订单号:',width:'315px',editable:false">                        
                    <input class="easyui-textbox" name="boi_order_state" data-options="label:'订单状态:',width:'180px',editable:false">  
                    <input class="easyui-textbox" name="boi_platform" id="bsc_fe_platform" data-options="label:'订单来源:',width:'180px',editable:false">                                                      
                </div>
                <div style="padding:5px;">
                    <input class="easyui-textbox" name="boi_shop_name" data-options="label:'商户名称:',width:'315px',editable:false">                        
                    <input class="easyui-textbox" name="boi_total_fee" data-options="label:'总金额:',width:'180px',editable:false">                        
                    <input class="easyui-textbox" name="boi_shop_fee" data-options="label:'商户应收:',width:'180px',editable:false">                                                    
                </div>
                <div style="padding:5px;">
                    <input type="hidden" name="boi_shop_org_sn" id="bsc_fe_ss"/>
                    <input class="easyui-textbox" name="boi_update_memo" data-options="label:'更新说明:',multiline:true,editable:false" style="width:95%;height:80px">
                </div>
            </form>
        </div>
        <div data-options="border:false,region:'center',split:true">
            <table id="bsc_w_dg" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'post',toolbar:BalanceStageConfirm.editWinMenu">
                <thead>
                    <tr>
                        <th data-options="width:260,align:'center',field:'bod_name'">商品名称</th>
                        <th data-options="width:140,align:'center',field:'bod_barcode'">商品条码</th>
                        <th data-options="width:50,align:'center',field:'bod_count',formatter:BalanceStageConfirm.EditPRCount"">数量</th>
                        <th data-options="width:70,align:'center',field:'bod_price'">单价</th>
                        <th data-options="width:80,align:'center',field:'bod_fee'">总价</th>
                        <th data-options="width:80,align:'center',field:'bod_modify_enum'">修改</th>
                    </tr>
                </thead>
            </table>
            <div id="bsc_w_detail_add" class="easyui-window" title="订单详情新增" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:400px;height:350px;padding:15px;">
                <form id="bsc_f_detail_add" action="../<?php echo $c_name; ?>/addOrderDetail/" method="post">
                    <input type="hidden" name="boi_code" id="bsc_fda_order_id"/>
                    <input type="hidden" name="boi_platform" id="bsc_fda_platform"/>
                    <input type="hidden" name="boi_shop_id" id="bsc_fda_shop_id"/>
                    <input type="hidden" name="boi_ba_bat_id" value="<?php echo $bat_id; ?>"/>
                    <div style="padding:5px;">
                        <input class="easyui-numberbox" name="bod_barcode" id="bsc_dfa_barcode" data-options="label:'条码:',labelWidth:50,width:'300px',onChange:BalanceStageConfirm.getGoodsNameByBarcode,required:true"/>
                    </div>
                    <div style="padding:5px;">
                        <input class="easyui-textbox" name="bod_goods_name" id="bsc_dfa_goods_name" data-options="label:'品名:',editable:false,labelWidth:50,width:'300px'"/>
                    </div>
                    <div style="padding:5px;">
                        <input class="easyui-numberbox" name="bod_count" id="bsc_wda_count" data-options="label:'数量:',labelWidth:50,width:'300px',precision:0,required:true">
                    </div>
                    <div style="padding:5px;">
                        <input class="easyui-textbox" name="bod_modify_memo" data-options="label:'说明:',labelWidth:50,multiline:true,width:'300px',height:'80px'">
                    </div>
                    <div style="padding:10px;text-align:center;width:100%;">
                        <a href="#" class="easyui-linkbutton" onclick="BalanceStageConfirm.doAddOrderDetail()" data-options="iconCls:'icon-ok',width:120">确认</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="#" class="easyui-linkbutton" onclick="BalanceStageConfirm.cancelAddOrderDetail()" data-options="iconCls:'icon-cancel',width:120">取消</a>
                    </div>
                </form>
            </div>
            <div id="bsc_w_detail_edit" class="easyui-window" title="订单详情编辑" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:400px;height:380px;padding:15px;">
                <form id="bsc_f_detail_edit" action="../<?php echo $c_name; ?>/editOrderDetail/" method="post">
                    <input type="hidden" name="bod_id" id="bsc_wde_bod_id"/>
                    <div style="padding:5px;">
                        <input class="easyui-textbox" name="bod_name" data-options="label:'商品:',labelWidth:50,width:'300px',editable:false">
                    </div>
                    <div style="padding:5px;">
                        <input class="easyui-numberbox" name="bod_count" id="bsc_wde_bod_count" data-options="label:'数量:',labelWidth:50,width:'300px',precision:0,required:true">
                    </div>
                    <div style="padding:5px;">
                        <input class="easyui-textbox" name="bod_modify_memo" data-options="label:'说明:',labelWidth:50,multiline:true,width:'300px',height:'80px'">
                    </div>
                    <div style="padding:10px;text-align:center;width:100%;">
                        <a href="#" class="easyui-linkbutton" onclick="BalanceStageConfirm.doEditOrderDetail()" data-options="iconCls:'icon-ok',width:120">确认</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="#" class="easyui-linkbutton" onclick="BalanceStageConfirm.cancelEditOrderDetail()" data-options="iconCls:'icon-cancel',width:120">取消</a>
                    </div>
                </form>
            </div>
            <div id="bsc_w_detail_del" class="easyui-window" title="订单详情删除" data-options="modal:true,closed:true,iconCls:'icon-remove'" style="width:370px;height:330px;padding:15px;">
                <form id="bsc_f_detail_del" action="../<?php echo $c_name; ?>/delOrderDetail/" method="post">
                    <input type="hidden" name="bod_id" />
                    <div style="padding:5px;">
                        本操作为逻辑删除，实际数据仍会保留，确认删除此记录吗？
                    </div>
                    <div style="padding:5px;">
                        <input class="easyui-textbox" name="bod_name" data-options="label:'商品:',labelWidth:50,width:'300px',editable:false">
                    </div>
                    <div style="padding:5px;">
                        <input class="easyui-numberbox" name="bod_count" id="bsc_wde_bod_count" data-options="label:'数量:',labelWidth:50,width:'300px',precision:0,editable:false">
                    </div>
                    <div style="padding:5px;">
                        <input class="easyui-textbox" name="bod_modify_memo" data-options="label:'说明:',labelWidth:50,multiline:true,width:'300px',height:'80px',required:true">
                    </div>
                    <div style="padding:10px;text-align:center;width:100%;">
                        <a href="#" class="easyui-linkbutton" onclick="BalanceStageConfirm.doDelOrderDetail()" data-options="iconCls:'icon-ok',width:120">确认</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="#" class="easyui-linkbutton" onclick="BalanceStageConfirm.cancelDelOrderDetail()" data-options="iconCls:'icon-cancel',width:120">取消</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
