<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_default_data.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/admin/AdmMemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/Page.php";

$menuCate = 3;
$menuNo = 9;

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");

$_iam_name = RequestUtil::getParam("_iam_name", "");
$_iam_grade = RequestUtil::getParam("_iam_grade", "");
$_order_by = RequestUtil::getParam("_order_by", "reg_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$pg = new Page($currentPage, $pageSize);

$wq = new WhereQuery(true, true);
//$wq->addAndString("rm_wallet_addr","=",$wallet_addr);
$wq->addAndString2("iam_fg_del","=","0");
$wq->addAndLike("iam_name",$_iam_name);
$wq->addAndString("iam_grade","=",$_iam_grade);

$wq->addOrderBy($_order_by, $_order_by_asc);

$rs = AdmMemberMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">
    <input type="hidden" name="_iam_name" value="<?=$_iam_name?>">
    <input type="hidden" name="_iam_grade" value="<?=$_iam_grade?>">
    
    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
</form>

            <!-- 회원검색(s) -->
            <div>
                <div style="padding-left:20px;">
                    <h3 class="icon-search">회원 검색</h3>
                    <ul class="icon_Btn">
                    	<li><a href="./adm_mem_write.php">추가</a></li>
                    	<li><a href="#" name="btnExcelDownload">엑셀</a></li>	
<?php /*                    
                        <li><a href="#">조회</a></li>
                        <li><a href="#">추가</a></li>
                        <li><a href="#">삭제</a></li>
                        <li><a href="#">저장</a></li>
                        <li><a href="#">인쇄</a></li>
*/?>
                    </ul>
                </div>
				<form name="searchForm" method="get" action="adm_mem_list.php">
				    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    				<input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
				
                    <table class="adm-table">
                        <caption>회원 검색</caption>
                        <colgroup>
                            <col style="width:8%;">
                            <col style="width:25%;">
                            <col style="width:9%;">
                            <col style="width:25%;">
                            <col style="width:8%;">
                            <col style="width:25%;">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th>이름</th> 
                                <td><input type="text" placeholder="이름으로 검색" name="_iam_name" value="<?=$_iam_name?>" style="width: 100%;"></td>
                                <th>권한</th>
                                <td>
									<select name="_iam_grade" class="sel_category" depth="1">
                						<option value="" <?=$_iam_grade==""?"selected='selected'":""?>>권한 선택</option>
<?php
$arrMemGradeKey = array_keys($arrMemGrade);
$arrMemGradeVal = array_values($arrMemGrade);

for($ii=0;$ii<count($arrMemGrade);$ii++) {
?>
<option value="<?=$arrMemGradeKey[$ii]?>" <?=$_iam_grade==$arrMemGradeKey[$ii]?"selected":""?>><?=$arrMemGradeVal[$ii]?></option>
<?php    
}
?>                					
                					</select>
                                </td>
                            	<th></th>
                            	<td></td>
                            </tr>
                        </tbody>
                    </table>
    				<!-- 검색버튼 START -->
    				<div class="wms_searchBtn">
    					<a href="#" class="ism_btnSearch" name="btnSearch">검색</a>
    				</div>
    				<!-- 검색버튼 END -->
				</form>
				
			</div>
			<!-- 회원검색(e) -->
                
			<div class="float-wrap">
				<h3 class="float-l">등록 회원 <strong><?=number_format($pg->getTotalCount())?>건</strong></h3>
				<p class="list-adding float-r">
					<a href="#none" name="_btn_sort" order_by="reg_date" order_by_asc="desc" class="<?=$_order_by=="reg_date"?"on":""?>">최신순</a>
					<a href="#none" name="_btn_sort" order_by="userid" order_by_asc="asc" class="<?=$_order_by=="userid" && $_order_by_asc=="asc"?"on":""?>">아이디<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="userid" order_by_asc="desc" class="<?=$_order_by=="userid" && $_order_by_asc=="desc"?"on":""?>">아이디<em>▼</em></a>
					<a href="#none" name="_btn_sort" order_by="iam_name" order_by_asc="asc" class="<?=$_order_by=="name" && $_order_by_asc=="asc"?"on":""?>">이름<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="iam_name" order_by_asc="desc" class="<?=$_order_by=="name" && $_order_by_asc=="desc"?"on":""?>">이름<em>▼</em></a>
					<a href="#none" name="_btn_sort" order_by="iam_grade" order_by_asc="asc" class="<?=$_order_by=="iam_grade" && $_order_by_asc=="asc"?"on":""?>">권한<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="iam_grade" order_by_asc="desc" class="<?=$_order_by=="iam_grade" && $_order_by_asc=="desc"?"on":""?>">권한<em>▼</em></a>
				</p>
			</div>
           
            <!-- 메인TABLE(s) -->
            <table class="display" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th class="tbl_first">No</th>
                        <th>아이디</th>
                        <th>이름</th>
                        <th>권한</th>
                        <th>등록일</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 2px solid #395467">
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
?>
                    <tr>
                        <td class="tbl_first" style="text-align:center;"><?=$pg->getMaxNumOfPage() - $i?></td>
                        <td><a href="./adm_mem_write.php?mode=UPD&userid=<?=$row["userid"]?>"><?=$row["userid"]?></a></td>
                        <td><?=$row["iam_name"]?></td>
                        <td style="text-align:center;"><?=$arrMemGrade[$row["iam_grade"]]?></td>
                        <td style="text-align:center;"><?=$row["reg_date"]?></td>
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="5" style="text-align:center;">No Data.</td></tr>
<?php
}
?>                

                </tbody>
            </table>
            
            <!-- 메인TABLE(e) -->
			<p class="hide"><strong>Pagination</strong></p>
			<div style="position: relative;">
    			<?=$pg->getNaviForFuncGP("goPage", "<<", "<", ">", ">>")?>
    			<div style="position: absolute; right: 17px; bottom: 3px; text-align: center; line-height: 30px; border-radius: 10px; background-color: #313A3D;" class="rig_new"><a href="./adm_mem_write.php" style="display:inline-block;padding: 5px 22px;color: #fff;">등록하기</a></div>
    		</div>

<script type="text/javascript">

$(document).on('click','a[name=btnExcelDownload]', function() {
	var f = document.pageForm;
	f.target = "_new";
	f.action = "adm_mem_list_xls.php";
	
	f.submit();
});

var goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "adm_mem_list.php";
	f.submit();
}

$(document).on('click', 'a[name=_btn_sort]', function() {
	goSort($(this).attr('order_by'), $(this).attr('order_by_asc'));
});

var goSort = function(p_order_by, p_order_by_asc) {
	var f = document.pageForm;
	f.currentPage.value = 1;
	f._order_by.value = p_order_by;
	f._order_by_asc.value = p_order_by_asc;
	f.action = "adm_mem_list.php";
	f.submit();
}

$(document).on("click","a[name=btnSearch]",function() {
	
	var f = document.searchForm;
	
    f.submit();	
    
});

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";

@ $rs->free();
?>