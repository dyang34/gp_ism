<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/category/CategoryMgr.php";

$menuCate = 3;
$menuNo = 6;

$wq = new WhereQuery(true, true);
$wq->addAndString2("imct_fg_del","=","0");

$_order_by = "cate_no";
$wq->addOrderBy($_order_by, "asc");

$rs = CategoryMgr::getInstance()->getList($wq, $pg);

$arrCategory = array();
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        array_push($arrCategory, $row);
    }
}

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
	<form name="pageForm" method="get"></form>
	         
						<!-- 제품검색(s) -->
						<div style="padding-left:20px;">
							<h3 class="wrt_icon_search">카테고리 관리</h3>
                            <ul class="icon_Btn">
                                <li><a href="#" name="btnExcelDownload">엑셀</a></li>
                            </ul>
						</div>
						<!-- 제품검색(e) -->
						
						<table class="wrt_table" style="margin-bottom: 50px;">
							<caption></caption>
							<colgroup>
								<col style="width:16%;"><col>
							</colgroup>
							<tbody>
								<tr></tr>
							</tbody>
						</table>
						
						<!--카테고리(s)-->
						<div class="ism-float-wrap float-wrap">
							<!-- 카테고리 관리(s) -->
							<div class="float-l cate_list" style="width:38%;">

								<div class="float-wrap" style="padding: 0;">
									<h3 class="icon-cate float-l">카테고리 관리</h3>
									<p class="float-r">
										<input type="button" name="btnTopIns" value="대분류 추가" style="border-radius: 5px;">
									</p>
								</div>
								<div class="adm-category-box">
									<ul class="ism_menu">
<?php

$cnt_ul = 0;

for($i=0;$i<count($arrCategory);$i++) {
?>
										<li class="list 
<?php
//if ($i > 0 && ($arrCategory[$i-1]["depth"] < $arrCategory[$i]["depth"])) {
if ($arrCategory[$i]["depth"]=="2") {
    echo " sub_list ";
}
/*
if ($i < count($arrCategory)-1 && ($arrCategory[$i+1]["depth"] > $arrCategory[$i]["depth"])) {
    echo " depth_list ";
} else {
    echo " depth_last_list ";
}
*/
?>
 " style="border-top: 0;">
											<input type="checkbox" name="cate<?=$arrCategory[$i]["imct_idx"]?>" id="cate<?=$arrCategory[$i]["imct_idx"]?>"/>   
											<label for="cate<?=$arrCategory[$i]["imct_idx"]?>">
												<span class="<?=($i < count($arrCategory)-1 && ($arrCategory[$i+1]["depth"] > $arrCategory[$i]["depth"]))?"folder":"folder folder_close"?>"></span> <?=$arrCategory[$i]["title"]?>
												<span class="btn_wrap">												
<?php
    if ($arrCategory[$i]["depth"] < 4) {
?>												
    												<a class="btn_inner" href="#" mode="INS" name="btnSubIns" imct_idx="<?=$arrCategory[$i]["imct_idx"]?>" depth="<?=$arrCategory[$i]["depth"]?>" title="" sort="<?=$arrCategory[$i]["sort"]?>" upper_imct_idx="<?=$arrCategory[$i]["upper_imct_idx"]?>" upper_title="<?=$arrCategory[$i]["upper_title"]?>" cate_title="<?=$arrCategory[$i]["title"]?>"><img src="/ism/images/common/add_item.gif" /></a>
<?php
    }
?>
                                                    <a class="btn_inner" href="#" mode="UPD" name="btnUpd" imct_idx="<?=$arrCategory[$i]["imct_idx"]?>" depth="<?=$arrCategory[$i]["depth"]?>" title="" sort="<?=$arrCategory[$i]["sort"]?>" upper_imct_idx="<?=$arrCategory[$i]["upper_imct_idx"]?>" upper_title="<?=$arrCategory[$i]["upper_title"]?>" cate_title="<?=$arrCategory[$i]["title"]?>"><img src="/ism/images/common/edit.png" /></a>
                                                    <a class="btn_inner" href="#" mode="UP" name="btnUp" imct_idx="<?=$arrCategory[$i]["imct_idx"]?>" depth="<?=$arrCategory[$i]["depth"]?>" title="" sort="<?=$arrCategory[$i]["sort"]?>"><img src="/ism/images/common/up.png" /></a>
                                                    <a class="btn_inner" href="#" mode="DOWN" name="btnDown" imct_idx="<?=$arrCategory[$i]["imct_idx"]?>" depth="<?=$arrCategory[$i]["depth"]?>" title="" sort="<?=$arrCategory[$i]["sort"]?>"><img src="/ism/images/common/down.png" /></a>
												</span>
<?php
/*
 if ($i < (count($arrCategory)-1) && ($arrCategory[$i+1]["depth"] > $arrCategory[$i]["depth"])) {
 ?>
 <span class="arw"></span>
 <?php
 }
 */
?>												
											</label>
<?php
    if ($i < (count($arrCategory)-1) && ($arrCategory[$i+1]["depth"] > $arrCategory[$i]["depth"])) {
        $cnt_ul++;
?>
											<ul class="options items ism_dep<?=$arrCategory[$i]["depth"]?>">
<?php
    } else if ($i < (count($arrCategory)-1) && ($arrCategory[$i+1]["depth"] < $arrCategory[$i]["depth"])) {
?>
    	</li>
<?php
        for($i_ul=$arrCategory[$i+1]["depth"];$i_ul<$arrCategory[$i]["depth"];$i_ul++) {
            $cnt_ul--;
?>
											</ul>
										</li>
<?php
        }
    } else if ($arrCategory[$i+1]["depth"] == $arrCategory[$i]["depth"]) {
?>    
    	</li>
<?php
    }
}

