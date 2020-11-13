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
        <table id="dg" class="easyui-datagrid" title="易捷站点库存信息" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',url:'',pagination:true,pageSize:100,pageList: [50, 100, 200, 300],toolbar:d_dgs_toolbar">
            <thead>
                <tr>
                    <th data-options="width:80,align:'center',field:'bssy_org_code'">组织编码</th>
                    <th data-options="width:150,align:'center',field:'bssy_org_name'">组织名称</th>
                    <th data-options="width:80,align:'center',field:'bssy_yj_code'">商品编码</th>
                    <th data-options="width:150,align:'center',field:'bssy_goods_name'">商品名称</th>
                    <th data-options="width:130,align:'center',field:'bssy_barcode'">条形码</th>
                    <th data-options="width:80,align:'center',field:'bssy_count'">库存数量</th>
                    <th data-options="width:50,align:'center',field:'bssy_unit'">单位</th>
                    <th data-options="width:50,align:'center',field:'bssy_specs'">规格</th>
                    <th data-options="width:50,align:'center',field:'bssy_settlement_type'">方式</th>
                    <th data-options="width:90,align:'center',field:'bssy_supplier_code'">供应商编码</th>
                    <th data-options="width:200,align:'center',field:'bssy_supplier_name'">供应商名称</th>
                    <th data-options="width:100,align:'center',field:'bssy_cost_duty'">含税库存成本</th>
                    <th data-options="width:100,align:'center',field:'bssy_cost_free'">无税库存成本</th>
                    <th data-options="width:80,align:'center',field:'bssy_update_dt'">更新时间</th>
                </tr>
            </thead>
        </table>
        <div id="d_dgs_toolbar">
            <div>
                <a id="btn_upload" iconCls='icon-add' href="#" class="easyui-linkbutton">预导入</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <a id="btn_update_base" iconCls='icon-reload' href="#" class="easyui-linkbutton">更新商品库</a>
                <a id="btn_update_cache" iconCls='icon-reload' href="#" class="easyui-linkbutton">更新缓存</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>                        
                <a id="btn_search_win" iconCls='icon-search' href="#" class="easyui-linkbutton">自定义查询</a>
<!--                <a id="btn_sync" iconCls='icon-reload' href="#" class="easyui-linkbutton">获取库存</a>
                <a id="btn_sync_sku" iconCls='icon-reload' href="#" class="easyui-linkbutton">获取SKU</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <a id="btn_storage_start" iconCls='icon-play' href="#" class="easyui-linkbutton">启动自动更新</a>
                <a id="btn_storage_pause" iconCls='icon-stop' href="#" class="easyui-linkbutton">停止自动更新</a>
                <a id="btn_storage_auto" iconCls='icon-power-off' href="#" disabled="true" class="easyui-linkbutton"></a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>                -->
            </div>
        </div>
        <div id="yjss_w_preview" class="easyui-window" title="数据预览" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:1024px;height:768px;">
            <table id="yjss_dg_preview" class="easyui-datagrid" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:100,pageList: [50, 100, 200, 300],toolbar:toolbar1">
                <thead>
                    <tr>
                        <th data-options="width:80,align:'center',field:'bssy_org_code'">组织编码</th>
                        <th data-options="width:150,align:'center',field:'bssy_org_name'">组织名称</th>
                        <th data-options="width:80,align:'center',field:'bssy_yj_code'">商品编码</th>
                        <th data-options="width:150,align:'center',field:'bssy_goods_name'">商品名称</th>
                        <th data-options="width:110,align:'center',field:'bssy_barcode'">条形码</th>
                        <th data-options="width:50,align:'center',field:'bssy_unit'">单位</th>
                        <th data-options="width:50,align:'center',field:'bssy_specs'">规格</th>
                        <th data-options="width:80,align:'center',field:'bssy_count'">库存数量</th>
                        <th data-options="width:100,align:'center',field:'bssy_cost_duty'">含税库存成本</th>
                        <th data-options="width:100,align:'center',field:'bssy_cost_free'">无税库存成本</th>
                        <th data-options="width:60,align:'center',field:'bssy_settlement_type'">结算方式</th>
                        <th data-options="width:80,align:'center',field:'bssy_supplier_code'">供应商编码</th>
                        <th data-options="width:90,align:'center',field:'bssy_supplier_name'">供应商名称</th>
                    </tr>
                </thead>
            </table>
            <input type="hidden" id="hid_tbn_preview"/>
        </div>
        <div id="yjss_win_input" class="easyui-window" title="导入数据" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:300px;height:200px;padding:10px;">
            <form id="form_input" method="post" enctype="multipart/form-data">	
                <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_易捷各站库存.csv") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
                <br/><br/>
                <input name="file_csv" class="easyui-filebox" data-options="prompt:'选择一个CSV文件...'" style="width:100%">
                <br/><br/>
                <a id="btn_do_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
            </form>
        </div>
        <div id="yjss_win_search" class="easyui-window" title="自定义查询" data-options="modal:true,closed:true,iconCls:'icon-search'" style="width:400px;height:450px;padding:10px;">
            <form id="yjss_f_search" method="post">
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-numberbox" id="q_bssy_org_code" name="bssy_org_code" data-options="labelWidth:'100px',label:'组织编码:',width:'300px'">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" id="q_bssy_org_name" name="bssy_org_name" data-options="labelWidth:'100px',label:'组织名称:',width:'300px'">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-numberbox" id="q_bssy_yj_code" name="bssy_yj_code" data-options="labelWidth:'100px',label:'商品编码:',width:'300px'">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" id="q_bssy_goods_name" name="bssy_goods_name" data-options="labelWidth:'100px',label:'商品名称:',width:'300px'">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-numberbox" id="q_bssy_barcode" name="bssy_barcode" data-options="labelWidth:'100px',label:'条形码:',width:'300px'">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <select id="q_bssy_count_type" class="easyui-combobox" labelWidth="100px" style="width:300px;" label='库存数量:' labelPosition='left' editable="false">
                        <option value="MORE" selected="true">大于</option>
                        <option value="LESS">小于</option>
                        <option value="EQUAL">等于</option>
                    </select>
                </div>                
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-numberbox" id="q_bssy_count" name="bssy_count" data-options="label:'库存数量:',labelWidth:'100px',width:'300px'">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-numberbox" id="q_bssy_supplier_code" name="bssy_supplier_code" data-options="labelWidth:'100px',label:'供应商编码:',width:'300px'">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" id="q_bssy_supplier_name" name="bssy_supplier_name" data-options="labelWidth:'100px',label:'供应商名称:',width:'300px'">
                </div>
                <div style="text-align:center;padding:5px 0">
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="doSearch()" style="width:80px">查询</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeSWin()" style="width:80px">取消</a>
                </div>
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
        <script src="<?php echo base_url("/resource/admin/baseConfig/YJShopStorage.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
