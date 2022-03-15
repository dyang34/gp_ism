<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/A_Dao.php";

class OrderAggrDao extends A_Dao
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

	function selectByKey($db, $key) {
		 
//	    $sql =" select no, order_date, channel, channel_id, name_collect, opt_name_collect, name_confirm, opt_name_confirm, amount, ea, goods_code, goods_code_mall, item_code, order_date, order_date_mall, order_date_sub, order_date_seq, fg_calculate, fg_separate, price_collect, price_goods, price_pay, status, tax_type, grp_code, reg_date, order_type, imc_idx, goods_mst_code, tmp_data3  "
	    $sql =" select order_date, code ,item_code ,imb_idx ,cate1_idx ,cate2_idx ,cate3_idx ,cate4_idx ,imc_idx ,tax_type ,order_type ,amount ,ea ,price_collect ,cnt "
	        ." from ism_order_aggr "
	            ." where order_date = ".$this->quot($db, $key)
//		            ." where io_idx = ".$this->quot($db, $key)
		 	 ;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();

        return $row;
	}

	function selectFirst($db, $wq) {

//		    $sql =" select no, order_date, channel, channel_id, name_collect, opt_name_collect, name_confirm, opt_name_confirm, amount, ea, goods_code, goods_code_mall, item_code, order_date, order_date_mall, order_date_sub, order_date_seq, fg_calculate, fg_separate, price_collect, price_goods, price_pay, status, tax_type, grp_code, reg_date, order_type, imc_idx, goods_mst_code, tmp_data3 "
	    $sql =" select order_date, code ,item_code ,imb_idx ,cate1_idx ,cate2_idx ,cate3_idx ,cate4_idx ,imc_idx ,tax_type ,order_type ,amount ,ea ,price_collect ,cnt "
	        ." from ism_order_aggr"
			 .$wq->getWhereQuery()
			 .$wq->getOrderByQuery()
			 ;
		
		$row = null;

		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();
		
		return $row;
	}

	function select($db, $wq) {
	    
	        //$sql =" select no, order_date, channel, channel_id, name_collect, opt_name_collect, name_confirm, opt_name_confirm, amount, ea, goods_code, goods_code_mall, a.item_code, order_date, order_date_mall, order_date_sub, order_date_seq, fg_calculate, fg_separate, price_collect, price_goods, price_pay, status, tax_type, grp_code, a.reg_date, g.code, g.name, g.item_name, g.imb_idx, g.cate1_idx, g.cate2_idx, g.cate3_idx, g.cate4_idx, order_type, goods_mst_code, tmp_data3 "
	            $sql =" select order_date, code ,item_code ,imb_idx ,cate1_idx ,cate2_idx ,cate3_idx ,cate4_idx ,imc_idx ,tax_type ,order_type ,amount ,ea ,price_collect ,cnt "
	                ." from ism_order_aggr a "
	            ." left join ism_mst_goods g "
	            ." on a.item_code = g.item_code "
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}

	function selectAggr($db, $wq, $group_by, $add_select1, $add_select2) {
	    
	    //	    $sql =" select io_idx, no, order_date, channel, channel_id, name_collect, opt_name_collect, name_confirm, opt_name_confirm, amount, ea, goods_code, goods_code_mall, item_code, order_date, order_date_mall, order_date_sub, order_date_seq, fg_calculate, fg_separate, price_collect, price_goods, price_pay, status, tax_type, grp_code, reg_date "
	    $sql =" select sum(amount) amount, sum(ea) ea, sum(price_collect) price_collect, sum(cnt) as cnt ".$add_select1.$add_select2
	        ." from ism_order_aggr a "
	            ." left join ism_mst_goods g "
	                ." on a.item_code = g.item_code "
	                    .$wq->getWhereQuery()
	                    ." group by ".$group_by
	                    .$wq->getOrderByQuery()
	                    ;
	                    
	                    return $db->query($sql);
	}
	
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
//			     ."		select @rnum:=0, no, order_date, channel, channel_id, name_collect, opt_name_collect, name_confirm, opt_name_confirm, amount, ea, goods_code, goods_code_mall, a.item_code, order_date, order_date_mall, order_date_sub, order_date_seq, fg_calculate, fg_separate, price_collect, price_goods, price_pay, status, tax_type, grp_code, a.reg_date, g.code, g.name, g.item_name, g.imb_idx, g.cate1_idx, g.cate2_idx, g.cate3_idx, g.cate4_idx, order_type, goods_mst_code, tmp_data3 "
			     ."		select @rnum:=0, order_date, code ,item_code ,imb_idx ,cate1_idx ,cate2_idx ,cate3_idx ,cate4_idx ,imc_idx ,tax_type ,order_type ,amount ,ea ,price_collect ,cnt "
			         ." ,(select name from ism_mst_brand b where b.imb_idx = g.imb_idx) as brand_name "
