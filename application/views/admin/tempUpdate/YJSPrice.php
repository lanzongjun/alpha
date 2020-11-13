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
            <div data-options="region:'center',border:false">
                <div id="tab_box" class="easyui-tabs" data-options="fit:true">
                    <div title="易捷结算价目录">
                        <table id="dg" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',url:'../AdYJSPriceC/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                            <thead>
                                <tr>
                                    <th data-options="width:150,align:'center',field:'bbp_bar_code'">条形码</th>
                                    <th data-options="width:80,align:'center',field:'bbp_yj_code'">商品编码</th>
                                    <th data-options="width:280,align:'center',field:'bbp_goods_name'">商品名称</th>
                                    <th data-options="width:90,align:'center',field:'bbp_settlement_price'">结算价</th>
                                    <th data-options="width:100,align:'center',field:'bbp_yj_sale_price'">线下零售价</th>
                                    <th data-options="width:80,align:'center',field:'bbp_specs'">规格</th>
                                    <th data-options="width:95,align:'center',field:'bbp_append_dt'">追加时间</th>
                                    <th data-options="width:95,align:'center',field:'bbp_update_dt'">更新时间</th>
                                    <th data-options="width:95,align:'center',field:'bbp_delete_dt'">删除时间</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="dom_toolbar1">
                        <div>
                            <a id="btn_todo_input" href="#" class="easyui-linkbutton" iconCls='icon-add'>预导入</a>                    
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                            <input id="s_barcode" class="easyui-textbox" labelWidth="60" style="width:200px;" label='条形码:' labelPosition='left'/>
                            <input id="s_goods" class="easyui-textbox" labelWidth="60" style="width:200px;" label="商品名:" labelPosition="left"/>
                            <a id="btn_search" href="#" iconCls='icon-search' class="easyui-linkbutton">查询</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        </div>
                    </div>
                    <div title="预览">
                        <table id="dg2" class="easyui-datagrid" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
                            <thead>
                                <tr>
                                    <th data-options="width:150,align:'center',field:'bbp_bar_code'">条形码</th>
                                    <th data-options="width:80,align:'center',field:'bbp_yj_code'">商品编码</th>
                                    <th data-options="width:280,align:'center',field:'bbp_goods_name'">商品名称</th>
                                    <th data-options="width:90,align:'center',field:'bbp_settlement_price'">结算价</th>
                                    <th data-options="width:100,align:'center',field:'bbp_yj_sale_price'">线下零售价</th>
                                    <th data-options="width:80,align:'center',field:'bbp_specs'">规格</th>
                                    <th data-options="width:80,align:'center',field:'bbp_dt'">时间</th>
                                </tr>
                            </thead>
                        </table>
                        <input type="hidden" id="hid_tbn"/>
                    </div>
                </div>
            </div>
            <div data-options="region:'east',split:true,hideCollapsedContent:false,collapsed:false,title:'缺失新品'" style='width:580px;'>
                <table id="dg_new_product" class="easyui-datagrid" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',url:'../AdYJSPriceC/getNewList/'">
                    <thead>
                        <tr>
                            <th data-options="width:130,align:'center',field:'cgbp_barcode'">条形码</th>
                            <th data-options="width:70,align:'center',field:'cgbp_code'">商品编码</th>
                            <th data-options="width:260,align:'center',field:'cgbp_name'">商品名称</th>
                            <th data-options="width:60,align:'center',field:'cgbp_sale_price'">零售价</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="yjsp_win_input" class="easyui-window" title="导入数据" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:300px;height:200px;padding:10px;">
                <form id="form_input" method="post" enctype="multipart/form-data">	
                    <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_易捷供货价目录.csv") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
                    <br/><br/>
                    <input name="file_csv" class="easyui-filebox" data-options="prompt:'选择一个CSV文件...'" style="width:100%">
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
            <script src="<?php echo base_url("/resource/admin/temp/YJSPrice.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
