<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/UploadUtil.php";
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

$last_order_date = "";
$wq = new WhereQuery(true, true);
$wq->addOrderBy("order_date", "desc");
$row_legacy = OrderMgr::getInstance()->getFirst($wq);

if($row_legacy) {
    $last_order_date = $row_legacy["order_date"];
}

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

// No.	주문일시	쇼핑몰명	쇼핑몰 id	상품명(수집)	옵션(수집)	     상품명(확정)	옵션(확정)	      수량	     EA	     상품코드(옵션코드)	쇼핑몰 상품코드	    품목코드	주문번호(사방넷)	판매번호(쇼핑몰)	부주문번호      주문순번	정산대조여부	세트분리여부	판매가 수집	   판매가 상품	결제금액	상태	    과면세 구분

foreach ($reader->getSheetIterator() as $sheet) {
    
    $fg_first = true;
    $no = 1;
    
    if ($sheet->getIndex() > 0) {   // 작업자의 실수를 줄이기 위해 첫번째 시트만 등록하도록 수정.
        break;
    }
    
    foreach ($sheet->getRowIterator() as $row) {
        
        if ($fg_first) {
            if ($row[0] != "No." || $row[1] != "주문일시" || $row[2] != "쇼핑몰명" || $row[8] != "수량" || $row[9] != "EA" || $row[22] != "상태" || $row[23] != "과면세 구분") {
                JsUtil::alertBack("[".$no_sheet."번째 sheet] "."엑셀 양식이 일치하지 않습니다.    ");
                exit;
            }
            
            $fg_first = false;
        } else {
            
            if ($row[0] && $row[1]) {
                if (!is_numeric($row[0])) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [No.] 항목은 숫자 타입만 가능합니다.    "."|".$row[0]."|".$row[1]."|");
                    exit;
                }
                
                if (!preg_match("/^([2][0][0-9]{2})-([0-9]{2})-([0-9]{2}) ([0-9]{2})\:([0-9]{2})$/", $row[1])) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [주문일시] 항목은 날짜 타입만 가능합니다.    ");
                    exit;
                }
                
                if (!is_numeric($row[8])) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [수량] 항목은 숫자 타입만 가능합니다.    ");
                    exit;
                }
                
                if (!is_numeric($row[9])) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [EA] 항목은 숫자 타입만 가능합니다.    ");
                    exit;
                }
                
                if (!is_numeric($row[19])) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [판매가 수집] 항목은 숫자 타입만 가능합니다.    ");
                    exit;
                }
                
                if (!is_numeric($row[20])) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [판매가 상품] 항목은 숫자 타입만 가능합니다.    ");
                    exit;
                }
                
                if (!is_numeric($row[21])) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [결제금액] 항목은 숫자 타입만 가능합니다.    ");
                    exit;
                }

                if (!$row[2]) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [쇼핑몰명] 항목은 필수값 입니다.    ");
                    exit;
                }
                
                if (!$row[12]) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [품목코드] 항목은 필수값 입니다.    ");
                    exit;
                }
    
                if (!$row[13]) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [주문번호(사방넷)] 항목은 필수값 입니다.    ");
                    exit;
                }
                
    /*            
                if($last_order_date > $row[1]) {
                    JsUtil::alertBack($no."번째 행의 [수집일시]가 기 등록된 최근 판매일보다 과거 판매일입니다.(최근 판매일 : ".$last_order_date.")    ");
                    exit;
                }
    */
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
        
        $arr_insert['no'] = $arr_data[$i][0];
        $arr_insert['order_date'] = $arr_data[$i][1];
        $arr_insert['channel'] = $arr_data[$i][2];
        $arr_insert['channel_id'] = $arr_data[$i][3];
        $arr_insert['name_collect'] = $arr_data[$i][4];
        $arr_insert['opt_name_collect'] = $arr_data[$i][5];
        $arr_insert['name_confirm'] = $arr_data[$i][6];
        $arr_insert['opt_name_confirm'] = $arr_data[$i][7];
        $arr_insert['amount'] = $arr_data[$i][8];
        $arr_insert['ea'] = $arr_data[$i][9];
        $arr_insert['goods_code'] = $arr_data[$i][10];
        $arr_insert['goods_code_mall'] = $arr_data[$i][11];
        $arr_insert['item_code'] = $arr_data[$i][12];
        $arr_insert['order_no'] = $arr_data[$i][13];
        $arr_insert['order_no_mall'] = $arr_data[$i][14];
        $arr_insert['order_no_sub'] = $arr_data[$i][15];
        $arr_insert['order_no_seq'] = $arr_data[$i][16];
        $arr_insert['fg_calculate'] = $arr_data[$i][17];
        $arr_insert['fg_separate'] = $arr_data[$i][18];
        $arr_insert['price_collect'] = $arr_data[$i][19];
        $arr_insert['price_goods'] = $arr_data[$i][20];
        $arr_insert['price_pay'] = $arr_data[$i][21];
        $arr_insert['status'] = $arr_data[$i][22];
        $arr_insert['tax_type'] = $arr_data[$i][23];
        $arr_insert['grp_code'] = $grp_code;
        
        OrderMgr::getInstance()->add($arr_insert);

    }
    
    OrderMgr::getInstance()->add_check(array("grp_code"=>$grp_code));
    
    JsUtil::alertReplace("총 ".($cnt_total-$no_sheet)."개의 Row가 등록되었습니다.    ", "./upload_sales_data.php");
    
}
?>