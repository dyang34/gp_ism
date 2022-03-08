<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/ism");
}

if (!LoginManager::getUserLoginInfo("iam_grade")) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/ism");
}
?>

<body style="font-family: 'Noto Sans KR', sans-serif; line-height:1; font-size:14px;">
        <div id="wrap" class="main_skin">
            <!--header(s)-->
			<div id="header">
				<div class="gp_wms">
					<div class="left">
						<span>통합 매출 관리 시스템(ISM)</span>
					</div>
					<div class="right">
						<ul>
							<li><a href="/admin_logout.php">Logout</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!--header(e)-->
			        
        <div id="container">
<?php 
    include $_SERVER['DOCUMENT_ROOT']."/ism/include/left_menu.php";
?>
            <div id="content">
            	<div class="inner">
