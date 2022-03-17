<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/brand/BrandMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/category/CategoryMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/basic/BasicDataMgr.php";

$menuCate = 3;
$menuNo = 4;

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");

$_imb_idx = RequestUtil::getParam("_imb_idx", "");
$_cate1_idx = RequestUtil::getParam("_cate1_idx", "");
$_cate2_idx = RequestUtil::getParam("_cate2_idx", "");
$_cate3_idx = RequestUtil::getParam("_cate3_idx", "");
$_cate4_idx = RequestUtil::getParam("_cate4_idx", "");

$_code = RequestUtil::getParam("_code", "");
$_name = RequestUtil::getParam("_name", "");
$_item_code = RequestUtil::getParam("_item_code", "");
$_item_name = RequestUtil::getParam("_item_name", "");
$_order_by = RequestUtil::getParam("_order_by", "reg_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$pg = new Page($currentPage, $pageSize);

$row_basic = BasicDataMgr::getInstance()->getByKey("LAST_STOCK_APPLY");

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

if($_cate1_idx && $_cate1_idx > 0) {
    $wq = new WhereQuery(true, true);
    $wq->addAndString2("imct_fg_del","=","0");
    $wq->addAndString("depth","=","2");
    $wq->addAndString("upper_imct_idx","=",$_cate1_idx);
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

if($_cate2_idx && $_cate2_idx > 0) {
    $wq = new WhereQuery(true, true);
    $wq->addAndString2("imct_fg_del","=","0");
    $wq->addAndString("depth","=","3");
    $wq->addAndString("upper_imct_idx","=",$_cate2_idx);
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

if($_cate3_idx && $_cate3_idx > 0) {
    $wq = new WhereQuery(true, true);
    $wq->addAndString2("imct_fg_del","=","0");
    $wq->addAndString("depth","=","4");
    $wq->addAndString("upper_imct_idx","=",$_cate3_idx);
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

$wq = new WhereQuery(true, true);
$wq->addAndString2("img_fg_del","=","0");

$wq->addAndString("imb_idx","=",$_imb_idx);
$wq->addAndString("cate1_idx","=",$_cate1_idx);
$wq->addAndString("cate2_idx","=",$_cate2_idx);
$wq->addAndString("cate3_idx","=",$_cate3_idx);
$wq->addAndString("cate4_idx","=",$_cate4_idx);

$wq->addAndLike("code",$_code);
$wq->addAndLike("item_code",$_item_code);
$wq->addAndLike("name",$_name);
$wq->addAndLike("item_name",$_item_name);

$wq->addOrderBy($_order_by, $_order_by_asc);

if ($_order_by=="cate1_name") {
    $wq->addOrderBy("cate2_name", "asc");
    $wq->addOrderBy("cate3_name", "asc");
    $wq->addOrderBy("cate4_name", "asc");
}

$wq->addOrderBy("reg_date", "desc");

$rs = GoodsMgr::getInstance()->getListPerPage2($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">
    <input type="hidden" name="_code" value="<?=$_code?>">
    <input type="hidden" name="_name" value="<?=$_name?>">
	<input type="hidden" name="_item_code" value="<?=$_item_code?>">
	<input type="hidden" name="_item_name" value="<?=$_item_name?>">
    <input type="hidden" name="_imb_idx" value="<?=$_imb_idx?>">
    <input type="hidden" name="_cate1_idx" value="<?=$_cate1_idx?>">
    <input type="hidden" name="_cate2_idx" value="<?=$_cate2_idx?>">
    <input type="hidden" name="_cate3_idx" value="<?=$_cate3_idx?>">
    <input type="hidden" name="_cate4_idx" value="<?=$_cate4_idx?>">
    
    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
</form>

            <!-- 상품검색(s) -->
            <div>
                <div style="padding-left:20px;">
                    <h3 class="icon-search">상품 검색</h3>
                    <ul class="icon_Btn">
                    	<li><a href="./goods_write.php">추가</a></li>
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
				<form name="searchForm" method="get" action="goods_list.php">
				    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    				<input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
				
                    <table class="adm-table">
                        <caption>상품 검색</caption>
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
                                <th>브랜드</th> 
                                <td>
                                    <select name="_imb_idx" class="select_brand">
                						<option value="">브랜드 선택</option>
                						<?php
                						foreach($arrBrand as $lt){
                							?>
                							<option value="<?=$lt['imb_idx']?>" <?=$_imb_idx==$lt['imb_idx']?"selected":""?>><?=$lt['name']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>
                                <th>카테고리</th>
                                <td colspan="3">
									<select name="_cate1_idx" class="sel_category" depth="1">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory1 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate1_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate2_idx" class="sel_category" depth="2" style="<?=(count($arrCategory2)>0)?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory2 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate2_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate3_idx" class="sel_category" depth="3" style="<?=(count($arrCategory3)>0)?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory3 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate3_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate4_idx" class="sel_category" depth="4" style="<?=(count($arrCategory4)>0)?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory4 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate4_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>
                            </tr>
							<tr>
                            	<th>상품코드</th>
                            	<td><input type="text" placeholder="상품코드로 검색" name="_code" value="<?=$_code?>" style="width: 100%;"></td>
                            	<th>상품명</th>
                            	<td colspan="3"><input type="text" placeholder="상품명으로 검색" name="_name" value="<?=$_name?>" style="width: 100%;"></td>
                            </tr>
                            <tr>
                            	<th>품목(옵션)코드</th>
                            	<td><input type="text" placeholder="품목(옵션)명으로 검색" name="_item_code" value="<?=$_item_code?>" style="width: 100%;"></td>
                            	<th>품목(옵션)명</th>
                            	<td colspan="3"><input type="text" placeholder="품목(옵션)명으로 검색" name="_item_name" value="<?=$_item_name?>" style="width: 100%;"></td>
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
			<!-- 상품검색(e) -->
                
			<div class="float-wrap">
				<h3 class="float-l">등록 상품 <strong><?=number_format($pg->getTotalCount())?>건</strong><?php /*				<font color="red">&nbsp;&nbsp;재고 반영일 : <?=$row_basic["data_val"]?></font> */?></h3>
				<p class="list-adding float-r">
					<a href="#none" name="_btn_sort" order_by="reg_date" order_by_asc="desc" class="<?=$_order_by=="reg_date"?"on":""?>">최신순</a>
					<a href="#none" name="_btn_sort" order_by="stock_qty" order_by_asc="desc" class="<?=$_order_by=="stock_qty" && $_order_by_asc=="desc"?"on":""?>">재고<em>▼</em></a>
					<a href="#none" name="_btn_sort" order_by="stock_qty" order_by_asc="asc" class="<?=$_order_by=="stock_qty" && $_order_by_asc=="asc"?"on":""?>">재고<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="code" order_by_asc="asc" class="<?=$_order_by=="code" && $_order_by_asc=="asc"?"on":""?>">상품코드<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="code" order_by_asc="desc" class="<?=$_order_by=="code" && $_order_by_asc=="desc"?"on":""?>">상품코드<em>▼</em></a>
					<a href="#none" name="_btn_sort" order_by="item_code" order_by_asc="asc" class="<?=$_order_by=="item_code" && $_order_by_asc=="asc"?"on":""?>">품목(옵션)코드<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="item_code" order_by_asc="desc" class="<?=$_order_by=="item_code" && $_order_by_asc=="desc"?"on":""?>">품목(옵션)코드<em>▼</em></a>
					<a href="#none" name="_btn_sort" order_by="name" order_by_asc="asc" class="<?=$_order_by=="name" && $_order_by_asc=="asc"?"on":""?>">상품명<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="name" order_by_asc="desc" class="<?=$_order_by=="name" && $_order_by_asc=="desc"?"on":""?>">상품명<em>▼</em></a>
					<a href="#none" name="_btn_sort" order_by="item_name" order_by_asc="asc" class="<?=$_order_by=="item_name" && $_order_by_asc=="asc"?"on":""?>">품목(옵션)명<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="item_name" order_by_asc="desc" class="<?=$_order_by=="item_name" && $_order_by_asc=="desc"?"on":""?>">품목(옵션)명<em>▼</em></a>
					<a href="#none" name="_btn_sort" order_by="brand_name" order_by_asc="asc" class="<?=$_order_by=="brand_name"?"on":""?>">브랜드순<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="cate1_name" order_by_asc="asc" class="<?=$_order_by=="cate1_name"?"on":""?>">카테고리<em>▲</em></a>
				</p>
			</div>
           
            <!-- 메인TABLE(s) -->
            <table class="display" cellpadding="0" cellspacing="0">
          		<colgroup>
                    <col >
                    <col >
                    <col >
                    <col >
                    <col >
                    <col >
                    <col >
                    <col >
                    <col >
                    <col >
                    <col >
                    <col >
                    <col >
                    <col style="width:100px;">
                </colgroup>
                <thead>
                    <tr>
                        <th class="tbl_first">No</th>
                        <th>상품코드</th>
                        <th>상품명</th>
                        <th>품목(옵션)코드</th>
                        <th>품목(옵션)명</th>
                        <th>브랜드</th>
                        <th>카테고리1</th>
                        <th>카테고리2</th>
                        <th>카테고리3</th>
                        <th>카테고리4</th>
                        <th>재고</th>
                        <th>재고반영일</th>
                        <th>등록일</th>
                        <th>작업</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 2px solid #395467">
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
?>
                    <tr>
                        <td class="tbl_first" style="text-align:center;"><?=number_format($pg->getMaxNumOfPage()-$i)?></td>
                        <td><?=$row["code"]?></td>
                        <td><a href="./goods_write.php?mode=UPD&item_code=<?=$row["item_code"]?>"><?=$row["name"]?></a></td>
                        <td><?=$row["item_code"]?></td>
                        <td><a href="./goods_write.php?mode=UPD&item_code=<?=$row["item_code"]?>"><?=$row["item_name"]?></a></td>
                        <td class="txt_c"><?=$row["brand_name"]?></td>
                        <td class="txt_c"><?=$row["cate1_name"]?></td>
                        <td class="txt_c"><?=$row["cate2_name"]?></td>
                        <td class="txt_c"><?=$row["cate3_name"]?></td>
                        <td class="txt_c"><?=$row["cate4_name"]?></td>
                        <td class="txt_r" name="td_stock_qty"><?=number_format($row["stock_qty"])?></td>
                        <td style="text-align:center;" name="td_stock_apply_date"><?=$row["stock_apply_date"]?></td>
                        <td style="text-align:center;"><?=substr($row["reg_date"],0,10)?></td>
                        <td style="text-align:center;"><a href="#" name="btnApplyStock" item_code="<?=$row["item_code"]?>" style=" display: block; background-color: #1b80c3; padding: 6px 12px; border-radius: 20px; color: #fff;">재고반영</a></td>
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="13" style="text-align:center;">No Data.</td></tr>
<?php
}
?>                

                </tbody>
            </table>
            
            <!-- 메인TABLE(e) -->
			<p class="hide"><strong>Pagination</strong></p>
			<div style="position: relative;">
    			<?=$pg->getNaviForFuncGP("goPage", "<<", "<", ">", ">>")?>
    			<div style="position: absolute; right: 17px; bottom: 3px; text-align: center; line-height: 30px; border-radius: 10px; background-color: #313A3D;" class="rig_new"><a href="./goods_write.php" style="display:inline-block;padding: 5px 22px;color: #fff;">등록하기</a></div>
    		</div>

<script type="text/javascript">

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

$(document).on('click','a[name=btnExcelDownload]', function() {
	var f = document.pageForm;
	f.target = "_new";
	f.action = "goods_list_xls.php";
	
	f.submit();
});

var goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "goods_list.php";
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
	f.action = "goods_list.php";
	f.submit();
}

$(document).on("click","a[name=btnSearch]",function() {
	
	var f = document.searchForm;

    f.submit();	
});

$(document).on("click","a[name=btnApplyStock]",function() {
	
	var obj = $(this);
	var item_code = obj.attr('item_code');
	
	obj_stock_qty = obj.parent().parent().find('td[name=td_stock_qty]');
	obj_stock_apply_date = obj.parent().parent().find('td[name=td_stock_apply_date]');
	
	$.ajax({
		url: '../api/api_stock_apply_ajax.php',
		type: 'POST',
		dataType: "json",
		async: true,
		cache: false,
		data: {
			mode : 'stock_apply',
			item_code : item_code
		},
		success: function (response) {
			switch(response.RESULTCD){
                case "success" :
					obj_stock_qty.html(response.stock_qty.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));	
					obj_stock_apply_date.html(response.stock_apply_date);
                    break;
                case "not_login" :
                    alert("로그인 후 작업하시기 바랍니다.    ");
                    break;                    
                case "no_item_code" :
                    alert("옵션코드 에러입니다.    ");
                    break;                    
                case "no_data" :
                    alert("해당 제품코드의 재고를 찾을 수 없습니다.    ");
                    break;                    
                case "error" :
                    alert("시스템 연동시 에러가 발생하였습니다.    ");
                    break;                    
                default:
                	alert("시스템 오류입니다.\r\n문의주시기 바랍니다.    ");
                    break;
            }
		},
		complete:function(){},
		error: function(xhr){}
	});
	
	return false;
	
});

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";

@ $rs->free();
?>