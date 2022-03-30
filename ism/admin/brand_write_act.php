<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/brand/BrandMgr.php";

if(!LoginManager::isUserLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/ism");
    exit;
}

if (LoginManager::getUserLoginInfo("iam_grade") < 10) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$mode = RequestUtil::getParam("mode", "INS");
$imb_idx = RequestUtil::getParam("imb_idx", "");
$code = RequestUtil::getParam("code", "");
$name = RequestUtil::getParam("name", "");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

try {
    if($mode=="INS") {
        
//        if (empty($userid)) {
        if (!$code) {
            JsUtil::alertBack("코드를 입력해 주십시오.   ");
            exit;
        }
        
        if (!$name) {
            JsUtil::alertBack("명칭을 입력해 주십시오.   ");
            exit;
        }
        
        $wq = new WhereQuery(true, true);
        $wq->addAndString("code","=",$code);
//        $wq->addAndString2("imb_fg_del","=","0");
        
        if (BrandMgr::getInstance()->exists($wq)) {
            JsUtil::alertBack("이미 존재하는 코드 입니다.   ");
            exit;
        }
        
        $wq = new WhereQuery(true, true);
        $wq->addAndString("name","=",$name);
        $wq->addAndString2("imb_fg_del","=","0");
        
        if (BrandMgr::getInstance()->exists($wq)) {
            JsUtil::alertBack("이미 존재하는 브랜드 입니다.   ");
            exit;
        }
        
        $arrIns = array();
        $arrIns["name"] = $name;
        $arrIns["code"] = $code;
        
        BrandMgr::getInstance()->add($arrIns);
        
        JsUtil::alertReplace("등록되었습니다.    ", "./brand_list.php");
        
    } else if($mode=="UPD") {
//        if (empty($userid)) {
        if (!$imb_idx) {
                JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
            exit;
        }
        
//        if (empty($rm_name)) {
        if (!$name) {
                JsUtil::alertBack("명칭을 입력해 주십시오.   ");
            exit;
        }
        
        $row_data = BrandMgr::getInstance()->getByKey($imb_idx);
        
        //        if (empty($row_data)) {
        if (!$row_data) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        
        $uq = new UpdateQuery();
        $uq->add("name", $name);
        $uq->add("code", $code);
        
        BrandMgr::getInstance()->edit($uq, $imb_idx);
        
        JsUtil::alertReplace("수정되었습니다.    ", "./brand_list.php");
        
    } else if($mode=="DEL") {
        
//        if (empty($userid)) {
        if (!$imb_idx) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x03)   ");
            exit;
        }
        
        $row_data = BrandMgr::getInstance()->getByKey($imb_idx);
        
        //        if (empty($row_data)) {
        if (!$row_data) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        

        BrandMgr::getInstance()->delete($imb_idx);
        
        JsUtil::alertReplace("삭제되었습니다.    ", "./brand_list.php");
        
    } else {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
    }
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>