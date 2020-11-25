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
        <div id="layout_room" class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',title:'美团订单信息'">
                <table id="dg" toolbar="#d_mtoi_toolbar" class="easyui-datagrid" data-options="fit:true,rownumbers:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                    <thead>
                        <tr>
                            <th data-options="field:'ck_id', checkbox:true"></th>
                            <th data-options="width:80,align:'center',field:'wm_order_id_view'">订单号</th>
                            <th data-options="width:50,align:'center',field:'confirm_type',formatter:confirmTypeFormat">接单</th>
                            <th data-options="width:150,align:'center',field:'wm_poi_name'">门店名称</th>
                            <th data-options="width:50,align:'center',field:'total'">支付价</th>
                            <th data-options="width:50,align:'center',field:'original_price'">总原价</th>
                            <th data-options="width:80,align:'center',field:'recipient_name'">收货人</th>
                            <th data-options="width:110,align:'center',field:'recipient_phone'">收货电话</th>
                            <th data-options="width:100,align:'center',field:'recipient_address'">收货地址</th>
                            <th data-options="width:70,align:'center',field:'status',formatter:statusFormat">订单状态</th>
                            <th data-options="width:110,align:'center',field:'ctime'">创建时间</th>
                            <th data-options="width:110,align:'center',field:'utime'">更新时间</th>
                            <th data-options="width:300,align:'center',field:'caution'">备注信息</th>    
                        </tr>
                    </thead>
                </table>                
            </div>
            <div id="d_mtoi_toolbar">
                <div>
                    <input id="q_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                    <input id="q_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                    <input id="q_order_id" class="easyui-textbox" labelWidth="60" style="width:200px;" label="订单号:" labelPosition="left"/>
                    <input id="q_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getShopMtIdList', method:'get',valueField:'id', textField:'text'" />
                    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                    <a id="btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                    <a id="btn_sync_load" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">获取...</a>
                    <a id="btn_sync_reload" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重新获取</a>
                    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                </div>
            </div>
            <div id="detail_room" data-options="region:'south',title:'详情',hideCollapsedContent:false,collapsed:true,split:true" style="height:300px;">
                <table id="dg2" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
                    <thead>
                        <tr>
                            <th data-options="width:300,align:'center',field:'food_name'">商品名称</th>
                            <th data-options="width:150,align:'center',field:'upc'">条形码</th>
                            <th data-options="width:80,align:'center',field:'price'">单价</th>
                            <th data-options="width:80,align:'center',field:'food_discount'">折扣系数</th>
                            <th data-options="width:80,align:'center',field:'quantity'">购买数量</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <style type="text/css">
            .datagrid-header-rownumber, .datagrid-cell-rownumber {
                width: 40px;
            }
        </style>
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/MTOrderInfo.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
