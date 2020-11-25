const COLOR_WARNING = '#F47983';
const ENUM_SEND_STATE_TODO = 'todo';
const ENUM_SEND_STATE_SUCCESS = 'success';
const ENUM_SEND_STATE_FAIL = 'fail';

var __o_mtoitd_refresh_handler;
var __o_mtoir_refresh_handler;
var __o_eboitd_refresh_handler;
var __o_eboir_refresh_handler;

var __audio_order_new = new Audio(__audio_path_order_new);
var __audio_order_refund = new Audio(__audio_path_order_refund);

function beforeOpen(){
    clearTimeout(__o_mtoitd_refresh_handler);
    clearTimeout(__o_mtoir_refresh_handler);
    clearTimeout(__o_eboitd_refresh_handler);
    clearTimeout(__o_eboir_refresh_handler);
}

var __i_OrderMonitor_refresh_rate = 30000;
var __o_OrderMonitor_refresh_handler;

function OrderMonitor(){
    $.ajax({
        url: '../AdMTOrderInfoC/isOrderToDo',
        type: "POST",
        success: function (data) {
            var i_res = data-0;
            console.log('美团订单监控 返回状态:'+i_res);
            if (i_res === 1){
                __audio_order_new.play();
            }
            if (i_res === 2){
                __audio_order_refund.play();
            }
            if (i_res === 3){
                __audio_order_new.play();
                __audio_order_refund.play();
            }
        }
    });
    __o_OrderMonitor_refresh_handler = setTimeout(OrderMonitor,__i_OrderMonitor_refresh_rate);
}

$(function () {
    OrderMonitor();
    $('#nav_base_eb_ShopGoods').bind('click', function () {
        beforeOpen();
        doShowShopGoods();
    });

    $('#nav_temp_yj_price').bind('click', function () {
        beforeOpen();
        doShowTempYJSPrice();
    });
    
    $('#nav_yj_shop_storage').bind('click', function () {
        beforeOpen();
        doShowYJShopStorage();
    });
    
    $('#nav_balance_account').bind('click', function () {
        beforeOpen();
        doShowBalanceAccount();
    });
    
    $('#nav_balance_record').bind('click', function () {
        beforeOpen();
        doShowBalanceRecord();
    });
    
    $('#nav_verification').bind('click', function () {
        beforeOpen();
        doShowVerification();
    });
    
    $('#nav_verify_count').bind('click', function () {
        beforeOpen();
        doShowVerifyCount();
    });
    
    $('#nav_verify_record').bind('click', function () {
        beforeOpen();
        doShowVerifyRecord();
    });
    
    $('#nav_invoice_manage').bind('click', function () {
        beforeOpen();
        doShowInvoiceManage();
    });
    
    $('#nav_base_OrdersInfoEB_TODO').bind('click', function () {
        beforeOpen();
        doOrderInfoEBToDo();
    });
    
    $('#nav_base_OrderInfoEB').bind('click', function () {
        beforeOpen();
        doShowEBOrdersInfo();
    });
    
    $('#nav_base_OrderInfoEB_Refund').bind('click', function () {
        beforeOpen();
        doShowEBOrdersRefundInfo();
    });

    $('#nav_base_OrderInfoEB_Refund_Detail').bind('click', function () {
        beforeOpen();
        doShowEBOrdersRefundDetail();
    });
    
    $('#nav_yj_cash_pool').bind('click', function () {
        beforeOpen();
        doShowYJCashPool();
    });
    
    $('#nav_bill_info_mt').bind('click', function () {
        beforeOpen();
        doShowMTBillInfo();
    });
    
    $('#nav_temp_yj_expire').bind('click', function () {
        beforeOpen();
        doShowTmpYJExpire();
    });
    
    $('#nav_onsale_yj').bind('click', function () {
        beforeOpen();
        doShowOnSaleYJ();
    });
    
    $('#nav_bill_info_eb').bind('click', function () {
        beforeOpen();
        doShowEBBillInfo();
    });
    
    $('#nav_shop_info_yj').bind('click', function () {
        beforeOpen();
        doShopInfoYJ();
    });
    
    $('#nav_market_original').bind('click', function () {
        beforeOpen();
        doMarketOriginal();
    });
    
    $('#nav_goods_info_yj').bind('click', function () {
        beforeOpen();
        doGoodsInfoYJ();
    });
    
    $('#nav_base_OrderInfoMT_TODO').bind('click', function () {
        beforeOpen();
        doOrderInfoMtToDo();
    });
    
    $('#nav_base_OrderInfoMT_Refund').bind('click', function () {
        beforeOpen();
        doOrderInfoMtRefund();
    });

    $('#nav_base_OrderInfoMT_Refund_Detail').bind('click', function () {
        beforeOpen();
        doOrderInfoMtRefundDetail();
    });
    
    $('#nav_base_OrderInfoMT').bind('click', function () {
        beforeOpen();
        doOrderInfoMt();
    });
    
    $('#nav_base_mt_ShopGoods').bind('click', function () {
        beforeOpen();
        doShowMTShopGoods();
    });
    
    $('#lnk_yj_shop_storage').bind('click', function () {
        beforeOpen();
        doShowYJShopStorage();
    });
    
    $('#lnk_goods_info_yj').bind('click', function () {
        beforeOpen();
        doGoodsInfoYJ();
    });
    
    $('#lnk_temp_yj_price').bind('click', function () {
        beforeOpen();
        doShowTempYJSPrice();
    });
    
    $('#lnk_base_eb_ShopGoods').bind('click', function () {
        beforeOpen();
        doShowShopGoods();
    });
    
    $('#lnk_base_mt_ShopGoods').bind('click', function () {
        beforeOpen();
        doShowMTShopGoods();
    });
    $('#lnk_balance_account').bind('click', function () {
        beforeOpen();
        doShowBalanceAccount();
    });
});

