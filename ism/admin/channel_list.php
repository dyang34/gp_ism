<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/channel/ChannelMgr.php";

$menuCate = 3;
$menuNo = 7;

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = ChannelMgr::getInstance()->getList($wq);

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
    <form name="pageForm" method="get">
    </form>

			<div style="padding-left:20px;">
                <h3 class="icon-list wrt_icon_search">채널 <strong><?=number_format($rs->num_rows)?>건</strong></h3>
                <ul class="icon_Btn">
                    <li><a href="#" name="btnExcelDownload">엑셀</a></li>
                </ul>
            </div>

            <!-- 메인TABLE(s) -->
            <table class="display" cellpadding="0" cellspacing="0">
            	<colgroup>
            		<col width="12%">
            		<col width="13%">
            		<col width="12%">
            		<col width="13%">
            		<col width="12%">
            		<col width="13%">
            		<col width="12%">
            		<col width="13%">
            	</colgroup>
                <thead>
                    <tr>
                        <th class="tbl_first">명칭</th>
                        <th>등록일</th>
                        <th style="border-left:12px solid #fff;">명칭</th>
                        <th>등록일</th>
                        <th style="border-left:12px solid #fff;">명칭</th>
                        <th>등록일</th>
                        <th style="border-left:12px solid #fff;">명칭</th>
                        <th>등록일</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 2px solid #395467">
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        if ( $i == 0) {
            echo "<tr>";
        } else if ( ($i % 4) == 0 ) {
            echo "</tr><tr>";
        }
?>
                        <td class="<?=($i % 4)==0?"tbl_first":""?>" style="text-align:center;<?=($i % 4)>0?"border-left:12px solid #fff;":""?>"><?=$row["name"]?></td>
                        <td style="text-align:center;"><?=$row["reg_date"]?></td>
<?php        
    }

    if ($rs->num_rows % 4 > 0) {
        for($j=0;$j<(4-($rs->num_rows % 4));$j++) {
            echo "<td style='border-left:12px solid #fff;'></td><td></td>";
        }
    }
    
    echo "</tr>";
    
} else {
?>
					<tr><td colspan="8" style="text-align:center;">No Data.</td></tr>
<?php
}
?>                
                </tbody>
            </table>
            
<script type="text/javascript">

$(document).on('click','a[name=btnExcelDownload]', function() {
	var f = document.pageForm;
	f.target = "_new";
	f.action = "channel_list_xls.php";
	
	f.submit();
});

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";

@ $rs->free();
?>