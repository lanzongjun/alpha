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
            <div title="易捷总商品库" data-options="region:'center',split:true">
                <table id="dg_gi_yj" toolbar="#giyj_dom_toolbar1" class="easyui-datagrid" data-options="fit:true,border:false,rownumbers:true,singleSelect:false,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                    <thead>
                        <tr>
                            <th data-options="field:'ck', checkbox:true"></th>
                            <th data-options="width:80,align:'center',field:'bgs_code'">商品编码</th>
                            <th data-options="width:120,align:'center',field:'bgs_barcode'">条形码</th>
                            <th data-options="width:80,align:'center',field:'bgs_dispatching'">配送类型</th>
                            <th data-options="width:300,align:'center',field:'bgs_name'">商品名称</th>
                            <th data-options="width:80,align:'center',field:'bgs_sale_online',formatter:goodsSaleFormat">线上可售</th>
                            <th data-options="width:80,align:'center',field:'bgs_state',formatter:goodsStateFormat">状态</th>
                            <th data-options="width:170,align:'center',field:'bgs_update_dt'">更新时间</th>
                            <th data-options="width:80,align:'center',field:'bgs_band_id'">品牌</th>
                            <th data-options="width:50,align:'center',field:'bgs_sales_min'">单位</th>
                            <th data-options="width:80,align:'center',field:'bgs_package'">包装规格</th>
                            <th data-options="width:80,align:'center',field:'bgs_purchase_min'">最小要货</th>
                            <th data-options="width:80,align:'center',field:'bgs_production'">产地</th>
                            <th data-options="width:80,align:'center',field:'bgs_biz_pattern'">经营方式</th>
                            <th data-options="width:80,align:'center',field:'bgs_expire_exchange'">临期退换</th>
                            <th data-options="width:80,align:'center',field:'bgs_storage_time'">保质期</th>
                            <th data-options="width:80,align:'center',field:'bgs_storage_form'">存储方式</th>
                        </tr>
                    </thead>
                </table>
                <div id="giyj_dom_toolbar1">
                    <div>
                        <input id="q_goods_name" class="easyui-textbox" labelWidth="45" style="width:170px;" label='品名:' labelPosition='left'/>
                        <input id="q_barcode" class="easyui-textbox" labelWidth="45" style="width:170px;" label="条码:" labelPosition="left"/>
                        <select id="q_dispatching" class="easyui-combobox" labelWidth="45" style="width:125px;" label='配送:' labelPosition='left' editable="false">
                            <option value="ALL" selected="true">所有</option>
                            <option value="WEEK">周配</option>
                            <option value="H_MONTH">半月配</option>
                        </select>
                        <select id="q_sale" class="easyui-combobox" labelWidth="45" style="width:125px;" label='可售:' labelPosition='left' editable="false">
                            <option value="ALL" selected="true">所有</option>
                            <option value="1">可销售</option>
                            <option value="0">不可售</option>
                            <option value="-1">未判断</option>
                        </select>
                        <select id="q_state" class="easyui-combobox" labelWidth="45" style="width:125px;" label='状态:' labelPosition='left' editable="false">
                            <option value="ALL" selected="true">所有</option>
                            <option value="NORMAL">正常</option>
                            <option value="DELETE">删除</option>
                            <option value="NEW">新增</option>
                        </select>
                        <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <a id="btn_output" href="#" data-options="iconCls:'icon-print'" class="easyui-linkbutton">导出</a>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <a id="btn_batsale" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">批量可售</a>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                    </div>
                </div>
            </div>
            <div data-options="region:'east',split:true,title:'附加信息',collapsible:true,collapsed:true,hideCollapsedContent:false" style="width:400px;">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'north',border:false,collapsible:false" style="height:40px">
                        <div style="padding:10px;">
                            <span style="font-weight:bold;width:180px;text-align:left;display:inline-block;"><span>零售价:</span><span id="dom_yj_price"></span></span> <span style="font-weight:bold;width:150px;text-align:left;display:inline-block;"><span>结算价:</span><span id="dom_yj_balance"></span></span>
                        </div>                            
                    </div>
                    <div data-options="region:'center',title:'站点信息',collapsible:false" style="height:300px;">
                        <table id="dg_ext_1" class="easyui-datagrid" data-options="border:false,fit:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/loadOfflineInfo/'">
                            <thead>
                                <tr>
                                    <th data-options="width:260,align:'center',field:'bssy_org_name'">站点</th>
                                    <th data-options="width:50,align:'center',field:'bssy_count'">库存</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div data-options="region:'south',border:false,title:'线上信息',collapsible:false" style="height:300px;">
                        <table id="dg_ext_2" class="easyui-datagrid" data-options="border:false,fit:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/loadOnlineInfo/'">
                            <thead>
                                <tr>
                                    <th data-options="width:150,align:'center',field:'sge_shop_name'">商户</th>
                                    <th data-options="width:80,align:'center',field:'sge_price'">售价</th>
                                    <th data-options="width:80,align:'center',field:'sge_count'">库存</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="w_edit_giyj" class="easyui-window" title="编辑商品信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:500px;height:240px;padding:5px;">
            <form id="f_edit_giyj" method="post">
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" name="bgs_code" data-options="editable:false,labelWidth:'100px',label:'商品编码:',width:'400px',required:true">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" name="bgs_barcode" data-options="labelWidth:'100px',label:'条形码:',width:'400px',required:true">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" name="bgs_name" data-options="labelWidth:'100px',label:'商品名称:',width:'400px',required:true">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <select name="bgs_sale_online" class="easyui-combobox"  data-options="editable:false,labelWidth:'100px',label:'线上可售:',width:'400px',required:true">
                        <option value="1">可销售</option>
                        <option value="0">不可售</option>
                        <option value="-1">未判断</option>
                    </select>
                </div>
                <div style="text-align:center;padding:5px 0">
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditForm()" style="width:80px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWin()" style="width:80px">取消</a>
                </div>
            </form>
        </div>
        <div id="w_bat_sale_giyj" class="easyui-window" title="批量设置可售状态" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:280px;height:130px;padding:5px;">
            <form id="f_bat_sale_giyj" method="post">
                <div style="margin-left:5px;margin-bottom:5px">
                    <input type="hidden" name="bat_sale_ids" id="hid_bat_sale_ids"/>                
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <select name="bgs_sale_online" class="easyui-combobox"  data-options="editable:false,labelWidth:'100px',label:'线上可售:',width:'220px',required:true">
                        <option value="1">可销售</option>
                        <option value="0">不可售</option>
                        <option value="-1">未判断</option>
                    </select>
                </div>
                <div style="text-align:center;padding:5px 0">
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveBatSale()" style="width:80px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeBatSale()" style="width:80px">取消</a>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/GoodsInfoYJ.js?" . rand()) ?>" type="text/javascript"></script>    
    </body>
</html>
