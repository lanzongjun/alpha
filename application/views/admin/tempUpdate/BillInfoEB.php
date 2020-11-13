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
        <div id="tab_box" class="easyui-tabs" data-options="fit:true">
            <div title="饿百账单信息">
                <table id="dg" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',toolbar:toolbar1,pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                    <thead>
                        <tr>
                            <th data-options="width:100,align:'center',field:'bie_date'">账单日期</th>
                            <th data-options="width:200,align:'center',field:'bie_shop_name'">商户名称</th>
                            <th data-options="width:80,align:'center',field:'bie_shop_id_ele'">ele商户ID</th>
                            <th data-options="width:90,align:'center',field:'bie_shop_id'">饿百商户ID</th>
                            <th data-options="width:70,align:'center',field:'bie_order_sn'">订单序号</th>
                            <th data-options="width:90,align:'center',field:'bie_order_code'">星选订单号</th>
                            <th data-options="width:90,align:'center',field:'bie_order_code_ele'">饿了么订单号</th>
                            <th data-options="width:80,align:'center',field:'bie_biz_type'">业务类型</th>
                            <th data-options="width:100,align:'center',field:'bie_delivery_type'">配送方式</th>
                            <th data-options="width:70,align:'center',field:'bie_order_type'">订单类型</th>
                            <th data-options="width:80,align:'center',field:'bie_order_from'">订单来源</th>
                            <th data-options="width:170,align:'center',field:'bie_order_create_dt'">下单时间</th>
                            <th data-options="width:70,align:'center',field:'bie_balance_amount'">结算金额</th>
                            <th data-options="width:70,align:'center',field:'bie_cus_pay'">用户支付</th>
                            <th data-options="width:90,align:'center',field:'bie_ss_amount'">商户补贴总额</th>
                            <th data-options="width:120,align:'center',field:'bie_ss_packet'">商户补贴-红包</th>
                            <th data-options="width:120,align:'center',field:'bie_ss_promotion'">商户补贴-活动</th>
                            <th data-options="width:140,align:'center',field:'bie_ss_d_ticket'">商户补贴-配送费券</th>
                            <th data-options="width:150,align:'center',field:'bie_ss_d_promotion'">商户补贴-配送费活动</th>
                            <th data-options="width:130,align:'center',field:'bie_ss_ticket'">商户补贴-商家劵</th>
                            <th data-options="width:110,align:'center',field:'bie_ss_gift'">商户补贴-礼金</th>
                            <th data-options="width:110,align:'center',field:'bie_ss_ticket_p'">商户补贴-单品券</th>
                            <th data-options="width:90,align:'center',field:'bie_shop_rate'">代理商补贴</th>
                            <th data-options="width:70,align:'center',field:'bie_agent_rate'">配送费</th>
                            <th data-options="width:70,align:'center',field:'bie_send_ratio'">抽拥比例</th>
                            <th data-options="width:70,align:'center',field:'bie_send_min'">保底抽拥</th>
                            <th data-options="width:70,align:'center',field:'bie_send_fee'">实收佣金</th>
                            <th data-options="width:70,align:'center',field:'bie_commission'">菜品</th>
                            <th data-options="width:70,align:'center',field:'bie_products'">餐盒费</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_amount'">饿了么平台补贴总额</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_packet'">饿了么平台补贴-红包</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_promotion'">饿了么平台补贴-活动</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_ticket'">饿了么平台补贴-商家劵</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_d_ticket'">饿了么平台补贴-配送费劵</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_d_promotion'">饿了么平台补贴-配送费活动</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_gift'">饿了么平台补贴-礼金</th>
                            <th data-options="width:90,align:'center',field:'bie_baidu_rate'">冷链加价费</th>
                            <th data-options="width:90,align:'center',field:'bie_cold_box_fee'">众包呼单费</th>
                            <th data-options="width:70,align:'center',field:'bie_delivery_party_fee'">呼单小费</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div title="预览">
                <table id="dg2" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
                    <thead>
                        <tr>
                            <th data-options="width:150,align:'center',field:'bie_date'">账单日期</th>
                            <th data-options="width:150,align:'center',field:'bie_shop_name'">商户名称</th>
                            <th data-options="width:150,align:'center',field:'bie_shop_id_ele'">ele商户ID</th>
                            <th data-options="width:150,align:'center',field:'bie_shop_id'">饿百商户ID</th>
                            <th data-options="width:150,align:'center',field:'bie_order_sn'">订单序号</th>
                            <th data-options="width:150,align:'center',field:'bie_order_code'">星选订单号</th>
                            <th data-options="width:150,align:'center',field:'bie_order_code_ele'">饿了么订单号</th>
                            <th data-options="width:150,align:'center',field:'bie_biz_type'">业务类型</th>
                            <th data-options="width:150,align:'center',field:'bie_delivery_type'">配送方式</th>
                            <th data-options="width:150,align:'center',field:'bie_order_type'">订单类型</th>
                            <th data-options="width:150,align:'center',field:'bie_order_from'">订单来源</th>
                            <th data-options="width:150,align:'center',field:'bie_order_create_date'">下单时间</th>
                            <th data-options="width:150,align:'center',field:'bie_balance_amount'">结算金额</th>
                            <th data-options="width:150,align:'center',field:'bie_cus_pay'">用户支付</th>
                            <th data-options="width:150,align:'center',field:'bie_ss_amount'">商户补贴总额</th>
                            <th data-options="width:150,align:'center',field:'bie_ss_packet'">商户补贴-红包</th>
                            <th data-options="width:150,align:'center',field:'bie_ss_promotion'">商户补贴-活动</th>
                            <th data-options="width:150,align:'center',field:'bie_ss_d_ticket'">商户补贴-配送费券</th>
                            <th data-options="width:150,align:'center',field:'bie_ss_d_promotion'">商户补贴-配送费活动</th>
                            <th data-options="width:150,align:'center',field:'bie_ss_ticket'">商户补贴-商家劵</th>
                            <th data-options="width:150,align:'center',field:'bie_ss_gift'">商户补贴-礼金</th>
                            <th data-options="width:150,align:'center',field:'bie_ss_ticket_p'">商户补贴-单品券</th>
                            <th data-options="width:150,align:'center',field:'bie_shop_rate'">代理商补贴</th>
                            <th data-options="width:150,align:'center',field:'bie_agent_rate'">配送费</th>
                            <th data-options="width:150,align:'center',field:'bie_send_ratio'">抽拥比例</th>
                            <th data-options="width:150,align:'center',field:'bie_send_min'">保底抽拥</th>
                            <th data-options="width:150,align:'center',field:'bie_send_fee'">实收佣金</th>
                            <th data-options="width:150,align:'center',field:'bie_commission'">菜品</th>
                            <th data-options="width:150,align:'center',field:'bie_products'">餐盒费</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_amount'">饿了么平台补贴总额</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_packet'">饿了么平台补贴-红包</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_promotion'">饿了么平台补贴-活动</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_ticket'">饿了么平台补贴-商家劵</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_d_ticket'">饿了么平台补贴-配送费劵</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_d_promotion'">饿了么平台补贴-配送费活动</th>
                            <th data-options="width:150,align:'center',field:'bie_sp_gift'">饿了么平台补贴-礼金</th>
                            <th data-options="width:150,align:'center',field:'bie_baidu_rate'">冷链加价费</th>
                            <th data-options="width:150,align:'center',field:'bie_cold_box_fee'">众包呼单费</th>
                            <th data-options="width:150,align:'center',field:'bie_delivery_party_fee'">呼单小费</th>
                        </tr>
                    </thead>
                </table>
                <input type="hidden" id="hid_tbn"/>
            </div>
        </div>
        <div id="bie_win_input" class="easyui-window" title="导入数据" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:300px;height:200px;padding:10px;">
            <form id="bie_form_input" method="post" enctype="multipart/form-data">	
                <a href="<?php echo base_url("/input_template/Example_饿百账单数据 .csv") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
                <br/><br/>
                <input name="file_csv" class="easyui-filebox" data-options="prompt:'选择一个CSV文件...'" style="width:100%">
                <br/><br/>
                <a id="bie_btn_do_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
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
        <script src="<?php echo base_url("/resource/admin/temp/BillInfoEB.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