for($i_ul=0;$i_ul<$cnt_ul;$i_ul++) {
?>
											</ul>
										</li>
<?php 
}
?>									
									</ul>
								</div>
<?php /*								
								<p class="align-r" style="font-size: 12px; margin-top: 10px;">선택한 카테고리 이동
									<button type="button" class="btn-icon" style="margin-left: 4px;">▲</button>
									<button type="button" class="btn-icon">▼</button>
								</p>
*/?>
							</div>
							<!-- 카테고리 관리(e) -->
							
							<!-- 카테고리 설정(s) -->
							<div class="float-r cate_view" style="width:60%">
								<h3 class="icon-pen">카테고리 상세설정</h3>
                				<form name="writeForm" action="./category_write_act.php" method="post">
                					<input type="hidden" name="mode" />
                					<input type="hidden" name="auto_defense" />
                					<input type="hidden" name="imct_idx" />
                					<input type="hidden" name="upper_imct_idx" />

									<table class="adm-table">
										<caption>카테고리 수정</caption>
										<colgroup>
											<col style="width:140px;">
											<col />
										</colgroup>
										<tbody>
											<tr>
												<th>상위 카테고리명</th>
												<td><span name="spanUpperTitle"></span></td>
											</tr>
											<tr>
												<th>Depth</th>
												<td><span name="spanDepth"></span></td>
											</tr>
											<tr>
												<th>카테고리명</th>
												<td><input type="text" class="width-xl" name="title" value="" style="line-height: 31px;"></td>
											</tr>
											<tr>
												<th>카테고리 삭제</th>
												<td>
													<button type="button" name="btnDel" class="btn-alert btn-sm" style="display:none;">삭제</button>
												</td>
											</tr>
										</tbody>
									</table>
								</form>
								<p style="margin-top: 10px; text-align: center;"><input type="button" name="btnSave" value="저장" class="btn-l btn-ok" style="height: 40px; font-size: 14px; border-radius: 5px; display:none;"></p>
							</div>
							<!-- 카테고리 설정(e) -->
						</div>
						<!--카테고리(e)-->

