<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/channel/ChannelMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/sales_type/SalesTypeMgr.php";

$menuCate = 4;
$menuNo = 8;

$mode = RequestUtil::getParam("mode", "INS");
$imc_idx = RequestUtil::getParam("imc_idx", "");

if (LoginManager::getUserLoginInfo("iam_grade") < 9) {
    JsUtil::alertBack("작업 권한이 없습니다.    ");
    exit;
}

$arrSalesType = array();

$wq = new WhereQuery(true, true);
$wq->addAndString("imst_idx","<>","1");
$rs = SalesTypeMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_sales_type = $rs->fetch_assoc();
        
        $arrSalesType[$row_sales_type["imst_idx"]] = $row_sales_type["title"];
    }
}

if ($mode=="UPD") {
    //    if(empty($userid)) {
    if(!$imc_idx) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
        exit;
    }
    
    $row = ChannelMgr::getInstance()->getByKey($imc_idx);
    
    //    if (empty($row)) {
    if (!$row) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
        exit;
    }
} else {
    //    if(!empty($userid)) {
    if($imc_idx) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x04)   ");
        exit;
    }
}

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".sel_item").select2();

	var w = $(".select2").css('width');
	add_w = parseInt(w)+50;
	$(".select2").css('width',add_w);
});

function reset_Select2(){
	$(".sel_item").val('');
	$(".sel_item").trigger('change');
}

</script>

			<!-- 202112123 등록하기(s) -->
            <div class="gp_rig_search">
                <div style="padding-left:20px;">
                    <h3 class="wrt_icon_search">도매 거래처 등록</h3>
                </div>
				<form name="writeForm" action="./wholesale_customer_write_act.php" method="post">
					<input type="hidden" name="mode" value="<?=$mode?>" />
					<input type="hidden" name="imc_idx" value="<?=$imc_idx?>" />
					<input type="hidden" name="auto_defense" />

                    <table class="wrt_table">
                        <caption>등록하기</caption>
                        <colgroup>
                            <col style="width:16%;"><col>
                        </colgroup>
                        <tbody>
							<tr>
                            	<th>판매유형</th>
                            	<td colspan="3">
									<select name="imst_idx" class="sel_order_type">
<?php                                     	
foreach($arrSalesType as $key => $value) {
?>
                                    	<option value="<?=$key?>" <?=$row["imst_idx"]==$key?"selected":""?>><?=$value?></option>
<?php
}
?>
                                    </select>
                                </td>                           
							</tr>                            
                            <tr>
                                <th>거래처명</th>
                                <td>
                                    <input type="text" name="name" value="<?=$row['name']?>" placeholder="거래처명을 입력하세요." style="width: 200px;">
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

	if ( VC_inValidText(f.name, "거래처명") ) return false;

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

@ $rs->free();
?>