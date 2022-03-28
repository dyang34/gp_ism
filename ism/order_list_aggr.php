<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/brand/BrandMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/channel/ChannelMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/category/CategoryMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/sales_type/SalesTypeMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/order/OrderMgr.php";

$menuCate = 1;
$menuNo = 22;

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");

$_grp_day_type = RequestUtil::getParam("_grp_day_type", "grp_order_date_day");
$_grp_code_type = RequestUtil::getParam("_grp_code_type", "");
$_grp_category = RequestUtil::getParam("_grp_category", "");
$_grp_brand = RequestUtil::getParam("_grp_brand", "");
$_grp_channel = RequestUtil::getParam("_grp_channel", "");
$_grp_tax_type = RequestUtil::getParam("_grp_tax_type", "");
$_grp_order_type = RequestUtil::getParam("_grp_order_type", "");

$_order_date_from = RequestUtil::getParam("_order_date_from", date("Y-m-01"));
$_order_date_to = RequestUtil::getParam("_order_date_to", date("Y-m-d"));
$_imc_idx = RequestUtil::getParam("_imc_idx", "");
$_imb_idx = RequestUtil::getParam("_imb_idx", "");
$_cate1_idx = RequestUtil::getParam("_cate1_idx", "");
$_cate2_idx = RequestUtil::getParam("_cate2_idx", "");
$_cate3_idx = RequestUtil::getParam("_cate3_idx", "");
$_cate4_idx = RequestUtil::getParam("_cate4_idx", "");
$_tax_type = RequestUtil::getParam("_tax_type", "");
$_order_type = RequestUtil::getParam("_order_type", "");
$_goods_mst_code = RequestUtil::getParam("_goods_mst_code", "");
$_goods_name = RequestUtil::getParam("_goods_name", "");
$_item_code = RequestUtil::getParam("_item_code", "");
$_item_name = RequestUtil::getParam("_item_name", "");

$_order_by = RequestUtil::getParam("_order_by", "order_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$pg = new Page($currentPage, $pageSize);

$arrDayOfWeek = array("일","월","화","수","목","금","토");
$arrChannel = $arrBrand = $arrCategory1 = $arrCategory2 = $arrCategory3 = $arrCategory4 = $arrSalesType = array();

$wq = new WhereQuery(true, true);
$rs = SalesTypeMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        $arrSalesType[$row["imst_idx"]] = $row["title"];
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
$wq->addOrderBy("imst_idx","");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = ChannelMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_channel = $rs->fetch_assoc();
        
        array_push($arrChannel, $row_channel);
    }
}

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
$wq->addAndNotIn("status", array("취소접수","취소완료","삭제"));
$wq->addAndString("order_date", ">=", $_order_date_from);
$wq->addAndStringBind("order_date", "<", $_order_date_to, "date_add('?', interval 1 day)");
$wq->addAndString("imc_idx", "=", $_imc_idx);
$wq->addAndString("imb_idx", "=", $_imb_idx);
$wq->addAndString("cate1_idx", "=", $_cate1_idx);
$wq->addAndString("cate2_idx", "=", $_cate2_idx);
$wq->addAndString("cate3_idx", "=", $_cate3_idx);
$wq->addAndString("cate4_idx", "=", $_cate4_idx);
$wq->addAndString("tax_type", "=", $_tax_type);
$wq->addAndString("order_type", "=", $_order_type);
$wq->addAndString("goods_mst_code", "=", $_goods_mst_code);
$wq->addAndString("a.item_code", "=", $_item_code);

$wq->addAndLike("name",$_goods_name);
$wq->addAndLike("item_name",$_item_name);

$wq->addOrderBy($_order_by, $_order_by_asc);

if ($_order_by=="cate1_name") {
    switch($_grp_category) {
        case "grp_cate2":
            $wq->addOrderBy("cate2_name", "asc");
            break;
        case "grp_cate3":
            $wq->addOrderBy("cate2_name", "asc");
            $wq->addOrderBy("cate3_name", "asc");
            break;
        case "grp_cate4":
            $wq->addOrderBy("cate2_name", "asc");
            $wq->addOrderBy("cate3_name", "asc");
            $wq->addOrderBy("cate4_name", "asc");
            break;
    }
}

