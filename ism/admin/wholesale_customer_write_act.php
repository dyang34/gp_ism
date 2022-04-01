<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/channel/ChannelMgr.php";

if(!LoginManager::isUserLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/ism");
    exit;
}

if (LoginManager::getUserLoginInfo("iam_grade") < 9) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$mode = RequestUtil::getParam("mode", "INS");
$imc_idx = RequestUtil::getParam("imc_idx", "");
$imst_idx = RequestUtil::getParam("imst_idx", "");
$name = RequestUtil::getParam("name", "");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

try {
    if($mode=="INS") {
        
        if (!$name) {
            JsUtil::alertBack("거래처명을 입력해 주십시오.   ");
            exit;
        }
        
        $arrIns = array();
        $arrIns["imst_idx"] = $imst_idx;
        $arrIns["name"] = $name;
        
        ChannelMgr::getInstance()->add($arrIns);
        
        JsUtil::alertReplace("등록되었습니다.    ", "./wholesale_customer_list.php");
        
    } else if($mode=="UPD") {

        if (!$imc_idx) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
            exit;
        }
        
        if (!$name) {
            JsUtil::alertBack("거래처명을 입력해 주십시오.   ");
            exit;
        }
        
        $row_data = ChannelMgr::getInstance()->getByKey($imc_idx);
        
        //        if (empty($row_data)) {
        if (!$row_data) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        
        $uq = new UpdateQuery();
        $uq->add("imst_idx", $imst_idx);
        $uq->add("name", $name);
        
        ChannelMgr::getInstance()->edit($uq, $imc_idx);
        
        JsUtil::alertReplace("수정되었습니다.    ", "./wholesale_customer_list.php");
        
    } else if($mode=="DEL") {
        
//        if (empty($userid)) {
        if (!$imc_idx) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x03)   ");
            exit;
        }
        
        $row_data = ChannelMgr::getInstance()->getByKey($imc_idx);
        
        //        if (empty($row_data)) {
        if (!$row_data) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        
        ChannelMgr::getInstance()->delete($imc_idx);
        
        JsUtil::alertReplace("삭제되었습니다.    ", "./wholesale_customer_list.php");
        
    } else {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
    }
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>