<script src="/ism/cms/js/util/ValidCheck.js"></script>	
<script type="text/javascript">
var mc_consult_submitted = false;

$(document).on('click','a[name=btnExcelDownload]', function() {
	var f = document.pageForm;
	f.target = "_new";
	f.action = "category_list_xls.php";
	
	f.submit();
});

$(document).on('click','input[name=btnTopIns]', function() {
	$('input[name=mode]').val('TOPINS');
	
	$('input[name=imct_idx]').val('0');
	$('input[name=upper_imct_idx]').val('0');
	$('span[name=spanUpperTitle]').html("<span style='color:blue'>[최상위]</span>");
	$('span[name=spanDepth]').html("1");
	$('input[name=title]').val("");
	
	$('button[name=btnDel]').hide();
	$('input[name=btnSave]').show();
});

$(document).on('click','a[name=btnSubIns]', function() {
	$('input[name=mode]').val('SUBINS');	
	$('input[name=imct_idx]').val('0');
	$('input[name=upper_imct_idx]').val($(this).attr('imct_idx'));
	$('span[name=spanUpperTitle]').html($(this).attr('cate_title'));
	$('span[name=spanDepth]').html(parseInt($(this).attr('depth'))+1);
	$('input[name=title]').val('');
	
	$('button[name=btnDel]').hide();
	$('input[name=btnSave]').show();
});

$(document).on('click','a[name=btnUpd]', function() {
	$('input[name=mode]').val('UPD');	
	$('input[name=upper_imct_idx]').val($(this).attr('upper_imct_idx'));
	$('input[name=imct_idx]').val($(this).attr('imct_idx'));
	if($(this).attr('upper_imct_idx')==0) {
		$('span[name=spanUpperTitle]').html("<span style='color:blue'>[최상위]</span>");
	} else {
		$('span[name=spanUpperTitle]').html($(this).attr('upper_title'));
	}
	$('span[name=spanDepth]').html($(this).attr('depth'));
	$('input[name=title]').val($(this).attr('cate_title'));
	
	$('button[name=btnDel]').show();
	$('input[name=btnSave]').show();
});

$(document).on('click','button[name=btnDel]', function() {

	if (!confirm("정말 삭제하시겠습니까?    ")) {
		return false;
	}

	$('input[name=mode]').val('DEL');
	
	if(mc_consult_submitted == true) { return false; }
	
	var f = document.writeForm;

	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
	
});

$(document).on('click','input[name=btnSave]', function() {

	if(mc_consult_submitted == true) { return false; }
	
	var f = document.writeForm;

	if ( VC_inValidText(f.title, "카테고리명") ) return false;

	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;

});

