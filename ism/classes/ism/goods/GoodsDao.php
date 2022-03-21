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
	    
	    $sql =" select code, name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, reg_date "
	        ." from ism_mst_goods g "
	            ." where code = ".$this->quot($db, $key)
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

		$sql =" select code, name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, reg_date "
			 ." from ism_mst_goods g "
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
	    
	    $sql =" select code, name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, reg_date "
	         ." from ism_mst_goods g "
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function select2($db, $wq) {
	    
	    $sql =" select code, name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, reg_date "   // , stock_qty, stock_apply_date
	        ."		,(select name from ism_mst_brand b where b.imb_idx = g.imb_idx) as brand_name "
	            ."		,(select title from ism_mst_category c1 where c1.imct_idx = g.cate1_idx) as cate1_name "
	                ."		,(select title from ism_mst_category c2 where c2.imct_idx = g.cate2_idx) as cate2_name "
	                    ."		,(select title from ism_mst_category c3 where c3.imct_idx = g.cate3_idx) as cate3_name "
	                        ."		,(select title from ism_mst_category c4 where c4.imct_idx = g.cate4_idx) as cate4_name "
	                            ." from ism_mst_goods g  "
	                                .$wq->getWhereQuery()
	                                .$wq->getOrderByQuery()
	                                ;
	                                
	                                //echo $sql;
	                                return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
	    
	    $sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
	        ."		select @rnum:=0, code, name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, reg_date "
	            ."		from ism_mst_goods g "
	                .$wq->getWhereQuery()
	                .$wq->getOrderByQuery()
	                ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	                ." ) r"
	                    ;
	                    
	                    return $db->query($sql);
	}
	
	function selectPerPage2($db, $wq, $pg) {
	    
	    $sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
	        ."		select @rnum:=0, code, name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, img_fg_del, reg_date "   // , stock_qty, stock_apply_date
	            ."		,(select name from ism_mst_brand b where b.imb_idx = g.imb_idx) as brand_name "
	                ."		,(select title from ism_mst_category c1 where c1.imct_idx = g.cate1_idx) as cate1_name "
	                    ."		,(select title from ism_mst_category c2 where c2.imct_idx = g.cate2_idx) as cate2_name "
	                        ."		,(select title from ism_mst_category c3 where c3.imct_idx = g.cate3_idx) as cate3_name "
	                            ."		,(select title from ism_mst_category c4 where c4.imct_idx = g.cate4_idx) as cate4_name "
	                                ."		from ism_mst_goods g "
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
	        ." from ism_mst_goods g "
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
	        ." from ism_mst_goods g "
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
	
	function insert($db, $arrVal) {
	    
	    $sql =" insert into ism_mst_goods(code, name, imb_idx, cate1_idx, cate2_idx, cate3_idx, cate4_idx, reg_date)"
	        ." values ('".$this->checkMysql($db, $arrVal["code"])
	        ."', '".$this->checkMysql($db, $arrVal["name"])
	        ."', '".$this->checkMysql($db, $arrVal["imb_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["cate1_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["cate2_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["cate3_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["cate4_idx"])
	        ."', now())"
	            ;
	            
	            return $db->query($sql);
	}

	function update($db, $uq, $key) {
	    
	    $sql =" update ism_mst_goods"
	        .$uq->getQuery($db)
	        ." where code = ".$this->quot($db, $key);
	        
	        return $db->query($sql);
	}
	
	function delete($db, $key) {
	    
	    $sql = "update ism_mst_goods set img_fg_del = 1 where code = ".$this->quot($db, $key);
	    
	    return $db->query($sql);
	}
}
?>