
var v_menu_sidemenu_data = [{
        text: '精确核销',
        iconCls: 'icon-aim',
        state: 'open',
        children: [{
                text: '自动关联',
                fname: 'aim_link'
            }, {
                text: '确认核销',
                fname: 'aim_verify'
            }]
    }, {
        text: '手动核销',
        iconCls: 'icon-man',
        state: 'open',
        children: [{
                text: '确认关联',
                fname: 'cus_link'
            }, {
                text: '确认核销',
                fname: 'cus_verify'
            }]
    }, {
        text: '数据过滤',
        iconCls: 'icon-filter',
        state: 'open',
        children: [{
                text: '未核销',
                fname: 'show_todo'
            }, {
                text: '精确核销',
                fname: 'show_aim'
            }, {
                text: '手动核销',
                fname: 'show_cus'
            }]
    }];

function fmt_unverify_diff(v, row, index){
    if (v-0 < 0){
        return '<span style="color:'+COLOR_WARNING+'">'+v+'</span>';
    }
    if (v-0 === 0){
        return '<span style="color:#333333">0</span>';
    }
    return v;
}

$(function () {
    $('#vc_dg2').datagrid('getPager').pagination({
        showPageList:false,
        showPageInfo:false,
        showRefresh: false
    });
    $('#vc_dg1').datagrid('getPager').pagination({
        showPageList:false,
        showPageInfo:false,
        showRefresh: false
    });
    $("#vc_dg3").datagrid({
        onLoadSuccess:doLocalCount,
        onClickRow: function (index, row) {
            $('#vc_dg2').datagrid('load', {
                s_sid: row.vc_org_sn
            });
            $('#vc_dg1').datagrid('load', {
                s_sid: row.vc_org_sn
            });
        }
    });
    $('#q_vct1_btn_search').bind('click', function () {
        doSearchList();
    });
    $('#q_vct2_btn_search').bind('click', function () {
        doSearchBalList();
    });
    $('#q_vct3_btn_search').bind('click', function () {
        doSearchVC();
    });
    $('#q_vct3_btn_count').bind('click', function () {
        doVerifyCount();
    });
    $('#q_vct3_btn_output').bind('click', function () {
        doOutput();
    });
});

function v_sidemenu_handler(item) {
    if (item.fname) {
        if (item.fname === 'aim_link') {
            doAimLink();
        } else if (item.fname === 'aim_verify') {
            doAimVerify();
        } else if (item.fname === 'cus_link') {
            doCusLink();
        } else if (item.fname === 'cus_verify') {
            doCusVerify();
        }
    }
}

function doSearchList() {
    var s_db = $('#q_vct1_date_begin').val();
    var s_de = $('#q_vct1_date_end').val();

    $('#vc_dg1').datagrid('load', {
        s_db: s_db,
        s_de: s_de
    });
}

function doSearchBalList() {
    var s_db = $('#q_vct2_date_begin').val();
    var s_de = $('#q_vct2_date_end').val();

    $('#vc_dg2').datagrid('load', {
        s_db: s_db,
        s_de: s_de
    });
}

function doSearchVC() {
    var s_sid = $('#q_vct3_district').combobox('getValue');

    $('#vc_dg3').datagrid('load', {
        s_sid: s_sid
    });
}

function doLocalCount(data){
    var s_district = $('#q_vct3_district').combobox('getText');
    var a_rows = $('#vc_dg3').datagrid('getRows');
    var i_count_verify_amount = 0;
    var i_tmp_verify_amount = 0;
    var i_count_unverify_brd = 0;
    var i_tmp_unverify_brd = 0;
    var i_count_unverify_cpd = 0;
    var i_tmp_unverify_cpd = 0;
    var i_count_unverify_diff = 0;
    var i_count_brd = 0;
    var i_count_cpd = 0;
    for (var i=0; i<a_rows.length; i++){
        i_tmp_verify_amount = a_rows[i].vc_verify_amount-0;
        i_count_verify_amount += i_tmp_verify_amount;
        
        i_tmp_unverify_brd = a_rows[i].vc_unverify_brd-0;
        i_count_unverify_brd += i_tmp_unverify_brd;
        
        i_tmp_unverify_cpd = a_rows[i].vc_unverify_cpd-0;
        i_count_unverify_cpd += i_tmp_unverify_cpd; 
        
        i_count_unverify_diff += a_rows[i].vc_unverify_diff-0;
        i_count_brd += i_tmp_verify_amount+i_tmp_unverify_brd;
        i_count_cpd += i_tmp_verify_amount+i_tmp_unverify_cpd;
    }
    
    $("#vc_dg4").datagrid('loadData', [{
           vc_district:s_district,
           vc_verify_amount:i_count_verify_amount.toFixed(2),
           vc_unverify_brd:i_count_unverify_brd.toFixed(2),
           vc_unverify_cpd:i_count_unverify_cpd.toFixed(2),
           vc_unverify_diff:i_count_unverify_diff.toFixed(2),
           vc_brd:i_count_brd.toFixed(2),
           vc_cpd:i_count_cpd.toFixed(2)
    }]);
}

function doVerifyCount() {
    $.messager.confirm('确认', '是否重新对所有核销记录进行统计?', function (r) {
        if (r) {
            $.messager.progress({
                title: 'Please waiting',
                msg: '正在进行核销统计......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/doVerifyCount',
                type: "POST",
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    if (o_res.state) {
                        $.messager.alert('信息', o_res.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_res.msg, 'error');
                    }
                    $('#vc_dg3').datagrid('reload');
                }
            });
        }
    });
}

function doOutput(){
    $.messager.confirm('确认', '是否对所有核销记录进行导出?', function (r) {
        if (r) {
            var s_url = __s_c_name + '/doOutput';
            var a = document.createElement('a');
            a.href = '../'+s_url;
            $("body").append(a);  
            a.click();
            $(a).remove();    
        }
    });
}