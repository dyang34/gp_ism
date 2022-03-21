<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/goods/GoodsItemMgr.php";

$menuCate = 2;
$menuNo = 10;

$wq = new WhereQuery(true, true);
$wq->addAndString2("img_fg_del","=","0");
$max_idx = GoodsItemMgr::getInstance()->getMaxIdx($wq);

$max_idx = ceil($max_idx / 500);

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>

<div class="gp_rig_search">
    <div style="padding-left:20px;">
        <h3 class="wrt_icon_search">전체 상품 재고 반영 (하나로 TNS 연동)</h3>
        <!--<ul class="icon_Btn">
            <li><a href="#">조회</a></li>  
            <li><a href="#">추가</a></li>
            <li><a href="#">엑셀</a></li>
            <li><a href="#">삭제</a></li>
            <li><a href="#">저장</a></li>
            <li><a href="#">인쇄</a></li>
        </ul>-->
	</div>
	<form name="writeForm" method="post" action="../api/api_stock_apply_all.php">
		<input type="hidden" name="mode" value="API_STOCK" />
		<input type="hidden" name="item_code" value="ISM_GOODS_ALL" />
    	<input type="hidden" name="auto_defense" />    									
    	
    	<table class="wrt_table">
            <caption>등록하기</caption>
            <colgroup>
                <col style="width:16%;"><col>
            </colgroup>
            <tbody>
                <tr></tr>
            </tbody>
		</table>
	</form>
	
    <div class="loading_box" style="display:none">
        <div class="loading">
            <p>작업중입니다</p>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!--loadingbar(e)-->
    
    <!--result(s)-->
    <div class="result_box" style="display:none">
        <div class="result_bg"></div>
        <div class="loading">
            <img src="/ism/images/common/checked.png" alt="" style="margin: -8px 0 16px;" />
            <p>- 전체 상품 : <span id="span_all">0</span> 건</p>
            <p>- 반영 상품 : <span id="span_applied"></span> 건</p>
            <p>- 미반영 상품 : <span id="span_not_applied"></span> 건</p>
            <p>- 에러 상품 : <span id="span_error"></span> 건</p>
        </div>
    </div>
    <!--result(e)-->
    
    
    <!--result(e)-->

	<!-- 취소/등록 버튼 START -->
	<div style="overflow: hidden; display: flex; display: -webkit-flex; -webkit-align-items: center; align-items: center; flex-direction: inherit; justify-content: center; margin-top: 9px;">
		<div class="wrt_searchBtn">
			<a href="#" name="btnSave">재고 반영</a>
		</div>
	</div>

</div>

<script type="text/javascript">
var mc_consult_submitted = false;
var cnt_all = cnt_apply = cnt_not_apply = cnt_error = 0;
var max_idx = <?=$max_idx?>;
var done_idx = 0;

$(document).on("click","a[name=btnSave]",function() {

	if(mc_consult_submitted == true) { return false; }
	
	if(!confirm("전체 상품에 대한 재고를 반영하시겠습니까?\r\n\r\n데이터의 양에 따라 수분~수십분 시간이 소요될 수 있습니다.")) {
		return false;
	}

	$('.loading_box').show();
    $('a[name=btnSave]').hide();
	
/*	
	var f = document.writeForm;
	
	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	
*/
    
    cnt_all = cnt_apply = cnt_not_apply = cnt_error = done_idx = 0;
    
    for(curr_idx=1;curr_idx <= max_idx;curr_idx++) {
    	run_ajax(curr_idx);
    }

    return false;
    
});

var run_ajax = function(curr_idx) {
	
	$.ajax({
		url: '../api/api_stock_apply_all_ajax.php',
		type: 'POST',
		dataType: "json",
		async: false,
		cache: false,
		data: {
			mode : 'API_STOCK',
			item_code : 'ISM_GOODS_ALL',
			div_idx : curr_idx
		},
		success: function (response) {
			switch(response.RESULTCD){
                case "SUCCESS" :
                
					cnt_all += response.CNT_ALL;
                	cnt_apply += response.CNT_APPLY;
                	cnt_not_apply += response.CNT_NOT_APPLY;
                	cnt_error += response.CNT_ERROR;
                	done_idx++;

					if (done_idx >= max_idx) {
                        $('#span_all').html(cnt_all.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                        $('#span_applied').html(cnt_apply.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                        $('#span_not_applied').html(cnt_not_apply.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                        $('#span_error').html(cnt_error.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                	
/*                	
                        $('#span_all').html(response.CNT_ALL.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                        $('#span_applied').html(response.CNT_APPLY.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                        $('#span_not_applied').html(response.CNT_NOT_APPLY.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                        $('#span_error').html(response.CNT_ERROR.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
*/                    
                    	$('.result_box').show();
					}
					
                    break;
                case "not_login" :
                    alert("로그인 후 작업하시기 바랍니다.    ");
                    break;                    
                case "not_item_code" :
                    alert("옵션코드 에러입니다.    ");
                    break;                    
                case "not_mode" :
                    alert("모드 에러입니다.    ");
                    break;                    
                case "no_data" :
                    alert("해당 제품코드의 재고를 찾을 수 없습니다.    ");
                    break;                    
                default:
                	alert("시스템 오류입니다.\r\n문의주시기 바랍니다.    ");
                    break;
            }
		},
		complete:function(){
			if (done_idx >= max_idx) {
    			$('.loading_box').hide();
    			$('a[name=btnSave]').show();
			}
		},
		error: function(request,status,error){
			$('.loading_box').hide();
			$('a[name=btnSave]').show();
			alert("code:"+request.status+"\n"+"error:"+error+"\n"+"status:"+status+"\n"+"curr_idx:"+curr_idx);	// +"message:"+request.responseText+"\n"
		}
	});
}
</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";
?>