<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsMgr.php";

if(!LoginManager::isUserLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/ism");
    exit;
}

if (LoginManager::getUserLoginInfo("iam_grade") < 10) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$mode = RequestUtil::getParam("mode", "INS");
$code = RequestUtil::getParam("code", "");
$name = RequestUtil::getParam("name", "");
$imb_idx = RequestUtil::getParam("imb_idx", "");
$cate1_idx = RequestUtil::getParam("cate1_idx", "");
$cate2_idx = RequestUtil::getParam("cate2_idx", "");
$cate3_idx = RequestUtil::getParam("cate3_idx", "");
$cate4_idx = RequestUtil::getParam("cate4_idx", "");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

try {
    if($mode=="INS") {
        
//        if (empty($userid)) {
        if (!$code) {
            JsUtil::alertBack("상품코드를 입력해 주십시오.   ");
            exit;
        }

        if (!$name) {
            JsUtil::alertBack("상품명칭을 입력해 주십시오.   ");
            exit;
        }
        
        if (!$imb_idx) {
            JsUtil::alertBack("브랜드를 입력해 주십시오.   ");
            exit;
        }
        
        if (!$cate1_idx) {
            JsUtil::alertBack("카테고리를 입력해 주십시오.   ");
            exit;
        }
        
        $wq = new WhereQuery(true, true);
        $wq->addAndString("code","=",$code);
        
        if (GoodsMgr::getInstance()->exists($wq)) {
            JsUtil::alertBack("이미 존재하는 옵션코드입니다.   ");
            exit;
        }
        
        $arrIns = array();
        $arrIns["name"] = $name;
        $arrIns["code"] = $code;
        $arrIns["imb_idx"] = $imb_idx;
        $arrIns["cate1_idx"] = $cate1_idx;
        $arrIns["cate2_idx"] = $cate2_idx;
        $arrIns["cate3_idx"] = $cate3_idx;
        $arrIns["cate4_idx"] = $cate4_idx;

        GoodsMgr::getInstance()->add($arrIns);
       
        JsUtil::alertReplace("등록되었습니다.    ", "./goods_list.php");
        
    } else if($mode=="UPD") {

        if (!$code) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
            exit;
        }
        
        if (!$name) {
            JsUtil::alertBack("상품명칭을 입력해 주십시오.   ");
            exit;
        }
        
        if (!$imb_idx) {
            JsUtil::alertBack("브랜드를 입력해 주십시오.   ");
            exit;
        }
        
        if (!$cate1_idx) {
            JsUtil::alertBack("카테고리를 입력해 주십시오.   ");
            exit;
        }
        
        $row_data = GoodsMgr::getInstance()->getByKey($code);
        
        //        if (empty($row_data)) {
        if (!$row_data) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        
        $uq = new UpdateQuery();
        $uq->add("name", $name);
        $uq->add("imb_idx", $imb_idx);
        $uq->add("cate1_idx", $cate1_idx);
        $uq->add("cate2_idx", $cate2_idx);
        $uq->add("cate3_idx", $cate3_idx);
        $uq->add("cate4_idx", $cate4_idx);
        
        GoodsMgr::getInstance()->edit($uq, $code);
        
        JsUtil::alertReplace("수정되었습니다.    ", "./goods_list.php");
        
    } else if($mode=="DEL") {
        
//        if (empty($userid)) {
        if (!$code) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x03)   ");
            exit;
        }
        
        $row_data = GoodsMgr::getInstance()->getByKey($code);
        
        //        if (empty($row_data)) {
        if (!$row_data) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        

        GoodsMgr::getInstance()->delete($code);
        
        JsUtil::alertReplace("삭제되었습니다.    ", "./goods_list.php");
        
    } else {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
    }
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>