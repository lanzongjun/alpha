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
        <table id="dg" title="易捷店铺信息" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
                <tr>
                    <th data-options="width:80,align:'center',field:'bs_org_sn'">组织编码</th>
                    <th data-options="width:80,align:'center',field:'bs_sale_sn'">销售编码</th>
                    <th data-options="width:50,align:'center',field:'bs_district'">城区</th>
                    <th data-options="width:100,align:'center',field:'bs_shop_name'">门店名称</th>
                    <th data-options="width:60,align:'center',field:'bs_master_name'">站长</th>
                    <th data-options="width:110,align:'center',field:'bs_master_phone'">站长电话</th>
                    <th data-options="width:60,align:'center',field:'bs_admin_name'">管理员</th>
                    <th data-options="width:110,align:'center',field:'bs_admin_phone'">管理员电话</th>
                    <th data-options="width:150,align:'center',field:'bs_email',formatter:formatMail">站点邮箱</th>
                    <th data-options="width:90,align:'center',field:'bs_phone'">站点电话</th>
                    <th data-options="width:110,align:'center',field:'bs_mphone'">站点手机</th>
                    <th data-options="width:80,align:'center',field:'bs_delivery_manager'">配送经理</th>
                    <th data-options="width:110,align:'center',field:'bs_delivery_phone'">配送经理电话</th>
                    <th data-options="width:80,align:'center',field:'bs_region_manager'">区域经理</th>
                    <th data-options="width:110,align:'center',field:'bs_region_phone'">区域经理电话</th>
                    <th data-options="width:80,align:'center',field:'bs_e_delivery_type'">饿百配送</th>
                    <th data-options="width:95,align:'center',field:'bs_e_create_dt'">饿百建店</th>
                    <th data-options="width:120,align:'center',field:'bs_e_id',formatter:formatEID">饿百ID</th>
                    <th data-options="width:120,align:'center',field:'bs_e_api_id'">饿百APID</th>
                    <th data-options="width:120,align:'center',field:'bs_m_id',formatter:formatMID">美团ID</th>
                    <th data-options="width:120,align:'center',field:'bs_m_api_id'">美团APIID</th>
                    <th data-options="width:300,align:'center',field:'bs_addr'">详细地址</th>
                    <th data-options="width:80,align:'center',field:'bs_e_account',formatter:formatEA"">饿百账号</th>
                </tr>
            </thead>
        </table>
        <div id="dom_toolbar1">
            <div>
                <input id="q_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="url:'../AdShopInfoYJC/getShopOrgList', method:'get',valueField:'id', textField:'text'" />
                <input id="q_district" class="easyui-combobox" labelWidth="45" style="width:130px;" label="城区:" labelPosition="left" data-options="url:'../<?php echo $c_name; ?>/getDistrict', method:'get',valueField:'text', textField:'text'"/>
                <input id="q_e_delivery_type" class="easyui-combobox" labelWidth="45" style="width:130px;" label="配送:" labelPosition="left" data-options="url:'../<?php echo $c_name; ?>/getEDeliveryType', method:'get',valueField:'text', textField:'text'"/>
                <input id="q_addr" class="easyui-textbox" labelWidth="45" style="width:180px;" label="地址:" labelPosition="left"/>
                <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
                <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
                <a id="sifyj_btn_sync_info" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">同步石化</a>                
                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            </div>
        </div>
        <div id="w_edit_shop" class="easyui-window" title="编辑站点信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:740px;height:570px;padding:5px;">
            <form id="f_edit_shop" method="post">
                <table>
                    <tr>
                        <td>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-numberbox" name="bs_org_sn" data-options="labelWidth:'100px',label:'组织编码:',width:'220px',required:true">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-numberbox" name="bs_sale_sn" data-options="labelWidth:'100px',label:'销售编码:',width:'220px',required:true">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-numberbox" name="bs_shop_sn" data-options="labelWidth:'100px',label:'店铺编码:',width:'220px',required:true">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_shop_name" data-options="labelWidth:'100px',label:'店铺名称:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_province" data-options="labelWidth:'100px',label:'所属省份:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_city" data-options="labelWidth:'100px',label:'所属城市:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_district" data-options="labelWidth:'100px',label:'所属城区:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_phone" data-options="labelWidth:'100px',label:'站点电话:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_mphone" data-options="labelWidth:'100px',label:'站点手机:',width:'220px'">
                            </div>
                        </td>
                        <td>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_master_name" data-options="labelWidth:'100px',label:'站长:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_master_phone" data-options="labelWidth:'100px',label:'站长电话:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_admin_name" data-options="labelWidth:'100px',label:'管理员:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_admin_phone" data-options="labelWidth:'100px',label:'管理员电话:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_email" data-options="labelWidth:'100px',label:'站点邮箱:',width:'220px',validType:'email'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-numberbox" name="bs_e_id" data-options="labelWidth:'100px',label:'饿百店铺ID:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_e_api_id" data-options="labelWidth:'100px',label:'饿百APIID:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_e_account" data-options="labelWidth:'100px',label:'饿百账号:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_e_password" data-options="labelWidth:'100px',label:'饿百密码:',width:'220px'">
                            </div>
                        </td>
                        <td>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_e_delivery_type" data-options="labelWidth:'100px',label:'饿百配送种类:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-datebox" name="bs_e_create_dt" data-options="labelWidth:'100px',label:'饿百开店时间:',width:'220px',formatter:myformatter,parser:myparser">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-numberbox" name="bs_m_id" data-options="labelWidth:'100px',label:'美团店铺ID:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_m_api_id" data-options="labelWidth:'100px',label:'美团APIID:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-datebox" name="bs_m_create_dt" data-options="labelWidth:'100px',label:'美团开店时间:',width:'220px',formatter:myformatter,parser:myparser">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_delivery_manager" data-options="labelWidth:'100px',label:'配送经理:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_delivery_phone" data-options="labelWidth:'100px',label:'配送经理电话:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_region_manager" data-options="labelWidth:'100px',label:'区域经理:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_region_phone" data-options="labelWidth:'100px',label:'区域经理电话:',width:'220px'">
                            </div>
                        </td>
                    </tr>
                </table>
                <input name="bs_id" type="hidden"/>                            
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" style="width:100%;height:80px" name="bs_addr" data-options="multiline:true,labelWidth:'100px',label:'详细地址:',width:'220px'">
                </div>
                <div style="text-align:center;padding:5px 0">
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditForm()" style="width:80px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWin()" style="width:80px">取消</a>
                </div>
            </form>
        </div>
        <div id="w_add_shop" class="easyui-window" title="新增站点信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:740px;height:570px;padding:5px;">
            <form id="f_add_shop" method="post">
                <table>
                    <tr>
                        <td>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-numberbox" name="bs_org_sn" data-options="labelWidth:'100px',label:'组织编码:',width:'220px',required:true">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-numberbox" name="bs_sale_sn" data-options="labelWidth:'100px',label:'销售编码:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-numberbox" name="bs_shop_sn" data-options="labelWidth:'100px',label:'店铺编码:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_shop_name" data-options="labelWidth:'100px',label:'店铺名称:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_province" data-options="labelWidth:'100px',label:'所属省份:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_city" data-options="labelWidth:'100px',label:'所属城市:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_district" data-options="labelWidth:'100px',label:'所属城区:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_phone" data-options="labelWidth:'100px',label:'站点电话:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_mphone" data-options="labelWidth:'100px',label:'站点手机:',width:'220px'">
                            </div>
                        </td>
                        <td>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_master_name" data-options="labelWidth:'100px',label:'站长:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_master_phone" data-options="labelWidth:'100px',label:'站长电话:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_admin_name" data-options="labelWidth:'100px',label:'管理员:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_admin_phone" data-options="labelWidth:'100px',label:'管理员电话:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_email" data-options="labelWidth:'100px',label:'站点邮箱:',width:'220px',validType:'email'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-numberbox" name="bs_e_id" data-options="labelWidth:'100px',label:'饿百店铺ID:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_e_api_id" data-options="labelWidth:'100px',label:'饿百APIID:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_e_account" data-options="labelWidth:'100px',label:'饿百账号:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_e_password" data-options="labelWidth:'100px',label:'饿百密码:',width:'220px'">
                            </div>
                        </td>
                        <td>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_e_delivery_type" data-options="labelWidth:'100px',label:'饿百配送种类:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-datebox" name="bs_e_create_dt" data-options="labelWidth:'100px',label:'饿百开店时间:',width:'220px',formatter:myformatter,parser:myparser">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-numberbox" name="bs_m_id" data-options="labelWidth:'100px',label:'美团店铺ID:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_m_api_id" data-options="labelWidth:'100px',label:'美团APIID:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-datebox" name="bs_m_create_dt" data-options="labelWidth:'100px',label:'美团开店时间:',width:'220px',formatter:myformatter,parser:myparser">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_delivery_manager" data-options="labelWidth:'100px',label:'配送经理:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_delivery_phone" data-options="labelWidth:'100px',label:'配送经理电话:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_region_manager" data-options="labelWidth:'100px',label:'区域经理:',width:'220px'">
                            </div>
                            <div style="margin-left:5px;margin-bottom:5px">
                                <input class="easyui-textbox" name="bs_region_phone" data-options="labelWidth:'100px',label:'区域经理电话:',width:'220px'">
                            </div>
                        </td>
                    </tr>
                </table>                           
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" style="width:100%;height:80px" name="bs_addr" data-options="multiline:true,labelWidth:'100px',label:'详细地址:',width:'220px'">
                </div>
                <div style="text-align:center;padding:5px 0">
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveAddForm()" style="width:80px">保存</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeAddWin()" style="width:80px">取消</a>
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
        <script src="<?php echo base_url("/resource/admin/baseConfig/ShopInfoYJ.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
