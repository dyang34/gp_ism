<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsItemMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/sales_type/SalesTypeMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/order/OrderMgr.php";

// ini_set('memory_limit','512M');

if (LoginManager::getUserLoginInfo("iam_grade") < 9) {
    echo "작업 권한이 없습니다.    ";
    exit;
}

$_order_date_from = RequestUtil::getParam("_order_date_from", date("Y-m-01"));
$_order_date_to = RequestUtil::getParam("_order_date_to", date("Y-m-d"));
$_imc_idx = RequestUtil::getParam("_imc_idx", "");
$_imb_idx = RequestUtil::getParam("_imb_idx", "");
$_cate1_idx = RequestUtil::getParam("_cate1_idx", "");
$_cate2_idx = RequestUtil::getParam("_cate2_idx", "");
$_cate3_idx = RequestUtil::getParam("_cate3_idx", "");
$_cate4_idx = RequestUtil::getParam("_cate4_idx", "");
$_tax_type = RequestUtil::getParam("_tax_type", "");
$_goods_mst_code = RequestUtil::getParam("_goods_mst_code", "");
$_goods_name = RequestUtil::getParam("_goods_name", "");
$_item_code = RequestUtil::getParam("_item_code", "");
$_item_name = RequestUtil::getParam("_item_name", "");
$_order_type = RequestUtil::getParam("_order_type", "");
$_except_cancel = RequestUtil::getParam("_except_cancel", "");
$_status = RequestUtil::getParam("_status", "");
$_order_no = RequestUtil::getParam("_order_no", "");

$_order_by = RequestUtil::getParam("_order_by", "order_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$arrSalesType = array();

$wq = new WhereQuery(true, true);
$wq->addAndString("imst_idx", "<>", "1");
$rs = SalesTypeMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        $arrSalesType[$row["imst_idx"]] = $row["title"];
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString("order_date", ">=", $_order_date_from);
$wq->addAndStringBind("order_date", "<", $_order_date_to, "date_add('?', interval 1 day)");
$wq->addAndString("imc_idx", "=", $_imc_idx);
$wq->addAndString("imb_idx", "=", $_imb_idx);
$wq->addAndString("cate1_idx", "=", $_cate1_idx);
$wq->addAndString("cate2_idx", "=", $_cate2_idx);
$wq->addAndString("cate3_idx", "=", $_cate3_idx);
$wq->addAndString("cate4_idx", "=", $_cate4_idx);
$wq->addAndString("tax_type", "=", $_tax_type);
$wq->addAndString("order_type", "<>", "1");
$wq->addAndString("order_type", "=", $_order_type);
$wq->addAndString("goods_mst_code", "=", $_goods_mst_code);
$wq->addAndString("a.item_code", "=", $_item_code);
$wq->addAndString("status", "=", $_status);
$wq->addAndString("order_no", "=", $_order_no);

$wq->addAndLike("name",$_goods_name);
$wq->addAndLike("item_name",$_item_name);

if($_except_cancel) {
    $wq->addAndNotIn("status", array("취소접수","취소완료","삭제"));
}

$wq->addOrderBy($_order_by, $_order_by_asc);

if ($_order_by=="cate1_name") {
    $wq->addOrderBy("cate2_name", "asc");
    $wq->addOrderBy("cate3_name", "asc");
    $wq->addOrderBy("cate4_name", "asc");
}

$wq->addOrderBy("order_date", "desc");

$rs = OrderMgr::getInstance()->getList($wq, $pg);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=ISM_도매_판매 내역_".date('Ymd').".xls");
Header("Content-Description: PHP5 Generated Data");
Header("Pragma: no-cache");
Header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");
?>
<style>
td{font-size:11px;text-align:center;}
th{font-size:11px;text-align:center;color:white;background-color:#000081;}
</style>

<table cellpadding=3 cellspacing=0 border=1 bordercolor='#bdbebd' style='border-collapse: collapse'>
    <tr>
        <th style="color:white;background-color:#000081;">주문일시</th>
        <th style="color:white;background-color:#000081;">판매유형</th>
        <th style="color:white;background-color:#000081;">거래처(채널)</th>
        <th style="color:white;background-color:#000081;">브랜드</th>
        <th style="color:white;background-color:#000081;">상품코드</th>
        <th style="color:white;background-color:#000081;">상품명</th>
        <th style="color:white;background-color:#000081;">옵션코드</th>
        <th style="color:white;background-color:#000081;">옵션명</th>
        <th style="color:white;background-color:#000081;">주문번호</th>
        <th style="color:white;background-color:#000081;">수량</th>
        <th style="color:white;background-color:#000081;">EA</th>
        <th style="color:white;background-color:#000081;">판매가</th>
        <th style="color:white;background-color:#000081;">과/면세</th>
        <th style="color:white;background-color:#000081;">상태</th>
        <th style="color:white;background-color:#000081;">작업일</th>
    </tr>
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
?>
    <tr>
        <td><?=substr($row["order_date"],0,10)?></td>
        <td><?=$arrSalesType[$row["order_type"]]?></td>
        <td><?=$row["channel"]?></td>
        <td><?=$row["brand_name"]?></td>
        <td><?=$row["code"]?></td>
        <td><?=$row["name"]?></td>
        <td><?=$row["item_code"]?></td>
        <td><?=$row["item_name"]?></td>
        <td><?=$row["order_no"]?></td>
        <td><?=number_format($row["amount"])?></td>
        <td><?=number_format($row["ea"])?></td>
        <td><?=number_format($row["price"])?></td>
        <td><?=$row["tax_type"]?></td>
        <td><?=$row["status"]?></td>
        <td><?=substr($row["reg_date"],0,10)?></td>
    </tr>
<?php
    }
}
?>
</table>
<?php
@ $rs->free();
?>