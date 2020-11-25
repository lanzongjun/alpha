<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/balanceCount/VerifyCount.js") ?>" type="text/javascript"></script>
<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:false" style="width:34%">
        <table id="vc_dg3" title="核销统计" class="easyui-datagrid" toolbar="#d_vc_toolbar3" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getVCList/'">
            <thead>
                <tr>
                    <th data-options="width:100,align:'center',field:'vc_shop_name'">门店名称</th>
                    <th data-options="width:76,align:'center',field:'vc_verify_amount',sortable:true">已核销</th>
                    <th data-options="width:76,align:'center',field:'vc_unverify_brd',sortable:true">未核结算</th>
                    <th data-options="width:76,align:'center',field:'vc_unverify_cpd',sortable:true">未核流水</th>
                    <th data-options="width:76,align:'center',field:'vc_unverify_diff',sortable:true,formatter:fmt_unverify_diff">未核差异</th>
                    <th data-options="width:166,align:'center',field:'vc_update_time'">更新时间</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'east',border:false" style="width:66%">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'north',border:false" style="height:100px">
                <table id="vc_dg4" class="easyui-datagrid" data-options="fit:true,singleSelect:true">
                    <thead>
                        <tr>
                            <th data-options="width:100,align:'center',field:'vc_district'">片区</th>
                            <th data-options="width:100,align:'center',field:'vc_verify_amount'">已核销</th>
                            <th data-options="width:100,align:'center',field:'vc_unverify_brd'">未核结算</th>
                            <th data-options="width:100,align:'center',field:'vc_unverify_cpd'">未核流水</th>
                            <th data-options="width:100,align:'center',field:'vc_unverify_diff',formatter:fmt_unverify_diff">未核差异</th>
                            <th data-options="width:100,align:'center',field:'vc_brd'">结算合计</th>
                            <th data-options="width:100,align:'center',field:'vc_cpd'">流水合计</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'center',border:false">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'center',border:false" style="width:50%">
                        <table id="vc_dg1" title="未核销-结算记录" class="easyui-datagrid" toolbar="#d_vc_toolbar1" data-options="fit:true,rownumbers:true,method:'get',url:'../<?php echo $c_name; ?>/getBRList/',pagination:true,pageSize:50">
                            <thead>
                                <tr>
                                    <th data-options="width:100,align:'center',field:'brd_date_begin'">开始日期</th>
                                    <th data-options="width:100,align:'center',field:'brd_date_end'">结束日期</th>
                                    <th data-options="width:110,align:'center',field:'brd_shop_name'">门店名称</th>
                                    <th data-options="width:70,align:'center',field:'brd_balance_amount'">结算金额</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div data-options="region:'east',border:false" style="width:50%">
                        <table id="vc_dg2" title="未核销-资金流水" class="easyui-datagrid" toolbar="#d_vc_toolbar2" data-options="fit:true,rownumbers:true,method:'get',url:'../<?php echo $c_name; ?>/getCashPoolList/',pagination:true,pageSize:50">
                            <thead>
                                <tr>
                                    <th data-options="width:100,align:'center',field:'cpd_date'">交易日期</th>
                                    <th data-options="width:110,align:'center',field:'cpd_shop'">网点</th>
                                    <th data-options="width:70,align:'center',field:'cpd_amount'">交易金额</th>
                                    <th data-options="width:80,align:'center',field:'cpd_remaining_sum'">账户余额</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="d_vc_toolbar1">
    <div>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <input id="q_vct1_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_vct1_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_vct1_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<div id="d_vc_toolbar2">
    <div>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <input id="q_vct2_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="q_vct2_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_vct2_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<div id="d_vc_toolbar3">
    <div>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <input id="q_vct3_district" class="easyui-combobox" labelWidth="45" style="width:170px;" label='片区:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getDistrictList', method:'get',valueField:'id', textField:'text'" />
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_vct3_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="q_vct3_btn_count" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-sum'">统计</a>
        <a id="q_vct3_btn_output" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-down'">导出</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<style type="text/css">
    .datagrid-header-rownumber, .datagrid-cell-rownumber {
        width: 30px;
    }
</style>