/*			             
			             ."		,(select title from ism_mst_category c1 where c1.imct_idx = a.cate1_idx) as cate1_name "
			                 ."		,(select title from ism_mst_category c2 where c2.imct_idx = a.cate2_idx) as cate2_name "
			                     ."		,(select title from ism_mst_category c3 where c3.imct_idx = a.cate3_idx) as cate3_name "
			                         ."		,(select title from ism_mst_category c4 where c4.imct_idx = a.cate4_idx) as cate4_name "
*/
			         ." from ism_order_aggr a "
			             ." left join ism_mst_goods g "
			                 ." on a.item_code = g.item_code "
			                     
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." ) r"
			 ;
			 
        return $db->query($sql);
	}

	function selectAggrPerPage($db, $wq, $pg, $group_by, $add_select1, $add_select2) {
	    
	    echo $sql =" select @rnum:=@rnum+1 as rnum, r.* ".$add_select2." from ("
//	        ."		select @rnum:=0, io_idx, no, order_date, channel, channel_id, name_collect, opt_name_collect, name_confirm, opt_name_confirm, amount, ea, goods_code, goods_code_mall, item_code, order_date, order_date_mall, order_date_sub, order_date_seq, fg_calculate, fg_separate, price_collect, price_goods, price_pay, status, tax_type, grp_code, reg_date "
	            ."	select @rnum:=0, sum(amount) amount, sum(ea) ea, sum(price_collect) price_collect, sum(cnt) as cnt ".$add_select1
                ." from ism_order_aggr a "
                ." left join ism_mst_goods g "
                ." on a.item_code = g.item_code "
                .$wq->getWhereQuery()
                ." group by ".$group_by
                .$wq->getOrderByQuery()
                ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
                ." ) r"
            ;
	         
        return $db->query($sql);
	}
	
	function selectAggrCount($db, $wq, $group_by) {
	    
	    $sql =" select count(*) cnt"
	        ." from ( "
            ." select count(*) cnt"
	        ." from ism_order_aggr a "
            ." left join ism_mst_goods g "
            ." on a.item_code = g.item_code "
            .$wq->getWhereQuery()
            ." group by ".$group_by
            .") as t"
        ;

        $row = null;
        $result = $db->query($sql);
        if ( $result->num_rows > 0 ) {
            $row = $result->fetch_assoc();
        }
        
        @ $result->free();
        
        return $row["cnt"];
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
		    ." from ism_order_aggr a "
	        ." left join ism_mst_goods g "
            ." on a.item_code = g.item_code "
            .$wq->getWhereQuery()
	   ;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();
		
		return $row["cnt"];
	}
	
	function exists($db, $wq) {

		$sql =" select count(*) cnt"
		    ." from ism_order_aggr a "
		        ." left join ism_mst_goods g "
		            ." on a.item_code = g.item_code "
			 .$wq->getWhereQuery()
			 ;

		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();
		
/*		
		$result = mysql_query($sql);
		if ( mysql_num_rows($result) > 0 ) {
		    $row = mysql_fetch_assoc($result);
		}
		
		@ mysql_free_result($result);
*/		
		if ( $row["cnt"] > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	function insert2($db, $arrVal) {
        $sql =" insert into ism_order_aggr(no, order_date, item_code, amount, ea, price_collect, tax_type, order_type, imc_idx, status, order_date, channel, goods_mst_code, name_collect, opt_name_collect, name_confirm, opt_name_confirm, tmp_data3, reg_date)"
    	    ." values ('1"
    	    ."', '".$this->checkMysql($db, $arrVal["order_date"])
    	    ."', '".$this->checkMysql($db, $arrVal["item_code"])
    	    ."', '".$this->checkMysql($db, $arrVal["amount"])
    	    ."', '".$this->checkMysql($db, $arrVal["ea"])
    	    ."', '".$this->checkMysql($db, $arrVal["price_collect"])
    	    ."', '".$this->checkMysql($db, $arrVal["tax_type"])
    	    ."','2"
            ."','21"
    	    ."','도매판매"
    	    ."',concat('W',DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'),lpad(FLOOR(RAND()*1000),3,0))"
    	    .",(select name from ism_mst_channel where imc_idx = 21)"
    	    .",(select code from ism_mst_goods where item_code = '".$this->checkMysql($db, $arrVal["item_code"])."')"
    	    .",(select name from ism_mst_goods where item_code = '".$this->checkMysql($db, $arrVal["item_code"])."')"
    	    .",(select item_name from ism_mst_goods where item_code = '".$this->checkMysql($db, $arrVal["item_code"])."')"
    	    .",(select name from ism_mst_goods where item_code = '".$this->checkMysql($db, $arrVal["item_code"])."')"
    	    .",(select item_name from ism_mst_goods where item_code = '".$this->checkMysql($db, $arrVal["item_code"])."')"
            .", '".$this->checkMysql($db, $arrVal["tmp_data3"])
            ."',now())"
        ;
	        
        return $db->query($sql);
	}
	
	function insert($db, $arrVal) {
/*	    
	    $sql =" insert into ism_order_aggr(no, order_date, channel, channel_id, name_collect, opt_name_collect, name_confirm, opt_name_confirm, amount, ea, goods_code, goods_code_mall, item_code, order_date, order_date_mall, order_date_sub, order_date_seq, fg_calculate, fg_separate, price_collect, price_goods, price_pay, status, tax_type, grp_code, reg_date)"
	        ." values ('".$this->checkMysql($db, $arrVal["no"])
	        ."', '".$this->checkMysql($db, $arrVal["order_date"])
	        ."', '".$this->checkMysql($db, $arrVal["channel"])
	        ."', '".$this->checkMysql($db, $arrVal["channel_id"])
	        ."', '".$this->checkMysql($db, $arrVal["name_collect"])
	        ."', '".$this->checkMysql($db, $arrVal["opt_name_collect"])
	        ."', '".$this->checkMysql($db, $arrVal["name_confirm"])
	        ."', '".$this->checkMysql($db, $arrVal["opt_name_confirm"])
	        ."', '".$this->checkMysql($db, $arrVal["amount"])
	        ."', '".$this->checkMysql($db, $arrVal["ea"])
	        ."', '".$this->checkMysql($db, $arrVal["goods_code"])
	        ."', '".$this->checkMysql($db, $arrVal["goods_code_mall"])
	        ."', '".$this->checkMysql($db, $arrVal["item_code"])
	        ."', '".$this->checkMysql($db, $arrVal["order_date"])
	        ."', '".$this->checkMysql($db, $arrVal["order_date_mall"])
	        ."', '".$this->checkMysql($db, $arrVal["order_date_sub"])
	        ."', '".$this->checkMysql($db, $arrVal["order_date_seq"])
	        ."', '".$this->checkMysql($db, $arrVal["fg_calculate"])
	        ."', '".$this->checkMysql($db, $arrVal["fg_separate"])
	        ."', '".$this->checkMysql($db, $arrVal["price_collect"])
	        ."', '".$this->checkMysql($db, $arrVal["price_goods"])
	        ."', '".$this->checkMysql($db, $arrVal["price_pay"])
	        ."', '".$this->checkMysql($db, $arrVal["status"])
	        ."', '".$this->checkMysql($db, $arrVal["tax_type"])
	        ."', '".$this->checkMysql($db, $arrVal["grp_code"])
	        ."', now())"
	            ." on duplicate key update amount = ".$arrVal["amount"].", ea = ".$arrVal["ea"].", channel = '".$arrVal["channel"]."' "
	            ;
*/
        $sql ="call sp_ism_order_aggr_ins('".$this->checkMysql($db, $arrVal["no"])
            ."', '".$this->checkMysql($db, $arrVal["order_date"])
            ."', '".$this->checkMysql($db, $arrVal["channel"])
            ."', '".$this->checkMysql($db, $arrVal["channel_id"])
            ."', '".$this->checkMysql($db, $arrVal["name_collect"])
            ."', '".$this->checkMysql($db, $arrVal["opt_name_collect"])
            ."', '".$this->checkMysql($db, $arrVal["name_confirm"])
            ."', '".$this->checkMysql($db, $arrVal["opt_name_confirm"])
            ."', '".$this->checkMysql($db, $arrVal["amount"])
            ."', '".$this->checkMysql($db, $arrVal["ea"])
            ."', '".$this->checkMysql($db, $arrVal["goods_code"])
            ."', '".$this->checkMysql($db, $arrVal["goods_code_mall"])
            ."', '".$this->checkMysql($db, $arrVal["item_code"])
            ."', '".$this->checkMysql($db, $arrVal["order_date"])
            ."', '".$this->checkMysql($db, $arrVal["order_date_mall"])
            ."', '".$this->checkMysql($db, $arrVal["order_date_sub"])
            ."', '".$this->checkMysql($db, $arrVal["order_date_seq"])
            ."', '".$this->checkMysql($db, $arrVal["fg_calculate"])
            ."', '".$this->checkMysql($db, $arrVal["fg_separate"])
            ."', '".$this->checkMysql($db, $arrVal["price_collect"])
            ."', '".$this->checkMysql($db, $arrVal["price_goods"])
            ."', '".$this->checkMysql($db, $arrVal["price_pay"])
            ."', '".$this->checkMysql($db, $arrVal["status"])
            ."', '".$this->checkMysql($db, $arrVal["tax_type"])
            ."', '".$this->checkMysql($db, $arrVal["grp_code"])
            ."')"
        ;
	                
        return $db->query($sql);
	}
	
	function insert_check($db, $arrVal) {
	    
	    $sql ="call sp_ism_upload_check('".$this->checkMysql($db, $arrVal["grp_code"])."')"
        ;
	        
        $row = array();
        $result = $db->query($sql);
        if ( $result->num_rows > 0 ) {
            $row = $result->fetch_assoc();
            @ $result->free();
        }
        
        return $row;
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update ism_order_aggr"
	        .$uq->getQuery($db)
	        ." where order_date = ".$this->quot($db, $key);
//	        ." where io_idx = ".$this->quot($db, $key);
	        
	        return $db->query($sql);
	}

	function update_wholesale($db, $uq, $item_code, $key) {
	    
	    $sql =" update ism_order_aggr"
	        .$uq->getQuery($db)
	        .",goods_mst_code=(select code from ism_mst_goods where item_code = '".$this->checkMysql($db, $item_code)."')"
	            .",name_collect=(select name from ism_mst_goods where item_code = '".$this->checkMysql($db, $item_code)."')"
	                .",opt_name_collect=(select item_name from ism_mst_goods where item_code = '".$this->checkMysql($db, $item_code)."')"
	                    .",name_confirm=(select name from ism_mst_goods where item_code = '".$this->checkMysql($db, $item_code)."')"
	                        .",opt_name_confirm=(select item_name from ism_mst_goods where item_code = '".$this->checkMysql($db, $item_code)."')"
	        ." where order_date = ".$this->quot($db, $key);
	        //	        ." where io_idx = ".$this->quot($db, $key);
	        
	        return $db->query($sql);
	}
	
	function delete($db, $key) {
	    if ($key) {
    	    $sql = "delete from ism_order_aggr where order_date = ".$this->quot($db, $key);
    	    return $db->query($sql);
	    }
	}	
}
?>