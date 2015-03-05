<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 5;
			var Smenu = 0;
			var subNav = <?php echo $subnavi; ?>;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");
			if ( $(".subNav") )
			{
				$(".subNav > li").eq(subNav).addClass("on");
			}
		});
	</script>
</head>
<body>
<!-- wrap -->
<div id="wrap">
	<?php include_once  APPPATH."views/include/header.php"; ?>
	<!-- contents -->
	<div id="contents" class="contents">
		<!--section-->
		<?php include_once  APPPATH."views/include/subnavi_event.php"; ?>
			<!--container-->
			<div class="container">
				<div class="board_view_st alignL">
					<table>
						<colgroup>
							<col width="15%" />
							<col width="" />
							<col width="9%" />
							<col width="" />
						</colgroup>
						<tbody>
							<tr>
								<th>시작일</th>
								<td colspan="3">
									<input type="text" class="inputTit datepicker" title="" style="width:100px" /> &nbsp;&nbsp;
									<span class="timePicker">
										<select name="" id="">
											<option value="">1</option>
											<option value="">2</option>
											<option value="">3</option>
											<option value="">4</option>
											<option value="">5</option>
											<option value="">6</option>
											<option value="">7</option>
											<option value="">8</option>
											<option value="">9</option>
											<option value="">10</option>
											<option value="">11</option>
											<option value="">12</option>
											<option value="">13</option>
											<option value="">14</option>
											<option value="">15</option>
											<option value="">16</option>
											<option value="">17</option>
											<option value="">18</option>
											<option value="">19</option>
											<option value="">20</option>
											<option value="">21</option>
											<option value="">22</option>
											<option value="">23</option>
											<option value="">24</option>
										</select> 시
										<select name="" id="">
											<option value="">00</option>
											<option value="">01</option>
											<option value="">02</option>
											<option value="">03</option>
											<option value="">04</option>
											<option value="">05</option>
											<option value="">06</option>
											<option value="">07</option>
											<option value="">08</option>
											<option value="">09</option>
											<option value="">10</option>
											<option value="">11</option>
											<option value="">12</option>
											<option value="">13</option>
											<option value="">14</option>
											<option value="">15</option>
											<option value="">16</option>
											<option value="">17</option>
											<option value="">18</option>
											<option value="">19</option>
											<option value="">20</option>
											<option value="">21</option>
											<option value="">22</option>
											<option value="">23</option>
											<option value="">24</option>
											<option value="">25</option>
											<option value="">26</option>
											<option value="">27</option>
											<option value="">28</option>
											<option value="">29</option>
											<option value="">30</option>
											<option value="">31</option>
											<option value="">32</option>
											<option value="">33</option>
											<option value="">34</option>
											<option value="">35</option>
											<option value="">36</option>
											<option value="">37</option>
											<option value="">38</option>
											<option value="">39</option>
											<option value="">40</option>
											<option value="">41</option>
											<option value="">42</option>
											<option value="">43</option>
											<option value="">44</option>
											<option value="">45</option>
											<option value="">46</option>
											<option value="">47</option>
											<option value="">48</option>
											<option value="">49</option>
											<option value="">50</option>
											<option value="">51</option>
											<option value="">52</option>
											<option value="">53</option>
											<option value="">54</option>
											<option value="">55</option>
											<option value="">56</option>
											<option value="">57</option>
											<option value="">58</option>
											<option value="">59</option>
										</select> 분
									</span>
								</td>
							</tr>
							<tr>
								<th>종료일</th>
								<td colspan="3">
									<input type="text" class="inputTit datepicker" title="" style="width:100px" /> &nbsp;&nbsp;
									<span class="timePicker">
										<select name="" id="">
											<option value="">1</option>
											<option value="">2</option>
											<option value="">3</option>
											<option value="">4</option>
											<option value="">5</option>
											<option value="">6</option>
											<option value="">7</option>
											<option value="">8</option>
											<option value="">9</option>
											<option value="">10</option>
											<option value="">11</option>
											<option value="">12</option>
											<option value="">13</option>
											<option value="">14</option>
											<option value="">15</option>
											<option value="">16</option>
											<option value="">17</option>
											<option value="">18</option>
											<option value="">19</option>
											<option value="">20</option>
											<option value="">21</option>
											<option value="">22</option>
											<option value="">23</option>
											<option value="">24</option>
										</select> 시
										<select name="" id="">
											<option value="">00</option>
											<option value="">01</option>
											<option value="">02</option>
											<option value="">03</option>
											<option value="">04</option>
											<option value="">05</option>
											<option value="">06</option>
											<option value="">07</option>
											<option value="">08</option>
											<option value="">09</option>
											<option value="">10</option>
											<option value="">11</option>
											<option value="">12</option>
											<option value="">13</option>
											<option value="">14</option>
											<option value="">15</option>
											<option value="">16</option>
											<option value="">17</option>
											<option value="">18</option>
											<option value="">19</option>
											<option value="">20</option>
											<option value="">21</option>
											<option value="">22</option>
											<option value="">23</option>
											<option value="">24</option>
											<option value="">25</option>
											<option value="">26</option>
											<option value="">27</option>
											<option value="">28</option>
											<option value="">29</option>
											<option value="">30</option>
											<option value="">31</option>
											<option value="">32</option>
											<option value="">33</option>
											<option value="">34</option>
											<option value="">35</option>
											<option value="">36</option>
											<option value="">37</option>
											<option value="">38</option>
											<option value="">39</option>
											<option value="">40</option>
											<option value="">41</option>
											<option value="">42</option>
											<option value="">43</option>
											<option value="">44</option>
											<option value="">45</option>
											<option value="">46</option>
											<option value="">47</option>
											<option value="">48</option>
											<option value="">49</option>
											<option value="">50</option>
											<option value="">51</option>
											<option value="">52</option>
											<option value="">53</option>
											<option value="">54</option>
											<option value="">55</option>
											<option value="">56</option>
											<option value="">57</option>
											<option value="">58</option>
											<option value="">59</option>
										</select> 분
									</span>
								</td>
							</tr>
							<tr>
								<th>매일보상 상품명</th>
								<td>
									<select name="" id="">
										<option value="">고급기체뽑기</option>
									</select>
									<!--고급기체뽑기
									일반기체뽑기
									고급무기뽑기
									일반무기뽑기
									-->
								</td>
								<th>개수</th>
								<td><input type="text" style="width:50px" value="10" />개 <a href="#" class="btn_action" onclick="$('#creatAlert1').show()">적용</a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!--container-->
			<div class="container">
				<h3>연락출석</h3>
				<div class="fLBox" style="width:50%;">
					<div class="board_view_st alignL">
						<table>
							<colgroup>
								<col width="20%" />
								<col width="" />
							</colgroup>
							<thead>
								<tr>
									<th>일자</th>
									<th>보상</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type="text" value="1" style="width:50px" /> 일</td>
									<td>
										<ul>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
											</li>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
											</li>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
											</li>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
												<a href="#" class="btn_action smBtn addUiBtn">추가</a>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td><input type="text" value="2" style="width:50px;" /> 일</td>
									<td>
										<ul>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
												<a href="#" class="btn_action smBtn addUiBtn">추가</a>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td><input type="text" value="3" style="width:50px;" /> 일</td>
									<td>
										<ul>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
												<a href="#" class="btn_action smBtn addUiBtn">추가</a>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td><input type="text" value="4" style="width:50px;" /> 일</td>
									<td>
										<ul>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
												<a href="#" class="btn_action smBtn addUiBtn">추가</a>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td><input type="text" value="4" style="width:50px;" /> 일</td>
									<td>
										<ul>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
												<a href="#" class="btn_action smBtn addUiBtn">추가</a>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td><input type="text" value="5" style="width:50px;" /> 일</td>
									<td>
										<ul>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
												<a href="#" class="btn_action smBtn addUiBtn">추가</a>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td><input type="text" value="6" style="width:50px;" /> 일</td>
									<td>
										<ul>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
												<a href="#" class="btn_action smBtn addUiBtn">추가</a>
											</li>
										</ul>
									</td>
								</tr>
								<tr>
									<td><input type="text" value="7" style="width:50px;" /> 일</td>
									<td>
										<ul>
											<li>
												<select name="" id="" style="width:80%">
													<option value="">뽑기권1</option>
												</select>
												<a href="#" class="btn_action smBtn addUiBtn">추가</a>
											</li>
										</ul>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="btnArea alignR"><a href="#" class="btn_action Lbtn" onclick="$('#creatAlert1').show()">적용</a></div>
				</div>
			</div>
			<!--//container-->
			<!--container-->
			<div class="container">
				<h3>이벤트 리스트</h3>
				<!-- board_list -->
				<div class="board_list">
					<table>
						<colgroup>
							<col width="50" />
							<col />
							<col />
							<col />
							<col />
							<col width="80" />
						</colgroup>
						<thead>
							<tr>
								<th class="bb2"></th>
								<th class="bb2">등록일</th>
								<th class="bb2">시작일</th>
								<th class="bb2">종료일</th>
								<th class="bb2">내용</th>
								<th class="bb2"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>뽑기/고급기체뽑기/10%</td>
								<td class="alignC"><a href="#" class="btn_action">중지</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>강화/-/10%</td>
								<td class="alignC"><a href="#" class="btn_basic">종료</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td></td>
								<td class="alignC"><a href="#" class="btn_basic">종료</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td></td>
								<td class="alignC"><a href="#" class="btn_basic">종료</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td></td>
								<td class="alignC"><a href="#" class="btn_basic">종료</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td></td>
								<td class="alignC"><a href="#" class="btn_basic">종료</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td></td>
								<td class="alignC"><a href="#" class="btn_basic">종료</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td></td>
								<td class="alignC"><a href="#" class="btn_basic">종료</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td></td>
								<td class="alignC"><a href="#" class="btn_basic">종료</a></td>
							</tr>
							<tr>
								<td class="alignC"><input type="checkbox" /></td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td>1231-12-12 12:12:12</td>
								<td></td>
								<td class="alignC"><a href="#" class="btn_basic">종료</a></td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>
			<!--//container-->
			<!-- Paging -->
			<div class="paging">
				<a href="#" class="prev" title="이전"></a>
				<ul>
					<li><strong>1</strong></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li><a href="#">6</a></li><li><a href="#">7</a></li><li><a href="#">8</a></li><li><a href="#">9</a></li><li><a href="#">...</a></li><li class="lastNum"><a href="#">101</a></li>
				</ul>
				<a href="#" class="next" title="다음"></a>
			</div>
			<!-- //Paging -->
		</div>
		<!--//section-->
	</div>
	<!--//contents -->

	<div class="alertPop" style="display:none; top:300px; left:50%; margin-left:-50px" id="creatAlert1">
		<p>적용 사유를 적으세요.</p>
		<input type="text" />
		<div class="btnArea alignC">
			<a href="#" class="btn_action sm addUiBtn" onclick="$('#creatAlert2').show()">확인</a>
			<a href="#" class="btn_basic sm alertClose">취소</a>
		</div>
	</div>
	<div class="alertPop" style="display:none; top:300px; left:50%; margin-left:-35px" id="creatAlert2">
		<p>적용 하시겠습니까?</p>
		<div class="btnArea alignC">
			<a href="#" class="btn_action sm addUiBtn">확인</a>
			<a href="#" class="btn_basic sm alertClose">취소</a>
		</div>
	</div>

</div>
</body>
</html>