$wq->addOrderBy("order_date", "desc");
//$wq->addOrderBy("name", "asc");
//$wq->addOrderBy("item_name", "asc");

$arrGroupBy = array();

if($_grp_day_type) {
    array_push($arrGroupBy, $_grp_day_type);
}

if($_grp_code_type) {
    array_push($arrGroupBy, $_grp_code_type);
}

if($_grp_category) {
    array_push($arrGroupBy, $_grp_category);
}

if($_grp_brand) {
    array_push($arrGroupBy, $_grp_brand);
}

if($_grp_channel) {
    array_push($arrGroupBy, $_grp_channel);
}

if($_grp_tax_type) {
    array_push($arrGroupBy, $_grp_tax_type);
}

if($_grp_order_type) {
    array_push($arrGroupBy, $_grp_order_type);
}

$rs = OrderMgr::getInstance()->getListAggrPerPage($wq, $pg, $arrGroupBy);

$row_sum = OrderMgr::getInstance()->getListAggrSum($wq);

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">

    <input type="hidden" name="_order_date_from" value="<?=$_order_date_from?>">
    <input type="hidden" name="_order_date_to" value="<?=$_order_date_to?>">
    <input type="hidden" name="_imc_idx" value="<?=$_imc_idx?>">
    <input type="hidden" name="_imb_idx" value="<?=$_imb_idx?>">
    <input type="hidden" name="_cate1_idx" value="<?=$_cate1_idx?>">
    <input type="hidden" name="_cate2_idx" value="<?=$_cate2_idx?>">
    <input type="hidden" name="_cate3_idx" value="<?=$_cate3_idx?>">
    <input type="hidden" name="_cate4_idx" value="<?=$_cate4_idx?>">
    <input type="hidden" name="_tax_type" value="<?=$_tax_type?>">
    <input type="hidden" name="_order_type" value="<?=$_order_type?>">
    <input type="hidden" name="_goods_mst_code" value="<?=$_goods_mst_code?>">
    <input type="hidden" name="_goods_name" value="<?=$_goods_name?>">
	<input type="hidden" name="_item_code" value="<?=$_item_code?>">
	<input type="hidden" name="_item_name" value="<?=$_item_name?>">
    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
    
    <input type="hidden" name="_grp_day_type" value="<?=$_grp_day_type?>">
    <input type="hidden" name="_grp_code_type" value="<?=$_grp_code_type?>">
    <input type="hidden" name="_grp_category" value="<?=$_grp_category?>">
    <input type="hidden" name="_grp_brand" value="<?=$_grp_brand?>">
    <input type="hidden" name="_grp_channel" value="<?=$_grp_channel?>">
    <input type="hidden" name="_grp_tax_type" value="<?=$_grp_tax_type?>">
    <input type="hidden" name="_grp_order_type" value="<?=$_grp_order_type?>">
    
</form>

            <!-- 상품검색(s) -->
            <div>
                <div style="padding-left:20px;">
                    <h3 class="icon-search">통합 판매 집계</h3>
                    <ul class="icon_Btn">
                        <li><a href="#" name="btnExcelDownload">엑셀</a></li>
                    </ul>
                </div>
				<form name="searchForm" method="get" action="order_list_aggr.php">
				
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
								<th style=" border-bottom: 2px solid #395467;">
                                    <span style=" display: block; width: 80%; padding: 10px; border: 1px solid #395467; margin: 0 auto; border-radius: 100px; background-color: #fff; font-weight: bold; box-shadow: 0px 8px 16px #d9d9d9; -webkit-box-shadow: 0px 8px 16px #d9d9d9;">
                                        <img src="https://bling-market.com/image/common/exclamation_mark.png" style=" margin-right: 5px; margin-top: -3px;">집계 구분
                                    </span>
                                </th> 
                                 
                                <td colspan="5" style=" border-bottom: 2px solid #395467;">
