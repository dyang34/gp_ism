<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/CmsConfig.php";

class DbUtil
{
    static function getConnection() {
        @ $db = new mysqli(CmsConfig::$mysql_host, CmsConfig::$mysql_user, CmsConfig::$mysql_password, CmsConfig::$mysql_database);
/*        
        $ism_connect = mysql_connect(CmsConfig::$mysql_host,CmsConfig::$mysql_user,CmsConfig::$mysql_password) or die(mysql_error().'<BR> DB connect fail !!');
        mysql_query("set names utf8");
        $ism_db = mysql_select_db(CmsConfig::$mysql_database, $ism_connect) or die('DB selected fail !!');
*/        
        
        if ( $db->connect_errno ) {
            throw new Exception("DbUtil getConnection Error!");
        } else {
            // 한글처리.
//            mysqli_query($db, 'set names euckr');
            return $db;
        }
    }

    static function freeProcedureResult($db) {
        while ( $db->more_results() ) {
            if ( $db->next_result() ) {
                if ( $use_result = $db->use_result() ) {
                    $use_result->close();
                }
            }
        }
    }
}
?>