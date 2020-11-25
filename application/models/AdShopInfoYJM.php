<?php
/**
 * Description of AdShopInfoYJM
 *
 * @author Vincent
 */
class AdShopInfoYJM extends CI_Model {

    var $__table_name = 'base_shop_info	';

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();
    }

    /**
     * 强制转码为UTF8
     * @param type $s_str
     * @return type
     */
    function _encodeUTF8($s_str) {
        $s_str = str_replace('"', "", $s_str);
        $s_str = str_replace("'", "", $s_str);
        $s_str = trim($s_str);
        $encode = mb_detect_encoding($s_str, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        $str_encode = mb_convert_encoding($s_str, 'UTF-8', $encode);
        return $str_encode;
    }

    /**
     * 获得列表
     * @return type
     */
    function getList($i_page, $i_rows, $a_get) {
        $i_end = $i_page * $i_rows;
        $i_start = $i_end - $i_rows;
        $s_where = $this->getWhere($a_get);
        $s_sql = "SELECT bs_id,bs_org_sn,bs_sale_sn,bs_shop_sn,bs_shop_name,"
                . "bs_province,bs_city,bs_district,bs_phone,bs_mphone,bs_master_name,"
                . "bs_master_phone,bs_admin_name,bs_admin_phone,bs_email,bs_addr,"
                . "bs_e_id,bs_e_api_id,bs_e_account,bs_e_password,bs_e_delivery_type,bs_e_create_dt,"
                . "bs_m_id,bs_m_api_id,bs_m_create_dt,bs_delivery_manager,bs_delivery_phone,"
                . "bs_region_manager,bs_region_phone FROM $this->__table_name $s_where "
                . "ORDER BY bs_e_create_dt DESC, bs_m_create_dt DESC, bs_shop_sn ASC "
                . "LIMIT $i_start,$i_rows ";
        $o_result = $this->db->query($s_sql);
        $i_total = $this->_getTotal($s_where);
        return array(
            'total' => $i_total,
            'rows' => $o_result->result()
        );
    }
    
    function getWhere($a_get) {
        $s_where = "WHERE 1=1";
        if (isset($a_get['s']) && $a_get['s']) {
            $s_where .= ' AND bs_org_sn='.$a_get['s'];
        }
        if (isset($a_get['d']) && $a_get['d']) {
            $s_where .= ' AND bs_district=\''.$a_get['d']."'";
        }
        if (isset($a_get['edt']) && $a_get['edt']) {
            $s_where .= ' AND bs_e_delivery_type=\''.$a_get['edt']."'";
        }
        if (isset($a_get['a']) && $a_get['a']) {
            $s_where .= ' AND bs_addr LIKE \'%'.$a_get['a']."%'";
        }
        return $s_where;
    }
    
    function _getTotal($s_where='') {
        $s_sql = "SELECT COUNT(1) t_num FROM $this->__table_name $s_where ";
        $o_result = $this->db->query($s_sql);
        $a_line = $o_result->result();
        return $a_line[0]->t_num-0;
    }

    function getShopInfo($i_bs_id) {
        $s_sql = "SELECT bs_id,bs_org_sn,bs_sale_sn,bs_shop_sn,bs_shop_name,"
                . "bs_province,bs_city,bs_district,bs_phone,bs_mphone,bs_master_name,"
                . "bs_master_phone,bs_admin_name,bs_admin_phone,bs_email,bs_addr,"
                . "bs_e_id,bs_e_api_id,bs_e_account,bs_e_password,bs_e_delivery_type,bs_e_create_dt,"
                . "bs_m_id,bs_m_api_id,bs_m_create_dt,bs_delivery_manager,bs_delivery_phone,"
                . "bs_region_manager,bs_region_phone FROM $this->__table_name "
                . "WHERE bs_id = $i_bs_id ";
        $o_result = $this->db->query($s_sql);
        if ($o_result && count($o_result->result()) == 1) {
            return $o_result->result()[0];
        }
        return array();
    }
    
    function getShopOrgList() {
        $s_sql = "SELECT bs_org_sn `id`,bs_shop_name `text` "
                . "FROM base_shop_info ORDER BY bs_e_api_id DESC,bs_m_api_id DESC ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getDistrictList(){
        $s_sql = "SELECT DISTINCT(bs_district) `id`, bs_district `text` "
                . "FROM base_shop_info";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getShopEbIdList(){
        $s_sql = "SELECT bs_e_id `id`,bs_shop_name `text` "
                . "FROM base_shop_info WHERE bs_e_id <> '' "
                . "ORDER BY bs_e_api_id DESC , bs_shop_name ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getShopMtIdList(){
        $s_sql = "SELECT bs_m_id `id`,bs_shop_name `text` "
                . "FROM base_shop_info WHERE bs_m_id <> '' "
                . "ORDER BY bs_m_id DESC , bs_shop_name ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getShopEbApiIdList(){
        $s_sql = "SELECT bs_e_api_id `id`,bs_shop_name `text` "
                . "FROM base_shop_info WHERE bs_e_api_id <> '' "
                . "ORDER BY bs_shop_name ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function getShopMtApiIdList(){
        $s_sql = "SELECT bs_m_api_id `id`,bs_shop_name `text` "
                . "FROM base_shop_info WHERE bs_m_api_id <> '' "
                . "ORDER BY bs_shop_name ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 新增商铺信息
     * @param type $a_post
     * @return type
     * @throws Exception
     */
    function addShopInfo($a_post) {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        if (!isset($a_post['bs_org_sn']) || !is_numeric($a_post['bs_org_sn'])){
            log_message('error', '组织编码为空，中断新增');
            $o_result['state'] = false;
            $o_result['msg'] = "更新记录数 : 0 条";
            return $o_result;
        }
        log_message('debug', '新增商铺信息');
        $s_sql = "INSERT INTO $this->__table_name (bs_org_sn,bs_sale_sn,bs_shop_sn,"
                . "bs_shop_name,bs_city,bs_district,bs_phone,bs_mphone,bs_master_name,"
                . "bs_master_phone,bs_admin_name,bs_admin_phone,bs_email,bs_addr,"
                . "bs_e_id,bs_e_api_id,bs_e_account,bs_e_password,bs_e_delivery_type,"
                . "bs_e_create_dt,bs_m_id,bs_m_api_id,bs_m_create_dt,bs_delivery_manager,bs_delivery_phone,"
                . "bs_region_manager,bs_region_phone) VALUES ("
                . $a_post['bs_org_sn']
                .",". ($a_post['bs_sale_sn']=='' ? '0' : $a_post['bs_sale_sn'])
                .",". ($a_post['bs_shop_sn']=='' ? '0' : $a_post['bs_shop_sn'])
                . ",'" . $this->_encodeUTF8($a_post['bs_shop_name'])
                . "','" . $this->_encodeUTF8($a_post['bs_city'])
                . "','" . $this->_encodeUTF8($a_post['bs_district'])
                . "','" . $this->_encodeUTF8($a_post['bs_phone'])
                . "','" . $this->_encodeUTF8($a_post['bs_mphone'])
                . "','" . $this->_encodeUTF8($a_post['bs_master_name'])
                . "','" . $this->_encodeUTF8($a_post['bs_master_phone'])
                . "','" . $this->_encodeUTF8($a_post['bs_admin_name'])
                . "','" . $this->_encodeUTF8($a_post['bs_admin_phone'])
                . "','" . $this->_encodeUTF8($a_post['bs_email'])
                . "','" . $this->_encodeUTF8($a_post['bs_addr'])
                . "','" . $this->_encodeUTF8($a_post['bs_e_id'])
                . "','" . $this->_encodeUTF8($a_post['bs_e_api_id'])
                . "','" . $this->_encodeUTF8($a_post['bs_e_account'])
                . "','" . $this->_encodeUTF8($a_post['bs_e_password'])
                . "','" . $this->_encodeUTF8($a_post['bs_e_delivery_type'])
                . "','" . $this->_encodeUTF8($a_post['bs_e_create_dt'])
                . "'," . $this->_encodeUTF8($a_post['bs_m_id'])
                . ",'" . $this->_encodeUTF8($a_post['bs_m_api_id'])
                . "','" . $this->_encodeUTF8($a_post['bs_m_create_dt'])
                . "','" . $this->_encodeUTF8($a_post['bs_delivery_manager'])
                . "','" . $this->_encodeUTF8($a_post['bs_delivery_phone'])
                . "','" . $this->_encodeUTF8($a_post['bs_region_manager'])
                . "','" . $this->_encodeUTF8($a_post['bs_region_phone'])
                . "')";
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', '编辑商铺信息-异常中断！\r\n' . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "编辑商铺信息-异常中断！\r\n" . $ex->getMessage();
            return $o_result;
        }
        log_message('debug', "受影响记录数:$i_rows");
        $o_result['state'] = $i_rows == 1;
        $o_result['msg'] = "更新记录数 : $i_rows 条";
        return $o_result;
    }
    
    /**
     * 编辑商铺信息
     * @param type $a_post
     * @return type
     * @throws Exception
     */
    function editShopInfo($a_post) {
        $o_result = array(
            'state' => false,
            'msg' => ''
        );
        log_message('debug', '编辑商铺信息');
        $s_sql = "UPDATE $this->__table_name SET "
                . "bs_org_sn = " . $a_post['bs_org_sn']
                . ", bs_sale_sn = " . $a_post['bs_sale_sn']
                . ", bs_shop_sn = " . $a_post['bs_shop_sn']
                . ", bs_shop_name = '" . $this->_encodeUTF8($a_post['bs_shop_name'])
                . "', bs_city = '" . $this->_encodeUTF8($a_post['bs_city'])
                . "', bs_district = '" . $this->_encodeUTF8($a_post['bs_district'])
                . "', bs_phone = '" . $this->_encodeUTF8($a_post['bs_phone'])
                . "', bs_mphone = '" . $this->_encodeUTF8($a_post['bs_mphone'])
                . "', bs_master_name = '" . $this->_encodeUTF8($a_post['bs_master_name'])
                . "', bs_master_phone = '" . $this->_encodeUTF8($a_post['bs_master_phone'])
                . "', bs_admin_name = '" . $this->_encodeUTF8($a_post['bs_admin_name'])
                . "', bs_admin_phone = '" . $this->_encodeUTF8($a_post['bs_admin_phone'])
                . "', bs_email = '" . $this->_encodeUTF8($a_post['bs_email'])
                . "', bs_addr = '" . $this->_encodeUTF8($a_post['bs_addr'])
                . "', bs_e_id = '" . $this->_encodeUTF8($a_post['bs_e_id'])
                . "', bs_e_api_id = '" . $this->_encodeUTF8($a_post['bs_e_api_id'])
                . "', bs_e_account = '" . $this->_encodeUTF8($a_post['bs_e_account'])
                . "', bs_e_password = '" . $this->_encodeUTF8($a_post['bs_e_password'])
                . "', bs_e_delivery_type = '" . $this->_encodeUTF8($a_post['bs_e_delivery_type'])
                . "', bs_e_create_dt = '" . $this->_encodeUTF8($a_post['bs_e_create_dt'])
                . "', bs_m_id = '" . $this->_encodeUTF8($a_post['bs_m_id'])
                . "', bs_m_api_id = '" . $this->_encodeUTF8($a_post['bs_m_api_id'])
                . "', bs_m_create_dt = '" . $this->_encodeUTF8($a_post['bs_m_create_dt'])
                . "', bs_delivery_manager = '" . $this->_encodeUTF8($a_post['bs_delivery_manager'])
                . "', bs_delivery_phone = '" . $this->_encodeUTF8($a_post['bs_delivery_phone'])
                . "', bs_region_manager = '" . $this->_encodeUTF8($a_post['bs_region_manager'])
                . "', bs_region_phone = '" . $this->_encodeUTF8($a_post['bs_region_phone'])
                . "' WHERE bs_id = " . $a_post['bs_id'];
        log_message('debug', "SQL文:$s_sql");
        try {
            $this->db->query($s_sql);
            $i_rows = $this->db->affected_rows();
        } catch (Exception $ex) {
            log_message('error', '编辑商铺信息-异常中断！\r\n' . $ex->getMessage());
            $o_result['state'] = false;
            $o_result['msg'] = "编辑商铺信息-异常中断！\r\n" . $ex->getMessage();
            return $o_result;
        }
        log_message('debug', "受影响记录数:$i_rows");
        $o_result['state'] = $i_rows == 1;
        $o_result['msg'] = "更新记录数 : $i_rows 条";
        return $o_result;
    }
    
    /**
     * 获得城区
     * @return type
     */
    function getDistrict(){
        $s_sql = "SELECT DISTINCT(bs_district) `text` FROM $this->__table_name "
                . "WHERE bs_district IS NOT NULL AND bs_district<> ''";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    /**
     * 获得饿百配送方式
     * @return type
     */
    function getEDeliveryType(){
        $s_sql = "SELECT DISTINCT(bs_e_delivery_type) `text` FROM $this->__table_name "
                . "WHERE bs_e_delivery_type IS NOT NULL AND bs_e_delivery_type<> ''";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function _getShopOrgcodeList(){
        $s_sql = "SELECT bs_org_sn,bs_shop_sn FROM $this->__table_name WHERE bs_e_id<>'' OR bs_m_id<>'' ";
        $o_result = $this->db->query($s_sql);
        return $o_result->result();
    }
    
    function doSyncInfo(){
        $a_orgcode = $this->_getShopOrgcodeList();
        try {
            $this->load->library('ThriftSH');        
            return $this->thriftsh->updateShopList($a_orgcode);
        } catch (Thrift\Exception\TException $e) {
            log_message('error', '[上传商铺信息]时发生错误！\r\n' . $e->getMessage());
            return false;
        }
    }
}
