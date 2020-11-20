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
        <table id="mtsg_dg" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="fit:true,title:'美团-门店商品',footer:'#dom_tb_ft',rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="width:100,align:'center',field:'sgm_shop_name'">店铺名称</th>
                    <th data-options="width:130,align:'center',field:'sgm_barcode'">商品条形码</th>
                    <th data-options="width:300,align:'center',field:'sgm_gname'">商品名称</th>
                    <th data-options="width:70,align:'center',field:'sgm_price'">销售价格</th>
                    <th data-options="width:65,align:'center',field:'sgm_count'">库存数量</th>
                    <th data-options="width:60,align:'center',field:'sgm_count_new',formatter:newStorageFormat">库存(新)</th>
                    <th data-options="width:55,align:'center',field:'sgm_weight'">重量(g)</th>
                    <th data-options="width:50,align:'center',field:'sgm_online',formatter:upFormat">上架</th>
                    <th data-options="width:150,align:'center',field:'sgm_cid'">自定义ID</th>
                    <th data-options="width:110,align:'center',field:'sgm_bs_m_id'">店铺美团ID</th>
                </tr>
            </thead>
        </table>
        <div id="dom_tb_ft" style="padding:2px 5px;">
            <a id="btn_refresh_storage" href="#" class="easyui-linkbutton" iconCls="icon-tip" plain="true">库存匹配</a>
            <a id="btn_refresh_price" href="#" class="easyui-linkbutton" iconCls="icon-tip" plain="true">价格匹配</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
