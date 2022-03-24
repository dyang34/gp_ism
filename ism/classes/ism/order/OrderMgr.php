<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/order/OrderDao.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/A_Mgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/DbUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";

class OrderMgr extends A_Mgr
{
    private static $instance = null;
    
    private function __construct() {
        // getInstance() 이용.
    }
    
    static function getInstance() {
        if ( self::$instance == null ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    function getByKey($key) {
        
        $row = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $row = OrderDao::getInstance()->selectByKey($db, $key);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $row;
    }
    
    function getFirst($wq) {
        
        $row = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $row = OrderDao::getInstance()->selectFirst($db, $wq);

        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
      @ $db->close();
        return $row;
    }
    
    /*
     *	$result 사용후 반드시 @ $result->free(); 해줘야 한다.
     */
    function getList($wq) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $result = OrderDao::getInstance()->select($db, $wq);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    /*
     *	$result 사용후 반드시 @ $result->free(); 해줘야 한다.
     */
    function getListAggr($wq, $arrGroupBy) {
        
        $result = null;
        $db = null;

        $group_by = "";
        $add_select1 = "";
        $add_select2 = "";
        
        try {
            for($i=0;$i<count($arrGroupBy);$i++) {
                switch($arrGroupBy[$i]) {
                    case "grp_order_date_day":
                        $group_by .= ",date_format(order_date, '%Y-%m-%d')";
                        $add_select1 .= ",date_format(order_date, '%Y-%m-%d') as order_date";
                        break;
                    case "grp_order_date_week":
/*                      일요일 시작.                        
                        $group_by .= ",date_format(order_date, '%Y-%U')";
                        $add_select1 .= ",concat(DATE_FORMAT(DATE_SUB(order_date, INTERVAL (DAYOFWEEK(order_date)-1) DAY), '%Y-%m-%d'),'~',DATE_FORMAT(DATE_SUB(order_date, INTERVAL (DAYOFWEEK(order_date)-7) DAY), '%Y-%m-%d')) as order_date";
*/                        
                        $group_by .= ",date_format(order_date, '%Y-%u')";
                        $add_select1 .= ",concat(DATE_FORMAT(DATE_SUB(order_date, INTERVAL (DAYOFWEEK(order_date)-2-(case when DAYOFWEEK(order_date)=1 then -7 ELSE 0 end)) DAY), '%Y-%m-%d'),' <br/>~ ',DATE_FORMAT(DATE_SUB(order_date, INTERVAL (DAYOFWEEK(order_date)-8-(case when DAYOFWEEK(order_date)=1 then -7 ELSE 0 end)) DAY), '%Y-%m-%d')) as order_date";
                        break;
                    case "grp_order_date_month":
                        $group_by .= ",date_format(order_date, '%Y-%m')";
                        $add_select1 .= ",date_format(order_date, '%Y-%m') as order_date";
                        break;
                    case "grp_goods":
                        $group_by .= ",gi.code";
                        $add_select1 .= ",gi.code,name";
                        
                        $wq->addOrderBy("name", "");
                        
                        break;
                    case "grp_item":
                        $group_by .= ",gi.code,a.item_code";
                        $add_select1 .= ",gi.code,a.item_code,name,item_name";
                        
                        $wq->addOrderBy("name", "");
                        $wq->addOrderBy("item_name", "");
                        
                        break;
                    case "grp_cate1":
                        $group_by .= ",cate1_idx";
                        $add_select1 .= ",cate1_idx";
                        $add_select2 .= ",(select title from ism_mst_category c1 where c1.imct_idx = g.cate1_idx) as cate1_name";
                        
                        $wq->addOrderBy("cate1_name", "");
                        
                        break;
                    case "grp_cate2":
                        $group_by .= ",cate1_idx,cate2_idx";
                        $add_select1 .= ",cate1_idx,cate2_idx";
                        $add_select2 .= ",(select title from ism_mst_category c1 where c1.imct_idx = g.cate1_idx) as cate1_name";
                        $add_select2 .= ",(select title from ism_mst_category c2 where c2.imct_idx = g.cate2_idx) as cate2_name";
                        
                        $wq->addOrderBy("cate1_name", "");
                        $wq->addOrderBy("cate2_name", "");
                        
                        break;
                    case "grp_cate3":
                        $group_by .= ",cate1_idx,cate2_idx,cate3_idx";
                        $add_select1 .= ",cate1_idx,cate2_idx,cate3_idx";
                        $add_select2 .= ",(select title from ism_mst_category c1 where c1.imct_idx = g.cate1_idx) as cate1_name";
                        $add_select2 .= ",(select title from ism_mst_category c2 where c2.imct_idx = g.cate2_idx) as cate2_name";
                        $add_select2 .= ",(select title from ism_mst_category c3 where c3.imct_idx = g.cate3_idx) as cate3_name";

                        $wq->addOrderBy("cate1_name", "");
                        $wq->addOrderBy("cate2_name", "");
                        $wq->addOrderBy("cate3_name", "");
                        
                        break;
                    case "grp_cate4":
                        $group_by .= ",cate1_idx,cate2_idx,cate3_idx,cate4_idx";
                        $add_select1 .= ",cate1_idx,cate2_idx,cate3_idx,cate4_idx";
                        $add_select2 .= ",(select title from ism_mst_category c1 where c1.imct_idx = g.cate1_idx) as cate1_name";
                        $add_select2 .= ",(select title from ism_mst_category c2 where c2.imct_idx = g.cate2_idx) as cate2_name";
                        $add_select2 .= ",(select title from ism_mst_category c3 where c3.imct_idx = g.cate3_idx) as cate3_name";
                        $add_select2 .= ",(select title from ism_mst_category c4 where c4.imct_idx = g.cate4_idx) as cate4_name";
                        
                        $wq->addOrderBy("cate1_name", "");
                        $wq->addOrderBy("cate2_name", "");
                        $wq->addOrderBy("cate3_name", "");
                        $wq->addOrderBy("cate4_name", "");
                        
                        break;
                    case "grp_brand":
                        $group_by .= ",imb_idx";
                        $add_select1 .= ",imb_idx";
                        $add_select2 .= ",(select name from ism_mst_brand b where b.imb_idx = g.imb_idx) as brand_name";
                        
                        $wq->addOrderBy("brand_name", "");
                        
                        break;
                    case "grp_channel":
                        $group_by .= ",imc_idx";
                        $add_select1 .= ",imc_idx";
                        $add_select2 .= ",(select name from ism_mst_channel b where b.imc_idx = a.imc_idx) as channel";
                        
                        $wq->addOrderBy("channel", "");
                        
                        break;
                    case "grp_tax_type":
                        $group_by .= ",tax_type";
                        $add_select1 .= ",tax_type";
                        
                        $wq->addOrderBy("tax_type", "");
                        
                        break;
                    case "grp_order_type":
                        $group_by .= ",order_type";
                        $add_select1 .= ",order_type";
                        
                        $wq->addOrderBy("order_type", "");
                        
                        break;
                }
            }
            
            $group_by = substr($group_by,1);
        
            $db = DbUtil::getConnection();
            
            $result = OrderDao::getInstance()->selectAggr($db, $wq, $group_by, $add_select1, $add_select2);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    /*
     *	$result 사용후 반드시 @ $result->free(); 해줘야 한다.
     */
    function getListPerPage($wq, $pg) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $pg->setTotalCount(OrderDao::getInstance()->selectCount($db, $wq));
            $result = OrderDao::getInstance()->selectPerPage($db, $wq, $pg);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }

    /*
     *	$result 사용후 반드시 @ $result->free(); 해줘야 한다.
     */
    function getListAggrPerPage($wq, $pg, $arrGroupBy) {
        
        $result = null;
        $db = null;

        $group_by = "";
        $add_select1 = "";
        $add_select2 = "";
        
        try {
            for($i=0;$i<count($arrGroupBy);$i++) {
                switch($arrGroupBy[$i]) {
                    case "grp_order_date_day":
                        $group_by .= ",date_format(order_date, '%Y-%m-%d')";
                        $add_select1 .= ",date_format(order_date, '%Y-%m-%d') as order_date";
                        break;
                    case "grp_order_date_week":
                        /*                      일요일 시작.
                         $group_by .= ",date_format(order_date, '%Y-%U')";
                         $add_select1 .= ",concat(DATE_FORMAT(DATE_SUB(order_date, INTERVAL (DAYOFWEEK(order_date)-1) DAY), '%Y-%m-%d'),'~',DATE_FORMAT(DATE_SUB(order_date, INTERVAL (DAYOFWEEK(order_date)-7) DAY), '%Y-%m-%d')) as order_date";
                         */
                        $group_by .= ",date_format(order_date, '%Y-%u')";
                        $add_select1 .= ",concat(DATE_FORMAT(DATE_SUB(order_date, INTERVAL (DAYOFWEEK(order_date)-2-(case when DAYOFWEEK(order_date)=1 then -7 ELSE 0 end)) DAY), '%Y-%m-%d'),' <br/>~ ',DATE_FORMAT(DATE_SUB(order_date, INTERVAL (DAYOFWEEK(order_date)-8-(case when DAYOFWEEK(order_date)=1 then -7 ELSE 0 end)) DAY), '%Y-%m-%d')) as order_date";
                        break;
                    case "grp_order_date_month":
                        $group_by .= ",date_format(order_date, '%Y-%m')";
                        $add_select1 .= ",date_format(order_date, '%Y-%m') as order_date";
                        break;
                    case "grp_goods":
                        $group_by .= ",gi.code";
                        $add_select1 .= ",gi.code,name";
                        
                        $wq->addOrderBy("name", "");
                        
                        break;
                    case "grp_item":
                        $group_by .= ",gi.code,a.item_code";
                        $add_select1 .= ",gi.code,a.item_code,name,item_name";
                        
                        $wq->addOrderBy("name", "");
                        $wq->addOrderBy("item_name", "");
                        
                        break;
                    case "grp_cate1":
                        $group_by .= ",cate1_idx";
                        $add_select1 .= ",cate1_idx";
                        $add_select1 .= ",(select title from ism_mst_category c1 where c1.imct_idx = cate1_idx) as cate1_name";
                        //$add_select2 .= ",(select title from ism_mst_category c1 where c1.imct_idx = cate1_idx) as cate1_name";
                        
                        $wq->addOrderBy("cate1_name", "");
                        
                        break;
                    case "grp_cate2":
                        $group_by .= ",cate1_idx,cate2_idx";
                        $add_select1 .= ",cate1_idx,cate2_idx";
                        $add_select1 .= ",(select title from ism_mst_category c1 where c1.imct_idx = cate1_idx) as cate1_name";
                        $add_select1 .= ",(select title from ism_mst_category c2 where c2.imct_idx = cate2_idx) as cate2_name";
                        //$add_select2 .= ",(select title from ism_mst_category c1 where c1.imct_idx = cate1_idx) as cate1_name";
                        //$add_select2 .= ",(select title from ism_mst_category c2 where c2.imct_idx = cate2_idx) as cate2_name";
                        
                        $wq->addOrderBy("cate1_name", "");
                        $wq->addOrderBy("cate2_name", "");
                        
                        break;
                    case "grp_cate3":
                        $group_by .= ",cate1_idx,cate2_idx,cate3_idx";
                        $add_select1 .= ",cate1_idx,cate2_idx,cate3_idx";
                        $add_select1 .= ",(select title from ism_mst_category c1 where c1.imct_idx = cate1_idx) as cate1_name";
                        $add_select1 .= ",(select title from ism_mst_category c2 where c2.imct_idx = cate2_idx) as cate2_name";
                        $add_select1 .= ",(select title from ism_mst_category c3 where c3.imct_idx = cate3_idx) as cate3_name";
/*                        
                        $add_select2 .= ",(select title from ism_mst_category c1 where c1.imct_idx = cate1_idx) as cate1_name";
                        $add_select2 .= ",(select title from ism_mst_category c2 where c2.imct_idx = cate2_idx) as cate2_name";
                        $add_select2 .= ",(select title from ism_mst_category c3 where c3.imct_idx = cate3_idx) as cate3_name";
*/
                        
                        $wq->addOrderBy("cate1_name", "");
                        $wq->addOrderBy("cate2_name", "");
                        $wq->addOrderBy("cate3_name", "");
                        
                        break;
                    case "grp_cate4":
                        $group_by .= ",cate1_idx,cate2_idx,cate3_idx,cate4_idx";
                        $add_select1 .= ",cate1_idx,cate2_idx,cate3_idx,cate4_idx";
                        $add_select1 .= ",(select title from ism_mst_category c1 where c1.imct_idx = cate1_idx) as cate1_name";
                        $add_select1 .= ",(select title from ism_mst_category c2 where c2.imct_idx = cate2_idx) as cate2_name";
                        $add_select1 .= ",(select title from ism_mst_category c3 where c3.imct_idx = cate3_idx) as cate3_name";
                        $add_select1 .= ",(select title from ism_mst_category c4 where c4.imct_idx = cate4_idx) as cate4_name";
/*
                        $add_select2 .= ",(select title from ism_mst_category c1 where c1.imct_idx = cate1_idx) as cate1_name";
                        $add_select2 .= ",(select title from ism_mst_category c2 where c2.imct_idx = cate2_idx) as cate2_name";
                        $add_select2 .= ",(select title from ism_mst_category c3 where c3.imct_idx = cate3_idx) as cate3_name";
                        $add_select2 .= ",(select title from ism_mst_category c4 where c4.imct_idx = cate4_idx) as cate4_name";
*/                        
                        $wq->addOrderBy("cate1_name", "");
                        $wq->addOrderBy("cate2_name", "");
                        $wq->addOrderBy("cate3_name", "");
                        $wq->addOrderBy("cate4_name", "");
                        
                        break;
                    case "grp_brand":
                        $group_by .= ",imb_idx";
                        $add_select1 .= ",imb_idx";
                        $add_select1 .= ",(select name from ism_mst_brand b where b.imb_idx = g.imb_idx) as brand_name";
                        //$add_select2 .= ",(select name from ism_mst_brand b where b.imb_idx = imb_idx) as brand_name";
                        
                        $wq->addOrderBy("brand_name", "");
                        
                        break;
                    case "grp_channel":
                        $group_by .= ",imc_idx";
                        $add_select1 .= ",imc_idx";
                        $add_select1 .= ",(select name from ism_mst_channel b where b.imc_idx = a.imc_idx) as channel";
                        //$add_select2 .= ",(select name from ism_mst_channel b where b.imc_idx = imc_idx) as channel";
                        
                        $wq->addOrderBy("channel", "");
                        
                        break;
                    case "grp_tax_type":
                        $group_by .= ",tax_type";
                        $add_select1 .= ",tax_type";
                        
                        $wq->addOrderBy("tax_type", "");
                        
                        break;
                    case "grp_order_type":
                        $group_by .= ",order_type";
                        $add_select1 .= ",order_type";
                        
                        $wq->addOrderBy("order_type", "");
                        
                        break;
                }
            }
            
            $group_by = substr($group_by,1);
        
            $db = DbUtil::getConnection();
            
            $pg->setTotalCount(OrderDao::getInstance()->selectAggrCount($db, $wq, $group_by));
            $result = OrderDao::getInstance()->selectAggrPerPage($db, $wq, $pg, $group_by, $add_select1, $add_select2);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    function getCount($wq) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $result = OrderDao::getInstance()->selectCount($db, $wq);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    function exists($wq) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $result = OrderDao::getInstance()->exists($db, $wq);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    function add2($arrVal) {
        
        $isOk = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            //            $this->startTran($db);
            
            $isOk = OrderDao::getInstance()->insert2($db, $arrVal);
            
            //            $this->commit($db);
            
        } catch(Exception $e) {
            //            $this->rollback($db);
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $isOk;
    }
    
    function add($arrVal) {
        
        $isOk = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            //            $this->startTran($db);
            
            $isOk = OrderDao::getInstance()->insert($db, $arrVal);
            
            //            $this->commit($db);
            
        } catch(Exception $e) {
            //            $this->rollback($db);
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $isOk;
    }
    
    function add_check($arrVal) {
        
        $isOk = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            //            $this->startTran($db);
            
            $isOk = OrderDao::getInstance()->insert_check($db, $arrVal);
            
            //            $this->commit($db);
            
        } catch(Exception $e) {
            //            $this->rollback($db);
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $isOk;
    }
    
    function edit($uq, $key) {
        
        $isOk = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            //$this->startTran($db);
            
            $isOk = OrderDao::getInstance()->update($db, $uq, $key);
            
            //$this->commit($db);
            
        } catch(Exception $e) {
            //$this->rollback($db);
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $isOk;
    }
    
    function edit_wholesale($uq, $item_code, $key) {
        
        $isOk = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            //$this->startTran($db);
            
            $isOk = OrderDao::getInstance()->update_wholesale($db, $uq, $item_code, $key);
            
            //$this->commit($db);
            
        } catch(Exception $e) {
            //$this->rollback($db);
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $isOk;
    }
    
    function delete($key) {
        
        $isOk = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            //$this->startTran($db);
            
            $isOk = OrderDao::getInstance()->delete($db, $key);
            
            //$this->commit($db);
            
        } catch(Exception $e) {
            //$this->rollback($db);
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $isOk;
    }
}
?>