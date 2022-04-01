<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/channel/ChannelMgr.php";

$menuCate = 3;
$menuNo = 7;

if (LoginManager::getUserLoginInfo("iam_grade") < 10) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
$wq->addOrderBy("imst_idx","asc");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = ChannelMgr::getInstance()->getList($wq);

$arrChannel = array();
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_channel = $rs->fetch_assoc();
        
        array_push($arrChannel, $row_channel);
    }
}

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
    <form name="pageForm" method="get">
    </form>

			<div style="padding-left:20px;">
                <h3 class="icon-list wrt_icon_search">거래처(채널) <strong><?=number_format($rs->num_rows)?>건</strong></h3>
                <ul class="icon_Btn">
                    <li><a href="#" name="btnExcelDownload">엑셀</a></li>
                </ul>
            </div>

<?php
$prev_imst_idx = 0;
$j=0;
for($i=0;$i<count($arrChannel);$i++) {
    if($prev_imst_idx != $arrChannel[$i]["imst_idx"]) {
        if ($prev_imst_idx > 0) {
?>
                </tbody>
            </table>
<?php             
        } 
?>
			<div><?=$arrChannel[$i]["sales_type_title"]?></div>
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
        $j = 0;
    }
    
    if ( $j == 0) {
        echo "<tr>";
    } else if ( ($j % 4) == 0 ) {
        echo "</tr><tr>";
    }
?>
                        <td class="<?=($i % 4)==0?"tbl_first":""?>" style="text-align:center;<?=($i % 4)>0?"border-left:12px solid #fff;":""?>"><?=$arrChannel[$i]["name"]?></td>
                        <td style="text-align:center;"><?=$arrChannel[$i]["reg_date"]?></td>
                
<?php
$j++;
$prev_imst_idx = $arrChannel[$i]["imst_idx"];
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