<!--            <a id="btn_refresh_storage" href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true">刷新</a>-->
<!--            <a id="btn_update_storage" href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true">更新</a>-->
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a href="#" class="easyui-menubutton" data-options="menu:'#dom_sub_upload_stock',iconCls:'icon-reload'" plain="true">上传库存</a>
            <a href="#" class="easyui-menubutton" data-options="menu:'#dom_sub_upload_sku',iconCls:'icon-reload'" plain="true">下载SKU列表</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>

            <a href="#" class="easyui-menubutton" data-options="menu:'#dom_sub_freeze_stock',iconCls:'icon-lock'" plain="true">库存冻结</a>

            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a id="btn_show_new_goods" href="#" class="easyui-linkbutton" iconCls="icon-down" plain="true">未上线商品</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        </div>
        <div id="dom_sub_upload_stock" style="width:150px;">
            <div id="btn_sync_log" data-options="iconCls:'icon-man'">日志记录</div>
            <div id="btn_sync_online" data-options="iconCls:'icon-reload'">整体更新</div>
            <div id="btn_sync_online_diff" data-options="iconCls:'icon-reload'">差异更新</div>
        </div>
        <div id="dom_sub_upload_sku" style="width:150px;">
            <div id="btn_sync_sku_log" data-options="iconCls:'icon-man'">日志记录</div>
            <div id="btn_sync_mt_sku_list" data-options="iconCls:'icon-reload'">下载SKU</div>
            <div id="btn_sync_mt_sku_diff" data-options="iconCls:'icon-reload'">差异下载</div>
        </div>
        <div id="dom_sub_freeze_stock" style="width:150px;">
            <div id="btn_freeze_storage" data-options="iconCls:'icon-lock'">冻结</div>
            <div id="btn_unfreeze_storage" data-options="iconCls:'icon-lock'">解冻</div>
        </div>
        <div id="dom_toolbar1">
            <div>                  
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <input id="s_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="method:'get',valueField:'id', textField:'text'" />
                <input id="s_goods" class="easyui-textbox" labelWidth="60" style="width:150px;" label="商品名:" labelPosition="left"/>
                <input id="s_barcode" class="easyui-textbox" labelWidth="60" style="width:200px;" label="条形码:" labelPosition="left"/>
                <select id="s_filter_storage" class="easyui-combobox" labelWidth="45" style="width:135px;" label='库存:' labelPosition='left'>
                    <option value="ALL" selected="true">所有</option>
                    <option value="NON_ZERO">非零库存</option>
                    <option value="DIFF">库存差异</option>
                </select>
                <select id="s_filter_up" class="easyui-combobox" labelWidth="45" style="width:110px;" label='上架:' labelPosition='left'>
                    <option value="ALL" selected="true">所有</option>
                    <option value="0">上架</option>
                    <option value="1">下架</option>
                </select>
                <a id="btn_search" href="#" iconCls="icon-search" class="easyui-linkbutton">查询</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            </div>
        </div>
        <div id="mtsg_w_stock_log" class="easyui-window" title="库存更新日志" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:1024px;height:600px;">
            <div class="easyui-layout" data-options="fit:true">                            
                <div data-options="region:'center'">
                    <table id="mtsg_dg_stock_log" class="easyui-datagrid" toolbar="#mtsg_tb_stock_log" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                        <thead>
                            <tr>
                                <th data-options="width:120,align:'center',field:'bs_shop_name',formatter:shopNameFormat">店铺名称</th>
                                <th data-options="width:80,align:'center',field:'lum_error_no',formatter:updateErrNoFormat">结果状态</th>
                                <th data-options="width:180,align:'center',field:'lum_dt'">更新时间</th>
                                <th data-options="width:80,align:'center',field:'lum_user'">操作人</th>
                            </tr>
                        </thead>
                    </table>
                    <div id="mtsg_tb_stock_log">
                        <div>
                            <input id="s_stock_log_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="method:'get',valueField:'id', textField:'text',url: '../AdShopInfoYJC/getShopMtIdList'" />
                            <select id="s_stock_log_status" class="easyui-combobox" labelWidth="45" style="width:135px;" label='结果:' labelPosition='left'>
                                <option value="ALL" selected="true">所有</option>
                                <option value="SUCCESS">成功</option>
                                <option value="FAIL">失败</option>
                            </select>
                            <a id="btn_stock_log_search" href="#" iconCls="icon-search" class="easyui-linkbutton">查询</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                            <a id="btn_stock_log_clear" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">清空日志</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        </div>
                    </div>
                </div>
                <div data-options="region:'east',title:'结果详情',split:true" style="width:40%;">
                    <div id="mtsg_msg_stock_log"></div>
                </div>
            </div>
        </div>
        <div id="mtsg_w_sku_log" class="easyui-window" title="SKU更新日志" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:1024px;height:600px;">
            <div class="easyui-layout" data-options="fit:true">                            
                <div data-options="region:'center'">
                    <table id="mtsg_dg_sku_log" class="easyui-datagrid" toolbar="#mtsg_tb_sku_log" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                        <thead>
                            <tr>
                                <th data-options="width:120,align:'center',field:'bs_shop_name',formatter:shopNameFormat">店铺名称</th>
                                <th data-options="width:80,align:'center',field:'lsm_error_no',formatter:updateErrNoFormat">结果状态</th>
                                <th data-options="width:180,align:'center',field:'lsm_dt'">更新时间</th>
                                <th data-options="width:80,align:'center',field:'lsm_user'">操作人</th>
                            </tr>
                        </thead>
                    </table>
                    <div id="mtsg_tb_sku_log">
                        <div>
                            <input id="s_sku_log_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="method:'get',valueField:'id', textField:'text',url: '../AdShopInfoYJC/getShopMtIdList'" />
                            <select id="s_sku_log_status" class="easyui-combobox" labelWidth="45" style="width:135px;" label='结果:' labelPosition='left'>
                                <option value="ALL" selected="true">所有</option>
                                <option value="SUCCESS">成功</option>
                                <option value="FAIL">失败</option>
                            </select>
                            <a id="btn_sku_log_search" href="#" iconCls="icon-search" class="easyui-linkbutton">查询</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                            <a id="btn_sku_log_clear" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">清空日志</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        </div>
                    </div>
                </div>
                <div data-options="region:'east',title:'结果详情',split:true" style="width:40%;">
                    <div id="mtsg_msg_sku_log"></div>
                </div>
            </div>
        </div>
        <div id="mtsg_tb_ng">
            <a id="tb_ng_dl" href="#" class="icon-down"></a>
        </div>
        <div id="mtsg_new_goods" class="easyui-window" data-options="iconCls:'icon-down',modal:true,closed:true,title:'未上线产品',tools:'#mtsg_tb_ng'" style="width:750px;height:500px;">
            <table id="mtsg_dg_new_goods" class="easyui-datagrid" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'post'">
                <thead>
                    <tr>
                        <th data-options="width:120,align:'center',field:'csgm_shop_name'">门店</th>
                        <th data-options="width:220,align:'center',field:'csgm_name'">品名</th>
                        <th data-options="width:150,align:'center',field:'csgm_barcode'">条码</th>
                        <th data-options="width:60,align:'center',field:'csgm_count'">数量</th>
                        <th data-options="width:70,align:'center',field:'csgm_sale_price'">零售价</th>
                        <th data-options="width:70,align:'center',field:'csgm_settlement_price'">结算价</th>
                    </tr>
                </thead>
            </table>
        </div>
        <style type="text/css">
            .datagrid-header-rownumber, .datagrid-cell-rownumber {
                width: 30px;
            }
        </style>
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/MTShopGoods.js?" . rand()) ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/MTShopGoods.SyncStorage.js?" . rand()) ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/MTShopGoods.SyncSKU.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
