<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:false">
        <table id="im_dg" title="发票信息" class="easyui-datagrid" toolbar="#d_im_toolbar1" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="width:100,align:'center',field:'ir_no'">发票号</th>
                    <th data-options="width:100,align:'center',field:'ir_amount'">发票金额</th>
                    <th data-options="width:100,align:'center',field:'ir_balance_amount',formatter:InvoiceManage.formatIRVAmount">结算金额</th>
                    <th data-options="width:100,align:'center',field:'ir_amount_diff',formatter:InvoiceManage.formatDiffAmount">发票差额</th>
                    <th data-options="width:100,align:'center',field:'ir_date_issued'">开票日期</th>
                    <th data-options="width:80,align:'center',field:'ir_district'">片区</th>
                    <th data-options="width:120,align:'center',field:'ir_shop_name'">门店</th>
                    <th data-options="width:94,align:'center',field:'ir_update_time'">录入日期</th>
                    <th data-options="width:70,align:'center',field:'ir_user'">操作人</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'south',border:false,split:true,height:350">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:false,title:'结算记录',split:true,collapsible:false">
                <table id="im_dg2" class="easyui-datagrid" toolbar="#d_im_toolbar2" data-options="fit:true,checkOnSelect:false,singleSelect:false,rownumbers:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                    <thead>
                        <tr>                            
                            <th data-options="field:'ck', checkbox:true"></th>
                            <th data-options="width:100,align:'center',field:'brd_date_begin'">开始日期</th>
                            <th data-options="width:100,align:'center',field:'brd_date_end'">结束日期</th>
                            <th data-options="width:50,align:'center',field:'bs_district'">片区</th>
                            <th data-options="width:120,align:'center',field:'brd_shop_name'">门店名称</th>
                            <th data-options="width:80,align:'center',field:'brd_balance_amount'">结算金额</th>
                            <th data-options="width:50,align:'center',field:'brd_vr_state',formatter:InvoiceManage.formatVerifyState">核销</th>
                            <th data-options="width:80,align:'center',field:'brd_ir_no'">发票号</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'east',border:false,title:'资金流水',split:true,collapsible:false" style="width:48%">
                <table id="im_dg3" class="easyui-datagrid" toolbar="#d_im_toolbar3" data-options="fit:true,checkOnSelect:false,singleSelect:false,rownumbers:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                    <thead>
                        <tr>
                            <th data-options="field:'ck', checkbox:true"></th>
                            <th data-options="width:100,align:'center',field:'cpd_date'">交易日期</th>
                            <th data-options="width:50,align:'center',field:'bs_district'">片区</th>
                            <th data-options="width:150,align:'center',field:'cpd_shop'">网点</th>
                            <th data-options="width:70,align:'center',field:'cpd_amount'">交易金额</th>
                            <th data-options="width:50,align:'center',field:'cpd_vr_state',formatter:InvoiceManage.formatVerifyState">核销</th>
                            <th data-options="width:80,align:'center',field:'brd_ir_no'">发票号</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="d_im_toolbar1">
    <div>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <input id="q_imt1_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_imt1_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_imt1_district" class="easyui-combobox" labelWidth="45" style="width:130px;" label='片区:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getDistrictList', method:'get',valueField:'id', textField:'text'" />
        <input id="q_imt1_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getShopOrgList', method:'get',valueField:'id', textField:'text'" />
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_imt1_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_imt1_btn_new" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-add'">新增</a>
        <a id="q_imt1_btn_remove" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
        <a id="q_imt1_btn_link" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-lock'">关联结算</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <span>合计：</span><span id="imt1_txt_count">0.00</span>
    </div>
</div>
<div id="d_im_toolbar2">
    <div>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <select id="q_imt2_verify" class="easyui-combobox" labelWidth="45" style="width:110px;" label='核销:' labelPosition='left' editable="false">
            <option value="ALL" selected="true">所有</option>
            <option value="YES">已核</option>
            <option value="NO">未核</option>
        </select>
        <select id="q_imt2_invoice" class="easyui-combobox" labelWidth="45" style="width:110px;" label='发票:' labelPosition='left' editable="false">
            <option value="ALL" selected="true">所有</option>
            <option value="YES">已开</option>
            <option value="NO">未开</option>
        </select>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_imt2_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
        <a id="q_imt2_btn_search_more" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">更多查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <span>合计：</span><span id="imt2_txt_count">0.00</span>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <span>差额：</span><span id="imt2_txt_diff">0.00</span>        
    </div>
