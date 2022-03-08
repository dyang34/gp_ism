<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

$menuCate = 2;
$menuNo = 3;

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>

<div class="gp_rig_search">
    <div style="padding-left:20px;">
        <h3 class="wrt_icon_search">판매 파일 업로드</h3>
        <!--<ul class="icon_Btn">
            <li><a href="#">조회</a></li>  
            <li><a href="#">추가</a></li>
            <li><a href="#">엑셀</a></li>
            <li><a href="#">삭제</a></li>
            <li><a href="#">저장</a></li>
            <li><a href="#">인쇄</a></li>
        </ul>-->
	</div>
	<form name="writeForm" method="post" action="./upload_xlsx.php" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="UPLOAD" />
    	<input type="hidden" name="auto_defense" />    									
    	
    	<table class="wrt_table">
            <caption>등록하기</caption>
            <colgroup>
                <col style="width:16%;"><col>
            </colgroup>
            <tbody>
                <tr>
                    <th>엑셀 파일 업로드(.xlsx)</th>
                    <td>
                        <input type="file" name="up_file" placeholder="xlsx 선택" style="width: 13%;">
                    </td>
                </tr>
            </tbody>
		</table>
	</form>
				
	<!-- 취소/등록 버튼 START -->
	<div style="overflow: hidden; display: flex; display: -webkit-flex; -webkit-align-items: center; align-items: center; flex-direction: inherit; justify-content: center; margin-top: 9px;">
		<div class="wrt_searchBtn">
			<a href="#" name="btnSave">업로드</a>
		</div>
	</div>

</div>

<script src="/ism/cms/js/util/ValidCheck.js"></script>
<script type="text/javascript">
var mc_consult_submitted = false;

$(document).on("click","a[name=btnSave]",function() {

	if(mc_consult_submitted == true) { return false; }
	
	var f = document.writeForm;

	if ( VC_inValidText(f.up_file, "업로드할 파일") ) return false;

	if(!confirm("엑셀 파일을 업로드 하시겠습니까?\r\n\r\n데이터의 양에 따라 수분~수십분 시간이 소요될 수 있습니다.\r\n\r\n업로드 중 절대로 작업을 중단하지 마십시오.")) {
		return false;
	}
	
	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
});
</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";
?>