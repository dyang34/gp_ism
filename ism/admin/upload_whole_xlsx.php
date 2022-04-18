<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/UploadUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/channel/ChannelMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/sales_type/SalesTypeMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/status/StatusMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsItemMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/order/OrderMgr.php";

require_once $_SERVER['DOCUMENT_ROOT'].'/tools/Spout/Autoloader/autoload.php';

header("Cache-Control;no-cache");
header("Pragma:no-cache");
header("Content-Type:text/html; charset=utf-8");

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');

if ( !$_FILES["up_file"]["name"] ) {
    JsUtil::alertBack("업로드할 파일을 지정해 주십시오.   ");
    exit;
}

$arrChannel = $arrSalesType = $arrStatus = $arrGoodsItem = array();

$wq = new WhereQuery(true, true);
$wq->addOrderBy("sort","asc");
$rs = StatusMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        array_push($arrStatus, $row["title_status"]);
    }
}

$wq = new WhereQuery(true, true);
$rs = SalesTypeMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        array_push($arrSalesType, $row["title"]);
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
$wq->addAndString2("imst_idx","<>","1");

$wq->addOrderBy("imst_idx","");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = ChannelMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        array_push($arrChannel, $row["sales_type_title"]."|".$row["name"]);
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("img_fg_del","=","0");
$wq->addAndString2("imgi_fg_del","=","0");

$rs = GoodsItemMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        array_push($arrGoodsItem, $row["item_code"]);
    }
}

/*
$last_order_date = "";
$wq = new WhereQuery(true, true);
$wq->addOrderBy("order_date", "desc");
$row_legacy = OrderMgr::getInstance()->getFirst($wq);

if($row_legacy) {
    $last_order_date = $row_legacy["order_date"];
}
*/
$newFileName = UploadUtil::getNewFileName();

$ret = UploadUtil::upload2("up_file", $newFileName, UploadUtil::$Excel_UpWebPath, UploadUtil::$Excel_MaxFileSize, UploadUtil::$Excel_AllowFileType, false);

if ( !empty($ret["err_code"]) ) {
    JsUtil::alertBack($ret["err_msg"]." ErrCode : ".$ret["err_code"]);
    exit;
}

$newWebPath = $ret["newWebPath"];
$newFileName = $ret["newFileName"];
$fileExtName = $ret["fileExtName"];
$fileSize = $ret["fileSize"];
    
//    $arrVal["wr_upload"] = $newFileName;
//    $arrVal["wr_filename"] = $_FILES["wr_upload"]["name"];

$file = $_SERVER['DOCUMENT_ROOT'].$newWebPath.$newFileName;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

$reader = ReaderFactory::create(Type::XLSX);
$reader->open($file);

$no_sheet = 1;
$cnt_total = 1;
$arr_data = array();

// 판매유형     거래처     주문일자    품목코드    수량      EA      금액      과세구분        상태

foreach ($reader->getSheetIterator() as $sheet) {
    
    $fg_first = true;
    $no = 1;
    
    if ($sheet->getIndex() > 0) {   // 작업자의 실수를 줄이기 위해 첫번째 시트만 등록하도록 수정.
        break;
    }
    
    foreach ($sheet->getRowIterator() as $row) {
        
        if ($fg_first) {
            if ($row[0] != "판매유형" || $row[1] != "거래처" || $row[2] != "주문일자" || $row[8] != "상태") {
                JsUtil::alertBack("[".$no_sheet."번째 sheet] "."엑셀 양식이 일치하지 않습니다.    ");
                exit;
            }
            
            $fg_first = false;
        } else {
            
            if ($row[0] && $row[1] && $row[2] && $row[3]) {

                if (!in_array($row[0], $arrSalesType)) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [판매유형]은 존재하지 않는 판매유형입니다.    "."|".$row[0]);
                    exit;
                }
                
                if (!in_array($row[0]."|".$row[1], $arrChannel)) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [거래처]는 존재하지 않는 거래처입니다.    "."\\r\\n\\r\\n".$row[0]."|".$row[1]."|");
                    exit;
                }
                
                if (!preg_match("/^([2][0][0-9]{2})-([0-9]{2})-([0-9]{2})$/", date_format($row[2], 'Y-m-d'))) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [주문일자] 항목은 날짜 타입만 가능합니다.    ".date_format($row[2], 'Y-m-d'));
                    exit;
                }
                
                if (!in_array($row[3], $arrGoodsItem)) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [품목코드]는 존재하지 않는 품목코드입니다.    "."|".$row[3]);
                    exit;
                }
                
                if (!is_numeric($row[4])) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [수량] 항목은 숫자 타입만 가능합니다.    "."|".$row[4]);
                    exit;
                }

                if (!is_numeric($row[5])) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [EA] 항목은 숫자 타입만 가능합니다.    "."|".$row[5]);
                    exit;
                }
                
                if (!is_numeric($row[6])) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [금액] 항목은 숫자 타입만 가능합니다.    "."|".$row[6]);
                    exit;
                }
                
                if ($row[7]!="과세" && $row[7]!="면세") {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [과세구분]은 과세 혹은 면세이어야만 합니다.    "."|".$row[7]);
                    exit;
                }
                
                if (!in_array($row[8], $arrStatus)) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [상태]는 존재하지 않는 상태입니다.    "."|".$row[8]);
                    exit;
                }
                
                array_push($arr_data, $row);
            }
        }
        
        $no++;
        $cnt_total++;
    }
    
    $no_sheet++;
}

$reader->close();

if ($cnt_total > 20000) {
    JsUtil::alertBack("[".$no_sheet."번째 sheet] 한번에 2만개 이상의 Data를 등록할 수 없습니다.    ");
    exit;
}

$grp_code = date("YmdHis");

$arr_insert = array();
if (count($arr_data) > 0) {
    for($i=0;$i<count($arr_data);$i++) {
        
        $arr_insert['order_type'] = $arr_data[$i][0];
        $arr_insert['channel'] = $arr_data[$i][1];
        $arr_insert['order_date'] = date_format($arr_data[$i][2], 'Y-m-d');
        $arr_insert['item_code'] = $arr_data[$i][3];
        $arr_insert['amount'] = $arr_data[$i][4];
        $arr_insert['ea'] = $arr_data[$i][5];
        $arr_insert['price'] = $arr_data[$i][6];
        $arr_insert['tax_type'] = $arr_data[$i][7];
        $arr_insert['status'] = $arr_data[$i][8];
        $arr_insert['i'] = $i;
        $arr_insert['grp_code'] = $grp_code;
        
        OrderMgr::getInstance()->add_wholesale_upload($arr_insert);

    }
    
    JsUtil::alertReplace("총 ".($cnt_total-$no_sheet)."개의 Row가 등록되었습니다.    ", "./upload_wholesales_data.php");
    
}
?>