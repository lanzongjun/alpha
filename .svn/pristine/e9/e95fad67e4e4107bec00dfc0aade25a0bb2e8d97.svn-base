<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/default/easyui.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/icon.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/demo.css") ?>">
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.min.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.easyui.min.js") ?>"></script>
        <style type="text/css">
            .datagrid-header-rownumber, .datagrid-cell-rownumber {
                width: 40px;
            }
            .mylink {
                font-weight:bolder; 
                color:#0000CD; 
                text-decoration:underline; 
            }
        </style>
    </head>
    <body>
        <div id="layout_room" class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center'">
                <div id="tab_box" class="easyui-tabs" data-options="fit:true,border:false">
                    <div data-options="title:'市场元数据',border:false">
                        <table id="dg" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                            <thead>
                                <tr>
                                    <th data-options="width:80,align:'center',field:'smo_class1'">大类</th>
                                    <th data-options="width:80,align:'center',field:'smo_class2'">小类</th>
                                    <th data-options="width:150,align:'center',field:'smo_gname'">品名</th>
                                    <th data-options="width:50,align:'center',field:'smo_sale_count'">销量</th>
                                    <th data-options="width:80,align:'center',field:'smo_price'">售价</th>
                                    <th data-options="width:50,align:'center',field:'smo_price_old'">原价</th>
                                    <th data-options="width:180,align:'center',field:'smo_catch_dt'">获取时间</th>
                                    <th data-options="width:150,align:'center',field:'smo_catch_source'">获取来源</th>
                                </tr>
                            </thead>
                        </table>
                        <div id="dom_toolbar1">
                            <div>                  
                                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                                <input id="q_ow_list" class="easyui-combobox" labelWidth="60" style="width:170px;" label='数据仓:' labelPosition='left' data-options="url:'../<?php echo $c_name; ?>/getOWList', method:'get',valueField:'id', textField:'text'" />
                                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                                <input id="s_shop" class="easyui-textbox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left'/>
                                <input id="s_goods" class="easyui-textbox" labelWidth="60" style="width:150px;" label="商品名:" labelPosition="left"/>
                                <a id="btn_search" href="#" iconCls="icon-search" class="easyui-linkbutton">查询</a>
                                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                                <a id="btn_showall" href="#" iconCls="icon-search" class="easyui-linkbutton">显示全部</a>
                                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                            </div>
                        </div>
                    </div>
                    <div title="预览">
                        <table id="dg2" class="easyui-datagrid" data-options="fit:true,border:false, rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
                            <thead>
                                <tr>
                                    <th data-options="width:50,align:'center',field:'smo_code'">编号</th>
                                    <th data-options="width:100,align:'center',field:'smo_class1'">大类</th>
                                    <th data-options="width:100,align:'center',field:'smo_class2'">小类</th>
                                    <th data-options="width:150,align:'center',field:'smo_gname'">品名</th>
                                    <th data-options="width:50,align:'center',field:'smo_sale_count'">销量</th>
                                    <th data-options="width:80,align:'center',field:'smo_price'">售价</th>
                                    <th data-options="width:50,align:'center',field:'smo_price_old'">原价</th>
                                    <th data-options="width:180,align:'center',field:'smo_catch_dt'">获取时间</th>
                                    <th data-options="width:100,align:'center',field:'smo_catch_source'">获取来源</th>
                                </tr>
                            </thead>
                        </table>
                        <input type="hidden" id="hid_tbn"/>
                        <input type="hidden" id="hid_sui_id"/>
                        <input type="hidden" id="hid_sow_id"/>
                    </div>
                </div>
            </div>
            <div data-options="region:'east',split:true,hideCollapsedContent:false,title:'上传列表'" style='width:590px;'>
                <table id="dg_smo_file" class="easyui-datagrid" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getCSVList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                    <thead>
                        <tr>
                            <th data-options="width:300,align:'center',field:'sui_filename'">文件名</th>
                            <th data-options="width:120,align:'center',field:'sow_name',formatter:smoOWFormat">数据仓</th>
                            <th data-options="width:110,align:'center',field:'sui_id',formatter:smoOpFormat">操作</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>        
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/interface/SpiderMarket.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
