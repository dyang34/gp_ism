<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsItemMgr.php";

if (LoginManager::getUserLoginInfo("iam_grade") < 10) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$rs = GoodsItemMgr::getInstance()->getMissingList();

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=ISM_누락 품목 리스트_".date('Ymd').".xls");
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
		<th style="color:white;background-color:#000081;">누락된 품목 코드</th>
		<th style="color:white;background-color:#000081;">예상 상품명</th>
		<th style="color:white;background-color:#000081;">최근 주문일(취소 포함)</th>
	</tr>
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
?>
                    <tr>
                        <td class="tbl_first"><?=$rs->num_rows-$i?></td>
						<td><?=$row["item_code"]?></td>
                        <td><?=$row["name_confirm"]?></td>
                        <td><?=$row["order_date"]?></td>
                    </tr>
<?php
    }
}
?>
</table>
<?php
@ $rs->free();
?>