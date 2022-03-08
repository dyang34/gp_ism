<?php
require_once $_SERVER['DOCUMENT_ROOT']."/ism/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ism/classes/ism/category/CategoryMgr.php";

$menuCate = 3;
$menuNo = 6;

$wq = new WhereQuery(true, true);
$wq->addAndString2("imct_fg_del","=","0");

$_order_by = "case depth when 1 then lpad(sort,'4','0')
        when 2 then CONCAT((SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = a.uppest_imct_idx),'-',lpad(sort,'4','0'))
        when 3 then CONCAT((SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = a.uppest_imct_idx),'-',(SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = a.upper_imct_idx),'-',lpad(sort,'4','0'))
        when 4 then CONCAT((SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = a.uppest_imct_idx),'-',(SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = (SELECT upper_imct_idx FROM ism_mst_category b WHERE b.imct_idx = a.upper_imct_idx)),'-',(SELECT lpad(sort,'4','0') FROM ism_mst_category b WHERE b.imct_idx = a.upper_imct_idx),'-',lpad(sort,'4','0'))
        END
";
$wq->addOrderBy($_order_by, "asc");

$rs = CategoryMgr::getInstance()->getList($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/ism/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/ism/include/header.php";
?>
           
						<!-- 제품검색(s) -->
						<div style="padding-left:20px;">
							<h3 class="wrt_icon_search">카테고리 관리</h3>
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
										<li class="list" style="border-top: 0;">
											<input type="checkbox" name="item" id="item1"/>   
											<label for="item1">
												<span class="folder"></span> List 1<input type="button" value="항목추가">
												<span class="arw"></span>
											</label>
											<!--
												하위 추가 시 <ul class="options items"> 추가
												
												- 첫번째 하위 카테고리에는 sub_list 가 포함되어있어야 함
												- 추가 하위 카테고리가 있을 시 li → list depth_list
												- 추가 하위 카테고리가 없을 시 li → list depth_last_list
											-->
											<ul class="options items">
												<li class="list sub_list depth_list" style="border-top: 0;">
													<input type="checkbox" name="item" id="item_1"/>
													<label for="item_1">
														<span class="folder"></span> List 1<input type="button" value="항목추가">
														<span class="arw"></span>
													</label>
													<!-- 세번째 추가 하위카테고리가 있을때 ism_dep -->
													<ul class="options items ism_dep">
														<li class="list depth_last_list" style="border-top: 0;">
															<input type="checkbox" name="item" id="item_1_1"/>
															<label for="item_1_1">
																<span class="folder"></span> List 1<input type="button" value="항목추가">
															</label>
														</li>
														<li class="list depth_last_list" style="border-top: 0;">
															<input type="checkbox" name="item" id="item_1_2"/>
															<label for="item_1_2">
																<span class="folder"></span> List 2<input type="button" value="항목추가">
															</label>
														</li>
													</ul>
													<!-- 세번째 추가 하위카테고리가 있을때 ism_dep -->
												</li>
											</ul>
										</li>
										<li class="list">
											<input type="checkbox" name="item" id="item2" />   
											<label for="item2">
												<span class="folder"></span> List 2<input type="button" value="항목추가">
												<span class="arw"></span>
											</label>
											<ul class="options items">
												<li class="list sub_list depth_last_list" style="border-top: 0;">
													<input type="checkbox" name="item" id="item2_1" checked/>   
													<label for="item2_1">
														<span class="folder"></span> List 1<input type="button" value="항목추가">
													</label>
												</li>
												<li class="list sub_list depth_list" style="border-top: 0;">
													<input type="checkbox" name="item" id="item2_2"/>   
													<label for="item2_2">
														<span class="folder"></span> List 2<input type="button" value="항목추가">
														<span class="arw"></span>
													</label>
													<!-- 세번째 추가 하위카테고리가 있을때 ism_dep 추가 -->
													<ul class="options items ism_dep"> 
														<li class="list depth_last_list" style="border-top: 0;">
															<input type="checkbox" name="item" id="item_1_1"/>
															<label for="item_1_1">
																<span class="folder"></span> List 1<input type="button" value="항목추가">
															</label>
														</li>
														<li class="list depth_last_list" style="border-top: 0;">
															<input type="checkbox" name="item" id="item_1_2"/>
															<label for="item_1_2">
																<span class="folder"></span> List 2<input type="button" value="항목추가">
															</label>
														</li>
														<li class="list depth_last_list" style="border-top: 0;">
															<input type="checkbox" name="item" id="item_1_3"/>
															<label for="item_1_3">
																<span class="folder"></span> List 3<input type="button" value="항목추가">
															</label>
														</li>
													</ul>
													<!-- 세번째 추가 하위카테고리가 있을때 ism_dep 추가 -->
												</li>
												<li class="list sub_list depth_last_list" style="border-top: 0;">
													<input type="checkbox" name="item" id="item2_3" checked/>   
													<label for="item2_3">
														<span class="folder"></span> List 3<input type="button" value="항목추가">
													</label>
												</li>
											</ul>
										</li>
										<li class="list">
											<input type="checkbox" name="item" id="item3" />   
											<label for="item3">
												<span class="folder"></span> List 3<input type="button" value="항목추가">
												<span class="arw"></span>
											</label>
											<ul class="options items">
												<li class="list sub_list depth_last_list" style="border-top: 0;">
													<input type="checkbox" name="item" id="item3_1" checked/>   
													<label for="item3_1">
														<span class="folder"></span> List 1<input type="button" value="항목추가">
													</label>
												</li>
												<li class="list sub_list depth_last_list" style="border-top: 0;">
													<input type="checkbox" name="item" id="item3_2" checked/>   
													<label for="item3_2">
														<span class="folder"></span> List 2<input type="button" value="항목추가">
													</label>
												</li>
											</ul>
										</li>
										<li class="list">
											<input type="checkbox" name="item" id="item4" />   
											<label for="item4">
												<span class="folder"></span> List 4<input type="button" value="항목추가">
												<span class="arw"></span>
											</label>
											<ul class="options items">
												<li class="list sub_list depth_last_list" style="border-top: 0;">
													<input type="checkbox" name="item" id="item4_1" checked/>   
													<label for="item4_1">
														<span class="folder"></span> List 1<input type="button" value="항목추가">
													</label>
												</li>
												<li class="list sub_list depth_last_list" style="border-top: 0;">
													<input type="checkbox" name="item" id="item4_2" checked/>   
													<label for="item4_2">
														<span class="folder"></span> List 2<input type="button" value="항목추가">
													</label>
												</li>
											</ul>
										</li>
										<li class="list">
											<input type="checkbox" name="item" id="item5" />   
											<label for="item5">
												<span class="folder"></span> List 5<input type="button" value="항목추가">
												<span class="arw"></span>
											</label>
											<ul class="options items">
												<li class="list sub_list depth_last_list" style="border-top: 0;">
													<input type="checkbox" name="item" id="item5_1" checked/>   
													<label for="item5_1">
														<span class="folder"></span> List 1<input type="button" value="항목추가">
													</label>
												</li>
											</ul>
										</li>
									</ul>
								</div>
								
								
								
								<p class="align-r" style="font-size: 12px; margin-top: 10px;">선택한 카테고리 이동
									<button type="button" class="btn-icon" style="margin-left: 4px;">▲</button>
									<button type="button" class="btn-icon">▼</button>
								</p>
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
												<th>상위 카테고리</th>
												<td>생활</td>
											</tr>
											<tr>
												<th>분류번호</th>
												<td>2-155</td>
											</tr>							
											<tr>
												<th>카테고리 이름</th>
												<td><input type="text" class="width-xl" name="title" value="생활" style="line-height: 31px;"></td>
											</tr>
											<tr>
												<th>접근권한</th>
												<td>
													<select name="access_level">
														<option value="5" selected="">레벨5</option>
														<option value="4">레벨4</option>
														<option value="3">레벨3</option>
														<option value="2">레벨2</option>
														<option value="1">레벨1</option>
													</select>
												</td>
											</tr>
											<tr>
												<th>사용 여부</th>
												<td>
													<input type="radio" name="display" id="cate_show" checked=""><label for="cate_show" style="margin-right: 20px;">사용</label>
													<input type="radio" name="display" id="cate_hide"><label for="cate_hide">미사용</label>
												</td>
											</tr>
											<tr>
												<th>서브비주얼</th>
												<td>
													<p style="margin-bottom: 5px;"><small>권장사이즈 : 1920 x 397 px</small></p>
													<div>
														<p class="file" style="width:250px;">
															<input type="file" name="img1" id="prod_thumb">
															<label for="prod_thumb">파일찾기</label>
														</p>
														<p class="float-l"><button type="button">삭제</button></p>
													</div>
												</td>
											</tr>
											<tr>
												<th>제품리스트 배너</th>
												<td>
													<p style="margin-bottom: 5px;"><small>권장사이즈 : 1200 * 225 px</small></p>
													<div>
														<p class="file" style="width:250px;">
															<input type="file" id="prod_thumb3">
															<label for="prod_thumb3">파일찾기</label>
														</p>
													</div>
												</td>
											</tr>
											<tr>
												<th>모바일 제품리스트 배너</th>
												<td>
													<p style="margin-bottom: 5px;"><small>권장사이즈 : 1200 * 225 px</small></p>
													<div>
														<p class="file" style="width:250px;">
															<input type="file" id="prod_thumb5">
															<label for="prod_thumb5">파일찾기</label>
														</p>
													</div>
												</td>
											</tr>
											<tr>
												<th>분류설명</th>
												<td><textarea name="content" cols="30" rows="3"></textarea></td>
											</tr>
											<tr>
												<th>카테고리 삭제</th>
												<td>
													<button type="button" class="btn-alert btn-sm">삭제</button>
													<span class="ft-red" style="margin-left: 5px !important; font-size: 12px;">삭제하신 카테고리는 복구가 불가합니다.</span>
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

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/ism/include/footer.php";

@ $rs->free();
?>