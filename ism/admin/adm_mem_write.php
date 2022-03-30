<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/admin/AdmMemberMgr.php";

$menuCate = 3;
$menuNo = 9;

if (LoginManager::getUserLoginInfo("iam_grade") < 10) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$mode = RequestUtil::getParam("mode", "INS");
$userid = RequestUtil::getParam("userid", "");

if ($mode=="UPD") {
    //    if(empty($userid)) {
    if(!$userid) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
        exit;
    }
    
    $row = AdmMemberMgr::getInstance()->getByKey($userid);
    
    //    if (empty($row)) {
    if (!$row) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
        exit;
    }
} else {
    //    if(!empty($userid)) {
    if($userid) {
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
                    <h3 class="wrt_icon_search">회원 등록하기</h3>
                    <!--<ul class="icon_Btn">
                        <li><a href="#">조회</a></li>  
                        <li><a href="#">추가</a></li>
                        <li><a href="#">엑셀</a></li>
                        <li><a href="#">삭제</a></li>
                        <li><a href="#">저장</a></li>
                        <li><a href="#">인쇄</a></li>
                    </ul>-->
                </div>
				<form name="writeForm" action="./adm_mem_write_act.php" method="post">
					<input type="hidden" name="mode" value="<?=$mode?>" />
					<input type="hidden" name="auto_defense" />

                    <table class="wrt_table">
                        <caption>등록하기</caption>
                        <colgroup>
                            <col style="width:16%;"><col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>
<?php
if ($mode=="UPD") {
?>
									<div style="height:30px;vertical-align:middle;padding:0 10px;margin:3px 0px;line-height:29px;"><?=$userid?><input type="hidden" value="<?=$userid?>" name="userid" /></div>
<?php
    
} else {
?>    									
    								<input type="text" value="" name="userid" placeholder="ID를 입력하세요." style="width: 200px;">
<?php
}
?>
                                </td>
                            </tr>
                            <tr>
                                <th>비밀번호</th>
                                <td>
                                    <input type="text" name="passwd" placeholder="<?=$mode=="UPD"?"비밀번호 변경시에만 입력.":"비밀번호를 입력하세요."?>" style="width: 200px;">
<?php
if ($mode=="UPD") {
?>
									<span style="color:red;"> ※ 비밀번호 변경시에만 입력해 주십시오.</span>
<?php
}
?>
                                </td>
                            </tr>
                            <tr>
                                <th>이름</th>
                                <td>
                                    <input type="text" name="iam_name" value="<?=$row['iam_name']?>" placeholder="이름을 입력하세요." style="width: 200px;">
                                </td>
                            </tr>
                            <tr>
                                <th>권한</th>
                                <td>
                					<select name="iam_grade" class="select_brand">
<?php
$arrMemGradeKey = array_keys($arrMemGrade);
$arrMemGradeVal = array_values($arrMemGrade);

for($ii=0;$ii<count($arrMemGrade);$ii++) {
?>
<option value="<?=$arrMemGradeKey[$ii]?>" <?=$row['iam_grade']==$arrMemGradeKey[$ii]?"selected":""?>><?=$arrMemGradeVal[$ii]?></option>
<?php    
}
?>                					
                					</select>
                                </td>
                            </tr>
                            <tr>
                                <th>관리 원가 노출</th>
                                <td>
                					<select name="iam_fg_cost" class="select_brand">
										<option value="0" <?=$row['iam_fg_cost']=="0"?"selected":""?>>비노출</option>
										<option value="1" <?=$row['iam_fg_cost']=="1"?"selected":""?>>노출</option>
                					</select>
                                </td>
                            </tr>
                            <tr>
                                <th>외부 접속</th>
                                <td>
                					<select name="iam_fg_outside" class="select_brand">
										<option value="0" <?=$row['iam_fg_outside']=="0"?"selected":""?>>불가</option>
										<option value="1" <?=$row['iam_fg_outside']=="1"?"selected":""?>>가능</option>
                					</select>
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
						<a href="#" name="btnDel">삭제</a>
					</div>
<?php
}
?>					
				</div>
				<!-- 취소/등록 버튼 END -->
			</div>
<script src="/ism/cms/js/util/ValidCheck.js"></script>	
<script type="text/javascript">
var mc_consult_submitted = false;

$(document).on("click","a[name=btnSave]",function() {
	if(mc_consult_submitted == true) { return false; }
	
	var f = document.writeForm;

	if ( VC_inValidText(f.userid, "ID") ) return false;
<?php
if ($mode=="INS") {
?>
	if ( VC_inValidText(f.passwd, "비밀번호") ) return false;
<?php
}
?>
	if ( VC_inValidText(f.iam_name, "이름") ) return false;
	
	
	//var reg_engnum = /^[A-Za-z0-9+]{4,20}$/;
	var reg_engnum = /^[A-Za-z0-9+\d$@$!%*#?&]{4,20}$/;
	
	if (f.passwd.value!="") {
    	if (!reg_engnum.test(f.passwd.value)) {
            alert("비밀번호는 숫자와 영문, 일부 특수문자($@$!%*#?&)만 가능하며, 4~20자리여야 합니다.    ");
            f.passwd.focus();
            return;
    	}
	}

	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
});

$(document).on("click","a[name=btnDel]",function() {
	if (!confirm("정말 삭제하시겠습니까?    ")) {
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