</div>
<div id="d_im_toolbar3">
    <div>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <select id="q_imt3_verify" class="easyui-combobox" labelWidth="45" style="width:110px;" label='核销:' labelPosition='left' editable="false">
            <option value="ALL" selected="true">所有</option>
            <option value="YES">已核</option>
            <option value="NO">未核</option>
        </select>
        <select id="q_imt3_invoice" class="easyui-combobox" labelWidth="45" style="width:110px;" label='发票:' labelPosition='left' editable="false">
            <option value="ALL" selected="true">所有</option>
            <option value="YES">已开</option>
            <option value="NO">未开</option>
        </select>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_imt3_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
        <a id="q_imt3_btn_search_more" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">更多查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <span>合计：</span><span id="imt3_txt_count">0.00</span>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <span>差额：</span><span id="imt3_txt_diff">0.00</span>        
    </div>
</div>
<div id="w_im_search_dg2" class="easyui-window" title="结算记录-自定义查询" data-options="modal:true,closed:true,iconCls:'icon-search'" style="width:400px;height:450px;padding:10px;">
    <form id="f_im_search_dg2" method="post">
        <div style="margin-left:5px;margin-bottom:5px">
            <input id="q_imt2_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input id="q_imt2_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input id="q_imt2_district" class="easyui-combobox" labelWidth="45" style="width:130px;" label='片区:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getDistrictList', method:'get',valueField:'id', textField:'text'" />
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input id="q_imt2_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getShopOrgList', method:'get',valueField:'id', textField:'text'" />
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <select id="q_imt2_verify" class="easyui-combobox" labelWidth="45" style="width:130px;" label='核销:' labelPosition='left' editable="false">
                <option value="ALL" selected="true">所有</option>
                <option value="YES">已核</option>
                <option value="NO">未核</option>
            </select>
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <select id="q_imt2_invoice" class="easyui-combobox" labelWidth="45" style="width:130px;" label='发票:' labelPosition='left' editable="false">
                <option value="ALL" selected="true">所有</option>
                <option value="YES">已开</option>
                <option value="NO">未开</option>
            </select>
        </div>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="doSearch()" style="width:80px">查询</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeSWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="w_im_search_dg3" class="easyui-window" title="资金流水-自定义查询" data-options="modal:true,closed:true,iconCls:'icon-search'" style="width:400px;height:450px;padding:10px;">
    <form id="f_im_search_dg3" method="post">
        <div style="margin-left:5px;margin-bottom:5px">
            <input id="q_imt3_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input id="q_imt3_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input id="q_imt3_district" class="easyui-combobox" labelWidth="45" style="width:130px;" label='片区:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getDistrictList', method:'get',valueField:'id', textField:'text'" />
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input id="q_imt3_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getShopOrgList', method:'get',valueField:'id', textField:'text'" />
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <select id="q_imt3_verify" class="easyui-combobox" labelWidth="45" style="width:130px;" label='核销:' labelPosition='left' editable="false">
                <option value="ALL" selected="true">所有</option>
                <option value="YES">已核</option>
                <option value="NO">未核</option>
            </select>
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <select id="q_imt3_invoice" class="easyui-combobox" labelWidth="45" style="width:130px;" label='发票:' labelPosition='left' editable="false">
                <option value="ALL" selected="true">所有</option>
                <option value="YES">已开</option>
                <option value="NO">未开</option>
            </select>
        </div>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="doSearch()" style="width:80px">查询</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeSWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="w_im_add_invoice" class="easyui-window" title="新增发票信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:450px;height:350px;padding:5px;">
    <form id="f_im_add_invoice" method="post">
        <div style="margin-left:5px;margin-bottom:5px">
            <input class="easyui-textbox" name="ir_no" data-options="labelWidth:'100px',label:'发票号码:',width:'400px',required:true">
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input class="easyui-numberbox" name="ir_amount" data-options="labelWidth:'100px',label:'发票金额:',width:'400px',precision:2,required:true">
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input class="easyui-combobox" name="ir_district" data-options="url:'../AdShopInfoYJC/getDistrictList',method:'get',valueField:'id',textField:'text',labelWidth:'100px',label:'所属片区:',width:'400px',editable:'false'" />
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input class="easyui-combobox" name="ir_org_sn" data-options="url:'../AdShopInfoYJC/getShopOrgList',method:'get',valueField:'id',textField:'text',labelWidth:'100px',label:'所属店铺:',width:'400px',editable:'false'" />
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input class="easyui-datebox" name="ir_date_issued" data-options="labelWidth:'100px',label:'开票日期:',width:'400px'">
        </div>
        <div style="margin-left:5px;margin-bottom:5px">
            <input class="easyui-textbox" style="height:80px" name="ir_memo" data-options="multiline:true,labelWidth:'100px',label:'备注:',width:'400px'">
        </div>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="InvoiceManage.saveAddForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="InvoiceManage.closeAddWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<style type="text/css">
    .datagrid-header-rownumber, .datagrid-cell-rownumber {
        width: 30px;
    }
</style>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/balanceCount/InvoiceManage.js") ?>" type="text/javascript"></script>