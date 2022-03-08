<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    JsUtil::alertBack("비정상적인 접근입니다.");
    exit;
}

switch(LoginManager::getUserLoginInfo('iam_grade')) {
    case "1":
        JsUtil::replace("/ism/order_list_aggr.php");
        break;
    case "8":
        JsUtil::replace("/ism/admin/upload_sales_data.php");
        break;
    case "9":
        JsUtil::replace("/ism/admin/wholesale_list.php");
        break;
    case "10":
        JsUtil::replace("/ism/order_list_aggr.php");
        break;
    default:
        JsUtil::alertBack("비정상적인 접근입니다.");
        exit;
        break;
}
?>