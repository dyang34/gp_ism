<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/admin/AdmMemberMgr.php";

$_iam_name = RequestUtil::getParam("_iam_name", "");
$_iam_grade = RequestUtil::getParam("_iam_grade", "");
$_order_by = RequestUtil::getParam("_order_by", "reg_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$wq = new WhereQuery(true, true);
$wq->addAndString2("iam_fg_del","=","0");
$wq->addAndLike("iam_name",$_iam_name);
$wq->addAndString("iam_grade","=",$_iam_grade);
$wq->addOrderBy($_order_by, $_order_by_asc);

$rs = AdmMemberMgr::getInstance()->getList($wq);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=ISM_회원 리스트_".date('Ymd').".xls");
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
		<th style="color:white;background-color:#000081;">아이디</th>
		<th style="color:white;background-color:#000081;">이름</th>
		<th style="color:white;background-color:#000081;">권한</th>
		<th style="color:white;background-color:#000081;">관리 원가 노출</th>
		<th style="color:white;background-color:#000081;">외부 접속</th>
		<th style="color:white;background-color:#000081;">최종 로그인</th>
		<th style="color:white;background-color:#000081;">등록일</th>
	</tr>
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
?>
                    <tr>
                    	<td class="tbl_first"><?=$i+1?></td>
                        <td><?=$row["userid"]?></td>
                        <td><?=$row["iam_name"]?></td>
                        <td><?=$arrMemGrade[$row["iam_grade"]]?></td>
						<td><?=$row["iam_fg_cost"]>0?"<font color='blue'>노출</font>":"<font color='gray'>비노출</font>"?></td>
						<td><?=$row["iam_fg_outside"]>0?"<font color='blue'>가능</font>":"<font color='gray'>불가</font>"?></td>
						<td><?=$row["iam_last_login"]?></td>
                        <td><?=$row["reg_date"]?></td>
                    </tr>
<?php
    }
}
?>
</table>
<?php
@ $rs->free(); ?>