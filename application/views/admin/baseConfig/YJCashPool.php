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
        <table id="yjcp_dg" title="资金池流水" toolbar="#d_yjcp_toolbar1" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',toolbar:toolbar1,pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="width:50,align:'center',field:'cpd_province'">省份</th>
                    <th data-options="width:96,align:'center',field:'cpd_date'">交易日期</th>
                    <th data-options="width:150,align:'center',field:'cpd_shop'">网点</th>
                    <th data-options="width:80,align:'center',field:'cpd_bs_sale_sn'">网点编码</th>
                    <th data-options="width:80,align:'center',field:'cpd_bs_org_sn'">组织编码</th>
                    <th data-options="width:80,align:'center',field:'cpd_amount'">交易金额</th>
                    <th data-options="width:150,align:'center',field:'cpd_pay_account'">付款方账号</th>
                    <th data-options="width:120,align:'center',field:'cpd_pay_name'">资金池账户名</th>
                    <th data-options="width:80,align:'center',field:'cpd_system'">业务系统</th>
                    <th data-options="width:80,align:'center',field:'cpd_biz_type'">业务类型</th>
                    <th data-options="width:60,align:'center',field:'cpd_cus_name'">客户名</th>
                    <th data-options="width:80,align:'center',field:'cpd_cash_cmp'">收款单位</th>
                    <th data-options="width:150,align:'center',field:'cpd_pool_account'">资金池账号</th>
                    <th data-options="width:100,align:'center',field:'cpd_bill_code'">资金池流水号</th>
                    <th data-options="width:80,align:'center',field:'cpd_cash_way'">收款方式</th>
                    <th data-options="width:150,align:'center',field:'cpd_system2'">业务系统</th>
                    <th data-options="width:80,align:'center',field:'cpd_remaining_sum'">账户余额</th>
                    <th data-options="width:80,align:'center',field:'cpd_according_to'">流水依据</th>
                    <th data-options="width:150,align:'center',field:'cpd_system_cusid'">业务系统客户号</th>
                    <th data-options="width:80,align:'center',field:'cpd_trade_state'">交易状态</th>
                </tr>
            </thead>
        </table>
        <div id="yjcp_w_preview" class="easyui-window" title="数据预览" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:1024px;height:768px;">
            <table id="yjcp_dg_preview" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
                <thead>
                    <tr>
                        <th data-options="width:50,align:'center',field:'tcpd_province'">省份</th>
                        <th data-options="width:96,align:'center',field:'tcpd_date'">交易日期</th>
                        <th data-options="width:96,align:'center',field:'tcpd_time'">交易时间</th>
                        <th data-options="width:150,align:'center',field:'tcpd_shop'">网点</th>
                        <th data-options="width:80,align:'center',field:'tcpd_bs_sale_sn'">网点编码</th>
                        <th data-options="width:80,align:'center',field:'bs_org_sn'">组织编码</th>
                        <th data-options="width:80,align:'center',field:'tcpd_amount'">交易金额</th>
                        <th data-options="width:150,align:'center',field:'tcpd_pay_account'">付款方账号</th>
                        <th data-options="width:120,align:'center',field:'tcpd_pay_name'">资金池账户名</th>
                        <th data-options="width:80,align:'center',field:'tcpd_system'">业务系统</th>
                        <th data-options="width:80,align:'center',field:'tcpd_biz_type'">业务类型</th>
                        <th data-options="width:60,align:'center',field:'tcpd_cus_name'">客户名</th>
                        <th data-options="width:80,align:'center',field:'tcpd_cash_cmp'">收款单位</th>
                        <th data-options="width:150,align:'center',field:'tcpd_pool_account'">资金池账号</th>
                        <th data-options="width:100,align:'center',field:'tcpd_bill_code'">资金池流水号</th>
                        <th data-options="width:80,align:'center',field:'tcpd_cash_way'">收款方式</th>
                        <th data-options="width:150,align:'center',field:'tcpd_system2'">业务系统</th>
                        <th data-options="width:80,align:'center',field:'tcpd_remaining_sum'">账户余额</th>
                        <th data-options="width:80,align:'center',field:'tcpd_according_to'">流水依据</th>
                        <th data-options="width:150,align:'center',field:'tcpd_system_cusid'">业务系统客户号</th>
                        <th data-options="width:80,align:'center',field:'tcpd_trade_state'">交易状态</th>
                    </tr>
                </thead>
            </table>
            <input type="hidden" id="yjcp_hid_preview"/>
        </div>
        <div id="d_yjcp_toolbar1">
            <div>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <input id="q_cpt1_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                <input id="q_cpt1_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                <input id="q_cpt1_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getShopOrgList', method:'get',valueField:'id', textField:'text'" />
                <input id="q_cpt1_district" class="easyui-combobox" labelWidth="45" style="width:120px;" label='片区:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getDistrictList', method:'get',valueField:'id', textField:'text'" />
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <a id="q_cpt1_btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <a id="q_cpt1_btn_input" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-add'">预导入</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            </div>
        </div>
        <div id="cp_win_input" class="easyui-window" title="导入数据" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:300px;height:200px;padding:10px;">
            <form id="form_input" method="post" enctype="multipart/form-data">	
                <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_资金池流水明细.xls") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
                <br/><br/>
                <input name="file_xls" class="easyui-filebox" data-options="prompt:'选择一个XLS文件...'" style="width:100%">
                <br/><br/>
                <a id="btn_do_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
            </form>
        </div>
        <style type="text/css">
            .datagrid-header-rownumber, .datagrid-cell-rownumber {
                width: 40px;
            }
        </style>
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/YJCashPool.js") ?>" type="text/javascript"></script>
    </body>
</html>
