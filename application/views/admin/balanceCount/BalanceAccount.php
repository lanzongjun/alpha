<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',title:'易捷总结算表'">
        <table id="ba_dg" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/'">
            <thead>
                <tr>
                    <th data-options="width:100,align:'center',field:'ba_balance_date_begin'">结算起始日期</th>
                    <th data-options="width:100,align:'center',field:'ba_balance_date_end'">结算结束日期</th>
                    <th data-options="width:100,align:'center',field:'ba_stage1_time',formatter:BalanceAccount.formatS1">S1.拉取/选定</th>
                    <th data-options="width:100,align:'center',field:'ba_stage2_time',formatter:BalanceAccount.formatS2">S2.调整/确认</th>
                    <th data-options="width:100,align:'center',field:'ba_stage3_time',formatter:BalanceAccount.formatS3">S3.汇总/统计</th>
                    <th data-options="width:100,align:'center',field:'ba_stage4_time',formatter:BalanceAccount.formatS4">S4.导出/发送</th>
<!--                            <th data-options="width:70,align:'center',field:'ba_balance_eb'">饿百结算</th>
                    <th data-options="width:70,align:'center',field:'ba_balance_mt'">美团结算</th>
                    <th data-options="width:70,align:'center',field:'ba_balance_jd'">京东结算</th>-->
                    <th data-options="width:100,align:'center',field:'ba_balance_yj'">易捷结算</th>
<!--                            <th data-options="width:100,align:'center',field:'ba_cpd_remaining_sum'">资金池余额</th>
                    <th data-options="width:100,align:'center',field:'ba_cpd_time'">资金池日期</th>
                    <th data-options="width:100,align:'center',field:'ba_cpd_bill_code'">资金池流水号</th>-->
                    <th data-options="width:160,align:'center',field:'ba_balance_time'">结算时间</th>
                </tr>
            </thead>
        </table>
        <div id="dom_toolbar1">
            <div>
                <input id="ba_q_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                <input id="ba_q_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <a id="ba_btn_search" href="#" class="easyui-linkbutton" iconCls='icon-search' data-options="disabled:true">查询</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>                        
                <a id="ba_btn_balance" href="#" class="easyui-linkbutton" iconCls='icon-add'>创建结算</a>
                <a id="ba_btn_balance_remove" href="#" class="easyui-linkbutton" iconCls='icon-remove'>删除结算</a>
            </div>
        </div>
    </div>
</div>
<div id="ba_w_select">
    <div class="easyui-layout" data-options="border:false,fit:true">
        <div id="ba_p_select" data-options="border:false,region:'center',split:false"></div>
        <div data-options="border:false,split:false,region:'south',height:50" style="padding:10px;text-align:right;">
            <a href="#" class="easyui-linkbutton" onclick="BalanceStageSelect.doBalance()" data-options="iconCls:'icon-right',width:120">选定</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="#" class="easyui-linkbutton" onclick="BalanceStageSelect.toNextStage()" data-options="iconCls:'icon-right',width:120">下一阶段</a>
        </div>
    </div>
</div>
<div id="ba_w_confirm">
    <div class="easyui-layout" data-options="border:false,fit:true">
        <div id="ba_p_confirm" data-options="border:false,region:'center',split:false"></div>
        <div data-options="border:false,split:false,region:'south',height:50" style="padding:10px;text-align:right;">
            <a href="#" class="easyui-linkbutton" onclick="BalanceStageConfirm.doConfirm()" data-options="iconCls:'icon-right',width:120">确认</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="#" class="easyui-linkbutton" onclick="BalanceStageConfirm.toNextStage()" data-options="iconCls:'icon-right',width:120">下一阶段</a>
        </div>
    </div>
</div>
<div id="ba_w_collect">
    <div class="easyui-layout" data-options="border:false,fit:true">
        <div id="ba_p_collect" data-options="border:false,region:'center',split:false"></div>
        <div data-options="border:false,split:false,region:'south',height:50" style="padding:10px;text-align:right;">
            <a href="#" class="easyui-linkbutton" onclick="BalanceStageCollect.doCollect()" data-options="iconCls:'icon-sum',width:120">汇总</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="#" class="easyui-linkbutton" onclick="BalanceStageCollect.toNextStage()" data-options="iconCls:'icon-right',width:120">下一阶段</a>
        </div>
    </div>
</div>
<div id="ba_w_export">
    <div id="ba_p_export" data-options="border:false,fit:true">                
    </div>
</div>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/balanceCount/BalanceAccount.js?" . rand()) ?>" type="text/javascript"></script>

