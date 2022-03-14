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
										<input type="button" value="대분류 추가" style="border-radius: 5px;">
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
													<input type="button" value="항목추가" mode="INS" name="btnIns" imct_idx="<?=$arrCategory[$i]["imct_idx"]?>" depth="<?=$arrCategory[$i]["depth"]?>" title="" sort="<?=$arrCategory[$i]["sort"]?>" upper_imct_idx="<?=$arrCategory[$i]["upper_imct_idx"]?>" >
<?php
    }
?>
    												<input type="button" value="수정" mode="UPD" name="btnUpd" imct_idx="<?=$arrCategory[$i]["imct_idx"]?>" depth="<?=$arrCategory[$i]["depth"]?>" title="" sort="<?=$arrCategory[$i]["sort"]?>" upper_imct_idx="<?=$arrCategory[$i]["upper_imct_idx"]?>" >
    												<input type="button" value="위로" mode="UP" name="btnUp" imct_idx="<?=$arrCategory[$i]["imct_idx"]?>" depth="<?=$arrCategory[$i]["depth"]?>" title="" sort="<?=$arrCategory[$i]["sort"]?>" upper_imct_idx="<?=$arrCategory[$i]["upper_imct_idx"]?>" >
    												<input type="button" value="아래료" mode="DOWN" name="btnDown" imct_idx="<?=$arrCategory[$i]["imct_idx"]?>" depth="<?=$arrCategory[$i]["depth"]?>" title="" sort="<?=$arrCategory[$i]["sort"]?>" upper_imct_idx="<?=$arrCategory[$i]["upper_imct_idx"]?>" >
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
								<form>
									<table class="adm-table">
										<caption>카테고리 수정</caption>
										<colgroup>
											<col style="width:140px;">
										</colgroup>
										<tbody>
											<tr>
												<th>상위 카테고리명</th>
												<td></td>
											</tr>
											<tr>
												<th>Depth</th>
												<td></td>
											</tr>
											<tr>
												<th>카테고리명</th>
												<td><input type="text" class="width-xl" name="title" value="생활" style="line-height: 31px;"></td>
											</tr>
											<tr>
												<th>카테고리 삭제</th>
												<td>
													<button type="button" class="btn-alert btn-sm">삭제</button>
												</td>
											</tr>
										</tbody>
									</table>
								</form>
								<p style="margin-top: 10px; text-align: center;"><input type="button" value="변경사항 적용하기" class="btn-l btn-ok" style="height: 40px; font-size: 14px; border-radius: 5px;"></p>
							</div>
							<!-- 카테고리 설정(e) -->
						</div>
						<!--카테고리(e)-->









<script type="text/javascript">

$(document).on('click','a[name=btnExcelDownload]', function() {
	var f = document.pageForm;
	f.target = "_new";
	f.action = "category_list_xls.php";
	
	f.submit();
});

$(document).on('click','input[name=btnUp]', function() {
	var obj = $(this).closest('li');
	var obj_tgt = obj.prev();
	
	if(isEmpty(obj_tgt.html())) {
		alert("최상위 카테고리입니다.\r\n\r\n카테고리 이동은 같은 상위 카테고리 내에서만 가능합니다.    ");
		return false;
	}
	
	var obj_tgt_btn = obj_tgt.find('[name=btnUp]');
	
//	alert($(this).attr('imct_idx')+" "+$(this).attr('sort'));
//	alert(obj_tgt_btn.attr('imct_idx')+" "+obj_tgt_btn.attr('sort'));

	$.ajax({
		url: '../ajax/category_sort_upd.php',
		type: 'POST',
		dataType: "json",
		async: true,
		cache: false,
		data: {
			mode : 'SORT_UP',
			src_idx : $(this).attr('imct_idx'),
			src_sort : obj_tgt_btn.attr('sort'),
			tgt_idx : obj_tgt_btn.attr('imct_idx'),
			tgt_sort : $(this).attr('sort'),
		},
		success: function (response) {
			switch(response.RESULTCD){
                case "SUCCESS" :
					obj_tgt.before(obj);                
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

$(document).on('click','input[name=btnDown]', function() {
	var obj = $(this).closest('li');
	var obj_tgt = obj.next();
	
	if(isEmpty(obj_tgt.html())) {
		alert("최상위 카테고리입니다.\r\n\r\n카테고리 이동은 같은 상위 카테고리 내에서만 가능합니다.    ");
		return false;
	}
	
	var obj_tgt_btn = obj_tgt.find('[name=btnUp]');
	
//	alert($(this).attr('imct_idx')+" "+$(this).attr('sort'));
//	alert(obj_tgt_btn.attr('imct_idx')+" "+obj_tgt_btn.attr('sort'));

	$.ajax({
		url: '../ajax/category_sort_upd.php',
		type: 'POST',
		dataType: "json",
		async: true,
		cache: false,
		data: {
			mode : 'SORT_UP',
			src_idx : $(this).attr('imct_idx'),
			src_sort : obj_tgt_btn.attr('sort'),
			tgt_idx : obj_tgt_btn.attr('imct_idx'),
			tgt_sort : $(this).attr('sort'),
		},
		success: function (response) {
			switch(response.RESULTCD){
                case "SUCCESS" :
					obj.before(obj_tgt);                
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