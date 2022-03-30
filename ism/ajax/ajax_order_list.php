<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/order/OrderMgr.php";

$mode = RequestUtil::getParam("mode", "");
$order_no = RequestUtil::getParam("order_no", "");

if(!LoginManager::isUserLogined()) {
    $rtnVal['RESULTCD'] = "not_login";
    print_r(json_encode($rtnVal));
    exit;
}

if ($mode != "DEL" ) {
    $rtnVal['RESULTCD'] = "not_mode";
    print_r(json_encode($rtnVal));
    exit;
}

if (!$order_no ) {
    $rtnVal['RESULTCD'] = "no_idx";
    print_r(json_encode($rtnVal));
    exit;
}

switch($mode) {
    case "DEL":
        $row = OrderMgr::getInstance()->delete($order_no);
        break;
    default:
        $rtnVal['RESULTCD'] = "not_mode";
        print_r(json_encode($rtnVal));
        exit;
}

$rtnVal['RESULTCD'] = "SUCCESS";

print_r(json_encode($rtnVal));
exit;
?>