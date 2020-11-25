<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/balanceCount/Verification.js") ?>" type="text/javascript"></script>
<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'south',border:false,title:'核销关联',split:true,collapsed:false,collapsible:true,hideCollapsedContent:false,height:350">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:false">
                <table id="br_dg3" class="easyui-datagrid" data-options="fit:true,checkOnSelect:false,singleSelect:false,rownumbers:true">
                    <thead>
                        <tr>
                            <th data-options="field:'ck_id', checkbox:true" rowspan="2"></th>
                            <th colspan="4">结算记录</th>
                            <th data-options="width:150,align:'center',field:'vr_id'" rowspan="2">核销号</th>
                            <th data-options="width:80,align:'center',field:'brd_org_sn'" rowspan="2">组织编码</th>
                            <th colspan="6">资金池流水</th>
                        </tr>
                        <tr>
                            <th data-options="width:90,align:'center',field:'brd_date_begin'">开始日期</th>
                            <th data-options="width:90,align:'center',field:'brd_date_end'">结束日期</th>
                            <th data-options="width:120,align:'center',field:'brd_shop_name'">门店名称</th>
                            <th data-options="width:66,align:'center',field:'brd_balance_amount'">结算金额</th>

                            <th data-options="width:90,align:'center',field:'cpd_date'">交易日期</th>
                            <th data-options="width:120,align:'center',field:'cpd_shop'">网点</th>
                            <th data-options="width:66,align:'center',field:'cpd_amount'">交易金额</th>
                            <th data-options="width:80,align:'center',field:'cpd_remaining_sum'">账户余额</th>
                            <th data-options="width:66,align:'center',field:'cpd_biz_type'">业务类型</th>
                            <th data-options="width:66,align:'center',field:'cpd_trade_state'">交易状态</th>
                        </tr>
                    </thead>
                </table>
            </div>            
            <div data-options="region:'east',title:'核销操作',border:false,collapsible:false,collapsed:false,hideCollapsedContent:false" style="width:120px">
                <div class="easyui-sidemenu" data-options="width:120,border:false,onSelect:v_sidemenu_handler,data:v_menu_sidemenu_data"></div>
            </div>
        </div>
    </div>
    <div data-options="region:'west',border:false,collapsible:true,hideCollapsedContent:false" style="width:50%">
        <table id="br_dg" title="未核销-结算记录" class="easyui-datagrid" toolbar="#d_br_toolbar1" data-options="fit:true,checkOnSelect:false,singleSelect:false,rownumbers:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',toolbar:toolbar1,pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="field:'ck', checkbox:true"></th>
                    <th data-options="width:100,align:'center',field:'brd_date_begin'">开始日期</th>
                    <th data-options="width:100,align:'center',field:'brd_date_end'">结束日期</th>
                    <th data-options="width:150,align:'center',field:'brd_shop_name'">门店名称</th>
                    <th data-options="width:80,align:'center',field:'brd_balance_amount'">结算金额</th>
                    <th data-options="width:80,align:'center',field:'brd_org_sn'">组织编码</th>
                    <th data-options="width:60,align:'center',field:'brd_id'">流水号</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'center',border:false">
        <table id="br_dg2" title="未核销-资金流水" class="easyui-datagrid" toolbar="#d_br_toolbar2" data-options="fit:true,checkOnSelect:false,singleSelect:false,rownumbers:true,method:'get',url:'../<?php echo $c_name; ?>/getCashPoolList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="field:'ck', checkbox:true"></th>
                    <th data-options="width:100,align:'center',field:'cpd_date'">交易日期</th>
                    <th data-options="width:150,align:'center',field:'cpd_shop'">网点</th>
                    <th data-options="width:70,align:'center',field:'cpd_amount'">交易金额</th>
                    <th data-options="width:80,align:'center',field:'cpd_bs_org_sn'">组织编码</th>
                    <th data-options="width:80,align:'center',field:'cpd_remaining_sum'">账户余额</th>
<!--                    <th data-options="width:70,align:'center',field:'cpd_biz_type'">业务类型</th>
                    <th data-options="width:70,align:'center',field:'cpd_trade_state'">交易状态</th>-->
                    <th data-options="width:80,align:'center',field:'cpd_bill_code'">流水号</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="d_br_toolbar1">
    <div>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <input id="q_brt1_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_brt1_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_brt1_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getShopOrgList', method:'get',valueField:'id', textField:'text'" />
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_brt1_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <span>合计：</span><span id="brt1_txt_count" style="color:#F47983">0.00</span>
    </div>
</div>
<div id="d_br_toolbar2">
    <div>
        <span>合计：</span><span id="brt2_txt_count" style="color:#F47983">0.00</span>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <input id="q_brt2_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_brt2_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_brt2_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getShopOrgList', method:'get',valueField:'id', textField:'text'" />
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_brt2_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        
    </div>
</div>
<style type="text/css">
    .datagrid-header-rownumber, .datagrid-cell-rownumber {
        width: 30px;
    }
</style>
