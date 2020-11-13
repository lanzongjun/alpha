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
        <table id="ebsg_dg" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="fit:true,title:'饿了么-门店商品',footer:'#dom_tb_ft',rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="width:100,align:'center',field:'sge_shop_name'">店铺名称</th>
                    <th data-options="width:130,align:'center',field:'sge_barcode'">商品条形码</th>
                    <th data-options="width:180,align:'center',field:'sge_gname'">商品名称</th>
                    <th data-options="width:70,align:'center',field:'sge_price'">销售价格</th>
                    <!--<th data-options="width:65,align:'center',field:'sge_price_new',formatter:newPriceFormat">价格(新)</th>-->
                    <th data-options="width:65,align:'center',field:'sge_count'">库存数量</th>
                    <th data-options="width:60,align:'center',field:'sge_count_new',formatter:newStorageFormat">库存(新)</th>
                    <th data-options="width:55,align:'center',field:'sge_weight'">重量(g)</th>
                    <th data-options="width:80,align:'center',field:'sge_fclass2'">前台二级</th>
                    <th data-options="width:50,align:'center',field:'sge_online',formatter:upFormat">上架</th>
                    <th data-options="width:100,align:'center',field:'sge_cid'">自定义ID</th>
                    <th data-options="width:110,align:'center',field:'sge_bs_e_id'">店铺饿百ID</th>
                </tr>
            </thead>
        </table>
        <div id="dom_tb_ft" style="padding:2px 5px;">
            <a id="btn_refresh_storage" href="#" class="easyui-linkbutton" iconCls="icon-tip" plain="true">库存匹配</a>
            <a id="btn_refresh_price" href="#" class="easyui-linkbutton" iconCls="icon-tip" plain="true">价格匹配</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <!--<a href="#" class="easyui-menubutton" data-options="menu:'#dom_sub_upload_price',iconCls:'icon-reload'" plain="true">上传价格</a>-->
            <a href="#" class="easyui-menubutton" data-options="menu:'#dom_sub_upload_stock',iconCls:'icon-reload'" plain="true">上传库存</a>
            <a href="#" class="easyui-menubutton" data-options="menu:'#dom_sub_upload_sku',iconCls:'icon-reload'" plain="true">下载SKU</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a href="#" class="easyui-menubutton" data-options="menu:'#dom_sub_freeze_stock',iconCls:'icon-lock'" plain="true">库存冻结</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a href="#" class="easyui-menubutton" data-options="menu:'#dom_sub_file_upload',iconCls:'icon-reload'" plain="true">CSV更新</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a id="btn_show_new_goods" href="#" class="easyui-linkbutton" iconCls="icon-down" plain="true">未上线商品</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        </div>                
        <div id="dom_sub_freeze_stock" style="width:150px;">
            <div id="btn_freeze_storage" data-options="iconCls:'icon-lock'">冻结</div>
            <div id="btn_unfreeze_storage" data-options="iconCls:'icon-lock'">解冻</div>
        </div>         
        <div id="dom_sub_upload_price" style="width:150px;">
            <div id="btn_sync_price_log" data-options="iconCls:'icon-man'">日志记录</div>
            <div id="btn_sync_price_diff" data-options="iconCls:'icon-reload'">差异更新</div>
        </div> 
        <div id="dom_sub_upload_stock" style="width:150px;">
            <div id="btn_sync_log" data-options="iconCls:'icon-man'">日志记录</div>
            <div id="btn_sync_online" data-options="iconCls:'icon-reload'">整体更新</div>
            <div id="btn_sync_online_diff" data-options="iconCls:'icon-reload'">差异更新</div>
        </div>
        <div id="dom_sub_upload_sku" style="width:150px;">
            <div id="btn_sync_sku_log" data-options="iconCls:'icon-man'">日志记录</div>
            <div id="btn_sync_eb_sku_list" data-options="iconCls:'icon-reload'">下载SKU</div>
            <div id="btn_sync_eb_sku_diff" data-options="iconCls:'icon-reload'">差异下载</div>
        </div>
        <div id="dom_sub_file_upload" style="width:150px;">
            <div id="btn_todo_input" data-options="iconCls:'icon-add'">预导入</div>
            <div id="btn_out_pf_csv" data-options="iconCls:'icon-print'">导出CSV</div>
            <div id="btn_out_pf_csv_win" data-options="iconCls:'icon-print'">导出历史</div>
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
                <select id="s_filter_price" class="easyui-combobox" labelWidth="60" style="width:150px;" label='零售价:' labelPosition='left'>
                    <option value="ALL" selected="true">所有</option>
                    <option value="NON_ZERO">价格非零</option>
                    <option value="DIFF">价格差异</option>
                </select>
                <select id="s_filter_up" class="easyui-combobox" labelWidth="45" style="width:110px;" label='上架:' labelPosition='left'>
                    <option value="ALL" selected="true">所有</option>
                    <option value="UP">上架</option>
                    <option value="DOWN">下架</option>
                </select>
                <a id="btn_search" href="#" iconCls="icon-search" class="easyui-linkbutton">查询</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            </div>
        </div>
        <div id="ebsg_w_update_preview" class="easyui-window" title="导入预览" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:1024px;height:600px;">
            <table id="dg2" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
                <thead>
                    <tr>
                        <th data-options="width:150,align:'center',field:'sge_gid'">商品ID</th>
                        <th data-options="width:150,align:'center',field:'sge_cid'">商品自定义ID</th>
                        <th data-options="width:150,align:'center',field:'sge_barcode'">商品条形码</th>
                        <th data-options="width:150,align:'center',field:'sge_gname'">商品名称</th>
                        <th data-options="width:150,align:'center',field:'sge_shelves'">货架号</th>
                        <th data-options="width:150,align:'center',field:'sge_band'">所属品牌</th>
                        <th data-options="width:150,align:'center',field:'sge_fclass1'">前台一级分类</th>
                        <th data-options="width:150,align:'center',field:'sge_fclass2'">前台二级分类</th>
                        <th data-options="width:150,align:'center',field:'sge_bclass1'">后台一级分类</th>
                        <th data-options="width:150,align:'center',field:'sge_bclass2'">后台二级分类</th>
                        <th data-options="width:150,align:'center',field:'sge_bclass3'">后台三级分类</th>
                        <th data-options="width:150,align:'center',field:'sge_propety'">商品属性</th>
                        <th data-options="width:150,align:'center',field:'sge_price'">销售价格</th>
                        <th data-options="width:150,align:'center',field:'sge_count'">库存数量</th>
                        <th data-options="width:150,align:'center',field:'sge_online'">是否上线</th>
                        <th data-options="width:150,align:'center',field:'sge_limit'">每单限购</th>
                        <th data-options="width:150,align:'center',field:'sge_type'">商品类型</th>
                        <th data-options="width:150,align:'center',field:'sge_weight'">重量(g)</th>
                        <th data-options="width:150,align:'center',field:'sge_bs_e_id'">店铺饿百ID</th>
                        <th data-options="width:150,align:'center',field:'sge_shop_name'">店铺名称</th>
                    </tr>
                </thead>
            </table>                
            <input type="hidden" id="hid_tbn"/>
        </div>
        <div id="ebsg_w_price_log" class="easyui-window" title="价格更新日志" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:1024px;height:600px;">
            <div class="easyui-layout" data-options="fit:true">                            
                <div data-options="region:'center'">
                    <table id="ebsg_dg_price_log" class="easyui-datagrid" toolbar="#dom_tb_price_log" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                        <thead>
                            <tr>
                                <th data-options="width:120,align:'center',field:'bs_shop_name',formatter:shopNameFormat">店铺名称</th>
                                <th data-options="width:80,align:'center',field:'lspe_error_no',formatter:updateErrNoFormat">结果状态</th>
                                <th data-options="width:180,align:'center',field:'lspe_dt'">更新时间</th>
                                <th data-options="width:80,align:'center',field:'lspe_user'">操作人</th>
                            </tr>
                        </thead>
                    </table>
                    <div id="dom_tb_price_log">
                        <div>
                            <input id="s_price_log_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="method:'get',valueField:'id', textField:'text',url: '../AdShopInfoYJC/getShopEbIdList'" />
                            <select id="s_price_log_status" class="easyui-combobox" labelWidth="45" style="width:135px;" label='结果:' labelPosition='left'>
                                <option value="ALL" selected="true">所有</option>
                                <option value="SUCCESS">成功</option>
                                <option value="FAIL">失败</option>
                            </select>
                            <a id="btn_price_log_search" href="#" iconCls="icon-search" class="easyui-linkbutton">查询</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                            <a id="btn_price_log_clear" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">清空日志</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        </div>
                    </div>
                </div>
                <div data-options="region:'east',title:'结果详情',split:true" style="width:40%;">
                    <div id="dom_log_price_msg"></div>
                </div>
            </div>
        </div>
        <div id="ebsg_w_stock_log" class="easyui-window" title="库存更新日志" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:1024px;height:600px;">
            <div class="easyui-layout" data-options="fit:true">                            
                <div data-options="region:'center'">
                    <table id="ebsg_dg_stock_log" class="easyui-datagrid" toolbar="#dom_tb_log" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                        <thead>
                            <tr>
                                <th data-options="width:120,align:'center',field:'bs_shop_name',formatter:shopNameFormat">店铺名称</th>
                                <th data-options="width:80,align:'center',field:'lue_error_no',formatter:updateErrNoFormat">结果状态</th>
                                <th data-options="width:180,align:'center',field:'lue_dt'">更新时间</th>
                                <th data-options="width:80,align:'center',field:'lue_user'">操作人</th>
                            </tr>
                        </thead>
                    </table>
                    <div id="dom_tb_log">
                        <div>
                            <input id="s_stock_log_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="method:'get',valueField:'id', textField:'text',url: '../AdShopInfoYJC/getShopEbIdList'" />
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
                    <div id="dom_log_msg"></div>
                </div>
            </div>
        </div>
        <div id="ebsg_w_sku_log" class="easyui-window" title="SKU更新日志" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:1024px;height:600px;">
            <div class="easyui-layout" data-options="fit:true">                            
                <div data-options="region:'center'">
                    <table id="ebsg_dg_sku_log" class="easyui-datagrid" toolbar="#ebsg_tb_sku_log" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                        <thead>
                            <tr>
                                <th data-options="width:120,align:'center',field:'bs_shop_name',formatter:shopNameFormat">店铺名称</th>
                                <th data-options="width:80,align:'center',field:'lse_error_no',formatter:updateErrNoFormat">结果状态</th>
                                <th data-options="width:180,align:'center',field:'lse_dt'">更新时间</th>
                                <th data-options="width:80,align:'center',field:'lse_user'">操作人</th>
                            </tr>
                        </thead>
                    </table>
                    <div id="ebsg_tb_sku_log">
                        <div>
                            <input id="s_sku_log_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="method:'get',valueField:'id', textField:'text',url: '../AdShopInfoYJC/getShopEbIdList'" />
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
                    <div id="ebsg_msg_sku_log"></div>
                </div>
            </div>
        </div>
        <div id="ebsg_w_update_file_download" class="easyui-window" title="库存更新文件下载" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:500px;height:400px;">
            <table id="dg_opfl" class="easyui-datagrid" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getOPFList/'">
                <thead>
                    <tr>
                        <th data-options="width:260,align:'center',field:'opf_filename'">文件名</th>
                        <th data-options="width:80,align:'center',field:'opf_url',formatter:opfURLFormat">文件地址</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="ebsg_new_goods" class="easyui-window"  data-options="iconCls:'icon-down',modal:true,closed:true,title:'未上线产品',tools:'#ebsg_tb_ng'" style='width:750px;height:500px;'>
            <table id="ebsg_dg_new_goods" class="easyui-datagrid" data-options="fit:true,border:false,tools:'#tb_ng',rownumbers:true,singleSelect:true,method:'post'">
                <thead>
                    <tr>
                        <th data-options="width:120,align:'center',field:'csge_shop_name'">门店</th>
                        <th data-options="width:220,align:'center',field:'csge_name'">品名</th>
                        <th data-options="width:150,align:'center',field:'csge_barcode'">条码</th>
                        <th data-options="width:60,align:'center',field:'csge_count'">数量</th>
                        <th data-options="width:70,align:'center',field:'csge_sale_price'">零售价</th>
                        <th data-options="width:70,align:'center',field:'csge_settlement_price'">结算价</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="ebsg_tb_ng">
            <a id="tb_ng_dl" href="#" class="icon-down"></a>
        </div>
        <div id="ebsg_win_input" class="easyui-window" title="导入饿百店铺商品" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:350px;height:260px;padding:10px;">
            <form id="form_input" method="post" enctype="multipart/form-data">	
                <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_饿百店铺商品-1站.csv") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
                <br/><br/>
                <input name="e_shop_id" id="dom_shop_id" class="easyui-combobox" labelWidth="45" style="width:100%;" label='店铺' labelPosition='after' data-options="method:'get',valueField:'id', textField:'text'" />
                <input name="file_csv" class="easyui-filebox" data-options="prompt:'选择一个CSV文件...'" style="width:100%;">
                <br/><br/>
                <a id="btn_do_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
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
        <script src="<?php echo base_url("/resource/admin/baseConfig/EBShopGoods.js?" . rand()) ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/EBShopGoods.SyncStorage.js?" . rand()) ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/EBShopGoods.SyncSKU.js?" . rand()) ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/EBShopGoods.SyncPrice.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
