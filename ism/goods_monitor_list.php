<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsItemMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/brand/BrandMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/category/CategoryMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/basic/BasicDataMgr.php";

$menuCate = 1;
$menuNo = 23;

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
$wq->addAndString2("imgi_fg_del","=","0");

$wq->addAndString("imb_idx","=",$_imb_idx);
$wq->addAndString("cate1_idx","=",$_cate1_idx);
$wq->addAndString("cate2_idx","=",$_cate2_idx);
$wq->addAndString("cate3_idx","=",$_cate3_idx);
$wq->addAndString("cate4_idx","=",$_cate4_idx);

$wq->addAndLike("a.code",$_code);
$wq->addAndLike("item_code",$_item_code);
$wq->addAndLike("name",$_name);
$wq->addAndLike("item_name",$_item_name);

$wq->addOrderBy($_order_by, $_order_by_asc);

if ($_order_by=="cate1_name") {
    $wq->addOrderBy("cate2_name", "asc");
    $wq->addOrderBy("cate3_name", "asc");
    $wq->addOrderBy("cate4_name", "asc");
}

$wq->addOrderBy("imgi_idx", "desc");

$rs = GoodsItemMgr::getInstance()->getListPerPage2($wq, $pg);

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

            <!-- ????????????(s) -->
            <div>
                <div style="padding-left:20px;">
                    <h3 class="icon-search">?????? ??????</h3>
                    <ul class="icon_Btn">
                    	<li><a href="#" name="btnExcelDownload">??????</a></li>	
