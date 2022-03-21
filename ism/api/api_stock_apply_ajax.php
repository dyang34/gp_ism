<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsItemMgr.php";

define('KEY', 'nCa3qJ5klW3pMjnW'); //128bit (16자리)
define('KEY_128', substr(KEY, 0, 128 / 8)); //256bit (32자리)
define('KEY_256', substr(KEY, 0, 256 / 8));

$ivBytes = chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0);

$rtnVal = array();

if(!LoginManager::isUserLogined()) {
    $rtnVal['RESULTCD'] = "not_login";
    print_r(json_encode($rtnVal));
    exit;
}

$mode = RequestUtil::getParam("mode", "");
$item_code = RequestUtil::getParam("item_code", "");

if (!$item_code) {
    $rtnVal['RESULTCD'] = "no_item_code";
    print_r(json_encode($rtnVal));
    exit;
}

$url = "http://cbt.htns.com/api/v2/prdlist.do";
$headers = array(
    "Content-Type: application/json; charset=utf-8"
);

$data = "{\"PRODUCT_CODE\":\"".$item_code."\"}";
$data = base64_encode(openssl_encrypt($data, "AES-128-CBC", KEY_128, true, $ivBytes));
$param = array(
    'CUSTOMER_CODE' => "1009084"
    ,'DATA' => $data
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
curl_setopt($ch, CURLOPT_POST, true);

$response  = curl_exec($ch);

curl_close($ch);

$arr = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
//$arr = json_decode($response, true);

$cnt_stock = 0;
if($arr[RESULTCD]=="00") {
    if($arr[RESULTS][0][STOCKS]) {
        for($j=0;$j<count($arr[RESULTS][0][STOCKS]);$j++) {
            $cnt_stock += $arr[RESULTS][0][STOCKS][$j][STOCK_QTY];
        }
        
        $rtnVal['RESULTCD'] = "success";
    } else {
        $rtnVal['RESULTCD'] = "no_data";
        print_r(json_encode($rtnVal));
        exit;
    }
} else {
    $rtnVal['RESULTCD'] = "error";
    print_r(json_encode($rtnVal));
    exit;
}

if($cnt_stock >= 0) {
    
    $uq = new UpdateQuery();
    $uq->add("stock_qty", $cnt_stock);
    $uq->addNotQuot("stock_apply_date", "now()");
    
    GoodsItemMgr::getInstance()->edit($uq, $item_code);
    
    $cnt_applied++;
}

$row = GoodsItemMgr::getInstance()->getByKey($item_code);
$rtnVal["stock_qty"] = $row["stock_qty"];
$rtnVal["stock_apply_date"] = $row["stock_apply_date"];

print_r(json_encode($rtnVal));
exit;
?>