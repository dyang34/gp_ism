<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/brand/BrandMgr.php";

$menuCate = 3;
$menuNo = 5;

if (LoginManager::getUserLoginInfo("iam_grade") < 10) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$mode = RequestUtil::getParam("mode", "INS");
$imb_idx = RequestUtil::getParam("imb_idx", "");

if ($mode=="UPD") {
    //    if(empty($userid)) {
    if(!$imb_idx) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
        exit;
    }
    
    $row = BrandMgr::getInstance()->getByKey($imb_idx);
    
    //    if (empty($row)) {
    if (!$row) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
        exit;
    }
} else {
    //    if(!empty($userid)) {
    if($imb_idx) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x04)   ");
        exit;
    }
}

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
            
			<!-- 202112123 등록하기(s) -->
            <div class="gp_rig_search">
                <div style="padding-left:20px;">
                    <h3 class="wrt_icon_search">브랜드 등록하기</h3>
                    <!--<ul class="icon_Btn">
                        <li><a href="#">조회</a></li>  
                        <li><a href="#">추가</a></li>
                        <li><a href="#">엑셀</a></li>
                        <li><a href="#">삭제</a></li>
                        <li><a href="#">저장</a></li>
                        <li><a href="#">인쇄</a></li>
                    </ul>-->
                </div>
				<form name="writeForm" action="./brand_write_act.php" method="post">
					<input type="hidden" name="mode" value="<?=$mode?>" />
					<input type="hidden" name="auto_defense" />
					<input type="hidden" name="imb_idx" value="<?=$imb_idx?>" />
                    <table class="wrt_table">
                        <caption>등록하기</caption>
                        <colgroup>
                            <col style="width:10%;"><col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <th>코드</th>
                                <td>
                                    <input type="text" name="code" value="<?=$row['code']?>" placeholder="코드를 입력하세요." style="width: 20%;">
                                </td>
                            </tr>
                            <tr>
                                <th>명칭</th>
                                <td>
                                    <input type="text" name="name" value="<?=$row['name']?>" placeholder="명칭을 입력하세요." style="width: 20%;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
				</form>
				<!-- 취소/등록 버튼 START -->
				<div style="overflow: hidden; display: flex; display: -webkit-flex; -webkit-align-items: center; align-items: center; flex-direction: inherit; justify-content: center; margin-top: 9px;">
					<div class="wrt_searchBtn">
						<a href="#" name="btnCancel">취소</a>
					</div>
					<div class="wrt_searchBtn">
						<a href="#" name="btnSave">저장</a>
					</div>
<?php
if ($mode=="UPD") {
?>
					<div class="wrt_searchBtn" style="margin-right: 0;">
						<a href="#" name="btnDel">비노출</a>
					</div>
<?php
}
?>
				</div>
				<!-- 취소/등록 버튼 END -->
			</div>
			<!-- 202112123 등록하기(e) -->

<script src="/ism/cms/js/util/ValidCheck.js"></script>	
<script type="text/javascript">
var mc_consult_submitted = false;

$(document).on("click","a[name=btnSave]",function() {
	if(mc_consult_submitted == true) { return false; }
	
	var f = document.writeForm;

	if ( VC_inValidText(f.code, "코드") ) return false;
	if ( VC_inValidText(f.name, "명칭") ) return false;

	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
});

$(document).on("click","a[name=btnDel]",function() {
	if (!confirm("정말 비노출하시겠습니까?    ")) {
		return false;
	}
	
	if(mc_consult_submitted == true) { return false; }

	var f = document.writeForm;

	f.mode.value="DEL";
	
	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
});

$(document).on("click","a[name=btnCancel]",function() {

	history.back();

    return false;
});
</script>	

<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";
?>