function onTitleClick(){
    $('#index_w_change_skin').window('open');
}

function doChangeSkin() {
    var s_skin = $('#index_select_skin').val();
    var s_href = "../../resource/admin/themes/"+s_skin+"/easyui.css";
    $('#link_themes').attr('href',s_href);
    $('#index_w_change_skin').window('close');
}

function doOrderInfoMtToDo(){
    $('#layout_center').panel({
        href: '../AdMTOrderInfoToDoC',
        onLoad: function () {

        }
    });
}

function doOrderInfoMtRefund(){
    $('#layout_center').panel({
        href: '../AdMTOrderRefundC',
        onLoad: function () {

        }
    });
}

function doOrderInfoMtRefundDetail(){
    $('#layout_center').panel({
        href: '../AdMTOrderRefundDetailC',
        onLoad: function () {

        }
    });
}

function doOrderInfoMt(){
    $('#layout_center').panel({
        href: '../AdMTOrderInfoC',
        onLoad: function () {

        }
    });
}

function doGoodsInfoYJ(){
    $('#layout_center').panel({
        href: '../AdYJGoodsInfoC',
        onLoad: function () {

        }
    });
}

function doMarketOriginal() {
    $('#layout_center').panel({
        href: '../IFSpiderDataC',
        onLoad: function () {

        }
    });
}

function doShowEBBillInfo() {
    $('#layout_center').panel({
        href: '../AdEBBillInfoC',
        onLoad: function () {

        }
    });
}

function doShopInfoYJ() {
    $('#layout_center').panel({
        href: '../AdShopInfoYJC',
        onLoad: function () {

        }
    });
}

function doShowOnSaleYJ() {
    $('#layout_center').panel({
        href: '../AdYJOnSaleC',
        onLoad: function () {

        }
    });
}

function doShowTmpYJExpire() {
    $('#layout_center').panel({
        href: '../AdTmpYJExpireC',
        onLoad: function () {

        }
    });
}

function doShowYJCashPool() {
    $('#layout_center').panel({
        href: '../AdYJCashPoolC',
        onLoad: function () {

        }
    });
}

function doShowMTBillInfo() {
    $('#layout_center').panel({
        href: '../AdMTBillInfoC',
        onLoad: function () {

        }
    });
}

function doShowOrderInfoEB(){
    $('#layout_center').panel({
        href: '../AdEBOrderInfoC/',
        onLoad: function () {

        }
    });
}

function doOrderInfoEBToDo(){
    $('#layout_center').panel({
        href: '../AdEBOrderInfoToDoC',
        onLoad: function () {
            
        }
    });
}

