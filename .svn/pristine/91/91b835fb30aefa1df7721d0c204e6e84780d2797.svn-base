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
            <div title="易捷临期产品">
                <table id="dg" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                    <thead>
                        <tr>
                            <th data-options="width:50,align:'center',field:'gey_id',checkbox:true">ID</th>
                            <th data-options="width:80,align:'center',field:'gey_org_sn'">门店编码</th>
                            <th data-options="width:90,align:'center',field:'gey_shop_name'">门店名称</th>
                            <th data-options="width:90,align:'center',field:'gey_supplier_code'">供应商编码</th>
                            <th data-options="width:150,align:'center',field:'gey_supplier_name'">供应商名称</th>
                            <th data-options="width:100,align:'center',field:'gey_yj_code'">商品编码</th>
                            <th data-options="width:130,align:'center',field:'bgs_barcode'">商品条码</th>
                            <th data-options="width:150,align:'center',field:'gey_goods_name'">商品名称</th>
                            <th data-options="width:50,align:'center',field:'gey_unit'">单位</th>
                            <th data-options="width:50,align:'center',field:'gey_count'">数量</th>
                            <th data-options="width:80,align:'center',field:'gey_price_old'">含税进价</th>
                            <th data-options="width:100,align:'center',field:'gey_amount'">含税进价金额</th>
                            <th data-options="width:100,align:'center',field:'gey_production_date'">生产日期</th>
                            <th data-options="width:100,align:'center',field:'gey_expiration_date'">到期日</th>
                            <th data-options="width:80,align:'center',field:'gey_keep_month'">保质期(月)</th>
                            <th data-options="width:80,align:'center',field:'gey_deal_way'">处理方式</th>
                            <th data-options="width:90,align:'center',field:'gey_profit_rate'">毛利率(%)</th>
                            <th data-options="width:100,align:'center',field:'gey_price'">处理后金额</th>
                            <th data-options="width:150,align:'center',field:'gey_update_time'">更新时间</th>
                        </tr>
                    </thead>
                </table> 
                <div id="dom_toolbar1">
                    <div>
                        <a id="btn_todo_input" href="#" class="easyui-linkbutton" iconCls='icon-add'>预导入</a>                    
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <input id="s_expire" class="easyui-datebox" labelWidth="60" style="width:180px;" label="到期日:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                        <input id="s_shop" class="easyui-combobox" labelWidth="45" style="width:150px;" label='店铺:' labelPosition='left' data-options="url:'../AdTmpYJExpireC/getShopList', method:'get',valueField:'id', textField:'text'" />
                        <input id="s_count" class="easyui-numberbox" labelWidth="45" style="width:80px;" label="数量:" labelPosition="left"/>
                        <input id="s_goods" class="easyui-textbox" labelWidth="60" style="width:150px;" label="商品名:" labelPosition="left"/>
                        <a id="btn_search" href="#" class="easyui-linkbutton">查询</a>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <a id="btn_onsale" href="#" class="easyui-linkbutton" iconCls='icon-add'>加入促销库</a>
                    </div>
                </div>
                <div id="separator" class="datagrid-btn-separator"></div>
            </div>
            <div title="预览">
                <table id="dg2" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
                    <thead>
                        <tr>
                            <th data-options="width:150,align:'center',field:'gey_id'">ID</th>
                            <th data-options="width:150,align:'center',field:'gey_org_sn'">门店编码</th>
                            <th data-options="width:150,align:'center',field:'gey_shop_name'">门店名称</th>
                            <th data-options="width:150,align:'center',field:'gey_supplier_code'">供应商编码</th>
                            <th data-options="width:150,align:'center',field:'gey_supplier_name'">供应商名称</th>
                            <th data-options="width:150,align:'center',field:'gey_yj_code'">商品编码</th>
                            <th data-options="width:150,align:'center',field:'gey_goods_name'">商品名称</th>
                            <th data-options="width:150,align:'center',field:'gey_unit'">单位</th>
                            <th data-options="width:150,align:'center',field:'gey_count'">数量</th>
                            <th data-options="width:150,align:'center',field:'gey_price_old'">含税进价</th>
                            <th data-options="width:150,align:'center',field:'gey_amount'">含税进价金额</th>
                            <th data-options="width:150,align:'center',field:'gey_production_date'">生产日期</th>
                            <th data-options="width:150,align:'center',field:'gey_expiration_date'">到期日</th>
                            <th data-options="width:150,align:'center',field:'gey_keep_month'">保质期(月)</th>
                            <th data-options="width:150,align:'center',field:'gey_deal_way'">处理方式</th>
                            <th data-options="width:150,align:'center',field:'gey_profit_rate'">毛利率(%)</th>
                            <th data-options="width:150,align:'center',field:'gey_price'">处理后金额</th>
                            <th data-options="width:150,align:'center',field:'gey_update_time'">更新时间</th>
                        </tr>
                    </thead>
                </table>
                <input type="hidden" id="hid_tbn"/>
            </div>
        </div>
        <div id="tyje_win_input" class="easyui-window" title="导入数据" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:300px;height:200px;padding:10px;">
            <form id="form_input" method="post" enctype="multipart/form-data">	
                <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_易捷临期商品.csv") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
                <br/><br/>
                <input name="file_csv" class="easyui-filebox" data-options="prompt:'选择一个CSV文件...'" style="width:100%">
                <br/><br/>
                <a id="btn_do_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
            </form>
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
    <script src="<?php echo base_url("/resource/admin/temp/TmpYJExpire.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
