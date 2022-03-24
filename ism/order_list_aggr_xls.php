<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/order/OrderMgr.php";

// ini_set('memory_limit','512M');

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

$arrDayOfWeek = array("일","월","화","수","목","금","토");

$wq = new WhereQuery(true, true);
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
$wq->addOrderBy("name", "asc");
$wq->addOrderBy("item_name", "asc");

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

$rs = OrderMgr::getInstance()->getListAggr($wq, $arrGroupBy);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=ISM_판매 통합 집계(".$_order_date_from."_".$_order_date_to.")_".date('Ymd').".xls");
Header("Content-Description: PHP5 Generated Data");
Header("Pragma: no-cache");
Header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");
?>
<style>
td{font-size:11px;text-align:center;}
th{font-size:11px;text-align:center;color:white;background-color:#000081;}
</style>

<table cellpadding=3 cellspacing=0 border=1 bordercolor='#bdbebd' style='border-collapse: collapse'>
    <tr>
<?php 
$cnt_columns = 4;

if (in_array("grp_order_date_day", $arrGroupBy) || in_array("grp_order_date_week", $arrGroupBy)) {
    $cnt_columns++;
?>
						<th style="color:white;background-color:#000081;">주문일자</th>
<?php             
}

if (in_array("grp_order_date_month", $arrGroupBy)) {
    $cnt_columns++;
?>
						<th style="color:white;background-color:#000081;">주문월</th>
<?php             
}

if (in_array("grp_goods", $arrGroupBy)) {
    $cnt_columns+=2;
?>
						<th style="color:white;background-color:#000081;">상품코드</th>
						<th style="color:white;background-color:#000081;">상품명</th>
            <?php             
}

if (in_array("grp_item", $arrGroupBy)) {
    $cnt_columns+=4;
?>
						<th style="color:white;background-color:#000081;">상품코드</th>
						<th style="color:white;background-color:#000081;">상품명</th>
						<th style="color:white;background-color:#000081;">옵션코드</th>
						<th style="color:white;background-color:#000081;">옵션명</th>
            <?php
}

if (in_array("grp_cate1", $arrGroupBy)) {
    $cnt_columns++;
?>
						<th style="color:white;background-color:#000081;">카테고리1</th>
            <?php
}

if (in_array("grp_cate2", $arrGroupBy)) {
    $cnt_columns+=2;
?>
						<th style="color:white;background-color:#000081;">카테고리1</th>
						<th style="color:white;background-color:#000081;">카테고리2</th>
            <?php
}

if (in_array("grp_cate3", $arrGroupBy)) {
    $cnt_columns+=3;
?>
						<th style="color:white;background-color:#000081;">카테고리1</th>
						<th style="color:white;background-color:#000081;">카테고리2</th>
						<th style="color:white;background-color:#000081;">카테고리3</th>
            <?php
}

if (in_array("grp_cate4", $arrGroupBy)) {
    $cnt_columns+=4;
?>
						<th style="color:white;background-color:#000081;">카테고리1</th>
						<th style="color:white;background-color:#000081;">카테고리2</th>
						<th style="color:white;background-color:#000081;">카테고리3</th>
						<th style="color:white;background-color:#000081;">카테고리4</th>
            <?php
}

if (in_array("grp_brand", $arrGroupBy)) {
    $cnt_columns++;
    ?>
						<th style="color:white;background-color:#000081;">브랜드</th>
            <?php
}

if (in_array("grp_channel", $arrGroupBy)) {
    $cnt_columns++;
    ?>
						<th style="color:white;background-color:#000081;">채널</th>
            <?php
}

if (in_array("grp_order_type", $arrGroupBy)) {
    $cnt_columns++;
?>
						<th style="color:white;background-color:#000081;">판매유형</th>
            <?php
}

if (in_array("grp_tax_type", $arrGroupBy)) {
    $cnt_columns++;
?>
						<th style="color:white;background-color:#000081;">과세구분</th>
            <?php
}
?>
                        <th style="color:white;background-color:#000081;">수량</th>
                        <th style="color:white;background-color:#000081;">EA</th>
                        <th style="color:white;background-color:#000081;">금액</th>
                        <th style="color:white;background-color:#000081;">건수</th>
                    </tr>
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
        
        if ($_grp_day_type=="grp_order_date_day") {
            $idx_day_of_week = date('w', strtotime(substr($row["order_date"],0,10)));
            $date_txt = substr($row["order_date"],0,10)." ".$arrDayOfWeek[$idx_day_of_week];
        } else if ($_grp_day_type=="grp_order_date_week") {
            $date_txt = str_replace("<br/>","",$row["order_date"]);
            
            $weekly_start_date = substr($row["order_date"],0,10);
            $weekly_end_date = substr($row["order_date"],18,10);
            
            if($weekly_start_date < $_order_date_from) {
                $weekly_start_date = $_order_date_from;
            }
            
            if($weekly_end_date > $_order_date_to) {
                $weekly_end_date = $_order_date_to;
            }
            
            $date_txt = $weekly_start_date."
 ~".$weekly_end_date;
            
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
                
                $date_txt = $monthly_start_date."
 ~".$monthly_end_date;
                
            }
        }
?>
                    
                    <tr>
                        <td class="txt_c" style="mso-number-format:'\@';<?=$idx_day_of_week=="6"?"color:blue;":($idx_day_of_week=="0"?"color:red;":"")?>"><?=$date_txt?></td>
                        
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
                        <td><?=$row["cate1_name"]?></td>
                        <td><?=$row["cate2_name"]?></td>
                        <td><?=$row["cate3_name"]?></td>
                        <td><?=$row["cate4_name"]?></td>
            <?php
            }
            
            if (in_array("grp_brand", $arrGroupBy)) {
                ?>
                        <td class="txt_c"><?=$row["brand_name"]?></td>
            <?php
            }
            
            if (in_array("grp_channel", $arrGroupBy)) {
                ?>
                        <td class="txt_c"><?=$row["channel"]?></td>
            <?php
            }
            
            if (in_array("grp_order_type", $arrGroupBy)) {
            ?>
            			<td class="txt_c"><?=$row["order_type"]=="1"?"온라인":"도매"?></td>
            <?php
            }
            
            if (in_array("grp_tax_type", $arrGroupBy)) {
            ?>
                        <td class="txt_c"><?=$row["tax_type"]?></td>
            <?php
            }
            ?>
						<td><?=number_format($row["amount"])?></td>
                        <td><?=number_format($row["ea"])?></td>
                        <td><?=number_format($row["price_collect"])?></td>
                        <td><?=number_format($row["cnt"])?></td>
                        
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="<?=$cnt_columns?>" class="txt_c">No Data.</td></tr>
<?php
}
?>
            </table>
<?php
@ $rs->free();
?>