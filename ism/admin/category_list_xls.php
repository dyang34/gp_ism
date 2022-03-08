<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/category/CategoryMgr.php";

$wq = new WhereQuery(true, true);
$wq->addAndString2("imct_fg_del","=","0");

$_order_by = "case depth when 1 then lpad(sort,'4','0')
        when 2 then CONCAT((SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = a.uppest_imct_idx),'-',lpad(sort,'4','0'))
        when 3 then CONCAT((SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = a.uppest_imct_idx),'-',(SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = a.upper_imct_idx),'-',lpad(sort,'4','0'))
        when 4 then CONCAT((SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = a.uppest_imct_idx),'-',(SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = (SELECT upper_imct_idx FROM ism_mst_category b WHERE b.imct_idx = a.upper_imct_idx)),'-',(SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = a.upper_imct_idx),'-',lpad(sort,'4','0'))
        END
";
$wq->addOrderBy($_order_by, "asc");

$rs = CategoryMgr::getInstance()->getList($wq, $pg);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=ISM_카테고리 리스트_".date('Ymd').".xls");
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
		<th style="color:white;background-color:#000081;">명칭1</th>
		<th style="color:white;background-color:#000081;">명칭2</th>
		<th style="color:white;background-color:#000081;">명칭3</th>
		<th style="color:white;background-color:#000081;">명칭4</th>
		<th style="color:white;background-color:#000081;">레벨</th>
		<th style="color:white;background-color:#000081;">등록일</th>
	</tr>
<?php
$prevCate = array();
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        $prevCate[$row["depth"]] = $row["title"];
?>
                    <tr>
                        <td class="tbl_first"><?=$i+1?></td>
                        <td><?=$prevCate[1]?></td>
                        <td><?=$row["depth"]>1?$prevCate[2]:""?></td>
                        <td><?=$row["depth"]>2?$prevCate[3]:""?></td>
                        <td><?=$row["depth"]>3?$prevCate[4]:""?></td>
                        <td><?=$row["depth"]?></td>
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