<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/admin/AdmMemberMgr.php";

if(!LoginManager::isUserLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/ism");
    exit;
}

if (LoginManager::getUserLoginInfo("iam_grade") < 10) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$mode = RequestUtil::getParam("mode", "INS");
$userid = RequestUtil::getParam("userid", "");
$passwd = RequestUtil::getParam("passwd", "");
$iam_name = RequestUtil::getParam("iam_name", "");
$iam_grade = RequestUtil::getParam("iam_grade", "");
$iam_fg_cost = RequestUtil::getParam("iam_fg_cost", "");
$iam_fg_outside = RequestUtil::getParam("iam_fg_outside", "");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

try {
    if($mode=="INS") {
        
        if (empty($userid)) {
            JsUtil::alertBack("아이디를 입력해 주십시오.   ");
            exit;
        }
        
        if (empty($passwd)) {
            JsUtil::alertBack("비밀번호를 입력해 주십시오.   ");
            exit;
        }
        
        if (empty($iam_name)) {
            JsUtil::alertBack("이름을 입력해 주십시오.   ");
            exit;
        }
        
        $wq = new WhereQuery(true, true);
        $wq->addAndString("userid","=",$userid);
        
        if (AdmMemberMgr::getInstance()->exists($wq)) {
            JsUtil::alertBack("이미 존재하는 아이디입니다.   ");
            exit;
        }
        
        $arrIns = array();
        $arrIns["userid"] = $userid;
        $arrIns["passwd"] = $passwd;
        $arrIns["iam_name"] = $iam_name;
        $arrIns["iam_grade"] = $iam_grade;
        $arrIns["iam_fg_cost"] = $iam_fg_cost;
        $arrIns["iam_fg_outside"] = $iam_fg_outside;
        
        AdmMemberMgr::getInstance()->add($arrIns);
        
        JsUtil::alertReplace("등록되었습니다.    ", "./adm_mem_list.php");
        
    } else if($mode=="UPD") {
        if (empty($userid)) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
            exit;
        }
        
        if (empty($iam_name)) {
            JsUtil::alertBack("이름을 입력해 주십시오.   ");
            exit;
        }
        
        $row_mem = AdmMemberMgr::getInstance()->getByKey($userid);
        
        if (empty($row_mem)) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }

        $uq = new UpdateQuery();
        $uq->add("iam_name", $iam_name);
        $uq->add("iam_grade", $iam_grade);
        $uq->add("iam_fg_cost", $iam_fg_cost);
        $uq->add("iam_fg_outside", $iam_fg_outside);
        
        if (!empty($passwd)) {
            $uq->addWithBind("passwd", $passwd, "password('?')");
        }
        
        AdmMemberMgr::getInstance()->edit($uq, $userid);
        
        JsUtil::alertReplace("수정되었습니다.    ", "./adm_mem_list.php");
        
    } else if($mode=="DEL") {
        
        if (empty($userid)) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x03)   ");
            exit;
        }
        
        $row_mem = AdmMemberMgr::getInstance()->getByKey($userid);
        
        if (empty($row_mem)) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }

        AdmMemberMgr::getInstance()->delete($userid);
        
        JsUtil::alertReplace("삭제되었습니다.    ", "./adm_mem_list.php");
        
    } else {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
    }
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>