<?php /*                    
                        <li><a href="#">??????</a></li>
                        <li><a href="#">??????</a></li>
                        <li><a href="#">??????</a></li>
                        <li><a href="#">??????</a></li>
                        <li><a href="#">??????</a></li>
*/?>
                    </ul>
                </div>
				<form name="searchForm" method="get" action="goods_monitor_list.php">
				    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    				<input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
				
                    <table class="adm-table">
                        <caption>?????? ??????</caption>
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
                                <th>?????????</th> 
                                <td>
                                    <select name="_imb_idx" class="select_brand">
                						<option value="">????????? ??????</option>
                						<?php
                						foreach($arrBrand as $lt){
                							?>
                							<option value="<?=$lt['imb_idx']?>" <?=$_imb_idx==$lt['imb_idx']?"selected":""?>><?=$lt['name']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>
                                <th>????????????</th>
                                <td colspan="3">
									<select name="_cate1_idx" class="sel_category" depth="1">
                						<option value="">???????????? ??????</option>
                						<?php
                						foreach($arrCategory1 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate1_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate2_idx" class="sel_category" depth="2" style="<?=(count($arrCategory2)>0)?"":"display:none;"?>">
                						<option value="">???????????? ??????</option>
                						<?php
                						foreach($arrCategory2 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate2_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate3_idx" class="sel_category" depth="3" style="<?=(count($arrCategory3)>0)?"":"display:none;"?>">
                						<option value="">???????????? ??????</option>
                						<?php
                						foreach($arrCategory3 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate3_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate4_idx" class="sel_category" depth="4" style="<?=(count($arrCategory4)>0)?"":"display:none;"?>">
                						<option value="">???????????? ??????</option>
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
                            	<th>????????????</th>
                            	<td><input type="text" placeholder="??????????????? ??????" name="_code" value="<?=$_code?>" style="width: 100%;"></td>
                            	<th>?????????</th>
                            	<td colspan="3"><input type="text" placeholder="??????????????? ??????" name="_name" value="<?=$_name?>" style="width: 100%;"></td>
                            </tr>
                            <tr>
                            	<th>??????(??????)??????</th>
                            	<td><input type="text" placeholder="??????(??????)????????? ??????" name="_item_code" value="<?=$_item_code?>" style="width: 100%;"></td>
                            	<th>??????(??????)???</th>
                            	<td colspan="3"><input type="text" placeholder="??????(??????)????????? ??????" name="_item_name" value="<?=$_item_name?>" style="width: 100%;"></td>
                            </tr>
                            
                        </tbody>
                    </table>
    				<!-- ???????????? START -->
    				<div class="wms_searchBtn">
    					<a href="#" class="ism_btnSearch" name="btnSearch">??????</a>
    				</div>
    				<!-- ???????????? END -->
				</form>
				
			</div>
			<!-- ????????????(e) -->
                
			<div class="float-wrap">
				<h3 class="float-l">?????? ?????? <strong><?=number_format($pg->getTotalCount())?>???</strong><?php /*				<font color="red">&nbsp;&nbsp;?????? ????????? : <?=$row_basic["data_val"]?></font> */?></h3>
				<p class="list-adding float-r">
					<a href="#none" name="_btn_sort" order_by="reg_date" order_by_asc="desc" class="<?=$_order_by=="reg_date"?"on":""?>">?????????</a>
					<a href="#none" name="_btn_sort" order_by="stock_qty" order_by_asc="desc" class="<?=$_order_by=="stock_qty" && $_order_by_asc=="desc"?"on":""?>">??????<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="stock_qty" order_by_asc="asc" class="<?=$_order_by=="stock_qty" && $_order_by_asc=="asc"?"on":""?>">??????<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="code" order_by_asc="asc" class="<?=$_order_by=="code" && $_order_by_asc=="asc"?"on":""?>">????????????<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="code" order_by_asc="desc" class="<?=$_order_by=="code" && $_order_by_asc=="desc"?"on":""?>">????????????<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="item_code" order_by_asc="asc" class="<?=$_order_by=="item_code" && $_order_by_asc=="asc"?"on":""?>">??????(??????)??????<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="item_code" order_by_asc="desc" class="<?=$_order_by=="item_code" && $_order_by_asc=="desc"?"on":""?>">??????(??????)??????<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="name" order_by_asc="asc" class="<?=$_order_by=="name" && $_order_by_asc=="asc"?"on":""?>">?????????<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="name" order_by_asc="desc" class="<?=$_order_by=="name" && $_order_by_asc=="desc"?"on":""?>">?????????<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="item_name" order_by_asc="asc" class="<?=$_order_by=="item_name" && $_order_by_asc=="asc"?"on":""?>">??????(??????)???<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="item_name" order_by_asc="desc" class="<?=$_order_by=="item_name" && $_order_by_asc=="desc"?"on":""?>">??????(??????)???<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="brand_name" order_by_asc="asc" class="<?=$_order_by=="brand_name"?"on":""?>">????????????<em>???</em></a>
					<a href="#none" name="_btn_sort" order_by="cate1_name" order_by_asc="asc" class="<?=$_order_by=="cate1_name"?"on":""?>">????????????<em>???</em></a>
				</p>
			</div>
           
            <!-- ??????TABLE(s) -->
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
                        <th>????????????</th>
                        <th>?????????</th>
                        <th>??????(??????)??????</th>
                        <th>??????(??????)???</th>
                        <th>?????????</th>
                        <th>????????????1</th>
                        <th>????????????2</th>
                        <th>????????????3</th>
                        <th>????????????4</th>
                        <th>??????</th>
                        <th>???????????????</th>
                        <th>?????????</th>
                        <th>??????</th>
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
                        <td><?=$row["name"]?></td>
                        <td><?=$row["item_code"]?></td>
                        <td><?=$row["item_name"]?></td>
                        <td class="txt_c"><?=$row["brand_name"]?></td>
                        <td class="txt_c"><?=$row["cate1_name"]?></td>
                        <td class="txt_c"><?=$row["cate2_name"]?></td>
                        <td class="txt_c"><?=$row["cate3_name"]?></td>
                        <td class="txt_c"><?=$row["cate4_name"]?></td>
                        <td class="txt_r" name="td_stock_qty"><?=number_format($row["stock_qty"])?></td>
                        <td style="text-align:center;" name="td_stock_apply_date"><?=$row["stock_apply_date"]?></td>
                        <td style="text-align:center;"><?=substr($row["reg_date"],0,10)?></td>
                        <td style="text-align:center;"><a href="#" name="btnApplyStock" item_code="<?=$row["item_code"]?>" style=" display: block; background-color: #1b80c3; padding: 6px 12px; border-radius: 20px; color: #fff;">????????????</a></td>
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
            
            <!-- ??????TABLE(e) -->
			<p class="hide"><strong>Pagination</strong></p>
			<div style="position: relative;">
    			<?=$pg->getNaviForFuncGP("goPage", "<<", "<", ">", ">>")?>
    		</div>

<a href="#none" onclick="javascript:goPageTop();"  style="position: fixed; right: 31px; bottom: 31px; width: 67px; height: 67px; line-height: 70px; background-color: #313A3D; border: none; border-radius: 50%; z-index: 999; box-sizing: border-box; color: #fff; letter-spacing: .3px; text-align: center;">TOP<img src="/ism/images/common/top.png" alt="" style=" margin: -2px 0 0 2px;"/></a>

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
	f.action = "goods_monitor_list_xls.php";
	
	f.submit();
});

var goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "goods_monitor_list.php";
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
	f.action = "goods_monitor_list.php";
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
		url: './api/api_stock_apply_ajax.php',
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
                    alert("????????? ??? ??????????????? ????????????.    ");
                    break;                    
                case "no_item_code" :
                    alert("???????????? ???????????????.    ");
                    break;                    
                case "no_data" :
                    alert("?????? ??????????????? ????????? ?????? ??? ????????????.    ");
                    break;                    
                case "error" :
                    alert("????????? ????????? ????????? ?????????????????????.    ");
                    break;                    
                default:
                	alert("????????? ???????????????.\r\n??????????????? ????????????.    ");
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