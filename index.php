<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/admin/AdmMemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/SystemUtil.php";

if ( $_SERVER['HTTP_HOST'] == "ganglife2018.cafe24.com" ) {
    JsUtil::Replace("http://gp-ism.com");
}
      
$rtnUrl = RequestUtil::getParam("rtnUrl", "");

$ism_adm_ck_auto = CookieUtil::getCookieMd5("ism_adm_ck_auto");
$ism_adm_ck_userid = CookieUtil::getCookieMd5("ism_adm_ck_userid");

if(!$ism_adm_ck_auto) $ism_adm_ck_auto = "";

if (LoginManager::isUserLogined() && !empty(LoginManager::getUserLoginInfo("iam_grade"))) {
    
    $wq = new WhereQuery(true, true);
    $wq->addAndString("userid", "=", LoginManager::getUserLoginInfo("userid"));
    $wq->addAndString("iam_fg_del", "=", "0");
    
    $row = AdmMemberMgr::getInstance()->getFirst($wq);
    
    if ( empty($row) ) {
        JsUtil::replace("./admin_logout.php");
        exit;
    } else {
        
        if (empty($row["iam_last_login"]) || $row["iam_last_login"] < date("Y-m-d h:i:s",strtotime ("-30 minutes"))) {
            $uq = new UpdateQuery();
            $uq->addNotQuot("iam_last_login", "now()");
            AdmMemberMgr::getInstance()->edit($uq, LoginManager::getUserLoginInfo("userid"));
        }
    }
    
    if (!empty($rtnUrl)) {
        JsUtil::replace($rtnUrl);
        exit;
    } else {
        $rtnUrl = "/branch.php";
        JsUtil::replace($rtnUrl);
        exit;
    }
}

if(!empty($rtnUrl)) {
    $rtnUrl = urldecode($rtnUrl);
}

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";

if (!SystemUtil::isLocalhost() && 1==2) {
?>
<script>
if(window.location.protocol == "http:"){
	window.location.protocol = "https:";
}
</script>
<?php
}
?>

	<body class="login_wrap">
		<div class="wrapper fadeInDown">
			<div id="formContent">
				<h2 class="active">통합 매출 관리 시스템(ISM)</h2>
				<form name="writeForm" class="custom-form" method="post" autocomplete="off">
                	<input type="hidden" name="auto_defense" />
                	<input type="hidden" name="mode" value="login" />
                
                	<input type="text" name="userid" id="userid" class="fadeIn second" placeholder="login" style="margin:6px;" />
					<input type="password" name="passwd"  id="passwd" class="fadeIn third" style="margin: 3px 0;" />
					<div class="bit_checks fadeIn third">
                        <input type="checkbox" id="nologin" name="ck_auto" value="1"><label for="nologin">자동 로그인</label>
                    </div>
					<input type="button" class="fadeIn fourth" value="LogIn" onClick="javascript:login_submit();return false;">
				</form>
				<div id="formFooter">
					<a class="underlineHover">Copyright ⓒ 2022 GP Club. All rights reserved.</a>
				</div>
			</div>
		</div>

<?php
if ($ism_adm_ck_auto=="ism_adm_auto_login" && !empty($ism_adm_ck_userid)) {
?>

<form name="autoLoginForm" method="post" action="./admin_login_act.php">
	<input type="hidden" name="mode" value="autologin" />
	<input type="hidden" name="auto_defense" value="identicharmc!@" />
    <input type="hidden" name="rtnUrl" value="<?=urlencode($rtnUrl)?>" />
    <input type="hidden" name="userid" value="<?=$ism_adm_ck_userid?>" />
</form>

<script type="text/javascript">
document.autoLoginForm.submit();
</script>

<?php 
}
?>       
        
<script src="/ism/cms/js/util/ValidCheck.js"></script>
<?php /*
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
*/?>
<script language="javascript">
//<![CDATA[

$(document).on('keypress','#userid, #passwd',function(e) {
	if (e.keyCode === 13) {
		login_submit();
		return false;
	}
});

function login_submit(){
	var f = document.writeForm;

    if ( VC_inValidText(f.userid, "아이디") ) return false;
    if ( f.userid.value == "아이디" ) {
    	alert("아이디를 입력해 주십시오.");
    	f.userid.focus();
		return false;
    }
    if ( VC_inValidText(f.passwd, "패스워드") ) return false;

<?php /*
    //f.action = "<?=SystemUtil::toSsl("http://".$_SERVER[SERVER_NAME]."/mcm/member/mb_login_act.php")?>";
*/?>

	f.auto_defense.value = "identicharmc!@";
	
    f.action = "./admin_login_act.php";
    f.submit();
}	

//]]>
</script>

    </body>
<?php 
    include $_SERVER['DOCUMENT_ROOT']."/ism/admin/include/footer.php";
?>