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
        <table id="ebbi_dg" title="饿了么-账单信息" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',toolbar:toolbar1,pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="width:80,align:'center',field:'bbie_shop_id'">门店ID</th>
                    <th data-options="width:80,align:'center',field:'bbie_org_sn'">组织编码</th>
                    <th data-options="width:200,align:'center',field:'bbie_shop_name'">门店名称</th>
                    <th data-options="width:100,align:'center',field:'bbie_date'">账单日期</th>
                    <th data-options="width:80,align:'center',field:'bbie_balance_amount',formatter:EBBillInfo.amountFormat">结算金额</th>
                    <th data-options="width:50,align:'center',field:'bbie_order_count'">单量</th>
                    <th data-options="width:80,align:'center',field:'bbie_cus_pay'">用户支付</th>
                    <th data-options="width:80,align:'center',field:'bbie_shop_pay'">商户补贴</th>
                    <th data-options="width:80,align:'center',field:'bbie_agent_pay'">代理商</th>
                    <th data-options="width:80,align:'center',field:'bbie_send_fee'">配送费</th>
                    <th data-options="width:80,align:'center',field:'bbie_commission'">实收佣金</th>
                    <th data-options="width:80,align:'center',field:'bbie_floors'">保底</th>
                    <th data-options="width:80,align:'center',field:'bbie_order_amount'">订单金额</th>
                    <th data-options="width:80,align:'center',field:'bbie_package_fee'">包装费</th>
                    <th data-options="width:80,align:'center',field:'bbie_ele_pay'">平台补贴</th>
                </tr>
            </thead>
        </table>
        <div id="ebbi_w_preview" class="easyui-window" title="数据预览" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:920px;height:600px;">
            <table id="ebbi_dg_preview" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
                <thead>
                    <tr>
                        <th data-options="width:80,align:'center',field:'bbie_shop_id'">门店ID</th>
                        <th data-options="width:80,align:'center',field:'bbie_org_sn'">组织编码</th>
                        <th data-options="width:200,align:'center',field:'bbie_shop_name'">门店名称</th>
                        <th data-options="width:100,align:'center',field:'bbie_date'">账单日期</th>
                        <th data-options="width:80,align:'center',field:'bbie_balance_amount',formatter:EBBillInfo.amountFormat">结算金额</th>
                        <th data-options="width:50,align:'center',field:'bbie_order_count'">单量</th>
                        <th data-options="width:80,align:'center',field:'bbie_cus_pay'">用户支付</th>
                        <th data-options="width:80,align:'center',field:'bbie_shop_pay'">商户补贴</th>
                        <th data-options="width:80,align:'center',field:'bbie_agent_pay'">代理商</th>
                        <th data-options="width:80,align:'center',field:'bbie_send_fee'">配送费</th>
                        <th data-options="width:80,align:'center',field:'bbie_commission'">实收佣金</th>
                        <th data-options="width:80,align:'center',field:'bbie_floors'">保底</th>
                        <th data-options="width:80,align:'center',field:'bbie_order_amount'">订单金额</th>
                        <th data-options="width:80,align:'center',field:'bbie_package_fee'">包装费</th>
                        <th data-options="width:80,align:'center',field:'bbie_ele_pay'">平台补贴</th>
                    </tr>
                </thead>
            </table>
            <input type="hidden" id="ebbi_hid_preview"/>
        </div>
        <div id="ebbi_win_input" class="easyui-window" title="导入数据" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:300px;height:200px;padding:10px;">
            <form id="ebbi_form_input" method="post" enctype="multipart/form-data">	
                <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_美团账单数据.xls") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
                <br/><br/>
                <input name="file_xls" class="easyui-filebox" data-options="prompt:'选择一个XLS文件...'" style="width:100%">
                <br/><br/>
                <a id="ebbi_btn_do_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
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
        <script src="<?php echo base_url("/resource/admin/baseConfig/EBBillInfo.js") ?>" type="text/javascript"></script>
    </body>
</html>
