<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/A_Dao.php";

class ChannelDao extends A_Dao
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
		 
		$sql =" select imc_idx, code, name, sort, imc_fg_del, reg_date, imst_idx, imc_fg_supply, (select title from ism_mst_sales_type b where b.imst_idx = a.imst_idx) as sales_type_title "
			 ." from ism_mst_channel a "
			 ." where imc_idx = ".$this->quot($db, $key)
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

		$sql =" select imc_idx, code, name, sort, imc_fg_del, reg_date, imst_idx, imc_fg_supply, (select title from ism_mst_sales_type b where b.imst_idx = a.imst_idx) as sales_type_title "
			 ." from ism_mst_channel a "
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
	    
	    $sql =" select imc_idx, code, name, sort, imc_fg_del, reg_date, imst_idx, imc_fg_supply, (select title from ism_mst_sales_type b where b.imst_idx = a.imst_idx) as sales_type_title "
	         ." from ism_mst_channel a "
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			 ."		select @rnum:=0, imc_idx, code, name, sort, imc_fg_del, reg_date, imst_idx, imc_fg_supply, (select title from ism_mst_sales_type b where b.imst_idx = a.imst_idx) as sales_type_title "
			 ."		from ism_mst_channel a "
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." ) r"
			 ;

        return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from ism_mst_channel a "
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
			 ." from ism_mst_channel"
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
	    
	    $sql =" insert into ism_mst_channel(code, name, sort, imst_idx, imc_fg_supply, reg_date)"
	        ." values ('".$this->checkMysql($db, $arrVal["code"])
	        ."', '".$this->checkMysql($db, $arrVal["name"])
	        ."', '".$this->checkMysql($db, $arrVal["sort"])
	        ."', '".$this->checkMysql($db, $arrVal["imst_idx"])
	        ."', '".$this->checkMysql($db, $arrVal["imc_fg_supply"])
	        ."', now())"
	            ;
	            
        return $db->query($sql);
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update ism_mst_channel"
	        .$uq->getQuery($db)
	        ." where imc_idx = ".$this->quot($db, $key);
	        
	        return $db->query($sql);
	}
	
	function delete($db, $key) {
	    
	    $sql = "update ism_mst_channel set imc_fg_del = 1 where imc_idx = ".$this->quot($db, $key);
	    
	    return $db->query($sql);
	}	
}
?>