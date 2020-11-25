<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/balanceCount/VerifyRecord.js") ?>" type="text/javascript"></script>
<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:false,title:'核销记录',split:true,collapsible:false">
        <table id="vr_dg3" class="easyui-datagrid" toolbar="#d_vr_toolbar1" data-options="fit:true,checkOnSelect:true,singleSelect:true,rownumbers:true,pagination:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="width:100,align:'center',field:'vr_verify_date'">核销日期</th>
                    <th data-options="width:80,align:'center',field:'vr_org_sn'">组织编码</th>
                    <th data-options="width:150,align:'center',field:'vr_shop_name'">门店名称</th>
                    <th data-options="width:100,align:'center',field:'vr_verify_amount'">核销金额</th>
                    <th data-options="width:80,align:'center',field:'vr_unique',formatter:formatType">类型</th>
                    <th data-options="width:180,align:'center',field:'vr_id'">核销号</th>
                    <th data-options="width:80,align:'center',field:'vr_user'">操作人</th>
                    <th data-options="width:180,align:'center',field:'vr_time'">操作日期</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'south',border:false,split:true,height:270">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:false,title:'结算记录',split:true,collapsible:false">
                <table id="vr_dg1" class="easyui-datagrid" data-options="fit:true,checkOnSelect:false,singleSelect:false,rownumbers:true,method:'get',url:'../<?php echo $c_name; ?>/getBRDList/'">
                    <thead>
                        <tr>
                            <th data-options="width:100,align:'center',field:'brd_date_begin'">开始日期</th>
                            <th data-options="width:100,align:'center',field:'brd_date_end'">结束日期</th>
                            <th data-options="width:120,align:'center',field:'brd_shop_name'">门店名称</th>
                            <th data-options="width:80,align:'center',field:'brd_balance_amount'">结算金额</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'east',border:false,title:'资金流水',split:true,collapsible:false" style="width:60%">
                <table id="vr_dg2" class="easyui-datagrid" data-options="fit:true,checkOnSelect:false,singleSelect:false,rownumbers:true,method:'get',url:'../<?php echo $c_name; ?>/getCashPoolList/'">
                    <thead>
                        <tr>
                            <th data-options="width:100,align:'center',field:'cpd_date'">交易日期</th>
                            <th data-options="width:150,align:'center',field:'cpd_shop'">网点</th>
                            <th data-options="width:70,align:'center',field:'cpd_amount'">交易金额</th>
                            <th data-options="width:90,align:'center',field:'cpd_pay_account'">付款方账号</th>
                            <th data-options="width:90,align:'center',field:'cpd_remaining_sum'">账户余额</th>
                            <th data-options="width:70,align:'center',field:'cpd_biz_type'">业务类型</th>
                            <th data-options="width:70,align:'center',field:'cpd_trade_state'">交易状态</th>
                            <th data-options="width:100,align:'center',field:'cpd_bill_code'">流水号</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>    
</div>
<div id="d_vr_toolbar1">
    <div>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <input id="q_vrt1_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_vrt1_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_vrt1_vr_id" class="easyui-textbox" labelWidth="55" style="width:170px;" label="核销号:" labelPosition="left"/>
        <input id="q_vrt1_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getShopOrgList', method:'get',valueField:'id', textField:'text'" />
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_vrt1_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_vrt1_btn_remove" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<style type="text/css">
    .datagrid-header-rownumber, .datagrid-cell-rownumber {
        width: 30px;
    }
</style>
