<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/order/OrderMgr.php";

if(!LoginManager::isUserLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/ism");
    exit;
}

if (LoginManager::getUserLoginInfo("iam_grade") < 9) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$mode = RequestUtil::getParam("mode", "INS");
$order_no = RequestUtil::getParam("order_no", "");
$item_code = RequestUtil::getParam("item_code", "");
$order_date = RequestUtil::getParam("order_date", "");
$amount = RequestUtil::getParam("amount", "");
$ea = RequestUtil::getParam("ea", "");
$price = RequestUtil::getParam("price", "");
$tax_type = RequestUtil::getParam("tax_type", "");
$order_type = RequestUtil::getParam("order_type", "");
$status = RequestUtil::getParam("status", "");
$imc_idx = RequestUtil::getParam("imc_idx", "");
$tmp_data3 = RequestUtil::getParam("tmp_data3", "");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

try {
    if($mode=="INS") {
        
        if (!$order_date) {
            JsUtil::alertBack("주문일자를 입력해 주십시오.   ");
            exit;
        }
        
        if (!$amount) {
            JsUtil::alertBack("수량을 입력해 주십시오.   ");
            exit;
        }
        
        if (!$ea) {
            JsUtil::alertBack("EA를 입력해 주십시오.   ");
            exit;
        }
        
        if (!$price) {
            JsUtil::alertBack("금액을 입력해 주십시오.   ");
            exit;
        }
        
        $arrIns = array();
        $arrIns["item_code"] = $item_code;
        $arrIns["order_date"] = $order_date;
        $arrIns["amount"] = $amount;
        $arrIns["ea"] = $ea;
        $arrIns["price"] = $price;
        $arrIns["tax_type"] = $tax_type;
        $arrIns["order_type"] = $order_type;
        $arrIns["imc_idx"] = $imc_idx;
        $arrIns["status"] = $status;
        $arrIns["tmp_data3"] = $tmp_data3;
        
        OrderMgr::getInstance()->add2($arrIns);
        
        JsUtil::alertReplace("등록되었습니다.    ", "./wholesale_list.php");
        
    } else if($mode=="UPD") {

        if (!$order_no) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
            exit;
        }
        
        if (!$order_date) {
            JsUtil::alertBack("주문일자를 입력해 주십시오.   ");
            exit;
        }
        
        if (!$amount) {
            JsUtil::alertBack("수량을 입력해 주십시오.   ");
            exit;
        }
        
        if (!$ea) {
            JsUtil::alertBack("EA를 입력해 주십시오.   ");
            exit;
        }
        
        if (!$price) {
            JsUtil::alertBack("금액을 입력해 주십시오.   ");
            exit;
        }
        
        $row_data = OrderMgr::getInstance()->getByKey($order_no);
        
        //        if (empty($row_data)) {
        if (!$row_data) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        
        $uq = new UpdateQuery();
        $uq->add("item_code", $item_code);
        $uq->add("order_date", $order_date);
        $uq->add("amount", $amount);
        $uq->add("ea", $ea);
        $uq->add("price", $price);
        $uq->add("tax_type", $tax_type);
        $uq->add("order_type", $order_type);
        $uq->add("imc_idx", $imc_idx);
        $uq->add("status", $status);
        $uq->add("tmp_data3", $tmp_data3);
        
        OrderMgr::getInstance()->edit_wholesale($uq, $item_code, $order_no);
        
        JsUtil::alertReplace("수정되었습니다.    ", "./wholesale_list.php");
        
    } else if($mode=="DEL") {
        
//        if (empty($userid)) {
        if (!$order_no) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x03)   ");
            exit;
        }
        
        $row_data = OrderMgr::getInstance()->getByKey($order_no);
        
        //        if (empty($row_data)) {
        if (!$row_data) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        
        OrderMgr::getInstance()->delete($order_no);
        
        JsUtil::alertReplace("삭제되었습니다.    ", "./wholesale_list.php");
        
    } else {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
    }
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>