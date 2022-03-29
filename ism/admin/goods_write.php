<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/brand/BrandMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/category/CategoryMgr.php";

$menuCate = 3;
$menuNo = 4;

$mode = RequestUtil::getParam("mode", "INS");
$code = RequestUtil::getParam("code", "");

$arrBrand = $arrCategory1 = $arrCategory2 = $arrCategory3 = $arrCategory4 = array();

$wq = new WhereQuery(true, true);
$wq->addAndString2("imb_fg_del","=","0");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = BrandMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_brand = $rs->fetch_assoc();
        
        array_push($arrBrand, $row_brand);
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imct_fg_del","=","0");
$wq->addAndString("depth","=","1");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("title","asc");

$rs = CategoryMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_category = $rs->fetch_assoc();
        
        array_push($arrCategory1, $row_category);
    }
}

if ($mode=="UPD") {
    //    if(empty($userid)) {
    if(!$code) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
        exit;
    }
    
    $row = GoodsMgr::getInstance()->getByKey($code);
    
    //    if (empty($row)) {
    if (!$row) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
        exit;
    }
    
    if($row["cate1_idx"] && $row["cate1_idx"] > 0) {
        $wq = new WhereQuery(true, true);
        $wq->addAndString2("imct_fg_del","=","0");
        $wq->addAndString("depth","=","2");
        $wq->addAndString("upper_imct_idx","=",$row["cate1_idx"]);
        $wq->addOrderBy("sort","desc");
        $wq->addOrderBy("title","asc");
        
        $rs = CategoryMgr::getInstance()->getList($wq);
        
        if($rs->num_rows > 0) {
            for($i=0;$i<$rs->num_rows;$i++) {
                $row_category = $rs->fetch_assoc();
                
                array_push($arrCategory2, $row_category);
            }
        }
    }
    
    if($row["cate2_idx"] && $row["cate2_idx"] > 0) {
        $wq = new WhereQuery(true, true);
        $wq->addAndString2("imct_fg_del","=","0");
        $wq->addAndString("depth","=","3");
        $wq->addAndString("upper_imct_idx","=",$row["cate2_idx"]);
        $wq->addOrderBy("sort","desc");
        $wq->addOrderBy("title","asc");
        
        $rs = CategoryMgr::getInstance()->getList($wq);
        
        if($rs->num_rows > 0) {
            for($i=0;$i<$rs->num_rows;$i++) {
                $row_category = $rs->fetch_assoc();
                
                array_push($arrCategory3, $row_category);
            }
        }
    }
    
    if($row["cate3_idx"] && $row["cate3_idx"] > 0) {
        $wq = new WhereQuery(true, true);
        $wq->addAndString2("imct_fg_del","=","0");
        $wq->addAndString("depth","=","4");
        $wq->addAndString("upper_imct_idx","=",$row["cate3_idx"]);
        $wq->addOrderBy("sort","desc");
        $wq->addOrderBy("title","asc");
        
        $rs = CategoryMgr::getInstance()->getList($wq);
        
        if($rs->num_rows > 0) {
            for($i=0;$i<$rs->num_rows;$i++) {
                $row_category = $rs->fetch_assoc();
                
                array_push($arrCategory4, $row_category);
            }
        }
    }
} else {
    //    if(!empty($userid)) {
    if($code) {
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
	$(".select_brand").select2();

	var w = $(".select2").css('width');
	add_w = parseInt(w)+50;
	$(".select2").css('width',add_w);
});

function reset_Select2(){
	$(".select_brand").val('');
	$(".select_brand").trigger('change');
}
</script>

			<!-- 202112123 등록하기(s) -->
            <div class="gp_rig_search">
                <div style="padding-left:20px;">
                    <h3 class="wrt_icon_search">상품 등록하기</h3>
                    <!--<ul class="icon_Btn">
                        <li><a href="#">조회</a></li>  
                        <li><a href="#">추가</a></li>
                        <li><a href="#">엑셀</a></li>
                        <li><a href="#">삭제</a></li>
                        <li><a href="#">저장</a></li>
                        <li><a href="#">인쇄</a></li>
                    </ul>-->
                </div>
				<form name="writeForm" action="./goods_write_act.php" method="post">
					<input type="hidden" name="mode" value="<?=$mode?>" />
					<input type="hidden" name="auto_defense" />

                    <table class="wrt_table">
                        <caption>등록하기</caption>
                        <colgroup>
                            <col style="width:16%;"><col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <th>상품코드</th>
                                <td>