$(document).on('click','a[name=btnUp]', function() {

	var obj = $(this).closest('li');
	var obj_tgt = obj.prev();

	var obj_src_btn = $(this);
	var obj_tgt_btn = obj_tgt.find('a[name=btnUp]');
	
	if(isEmpty(obj_tgt.html())) {
		alert("최상위 카테고리입니다.\r\n\r\n카테고리 이동은 같은 상위 카테고리 내에서만 가능합니다.    ");
		return false;
	}
	
	var src_sort = obj_src_btn.attr('sort');
	var tgt_sort = obj_tgt_btn.attr('sort');
	
	$.ajax({
		url: '../ajax/category_sort_upd.php',
		type: 'POST',
		dataType: "json",
		async: true,
		cache: false,
		data: {
			mode : 'SORT_UP',
			src_idx : obj_src_btn.attr('imct_idx'),
			src_sort : tgt_sort,
			tgt_idx : obj_tgt_btn.attr('imct_idx'),
			tgt_sort : src_sort,
		},
		success: function (response) {
			switch(response.RESULTCD){
                case "SUCCESS" :
					obj_tgt.before(obj);        
					obj.find('a[name=btnUp]').attr('sort', tgt_sort);
					obj.find('a[name=btnDown]').attr('sort', tgt_sort);
					obj_tgt.find('a[name=btnUp]').attr('sort', src_sort);
					obj_tgt.find('a[name=btnDown]').attr('sort', src_sort);
                    break;
                case "not_login" :
                    alert("로그인 후 작업하시기 바랍니다.    ");
                    break;                    
                case "no_idx" :
                    alert("Index 에러입니다.    ");
                    break;                    
                case "no_sort" :
                    alert("Sort 에러입니다.    ");
                    break;                    
                case "not_mode" :
                    alert("모드 에러입니다.    ");
                    break;                    
                case "no_data" :
                    alert("해당 카테고리의 정렬을 찾을 수 없습니다.    ");
                    break;                    
                default:
                	alert("시스템 오류입니다.\r\n문의주시기 바랍니다.\r\n\r\n"+response.RESULTCD);
                    break;
            }
		},
		complete:function(){
			;
		},
		error: function(request,status,error){
			alert("code:"+request.status+"\n"+"error:"+error+"\n"+"status:"+status+"\n"+"curr_idx:"+curr_idx);	// +"message:"+request.responseText+"\n"
		}
	});
});

$(document).on('click','a[name=btnDown]', function() {
	var obj = $(this).closest('li');
	var obj_tgt = obj.next();

	var obj_src_btn = $(this);
	var obj_tgt_btn = obj_tgt.find('a[name=btnUp]');
	
	if(isEmpty(obj_tgt.html())) {
		alert("최하위 카테고리입니다.\r\n\r\n카테고리 이동은 같은 상위 카테고리 내에서만 가능합니다.    ");
		return false;
	}
	
	var src_sort = obj_src_btn.attr('sort');
	var tgt_sort = obj_tgt_btn.attr('sort');

	$.ajax({
		url: '../ajax/category_sort_upd.php',
		type: 'POST',
		dataType: "json",
		async: true,
		cache: false,
		data: {
			mode : 'SORT_UP',
			src_idx : obj_src_btn.attr('imct_idx'),
			src_sort : tgt_sort,
			tgt_idx : obj_tgt_btn.attr('imct_idx'),
			tgt_sort : src_sort,
		},
		success: function (response) {
			switch(response.RESULTCD){
                case "SUCCESS" :
					obj.before(obj_tgt);
					obj.find('a[name=btnUp]').attr('sort', tgt_sort);
					obj.find('a[name=btnDown]').attr('sort', tgt_sort);
					obj_tgt.find('a[name=btnUp]').attr('sort', src_sort);
					obj_tgt.find('a[name=btnDown]').attr('sort', src_sort);
                    break;
                case "not_login" :
                    alert("로그인 후 작업하시기 바랍니다.    ");
                    break;                    
                case "no_idx" :
                    alert("Index 에러입니다.    ");
                    break;                    
                case "no_sort" :
                    alert("Sort 에러입니다.    ");
                    break;                    
                case "not_mode" :
                    alert("모드 에러입니다.    ");
                    break;                    
                case "no_data" :
                    alert("해당 카테고리의 정렬을 찾을 수 없습니다.    ");
                    break;                    
                default:
                	alert("시스템 오류입니다.\r\n문의주시기 바랍니다.\r\n\r\n"+response.RESULTCD);
                    break;
            }
		},
		complete:function(){
			;
		},
		error: function(request,status,error){
			alert("code:"+request.status+"\n"+"error:"+error+"\n"+"status:"+status+"\n"+"curr_idx:"+curr_idx);	// +"message:"+request.responseText+"\n"
		}
	});
});

var isEmpty = function(str){
//	if( value == "" || value == null || value == undefined || ( value != null && typeof value == "object" && !Object.keys(value).length ) ) {
    if(typeof str == "undefined" || str == null || str == "")
        return true;
    else
        return false ;
}

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";

@ $rs->free();
?>