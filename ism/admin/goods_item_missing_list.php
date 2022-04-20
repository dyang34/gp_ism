<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsItemMgr.php";

$menuCate = 3;
$menuNo = 29;

if (LoginManager::getUserLoginInfo("iam_grade") < 10) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$rs = GoodsItemMgr::getInstance()->getMissingList();

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
    <form name="pageForm" method="get">
        
    </form>

			<div style="padding-left:20px;">
                <h3 class="icon-list wrt_icon_search">누락 품목 <strong><?=number_format($rs->num_rows)?>건</strong></h3>
                <ul class="icon_Btn">
                    <li><a href="#" name="btnExcelDownload">엑셀</a></li>
                </ul>
            </div>
           
            <!-- 메인TABLE(s) -->
            <table class="display" cellpadding="0" cellspacing="0">
          		<colgroup>
                    <col >
                    <col >
                    <col >
                </colgroup>
                <thead>
                    <tr>
                        <th class="tbl_first">No</th>
                        <th>누락된 품목 코드</th>
                        <th>예상 상품명</th>
                        <th>최근 주문일(취소 포함)</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 2px solid #395467">
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
?>
                    <tr>
                        <td class="tbl_first" style="text-align:center;"><?=number_format($rs->num_rows)-$i?></td>
                        <td><?=$row["item_code"]?></td>
                        <td><?=$row["name_confirm"]?></td>
                        <td class="txt_c"><?=$row["order_date"]?></td>
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="4" style="text-align:center;">No Data.</td></tr>
<?php
}
?>                

                </tbody>
            </table>
            

<script type="text/javascript">

$(document).on('click','a[name=btnExcelDownload]', function() {
	var f = document.pageForm;
	f.target = "_new";
	f.action = "goods_item_missing_list_xls.php";
	
	f.submit();
});

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";

@ $rs->free();
?>