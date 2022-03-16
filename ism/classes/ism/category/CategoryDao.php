<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/A_Dao.php";

class CategoryDao extends A_Dao
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
		 
		$sql =" select imct_idx, depth, cate_no, code, title, display, sort, imct_fg_del, reg_date "
			 ." from ism_mst_category "
			 ." where imct_idx = ".$this->quot($db, $key)
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

		$sql =" select imct_idx, depth, cate_no, code, title, display, sort, imct_fg_del, reg_date, upper_imct_idx, uppest_imct_idx "
			 ." from ism_mst_category"
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
	    
	    $sql =" select imct_idx, depth, cate_no, code, title, display, sort, imct_fg_del, reg_date, upper_imct_idx, uppest_imct_idx "
	        ."		        ,case when depth = 1 then '' else (select title from ism_mst_category b where b.imct_idx = a.upper_imct_idx) end as upper_title "
	        ." from ism_mst_category a "
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			 ."		select @rnum:=0, imct_idx, depth, cate_no, code, title, display, sort, imct_fg_del, reg_date, upper_imct_idx, uppest_imct_idx "
            ."		        ,case when depth = 1 then '' else (select title from ism_mst_category b where b.imct_idx = a.upper_imct_idx) end as upper_title "
            ."		from ism_mst_category a"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." ) r"
			 ;

        return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from ism_mst_category a "
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
			 ." from ism_mst_category"
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
	
	function save($db, $arrVal) {
	    
	    $sql ="call sp_ism_category_save('".$this->checkMysql($db, $arrVal["mode"])."','".$this->checkMysql($db, $arrVal["imct_idx"])."','".$this->checkMysql($db, $arrVal["upper_imct_idx"])."','".$this->checkMysql($db, $arrVal["code"])."','".$this->checkMysql($db, $arrVal["title"])."')";
	    
	    $row = array();
	    $result = $db->query($sql);
	    if ( $result->num_rows > 0 ) {
	        $row = $result->fetch_assoc();
	        @ $result->free();
	    }
	    
	    return $row;
	}
	
	
	function insert($db, $arrVal) {
	    
	    $sql =" insert into ism_mst_category(depth, cate_no, code, title, sort, reg_date)"
	        ." values ('".$this->checkMysql($db, $arrVal["depth"])
	        ."', '".$this->checkMysql($db, $arrVal["cate_no"])
	        ."', '".$this->checkMysql($db, $arrVal["code"])
	        ."', '".$this->checkMysql($db, $arrVal["title"])
	        ."', '".$this->checkMysql($db, $arrVal["sort"])
	        ."', now())"
	            ;
	            
        return $db->query($sql);
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update ism_mst_category"
	        .$uq->getQuery($db)
	        ." where imct_idx = ".$this->quot($db, $key);
	        
	        return $db->query($sql);
	}

	function updateSort($db, $arrVal) {
	    
	    $sql ="call sp_ism_category_sort('".$this->checkMysql($db, $arrVal["src_idx"])."','".$this->checkMysql($db, $arrVal["tgt_idx"])."','".$this->checkMysql($db, $arrVal["src_sort"])."','".$this->checkMysql($db, $arrVal["tgt_sort"])."')";
	    
	    $row = array();
	    $result = $db->query($sql);
	    if ( $result->num_rows > 0 ) {
	        $row = $result->fetch_assoc();
	        @ $result->free();
	    }
	    
	    return $row;
	    
	}

	function delete($db, $key) {
	    
	    $sql = "update ism_mst_category set imct_fg_del = 1 where imct_idx = ".$this->quot($db, $key);
	    
	    return $db->query($sql);
	}	
}
?>