<?php /*                                
                                	<input type="radio" id="_grp_code_type_goods" class="" name="_grp_code_type" value="goods_mst_code" <?=$_grp_code_type=="goods_mst_code"?"checked='checked'":""?>><label for="_grp_code_type_goods">상품</label>
                                    <input type="radio" id="_grp_code_type_item" name="_grp_code_type" value="item_code" <?=$_grp_code_type=="item_code"?"checked='checked'":""?>><label for="_grp_code_type_item">품목(옵션)</label>
*/?>
									<select name="_grp_day_type">
                                    	<option value="grp_order_date_day" <?=$_grp_day_type=="grp_order_date_day"?"selected='selected'":""?>>일별</option>
                                    	<option value="grp_order_date_week" <?=$_grp_day_type=="grp_order_date_week"?"selected='selected'":""?>>주별</option>
                                    	<option value="grp_order_date_month" <?=$_grp_day_type=="grp_order_date_month"?"selected='selected'":""?>>월별</option>
                                    </select>
									<select name="_grp_code_type">
                                    	<option value="" <?=$_grp_code_type==""?"selected='selected'":""?>>상품 구분 없음</option>
                                    	<option value="grp_goods" <?=$_grp_code_type=="grp_goods"?"selected='selected'":""?>>상품별</option>
                                    	<option value="grp_item" <?=$_grp_code_type=="grp_item"?"selected='selected'":""?>>품목별(옵션)</option>
                                    </select>
                                    <select name="_grp_category">
                                    	<option value="" <?=$_grp_category==""?"selected='selected'":""?>>카테고리 구분 없음</option>
                                    	<option value="grp_cate1" <?=$_grp_category=="grp_cate1"?"selected='selected'":""?>>카테고리1</option>
                                    	<option value="grp_cate2" <?=$_grp_category=="grp_cate2"?"selected='selected'":""?>>카테고리2</option>
                                    	<option value="grp_cate3" <?=$_grp_category=="grp_cate3"?"selected='selected'":""?>>카테고리3</option>
                                    	<option value="grp_cate4" <?=$_grp_category=="grp_cate4"?"selected='selected'":""?>>카테고리4</option>
                                    </select>
                                    <input type="checkbox" name="_grp_order_type" id="_grp_order_type" value="grp_order_type" <?=$_grp_order_type=="grp_order_type"?"checked='checked'":""?>><label for="_grp_order_type">판매유형</label>
                                    <input type="checkbox" name="_grp_channel" id="_grp_channel" value="grp_channel" <?=$_grp_channel=="grp_channel"?"checked='checked'":""?>><label for="_grp_channel">거래처(채널)</label>
									<input type="checkbox" name="_grp_brand" id="_grp_brand" value="grp_brand" <?=$_grp_brand=="grp_brand"?"checked='checked'":""?>><label for="_grp_brand">브랜드</label>
                                    <input type="checkbox" name="_grp_tax_type" id="_grp_tax_type" value="grp_tax_type" <?=$_grp_tax_type=="grp_tax_type"?"checked='checked'":""?>><label for="_grp_tax_type">과세</label>
                                </td>
							</tr>
							<tr>
                                <th>판매일자</th>
                                <td><input type="date" id="_order_date_from" name="_order_date_from" class="date_in" value="<?=$_order_date_from?>" style="padding:0 16px;">~<input type="date" id="_order_date_to" name="_order_date_to" value="<?=$_order_date_to?>" class="date_in" style="padding:0 16px;"></td>
                                <th>판매유형/거래처(채널)</th>
                            	<td>
									<select name="_order_type" class="sel_order_type">
                                    	<option value="">판매 유형</option>
