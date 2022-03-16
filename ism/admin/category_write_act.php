<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/category/CategoryMgr.php";

if(!LoginManager::isUserLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/ism");
    exit;
}

$mode = RequestUtil::getParam("mode", "");
$imct_idx = RequestUtil::getParam("imct_idx", "");
$upper_imct_idx = RequestUtil::getParam("upper_imct_idx", "");
$title = RequestUtil::getParam("title", "");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

try {
    
    if($mode!="TOPINS" && $mode!="SUBINS" && $mode!="UPD" && $mode!="DEL") {
        
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
        
    }
//        if (empty($userid)) {
    if (!$title) {
        JsUtil::alertBack("카테고리명을 입력해 주십시오.   ");
        exit;
    }

    $arrVal = array();
    $arrVal["mode"] = $mode;
    $arrVal["imct_idx"] = $imct_idx;
    $arrVal["upper_imct_idx"] = $upper_imct_idx;
    $arrVal["title"] = $title;
    
    $row = CategoryMgr::getInstance()->save($arrVal);
    
    if ($row['rtn_val']==0) {
        JsUtil::alertReplace("적용되었습니다.    ", "./category_list.php");
    } else {
        JsUtil::alertReplace($row['rtn_msg'], "./category_list.php");
    }
        
        
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>