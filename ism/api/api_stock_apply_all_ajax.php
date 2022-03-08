<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsMgr.php";

header("Cache-Control;no-cache");
header("Pragma:no-cache");
header("Content-Type:text/html; charset=utf-8");

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '1200');

define('KEY', 'nCa3qJ5klW3pMjnW'); //128bit (16자리)
define('KEY_128', substr(KEY, 0, 128 / 8)); //256bit (32자리)
define('KEY_256', substr(KEY, 0, 256 / 8));

$ivBytes = chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0);

if(!LoginManager::isUserLogined()) {
    //JsUtil::alertReplace("로그인이 필요합니다.    ","/ism");
    $rtnVal['RESULTCD'] = "not_login";
    print_r(json_encode($rtnVal));
    exit;
}

$mode = RequestUtil::getParam("mode", "");
$item_code = RequestUtil::getParam("item_code", "");
$div_idx = RequestUtil::getParam("div_idx", "");
/*
$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
*/
if($mode != "API_STOCK") {
    //JsUtil::alertBack("모드 에러입니다.   ");
    $rtnVal['RESULTCD'] = "not_mode";
    print_r(json_encode($rtnVal));
    exit;
}

if($item_code!="ISM_GOODS_ALL") {
    //JsUtil::alertBack("품목(옵션) 코드가 잘못 지정되었습니다.   ");
    $rtnVal['RESULTCD'] = "not_item_code";
    print_r(json_encode($rtnVal));
    exit;
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("img_fg_del","=","0");
//$wq->addAndLike("item_code","DG");

if (!empty($div_idx)) {
    $start_idx = ($div_idx-1)*500;
    $end_idx = $div_idx*500;
    
    $wq->addAndString("img_idx",">",$start_idx);
    $wq->addAndString("img_idx","<=",$end_idx);
}

$wq->addOrderBy("stock_apply_date","asc");

$rs = GoodsMgr::getInstance()->getList($wq);

$url = "http://cbt.htns.com/api/v2/prdlist.do";
$headers = array(
    "Content-Type: application/json; charset=utf-8"
);

$cnt_applied = $cnt_not_applied = $cnt_error = 0;

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        $data = "{\"PRODUCT_CODE\":\"".$row["item_code"]."\"}";

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
//        $arr = json_decode($response, true);
        
        $cnt_stock = 0;
        $fg_apply = true;
        if($arr[RESULTCD]=="00") {
            if($arr[RESULTS][0][STOCKS]) {
                for($j=0;$j<count($arr[RESULTS][0][STOCKS]);$j++) {
                    $cnt_stock += $arr[RESULTS][0][STOCKS][$j][STOCK_QTY];
                }

                $cnt_applied++;
            } else {
                $cnt_not_applied++;
                $fg_apply = false;
            }
        } else {
            $cnt_error++;
            $fg_apply = false;
        }
        
        if($fg_apply) {
            
            $uq = new UpdateQuery();
            $uq->add("stock_qty", $cnt_stock);
            $uq->addNotQuot("stock_apply_date", "now()");
            
            GoodsMgr::getInstance()->edit($uq, $row["item_code"]);
        }
    }
    
    //JsUtil::alertReplace("총 상품     : ".number_format($rs->num_rows)."건.\\r\\n\\r\\n반영 상품   : ".number_format($cnt_applied)."건.\\r\\n\\r\\n미반영 상품 : ".number_format($cnt_not_applied)."건.\\r\\n\\r\\n에러 상품   : ".number_format($cnt_error)."건.", "../admin/apply_stock.php");
    $rtnVal['RESULTCD'] = "SUCCESS";
    $rtnVal['CNT_ALL'] = $rs->num_rows;
    $rtnVal['CNT_APPLY'] = $cnt_applied;
    $rtnVal['CNT_NOT_APPLY'] = $cnt_not_applied;
    $rtnVal['CNT_ERROR'] = $cnt_error;
    print_r(json_encode($rtnVal));
    exit;
} else {
    //JsUtil::alertBack("상품이 없습니다.   ");
    $rtnVal['RESULTCD'] = "no_data";
    print_r(json_encode($rtnVal));
    exit;
}
?>