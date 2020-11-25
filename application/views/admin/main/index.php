<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CVS管理中心</title>
        <link id="link_themes" rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/black/easyui.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/icon.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/demo.css") ?>">
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.min.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.easyui.min.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/easyui-lang-zh_CN.js") ?>"></script>
        
        <script>
            var __audio_path_order_new = "<?php echo base_url("/resource/audio/pikachu.m4a") ?>";
            var __audio_path_order_refund = "<?php echo base_url("/resource/audio/duanxin.m4a") ?>";
        </script>
    </head>
    <body class="easyui-layout">
        <div data-options="region:'north',border:false" class="system_title_panel">
            <SPAN onclick="onTitleClick()">CVS Manage System</SPAN>
            <div id="index_w_change_skin" class="easyui-window" title="更换皮肤" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:230px;height:120px;">
                <div style="margin-left:5px;margin-bottom:5px">
                    <select id="index_select_skin" class="easyui-combobox" editable="false" labelWidth="45" style="width:200px;" label='皮肤:' labelPosition='left'>
                        <option value="default" selected="true">default</option>
                        <option value="black">black</option>
                        <option value="bootstrap">bootstrap</option>
                        <option value="gray">gray</option>
                        <option value="material">material</option>
                        <option value="material-blue">material-blue</option>
                        <option value="material-teal">material-teal</option>
                        <option value="metro">metro</option>
                        <option value="ui-cupertino">ui-cupertino</option>
                        <option value="ui-dark-hive">ui-dark-hive</option>
                        <option value="ui-pepper-grinder">ui-pepper-grinder</option>
                        <option value="ui-sunny">ui-sunny</option>
                    </select>
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <a id="index_btn_skin" iconCls='icon-edit' href="#" onclick="doChangeSkin()" class="easyui-linkbutton">确定</a>
                </div>
            </div>
        </div>
        <div data-options="region:'west',split:true" style="width:150px;">            
            <div class="easyui-accordion" data-options="fit:true,border:false">                
                <div title="快捷-每日更新">
                    <a id='lnk_balance_account' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">易捷每日结算</a>
                    <a id='lnk_yj_shop_storage' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">易捷站点库存</a>
                    <a id='lnk_goods_info_yj' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">易捷总商品库</a>
                    <a id='lnk_temp_yj_price' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">易捷结算价格</a>
                    <a id='lnk_base_eb_ShopGoods' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">饿百店铺商品</a>
                    <a id='lnk_base_mt_ShopGoods' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">美团店铺商品</a>
                </div>
                <div title="导航">
                    <ul class="easyui-tree">
                        <li data-options="state:'closed'">
                            <span>大数据分析</span>
                            <ul>
                                <li>
                                    <span><a id='nav_market_original' href="#">对标元数据</a></span>
                                </li>
                            </ul>
                        </li>
                        <li data-options="state:'closed'">
                            <span>结算统计</span>
                            <ul>
                                <li>
                                    <span><a id='nav_balance_account' href="#">每日结算</a></span>
                                </li>
                                <li>
                                    <span><a id='nav_balance_record' href="#">结算记录</a></span>
                                </li>
<!--                                <li>
                                    <span><a id='nav_verification' href="#">核销</a></span>
                                </li>-->
                                <li>
                                    <span><a id='nav_invoice_manage' href="#">发票关联</a></span>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span>易捷数据</span>
                            <ul>
                                <li>
                                    <span><a id='nav_yj_cash_pool' href="#">资金池流水</a></span>
                                </li>
                                <li>
                                    <span><a id='nav_yj_shop_storage' href="#">站点库存</a></span>
                                </li>
                                <li data-options="state:'closed'">
                                    <span>临期管理</span>
                                    <ul>                                        
                                        <li>
                                            <span><a id='nav_onsale_yj' href="#">临期促销</a></span>                            
                                        </li>
                                        <li>
                                            <span><a id='nav_temp_yj_expire' href="#">临期导入</a></span>                            
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span><a id='nav_shop_info_yj' href="#">店铺信息</a></span>
                                </li>
                                <li>
                                    <span><a id='nav_temp_yj_price' href="#">结算价格</a></span>
                                </li>
                                <li>
                                    <span><a id="nav_goods_info_yj" href="#">总商品库</a></span>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span>饿百平台</span>
                            <ul>
                                <li data-options="state:'closed'">
                                    <span>订单管理</span>
                                    <ul>                                        
                                        <li>
                                            <span><a id='nav_base_OrdersInfoEB_TODO' href="#">接单</a></span>
                                        </li>
                                        <li>
                                            <span><a id='nav_base_OrderInfoEB_Refund' href="#">退单</a></span>
                                        </li>
                                        <li>
                                            <span><a id='nav_base_OrderInfoEB_Refund_Detail' href="#">退单详情</a></span>
                                        </li>
                                        <li>
                                            <span><a id='nav_base_OrderInfoEB' href="#">历史订单</a></span>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span><a id='nav_base_eb_ShopGoods' href="#">门店商品</a></span>
                                </li>
                                <li>
                                    <span><a id="nav_bill_info_eb" href="#">账单信息</a></span>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span>美团平台</span>
                            <ul>
                                <li>
                                    <span>订单管理</span>
                                    <ul>                                        
                                        <li>
                                            <span><a id='nav_base_OrderInfoMT_TODO' href="#">接单</a></span>
                                        </li>
                                        <li>
                                            <span><a id='nav_base_OrderInfoMT_Refund' href="#">退单</a></span> 
                                        </li>
                                        <li>
                                            <span><a id='nav_base_OrderInfoMT_Refund_Detail' href="#">退单详情</a></span>
                                        </li>
                                        <li>
                                            <span><a id='nav_base_OrderInfoMT' href="#">历史订单</a></span>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span><a id='nav_base_mt_ShopGoods' href="#">门店商品</a></span>
                                </li>
                                <li>
                                    <span><a id="nav_bill_info_mt" href="#">账单信息</a></span>
                                </li>                                
                            </ul>
                        </li>
                        <li data-options="state:'closed'">
                            <span>京东平台</span>
                            <ul>                                
                                <li>
                                    <span>正在建设</span>                            
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="layout_center" data-options="region:'center'"></div>
    </body>
    <script src="<?php echo base_url("/resource/admin/main.js?" . rand()) ?>" type="text/javascript"></script>
</html>