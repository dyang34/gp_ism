<div class="side_scroll">

    <div id="side">
        <h1 class="wms_setting">MENU</h1>
        <ul class="gp_wms_snb mainMenu">
            <li class="">
                <a href='#' class="btn <?=$menuCate==1?"on":""?>">판매 통계<span></span></a>
                <div class="subMenu">
                    <a href="/ism/order_list.php " class="<?=$menuNo==1?"on":""?>">- 판매 내역</a>
<?php /*                    
                    <a href="/ism/order_list_by_channel.php" class="<?=$menuNo==2?"on":""?>">- 채널별 집계</a>
                    <a href="/ism/order_list_by_brand.php" class="<?=$menuNo==11?"on":""?>">- 브랜드별 집계</a>
                    <a href="/ism/order_list_by_category.php" class="<?=$menuNo==12?"on":""?>">- 카테고리별 집계</a>
*/?>
                    <a href="/ism/order_list_aggr.php" class="<?=$menuNo==22?"on":""?>">- 통합 판매 집계</a>
                    <a href="/ism/goods_monitor_list.php" class="<?=$menuNo==23?"on":""?>">- 품목 리스트</a>
    			</div>
            </li>
    <?php
    if (LoginManager::getUserLoginInfo("iam_grade") >= 8 && LoginManager::getUserLoginInfo("iam_grade") != 9) {
    ?>                        
            <li class="">
                <a href='#' class="btn <?=$menuCate==2?"on":""?>">판매 관리<span></span></a>
                <div class="subMenu">
    				<a href="/ism/admin/upload_sales_data.php" class="<?=$menuNo==3?"on":""?>">- 판매 업로드</a>
    				<a href="/ism/admin/adm_order_list.php" class="<?=$menuNo==3?"on":""?>">- 판매 내역 작업</a>
    				<a href="/ism/admin/apply_stock.php" class="<?=$menuNo==10?"on":""?>">- 재고 반영</a>
    			</div>
            </li>
    <?php
    }

    if (LoginManager::getUserLoginInfo("iam_grade") >= 9) {
    ?>                        
            <li class="">
                <a href='#' class="btn <?=$menuCate==4?"on":""?>">도매 관리<span></span></a>
                <div class="subMenu">
    				<a href="/ism/admin/wholesale_list.php" class="<?=$menuNo==8?"on":""?>">- 도매 판매 내역</a>
    			</div>
            </li>
    <?php
    }
    
    if (LoginManager::getUserLoginInfo("iam_grade") >= 10) {
    ?>
            <li>
                <a href='#' class="btn <?=$menuCate==3?"on":""?>">기초 정보<span></span></a>
                <div class="subMenu">
                	<a href="/ism/admin/adm_mem_list.php " class="<?=$menuNo==9?"on":""?>">- 회원 관리</a>
                    <a href="/ism/admin/goods_list.php " class="<?=$menuNo==4?"on":""?>">- 상품 관리</a>
                    <a href="/ism/admin/goods_item_list.php " class="<?=$menuNo==24?"on":""?>">- 품목 관리</a>
                    <a href="/ism/admin/brand_list.php " class="<?=$menuNo==5?"on":""?>">- 브랜드 관리</a>
                    <a href="/ism/admin/category_list.php " class="<?=$menuNo==6?"on":""?>">- 카테고리 관리</a>
                    <a href="/ism/admin/channel_list.php " class="<?=$menuNo==7?"on":""?>">- 거래처(채널) 관리</a>
                </div>
            </li>
    <?php
    }
    ?>
        </ul>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click','.mainMenu .btn',function(){
        if( $(this).hasClass('on') ){
            $('.mainMenu .btn').removeClass('on').next().slideUp(300);
            $('.mainMenu .subMenu a').removeClass('on');
        } else {
            $(this).addClass('on').next().slideDown(300).parent().siblings().find('.btn').removeClass('on').next().slideUp(300);
        }
    });
				
    $(document).on('click','.mainMenu .subMenu a',function(e){

        $('.mainMenu .subMenu a').removeClass('on');
        $(this).addClass('on');
        
    });

    $(document).ready(function() {
    	$(".mainMenu .btn.on").next().slideDown(300).parent().siblings().find('.btn').removeClass('on').next().slideUp(300);
    	
//    	$('.mainMenu .subMenu a.on').scrollIntoView(true);
//    	alert($('.mainMenu .subMenu .on').scrollTop());
    });

</script>