function doShowEBOrdersInfo(){
    $('#layout_center').panel({
        href: '../AdEBOrderInfoC',
        onLoad: function () {
            
        }
    });
}

function doShowEBOrdersRefundInfo(){
    $('#layout_center').panel({
        href: '../AdEBOrderRefundC',
        onLoad: function () {
            
        }
    });
}

function doShowEBOrdersRefundDetail(){
    $('#layout_center').panel({
        href: '../AdEBOrderRefundDetailC',
        onLoad: function () {

        }
    });
}

function doShowOrdersInfoEB(){
    $('#layout_center').panel({
        href: '../AdEBOrderInfoC',
        onLoad: function () {
            
        }
    });
}

function doShowBalanceAccount() {
    $('#layout_center').panel({
        href: '../AdBalanceAccountC',
        onLoad: function () {
        }
    });
}

function doShowBalanceRecord(){
    $('#layout_center').panel({
        href: '../AdBalanceRecordC',
        onLoad: function () {
        }
    });
}

function doShowVerification(){
    $('#layout_center').panel({
        href: '../AdVerificationC',
        onLoad: function () {
        }
    });
}

function doShowVerifyCount(){
    $('#layout_center').panel({
        href: '../AdVerifyCountC',
        onLoad: function () {
        }
    });
}

function doShowVerifyRecord(){
    $('#layout_center').panel({
        href: '../AdVerifyRecordC',
        onLoad: function () {
        }
    });
}

function doShowInvoiceManage(){
    $('#layout_center').panel({
        href: '../AdInvoiceManageC',
        onLoad: function () {
        }
    });
}

function doShowShopGoods() {
    $('#layout_center').panel({
        href: '../AdEBShopGoodsC',
        onLoad: function () {

        }
    });
}

function doShowMTShopGoods() {
    $('#layout_center').panel({
        href: '../AdMTShopGoodsC',
        onLoad: function () {

        }
    });
}

function doShowTempYJSPrice() {
    $('#layout_center').panel({
        href: '../AdYJSPriceC',
        onLoad: function () {

        }
    });
}

function doShowYJShopStorage() {
    $('#layout_center').panel({
        href: '../AdYJShopStorageC',
        onLoad: function () {

        }
    });
}

function ajaxLoading() {
    $("<div class=\"datagrid-mask\"></div>").css({display: "block", width: "100%", height: $(window).height()}).appendTo("body");
    $("<div class=\"datagrid-mask-msg\"></div>").html("正在处理，请稍候。。。").appendTo("body").css({height:'40px',display: "block", left: ($(document.body).outerWidth(true) - 190) / 2, top: ($(window).height() - 45) / 2});
}
function ajaxLoadEnd() {
    $(".datagrid-mask").remove();
    $(".datagrid-mask-msg").remove();
}

function myformatter(date) {
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
}

function myparser(s) {
    if (!s)
        return new Date();
    var ss = (s.split('-'));
    var y = parseInt(ss[0], 10);
    var m = parseInt(ss[1], 10);
    var d = parseInt(ss[2], 10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
        return new Date(y, m - 1, d);
    } else {
        return new Date();
    }
}
//
//if(!window.showModalDialog){
//    window.showModalDialog=function(url,name,option){
//        console.log('木有模态对话框！！！');
//        if(window.hasOpenWindow){
//            window.newWindow.focus();
//        }
//        var re = new RegExp(";", "g");
//        var option  = option.replace(re, '","'); //把option转为json字符串
//        var re2 = new RegExp(":", "g");
//        option = '{"'+option.replace(re2, '":"')+'"}';
//        option = JSON.parse(option);
//        var openOption = 'width='+parseInt(option.dialogWidth)+',height='+parseInt(option.dialogHeight)+',left='+(window.screen.width-parseInt(option.dialogWidth))/2+',top='+(window.screen.height-30-parseInt(option.dialogHeight))/2;
//        window.hasOpenWindow = true;
//        window.newWindow = window.open(url,name,openOption);
//    }
//}
//
//function showModal(url,width,height){
//    //var myWindow=window.open(url,'_blank','width='+width+',height='+height);
//    //myWindow.document.write("<p>这是'我的窗口'</p>");
//    //myWindow.focus();
//    window.showModalDialog(url,window,"dialogWidth:"+width+";dialogHeight:"+height);
//}