<?php
if ($mode=="UPD") {
?>
									<div style="height:30px;vertical-align:middle;padding:0 10px;margin:3px 0px;line-height:29px;"><?=$row['code']?><input type="hidden" value="<?=$row['code']?>" name="code" /></div>
<?php
} else {
?>    									
                                    <input type="text" name="code" value="" placeholder="상품코드를 입력하세요." style="width: 200px;">
<?php
}
?>
                                </td>
                            </tr>
                            <tr>
                                <th>상품명</th>
                                <td>
                                    <input type="text" name="name" value="<?=$row['name']?>" placeholder="상품명을 입력하세요." style="width: 80%;">
                                </td>
                            </tr>
                            <tr>
                                <th>브랜드</th>
                                <td>
                					<select name="imb_idx" class="select_brand">
                						<option value="">브랜드 선택</option>
                						<?php
                						foreach($arrBrand as $lt){
                							?>
                							<option value="<?=$lt['imb_idx']?>" <?=$row['imb_idx']==$lt['imb_idx']?"selected":""?>><?=$lt['name']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>
                            </tr>
							<tr>
                                <th>카테고리</th>
                                <td>
                					<select name="cate1_idx" class="sel_category" depth="1">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory1 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$row['cate1_idx']==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="cate2_idx" class="sel_category" depth="2" style="<?=$row['cate1_idx']>0 && $arrCategory2?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory2 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$row['cate2_idx']==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="cate3_idx" class="sel_category" depth="3" style="<?=$row['cate2_idx']>0 && $arrCategory3?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory3 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$row['cate3_idx']==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="cate4_idx" class="sel_category" depth="4" style="<?=$row['cate3_idx']>0 && $arrCategory4?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory4 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$row['cate4_idx']==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>
                            </tr>
<?php /*                            
                            <tr>
                                <th>기준일자</th>
                                <td>
                                    <input type="date" id="nodate" class="date_in" style="padding:0 24px;"><label for="nodate"></label>
                                </td>
                            </tr>
                            <tr>
                                <th>선택사항</th>
                                <td class="wrt_radio_box">
                                    <input type="radio" id="same" name="q1" checked>
                                    <label for="same" class="sameR">신규</label>
                                    <input type="radio" id="new" name="q1">
                                    <label for="new" class="newR" style="margin-right:26px;">기존</label>
                                    <input type="radio" id="not" name="q1">
                                    <label for="not" class="newR">미등록</label>
                                </td>
                            </tr>
                            <tr>
                                <th>공개여부</th>
                                <td class="wrt_checks">
                                    <input type="checkbox" id="display_on"><label for="display_on">공개</label>
                                    <input type="checkbox" id="display_off"><label for="display_off">미공개</label>
                                </td>
                            </tr>
*/?>
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
//	if(mc_consult_submitted == true) { return false; }
	
	var f = document.writeForm;

	if ( VC_inValidText(f.code, "상품코드") ) return false;
	if ( VC_inValidText(f.name, "상품명") ) return false;
	
	if ( VC_isUnselect(f.imb_idx, "브랜드") ) return false;
	if ( VC_isUnselect(f.cate1_idx, "카테고리") ) return false;

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

$(document).on('change','.sel_category',function() {
	var obj_select, obj_select_other;
	var next_depth = parseInt($(this).attr('depth'))+1;
	var i;

	if($("option:selected", this).val()!=="") {
	
    	obj_select = $('.sel_category[depth='+next_depth+']');
    	
    	for(i=next_depth+1;i<=4;i++) {
    		$('.sel_category[depth='+i+']').css("display","none");
    		$('.sel_category[depth='+i+'] option:eq(0)').prop("selected",true);
    	}

    	$.ajax({
    		url: "/ism/ajax/ajax_category.php",
    		data: {upper_imct_idx: $("option:selected", this).val()},
    		async: true,
    		cache: false,
    		error: function(xhr){	},
    		success: function(data){
    		
    			if(data.length > 10) {
        			obj_select.html(data);
        			
        			obj_select.css("display","inline-block");
    			} else {
    				obj_select.css("display","none");
    			}
    		}
    	});
	} else {
    	for(i=next_depth;i<=4;i++) {
    		$('.sel_category[depth='+i+']').css("display","none");
    		$('.sel_category[depth='+i+'] option:eq(0)').prop("selected",true);
    	}
	}
});
</script>	

<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";
?>