<?php                                     	
foreach($arrSalesType as $key => $value) {
?>
                                    	<option value="<?=$key?>" <?=$_order_type==$key?"selected":""?>><?=$value?></option>
<?php
}
?>
                                    </select>
                                    <select name="_imc_idx" class="sel_channel">
                						<option value="">거래처(채널) 선택</option>
                						<?php
                						foreach($arrChannel as $lt){
                							?>
                							<option value="<?=$lt['imc_idx']?>" <?=$_imc_idx==$lt['imc_idx']?"selected":""?>><?="[".$lt['sales_type_title']."] ".$lt['name']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>                           
							</tr>
							<tr>
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
                            </tr>
                            <tr>
                            	<th>상품코드</th>
                            	<td><input type="text" placeholder="상품코드로 검색" name="_goods_mst_code" style="width: 100%;" value=<?=$_goods_mst_code?>></td>
                            	<th>상품명</th>
                            	<td><input type="text" placeholder="상품명으로 검색" name="_goods_name" style="width: 100%;" value=<?=$_goods_name?>></td>
                            	<th>과세구분</th>
                                <td>
                                	<select name="_tax_type">
                                    	<option value="">과세 구분</option>
                                    	<option value="과세" <?=$_tax_type=="과세"?"selected":""?>>과세</option>
                                    	<option value="면세" <?=$_tax_type=="면세"?"selected":""?>>면세</option>
                                    </select>
								</td>
                            </tr>
                            <tr>
                            	<th>품목(옵션)코드</th>
                            	<td><input type="text" placeholder="품목(옵션)코드로 검색" name="_item_code" style="width: 100%;" value=<?=$_item_code?>></td>
                            	<th>품목(옵션)명</th>
                            	<td colspan="3"><input type="text" placeholder="품목(옵션)명으로 검색" name="_item_name" style="width: 100%;" value=<?=$_item_name?>></td>
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
				<h3 class="float-l">집계 Data <strong><?=number_format($pg->getTotalCount())?>건</strong></h3>
				<p class="list-adding float-r ism_total" style="border: 1px solid #395467; border-radius: 20px; padding: 7px 20px; background: #fff;">
                    <span><span style="font-weight:bold">전체 수량</span> <em><?=number_format($row_sum["amount"])?></em>개</span>
                    <span><span style="font-weight:bold">전체 EA</span> <em><?=number_format($row_sum["ea"])?></em>개</span>
                    <span><span style="font-weight:bold">전체 금액</span> <em><?=number_format($row_sum["price_collect"])?></em>원</span>
                    <span><span style="font-weight:bold">전체 주문수</span> <em><?=number_format($row_sum["cnt"])?></em>건</span>
                </p>
			</div>

			<p class="list-adding float-r">
				<a href="#none" name="_btn_sort" order_by="order_date" order_by_asc="desc" class="<?=$_order_by=="order_date" && $_order_by_asc=="desc"?"on":""?>" >판매일순<em>▼</em></a>
<?php
if ($_grp_code_type=="grp_goods" || $_grp_code_type=="grp_item") {
?>					
				<a href="#none" name="_btn_sort" order_by="name" order_by_asc="asc" class="<?=$_order_by=="name" && $_order_by_asc=="asc"?"on":""?>">상품명<em>▲</em></a>
				<a href="#none" name="_btn_sort" order_by="name" order_by_asc="desc" class="<?=$_order_by=="name" && $_order_by_asc=="desc"?"on":""?>">상품명<em>▼</em></a>
<?php
}
?>


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
           
            <!-- 메인TABLE(s) -->
            <table class="display" cellpadding="0" cellspacing="0">
				<colgroup>
                    <col style="width:110px;">
<?php                    
if (in_array("grp_goods", $arrGroupBy)) {
?>
					<col />
					<col />
            <?php             
}

if (in_array("grp_item", $arrGroupBy)) {
?>
					<col />
					<col />
					<col />
					<col />
            <?php
}

if (in_array("grp_cate1", $arrGroupBy)) {
?>
					<col />
            <?php
}

if (in_array("grp_cate2", $arrGroupBy)) {
?>
					<col />
					<col />
            <?php
}

if (in_array("grp_cate3", $arrGroupBy)) {
?>
					<col />
            <?php
}

if (in_array("grp_cate4", $arrGroupBy)) {
?>
					<col />
            <?php
}

if (in_array("grp_order_type", $arrGroupBy)) {
    ?>
					<col />
            <?php
}

if (in_array("grp_channel", $arrGroupBy)) {
    ?>
					<col />
            <?php
}

if (in_array("grp_brand", $arrGroupBy)) {
    ?>
					<col />
            <?php
}

if (in_array("grp_tax_type", $arrGroupBy)) {
?>
					<col />
            <?php
}
?>                    
					<col />
					<col />
					<col />
					<col />
                </colgroup>
                <thead>
                    <tr>
<?php 
$cnt_columns = 4;

if (in_array("grp_order_date_day", $arrGroupBy) || in_array("grp_order_date_week", $arrGroupBy)) {
    $cnt_columns++;
?>
						<th class="tbl_first">주문일자</th>
<?php             
}

if (in_array("grp_order_date_month", $arrGroupBy)) {
    $cnt_columns++;
?>
						<th class="tbl_first">주문월</th>
<?php             
}

if (in_array("grp_goods", $arrGroupBy)) {
    $cnt_columns+=2;
?>
						<th class="">상품코드</th>
						<th class="">상품명</th>
            <?php             
}

if (in_array("grp_item", $arrGroupBy)) {
    $cnt_columns+=4;
?>
						<th class="">상품코드</th>
						<th class="">상품명</th>
						<th class="">옵션코드</th>
						<th class="">옵션명</th>
            <?php
}

if (in_array("grp_cate1", $arrGroupBy)) {
    $cnt_columns++;
?>
						<th class="">카테고리1</th>
            <?php
}

if (in_array("grp_cate2", $arrGroupBy)) {
    $cnt_columns+=2;
?>
						<th class="">카테고리1</th>
						<th class="">카테고리2</th>
            <?php
}

if (in_array("grp_cate3", $arrGroupBy)) {
    $cnt_columns+=3;
?>
						<th class="">카테고리1</th>
						<th class="">카테고리2</th>
						<th class="">카테고리3</th>
            <?php
}

if (in_array("grp_cate4", $arrGroupBy)) {
    $cnt_columns+=4;
?>
						<th class="">카테고리1</th>
						<th class="">카테고리2</th>
						<th class="">카테고리3</th>
						<th class="">카테고리4</th>
            <?php
}

if (in_array("grp_order_type", $arrGroupBy)) {
    $cnt_columns++;
    ?>
						<th class="">판매유형</th>
            <?php
}

if (in_array("grp_channel", $arrGroupBy)) {
    $cnt_columns++;
    ?>
						<th class="">거래처(채널)</th>
            <?php
}

if (in_array("grp_brand", $arrGroupBy)) {
    $cnt_columns++;
    ?>
						<th class="">브랜드</th>
            <?php
}

if (in_array("grp_tax_type", $arrGroupBy)) {
    $cnt_columns++;
?>
						<th class="">과세구분</th>
            <?php
}
?>
                        <th>수량</th>
                        <th>EA</th>
                        <th>금액</th>
                        <th>주문수</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 2px solid #395467">
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
        
        if ($_grp_day_type=="grp_order_date_day") {
            $idx_day_of_week = date('w', strtotime(substr($row["order_date"],0,10)));
            $date_txt = substr($row["order_date"],0,10)." ".$arrDayOfWeek[$idx_day_of_week];
            
            $order_list_link_param = "_order_date_from=".substr($row["order_date"],0,10);
            $order_list_link_param .= "&_order_date_to=".substr($row["order_date"],0,10);
            
        } else if ($_grp_day_type=="grp_order_date_week") {
            
            $weekly_start_date = substr($row["order_date"],0,10);
            $weekly_end_date = substr($row["order_date"],18,10);
            
            if($weekly_start_date < $_order_date_from) {
                $weekly_start_date = $_order_date_from;
            }

            if($weekly_end_date > $_order_date_to) {
                $weekly_end_date = $_order_date_to;
            }
            
            $date_txt = $weekly_start_date."<br/>~ ".$weekly_end_date;
            
            $order_list_link_param = "_order_date_from=".$weekly_start_date;
            $order_list_link_param .= "&_order_date_to=".$weekly_end_date;
            
        } else {
            $date_txt = substr($row["order_date"],0,7);
            
            $monthly_start_date = substr($row["order_date"],0,7)."-01";
            $monthly_end_date = date("Y-m-t", strtotime(substr($row["order_date"],0,7)."-01"));
            
            if ($monthly_start_date < $_order_date_from || $monthly_end_date > $_order_date_to) {
                if($monthly_start_date < $_order_date_from) {
                    $monthly_start_date = $_order_date_from;
                }
                
                if($monthly_end_date > $_order_date_to) {
                    $monthly_end_date = $_order_date_to;
                }
            
                $date_txt = $monthly_start_date."<br/>~ ".$monthly_end_date;
                
            }
            
            $order_list_link_param = "_order_date_from=".$monthly_start_date;
            $order_list_link_param .= "&_order_date_to=".$monthly_end_date;
        }
?>
                    
                    <tr>
                        <td class="tbl_first txt_c" style="<?=$idx_day_of_week=="6"?"color:blue;":($idx_day_of_week=="0"?"color:red;":"")?>">
<?php

if (in_array("grp_goods", $arrGroupBy)) {
    $order_list_link_param .= "&_goods_mst_code=".$row["code"];
}

if (in_array("grp_item", $arrGroupBy)) {
    $order_list_link_param .= "&_item_code=".$row["item_code"];
}

if (in_array("grp_cate1", $arrGroupBy)) {
    $order_list_link_param .= "&_cate1_idx=".$row["cate1_idx"];
}

if (in_array("grp_cate2", $arrGroupBy)) {
    $order_list_link_param .= "&_cate1_idx=".$row["cate1_idx"];
    $order_list_link_param .= "&_cate2_idx=".$row["cate2_idx"];
}

if (in_array("grp_cate3", $arrGroupBy)) {
    $order_list_link_param .= "&_cate1_idx=".$row["cate1_idx"];
    $order_list_link_param .= "&_cate2_idx=".$row["cate2_idx"];
    $order_list_link_param .= "&_cate3_idx=".$row["cate3_idx"];
}

if (in_array("grp_cate4", $arrGroupBy)) {
    $order_list_link_param .= "&_cate1_idx=".$row["cate1_idx"];
    $order_list_link_param .= "&_cate2_idx=".$row["cate2_idx"];
    $order_list_link_param .= "&_cate3_idx=".$row["cate3_idx"];
    $order_list_link_param .= "&_cate4_idx=".$row["cate4_idx"];
}

if (in_array("grp_order_type", $arrGroupBy)) {
    $order_list_link_param .= "&_order_type=".$row["order_type"];
}

if (in_array("grp_channel", $arrGroupBy)) {
    $order_list_link_param .= "&_imc_idx=".$row["imc_idx"];
}

if (in_array("grp_brand", $arrGroupBy)) {
    $order_list_link_param .= "&_imb_idx=".$row["imb_idx"];
}

if (in_array("grp_tax_type", $arrGroupBy)) {
    $order_list_link_param .= "&_tax_type=".$row["tax_type"];
}

$order_list_link_param .= "&_except_cancel=1";
?>
                        <a href="./order_list.php?<?=$order_list_link_param?>" target="_blank"><?=$date_txt?></a>
                        </td>
                        
<?php 
            if (in_array("grp_goods", $arrGroupBy)) {
?>
                        <td><?=$row["code"]?></td>
                        <td><?=$row["name"]?></td>
            <?php
            }
            
            if (in_array("grp_item", $arrGroupBy)) {
            ?>
                        <td><?=$row["code"]?></td>
                        <td><?=$row["name"]?></td>
                        <td><?=$row["item_code"]?></td>
                        <td><?=$row["item_name"]?></td>
            <?php
            }
            
            if (in_array("grp_cate1", $arrGroupBy)) {
            ?>
                        <td class="txt_c"><?=$row["cate1_name"]?></td>
            <?php
            }
            
            if (in_array("grp_cate2", $arrGroupBy)) {
            ?>
                        <td class="txt_c"><?=$row["cate1_name"]?></td>
                        <td class="txt_c"><?=$row["cate2_name"]?></td>
            <?php
            }
            
            if (in_array("grp_cate3", $arrGroupBy)) {
            ?>
                        <td class="txt_c"><?=$row["cate1_name"]?></td>
                        <td class="txt_c"><?=$row["cate2_name"]?></td>
                        <td class="txt_c"><?=$row["cate3_name"]?></td>
            <?php
            }
            
            if (in_array("grp_cate4", $arrGroupBy)) {
            ?>
                        <td class="txt_c"><?=$row["cate1_name"]?></td>
                        <td class="txt_c"><?=$row["cate2_name"]?></td>
                        <td class="txt_c"><?=$row["cate3_name"]?></td>
                        <td class="txt_c"><?=$row["cate4_name"]?></td>
            <?php
            }
            
            if (in_array("grp_order_type", $arrGroupBy)) {
                ?>
            <td class="txt_c"><?=$arrSalesType[$row["order_type"]]?></td>
            <?php
            }
            
            if (in_array("grp_channel", $arrGroupBy)) {
                ?>
                        <td class="txt_c"><?=$row["channel"]?></td>
            <?php
            }
            
            if (in_array("grp_brand", $arrGroupBy)) {
                ?>
                        <td class="txt_c"><?=$row["brand_name"]?></td>
            <?php
            }
            
            if (in_array("grp_tax_type", $arrGroupBy)) {
            ?>
                        <td class="txt_c"><?=$row["tax_type"]?></td>
            <?php
            }
            ?>
						<td class="txt_r"><?=number_format($row["amount"])?></td>
                        <td class="txt_r"><?=number_format($row["ea"])?></td>
                        <td class="txt_r"><?=number_format($row["price_collect"])?></td>
                        <td class="txt_r"><?=number_format($row["cnt"])?></td>
                        
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="<?=$cnt_columns?>" class="txt_c">No Data.</td></tr>
<?php
}
?>
                </tbody>
            </table>
            <!-- 메인TABLE(e) -->
			<p class="hide"><strong>Pagination</strong></p>
			<div style="position: relative;">
    			<?=$pg->getNaviForFuncGP("goPage", "<<", "<", ">", ">>")?>
