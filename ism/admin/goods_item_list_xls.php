<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsItemMgr.php";

$_imb_idx = RequestUtil::getParam("_imb_idx", "");
$_cate1_idx = RequestUtil::getParam("_cate1_idx", "");
$_cate2_idx = RequestUtil::getParam("_cate2_idx", "");
$_cate3_idx = RequestUtil::getParam("_cate3_idx", "");
$_cate4_idx = RequestUtil::getParam("_cate4_idx", "");

$_code = RequestUtil::getParam("_code", "");
$_name = RequestUtil::getParam("_name", "");
$_item_code = RequestUtil::getParam("_item_code", "");
$_item_name = RequestUtil::getParam("_item_name", "");
$_order_by = RequestUtil::getParam("_order_by", "reg_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$wq = new WhereQuery(true, true);
$wq->addAndString2("img_fg_del","=","0");

$wq->addAndString("imb_idx","=",$_imb_idx);
$wq->addAndString("cate1_idx","=",$_cate1_idx);
$wq->addAndString("cate2_idx","=",$_cate2_idx);
$wq->addAndString("cate3_idx","=",$_cate3_idx);
$wq->addAndString("cate4_idx","=",$_cate4_idx);

$wq->addAndLike("a.code",$_code);
$wq->addAndLike("item_code",$_item_code);
$wq->addAndLike("name",$_name);
$wq->addAndLike("item_name",$_item_name);

$wq->addOrderBy($_order_by, $_order_by_asc);

if ($_order_by=="cate1_name") {
    $wq->addOrderBy("cate2_name", "asc");
    $wq->addOrderBy("cate3_name", "asc");
    $wq->addOrderBy("cate4_name", "asc");
}

$wq->addOrderBy("reg_date", "desc");

$rs = GoodsItemMgr::getInstance()->getList2($wq, $pg);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=ISM_상품 리스트_".date('Ymd').".xls");
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
	<tr style="height:30px;">
		<th style="color:white;background-color:#000081;">No</th>
		<th style="color:white;background-color:#000081;">상품코드</th>
		<th style="color:white;background-color:#000081;">상품명</th>
		<th style="color:white;background-color:#000081;">품목(옵션)코드</th>
		<th style="color:white;background-color:#000081;">품목(옵션)명</th>
		<th style="color:white;background-color:#000081;">브랜드</th>
		<th style="color:white;background-color:#000081;">카테고리1</th>
		<th style="color:white;background-color:#000081;">카테고리2</th>
		<th style="color:white;background-color:#000081;">카테고리3</th>
		<th style="color:white;background-color:#000081;">카테고리4</th>
		<th style="color:white;background-color:#000081;">재고</th>
		<th style="color:white;background-color:#000081;">재고반영일</th>
		<th style="color:white;background-color:#000081;">등록일</th>
	</tr>
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
?>
                    <tr>
                        <td class="tbl_first"><?=$i+1?></td>
                        <td><?=$row["code"]?></td>
                        <td><?=$row["name"]?></td>
                        <td><?=$row["item_code"]?></td>
                        <td><?=$row["item_name"]?></td>
                        <td><?=$row["brand_name"]?></td>
                        <td><?=$row["cate1_name"]?></td>
                        <td><?=$row["cate2_name"]?></td>
                        <td><?=$row["cate3_name"]?></td>
                        <td><?=$row["cate4_name"]?></td>
                        <td><?=$row["stock_qty"]?></td>
                        <td><?=$row["stock_apply_date"]?></td>
                        <td><?=$row["reg_date"]?></td>
                    </tr>
<?php
    }
}
?>
</table>
<?php
@ $rs->free();
?>