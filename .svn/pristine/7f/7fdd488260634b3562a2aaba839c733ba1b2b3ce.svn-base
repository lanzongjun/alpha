<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:false">
        <table id="br_dg" title="结算记录" class="easyui-datagrid" toolbar="#d_br_toolbar1" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',toolbar:toolbar1,pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="width:100,align:'center',field:'brd_date_begin'">开始日期</th>
                    <th data-options="width:100,align:'center',field:'brd_date_end'">结束日期</th>
                    <th data-options="width:150,align:'center',field:'brd_shop_name'">门店名称</th>
                    <th data-options="width:80,align:'center',field:'brd_org_sn'">组织编码</th>
                    <th data-options="width:80,align:'center',field:'brd_balance_amount',formatter:BalanceRecord.amountFormat">结算金额</th>
                    <th data-options="width:50,align:'center',field:'brd_memo'">备注</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'east',border:false" style="width:50%;">
        <table id="br_dg2" title="每日结算" class="easyui-datagrid" toolbar="#d_br_toolbar2" data-options="checkOnSelect:false,singleSelect:false,fit:true,rownumbers:true,method:'get',url:'../<?php echo $c_name; ?>/getBalList/',onLoadSuccess:onBalListLoad,pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="field:'ck', checkbox:true"></th>
                    <th data-options="width:100,align:'center',field:'ba_balance_date_begin'">开始日期</th>
                    <th data-options="width:100,align:'center',field:'ba_balance_date_end'">结束日期</th>
                    <th data-options="width:150,align:'center',field:'bas_bs_shop_name'">门店名称</th>
                    <th data-options="width:80,align:'center',field:'bas_bs_org_sn'">组织编码</th>
                    <th data-options="width:80,align:'center',field:'bas_order_amount',formatter:BalanceRecord.amountFormat">结算金额</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="br_w_preview" class="easyui-window" title="数据预览" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:800px;height:500px;">
    <table id="br_dg_preview" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
        <thead>
            <tr>
                <th data-options="width:100,align:'center',field:'brd_date_begin'">开始日期</th>
                <th data-options="width:100,align:'center',field:'brd_date_end'">结束日期</th>
                <th data-options="width:150,align:'center',field:'brd_shop_name'">门店名称</th>
                <th data-options="width:80,align:'center',field:'brd_org_sn'">组织编码</th>
                <th data-options="width:80,align:'center',field:'brd_balance_amount',formatter:BalanceRecord.amountFormat">结算金额</th>
                <th data-options="width:50,align:'center',field:'brd_memo'">备注</th>
            </tr>
        </thead>
    </table>
    <input type="hidden" id="br_hid_preview"/>
</div>
<div id="br_win_input" class="easyui-window" title="导入数据" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:300px;height:200px;padding:10px;">
    <form id="br_form_input" method="post" enctype="multipart/form-data">	
        <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_结算记录.xlsx") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
        <br/><br/>
        <input name="file_xls" class="easyui-filebox" data-options="prompt:'选择一个XLS文件...'" style="width:100%">
        <br/><br/>
        <a id="br_btn_do_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
    </form>
</div>
<div id="d_br_toolbar1">
    <div>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_brt1_btn_input" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-add'">预导入</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <input id="q_brt1_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_brt1_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_brt1_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getShopOrgList', method:'get',valueField:'id', textField:'text'" />
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_brt1_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<div id="d_br_toolbar2">
    <div>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_brt2_btn_append" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-add'">追加记录</a>
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
        width: 60px;
    }
</style>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/balanceCount/BalanceRecord.js") ?>" type="text/javascript"></script>