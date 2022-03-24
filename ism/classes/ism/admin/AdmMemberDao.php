<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/A_Dao.php";

class AdmMemberDao extends A_Dao
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
		 
		$sql =" select userid, iam_name, iam_grade, iam_fg_del, iam_last_login, reg_date, iam_fg_cost, iam_fg_outside "
			 ." from ism_adm_member "
			 ." where userid = ".$this->quot($db, $key)
		 	 ;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();

        return $row;
	}

	function selectByKeyForLogin($db, $key) {
	    
	    $sql =" select userid, iam_name, iam_grade, iam_fg_del, iam_last_login, reg_date, iam_fg_cost, iam_fg_outside "
	        ." from ism_adm_member "
            ." where userid = ".$this->quot($db, $key)
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

		$sql =" select userid, iam_name, iam_grade, iam_fg_del, iam_last_login, reg_date, iam_fg_cost, iam_fg_outside "
			 ." from ism_adm_member"
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

	function selectFirstForLogin($db, $wq) {
	    
	    
	    $sql =" select userid, iam_name, iam_grade, iam_fg_del, iam_last_login, reg_date, iam_fg_cost, iam_fg_outside "
	        ." from ism_adm_member"
            .$wq->getWhereQuery()
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
	    
	    $sql =" select userid, iam_name, iam_grade, iam_fg_del, iam_last_login, reg_date, iam_fg_cost, iam_fg_outside "
	         ." from ism_adm_member"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			 ."		select @rnum:=0, userid, iam_name, iam_grade, iam_fg_del, iam_last_login, reg_date, iam_fg_cost, iam_fg_outside "
			 ."		from ism_adm_member"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." ) r"
			 ;

        return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from ism_adm_member a "
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
			 ." from ism_adm_member"
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
	    
	    $sql =" insert ism_adm_member(userid, passwd, iam_name, iam_grade, iam_fg_cost, iam_fg_outside, reg_date)"
	        ." values ('".$this->checkMysql($db, $arrVal["userid"])
	        ."', password('".$this->checkMysql($db, $arrVal["passwd"])."')"
	            .", '".$this->checkMysql($db, $arrVal["iam_name"])
	            ."', '".$this->checkMysql($db, $arrVal["iam_grade"])
	            ."', '".$this->checkMysql($db, $arrVal["iam_fg_cost"])
	            ."', '".$this->checkMysql($db, $arrVal["iam_fg_outside"])
	            ."', now())"
	                ;
	                
	                return $db->query($sql);
	                
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update ism_adm_member"
	        .$uq->getQuery($db)
	        ." where userid = ".$this->quot($db, $key);
	        
	        return $db->query($sql);
	}
	
	function delete($db, $key) {
	    
	    $sql =" update ism_adm_member set iam_fg_del=1 where userid = ".$this->quot($db, $key);
	    
	    return $db->query($sql);
	}	
}
?>