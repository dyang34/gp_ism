<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/A_Dao.php";

class GoodsDao extends A_Dao
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
		 
		$sql =" select imgi_idx, gi.code, name, item_code, item_name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, gi.reg_date, stock_qty, stock_apply_date "
			 ." from ism_mst_goods_item gi inner join ism_mst_goods g on gi.code = g.code "
			 ." where item_code = ".$this->quot($db, $key)
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

		$sql =" select imgi_idx, gi.code, name, item_code, item_name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, gi.reg_date, stock_qty, stock_apply_date "
			 ." from ism_mst_goods_item gi inner join ism_mst_goods g on gi.code = g.code "
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
	    
	    $sql =" select imgi_idx, gi.code, name, item_code, item_name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, gi.reg_date, stock_qty, stock_apply_date "
	         ." from ism_mst_goods_item gi inner join ism_mst_goods g on gi.code = g.code "
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function select2($db, $wq) {
	    
	    $sql =" select imgi_idx, a.code, name, item_code, item_name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, a.reg_date, stock_qty, stock_apply_date "
	        ."		,(select name from ism_mst_brand b where b.imb_idx = g.imb_idx) as brand_name "
	            ."		,(select title from ism_mst_category c1 where c1.imct_idx = g.cate1_idx) as cate1_name "
	                ."		,(select title from ism_mst_category c2 where c2.imct_idx = g.cate2_idx) as cate2_name "
	                    ."		,(select title from ism_mst_category c3 where c3.imct_idx = g.cate3_idx) as cate3_name "
	                        ."		,(select title from ism_mst_category c4 where c4.imct_idx = g.cate4_idx) as cate4_name "
	                            ." from ism_mst_goods_item a inner join ism_mst_goods g on a.code = g.code  "
	            .$wq->getWhereQuery()
	            .$wq->getOrderByQuery()
	            ;
	            
	            //echo $sql;
	            return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
	    
	    $sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
	        ."		select @rnum:=0, imgi_idx, gi.code, name, item_code, item_name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, gi.reg_date, stock_qty, stock_apply_date "
	            ."		from ism_mst_goods_item gi inner join ism_mst_goods g on gi.code = g.code "
	                .$wq->getWhereQuery()
	                .$wq->getOrderByQuery()
	                ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	                ." ) r"
	                    ;
	                    
	                    return $db->query($sql);
	}
	
	function selectPerPage2($db, $wq, $pg) {
	    
	    $sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
	        ."		select @rnum:=0, imgi_idx, a.code, name, item_code, item_name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, a.reg_date, stock_qty, stock_apply_date "
	        ."		,(select name from ism_mst_brand b where b.imb_idx = g.imb_idx) as brand_name "
            ."		,(select title from ism_mst_category c1 where c1.imct_idx = g.cate1_idx) as cate1_name "
            ."		,(select title from ism_mst_category c2 where c2.imct_idx = g.cate2_idx) as cate2_name "
            ."		,(select title from ism_mst_category c3 where c3.imct_idx = g.cate3_idx) as cate3_name "
            ."		,(select title from ism_mst_category c4 where c4.imct_idx = g.cate4_idx) as cate4_name "
                    ."		from ism_mst_goods_item a inner join ism_mst_goods g on a.code = g.code "
	                .$wq->getWhereQuery()
	                .$wq->getOrderByQuery()
	                ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	                ." ) r"
	                    ;
	                    
	                    //echo $sql;
	                    return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from ism_mst_goods_item a inner join ism_mst_goods g on a.code = g.code "
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
			 ." from ism_mst_goods_item a inner join ism_mst_goods g on a.code = g.code"
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
	
	function selectMaxIdx($db, $wq) {
	    
	    $sql =" select max(imgi_idx) max_idx"
	        ." from ism_mst_goods_item a inner join ism_mst_goods g on a.code = g.code "
	            .$wq->getWhereQuery()
	            ;

	            $row = null;
	            $result = $db->query($sql);
	            if ( $result->num_rows > 0 ) {
	                $row = $result->fetch_assoc();
	            }
	            
	            @ $result->free();
	            
	            return $row["max_idx"];
	}
	
	function insert($db, $arrVal) {
	    
	    $sql =" insert into ism_mst_goods(code, name, item_code, item_name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, stock_qty, reg_date)"
	        ." values ('".$this->checkMysql($db, $arrVal["code"])
	        ."', '".$this->checkMysql($db, $arrVal["name"])
	        ."', '".$this->checkMysql($db, $arrVal["item_code"])
	        ."', '".$this->checkMysql($db, $arrVal["item_name"])
	        ."', '".$this->checkMysql($db, $arrVal["imb_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["cate1_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["cate2_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["cate3_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["cate4_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["stock_qty"])
	        ."', now())"
	            ;
	            
        return $db->query($sql);
	}
	
	function insert_check($db, $arrVal) {
	    
	    $sql ="call sp_ism_mst_goods_ins_chk('".$this->checkMysql($db, $arrVal["item_code"])."','".$this->checkMysql($db, $arrVal["code"])."')";
	        
        $row = array();
        $result = $db->query($sql);
        if ( $result->num_rows > 0 ) {
            $row = $result->fetch_assoc();
            @ $result->free();
        }
        
        return $row;
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update ism_mst_goods_item"
	        .$uq->getQuery($db)
	        ." where item_code = ".$this->quot($db, $key);
	        
	        return $db->query($sql);
	}
	
	function delete($db, $key) {
	    
	    $sql = "update ism_mst_goods set img_fg_del = 1 where item_code = ".$this->quot($db, $key);
	    
	    return $db->query($sql);
	}	
}
?>