<?php /*    			
    			<div style="position: absolute; right: 17px; bottom: 3px; text-align: center; line-height: 30px; border-radius: 10px; background-color: #313A3D;" class="rig_new"><a href="./goods_write.php" style="display:inline-block;padding: 5px 22px;color: #fff;">등록하기</a></div>
*/?>
    		</div>
    		
    		<a href="#none" onclick="javascript:goPageTop();"  style="position: fixed; right: 31px; bottom: 31px; width: 67px; height: 67px; line-height: 70px; background-color: #313A3D; border: none; border-radius: 50%; z-index: 999; box-sizing: border-box; color: #fff; letter-spacing: .3px; text-align: center;">TOP<img src="/ism/images/common/top.png" alt="" style=" margin: -2px 0 0 2px;"/></a>

<script src="/ism/cms/js/util/ValidCheck.js"></script>
<script type="text/javascript">

$(document).on("click","a[name=btnSearch]",function() {
	
	var f = document.searchForm;

    if ( VC_inValidDate(f._order_date_from, "판매일자 시작일") ) return false;
    if ( VC_inValidDate(f._order_date_to, "판매일자 종료일") ) return false;

	var arrFromDate=f._order_date_from.value.split('-');
	var arrToDate=f._order_date_to.value.split('-');
	
	var fromDate = new Date(arrFromDate[0],arrFromDate[1]-1,arrFromDate[2]);
	var toDate = new Date(arrToDate[0],arrToDate[1]-1,arrToDate[2]);

	toDate.setMonth(toDate.getMonth()-12);
	
	if (fromDate < toDate) {
		alert("최대 12개월 단위로 조회하실 수 있습니다.    ");
		f._order_date_from.focus();
	
		return false;
	}
	
    f.submit();	
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

$(document).on('change','.sel_order_type',function() {
	var obj_select

	obj_select = $('.sel_channel');

	$.ajax({
		url: "/ism/ajax/ajax_channel.php",
		data: {imst_idx: $("option:selected", this).val()},
		async: true,
		cache: false,
		error: function(xhr){	},
		success: function(data){
			obj_select.html(data);
		}
	});
});

$(document).on('click','a[name=btnExcelDownload]', function() {
	var f = document.pageForm;
	f.target = "_new";
	f.action = "order_list_aggr_xls.php";
	
	f.submit();
});

var goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "order_list_aggr.php";
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
	f.action = "order_list_aggr.php";
	f.submit();
}

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";

@ $rs->free();
?>