<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/category/CategoryMgr.php";

$mode = RequestUtil::getParam("mode", "");
$src_idx = RequestUtil::getParam("src_idx", "");
$src_sort = RequestUtil::getParam("src_sort", "");
$tgt_idx = RequestUtil::getParam("tgt_idx", "");
$tgt_sort = RequestUtil::getParam("tgt_sort", "");

if(!LoginManager::isUserLogined()) {
    $rtnVal['RESULTCD'] = "not_login";
    print_r(json_encode($rtnVal));
    exit;
}

if ($mode != "SORT_UP" &&  $mode != "SORT_DOWN" ) {
    $rtnVal['RESULTCD'] = "not_mode";
    print_r(json_encode($rtnVal));
    exit;
}

if (!$src_idx || !$tgt_idx ) {
    $rtnVal['RESULTCD'] = "no_idx";
    print_r(json_encode($rtnVal));
    exit;
}

if (!$src_sort || !$tgt_sort ) {
    $rtnVal['RESULTCD'] = "no_sort";
    print_r(json_encode($rtnVal));
    exit;
}

$arrUpd = array();
$arrUpd["src_idx"] = $src_idx;
$arrUpd["tgt_idx"] = $tgt_idx;
$arrUpd["src_sort"] = $src_sort;
$arrUpd["tgt_sort"] = $tgt_sort;

$row = CategoryMgr::getInstance()->editSort($arrUpd);

if ($row['rtn_val']==0) {
    $rtnVal['RESULTCD'] = "SUCCESS";
} else {
    $rtnVal['RESULTCD'] = $row['rtn_msg'];
}

print_r(json_encode($rtnVal));
exit;
?>