<script type="text/javascript">
    var __bss_c_name = '<?php echo $c_name; ?>';
    var __bss_bat_id = '<?php echo $bat_id; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/balanceCount/BalanceStageSelect.js?" . rand()) ?>" type="text/javascript"></script>
<div class="easyui-layout" data-options="border:false,fit:true">
    <div title="原始订单" data-options="border:true,region:'center',split:true">
        <table id="bss_dg_orders" class="easyui-datagrid" toolbar="#bss_dg_order_toolbar" data-options="checkOnSelect:false,border:false,fit:true,singleSelect:false,method:'get',url: '../AdBalanceStageSelectC/getOrderList',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="field:'ck', checkbox:true"></th>
                    <th data-options="width:65,align:'center',field:'oi_platform'">订单来源</th>
                    <th data-options="width:120,align:'center',field:'oi_create_time'">订单日期</th>
                    <th data-options="width:200,align:'center',field:'oi_shop_name'">商户名称</th>
                    <th data-options="width:80,align:'center',field:'oi_total_fee'">总金额</th>
                    <th data-options="width:70,align:'center',field:'oi_order_state'">订单状态</th>
                    <th data-options="width:70,align:'center',field:'oi_order_state_enum',formatter:BalanceStageSelect.orderStateFormat">完成状态</th>
                    <th data-options="width:65,align:'center',field:'oi_ba_bat_id',formatter:BalanceStageSelect.orderCheckFormat">结算状态</th>
                </tr>
            </thead>
        </table>   
        <div id="bss_dg_order_toolbar">
            <div>
                <input id="bss_q_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                <input id="bss_q_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <!--<input id="q_shop" class="easyui-combobox" labelWidth="45" style="width:150px;" label='店铺:' labelPosition='left' data-options="url:'../AdTmpYJExpireC/getShopList', method:'get',valueField:'id', textField:'text'" />-->
                <select id="bss_q_from" class="easyui-combobox" labelWidth="45" style="width:125px;" label='来源:' labelPosition='left'>
                    <option value="ALL" selected="true">所有</option>                            
                    <option value="ELE">饿了么</option>
                    <option value="MT">美团</option>
                    <option value="JD">京东到家</option>
                </select>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <select id="bss_q_b_state" class="easyui-combobox" labelWidth="45" style="width:125px;" label='结算:' labelPosition='left'>
                    <option value="ALL" selected="true">所有</option>
                    <option value="TODO">未结算</option>
                    <option value="DONE">已结算</option>
                </select>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <a id="bss_btn_search" iconCls='icon-search' href="#" class="easyui-linkbutton">查询</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <a id="bss_btn_pull_order" iconCls='icon-filter' href="#" class="easyui-linkbutton">拉取</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            </div>
        </div>
    </div>
    <div data-options="border:true,region:'east',split:true,collapsible:false,title:'结算订单'" style="width:550px;">
        <table id="bss_dg_balance" class="easyui-datagrid" data-options="border:false,fit:true,singleSelect:true,method:'GET',toolbar:BalanceStageSelect.tb_balance">
            <thead>
                <tr>
                    <th data-options="width:65,align:'center',field:'boi_platform'">订单来源</th>
                    <th data-options="width:90,align:'center',field:'boi_create_time'">订单日期</th>
                    <th data-options="width:200,align:'center',field:'boi_shop_name'">商户名称</th>
                    <th data-options="width:80,align:'center',field:'boi_total_fee'">总金额</th>
                    <th data-options="width:85,align:'center',field:'boi_order_state_enum'">状态</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
