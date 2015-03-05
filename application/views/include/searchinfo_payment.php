			<!-- 검색영역 -->
			<div class="searchArea">
				<table class="line1Tb">
					<colgroup>
						<col width="300" />
						<col width="50" />
						<col width="300" />
						<col width="80" />
						<col width="" />
					</colgroup>
					<tbody>
						<tr>
							<td>
								<select name="searchParam" id="searchParam">
									<option value="pid"<?php if ( $searchParam == "pid" ) echo " selected=\"true\""; ?>>PID</option>
									<option value="id"<?php if ( $searchParam == "id" ) echo " selected=\"true\""; ?>>이메일</option>
									<option value="nm"<?php if ( $searchParam == "nm" ) echo " selected=\"true\""; ?>>닉네임</option>
								</select>
								<input type="text" class="inputTit" title="" name="searchValue" id="searchValue" value="<?php if ( $searchValue != "" && $searchValue != null ) echo $searchValue; ?>" >
							</td>
							<th><label for="kakaoId">기간</label></th>
							<td>
								<input type="text" id="start_date" name="start_date" class="inputTit datepicker" title="" style="width:100px;" value="" />
								<input type="text" id="end_date" name="end_date" class="inputTit datepicker lastDate" title="" style="width:100px" value="" />
							</td>
<?php
	if ( array_key_exists( "REQUEST_URI", $_SERVER ) )
	{
		if ( strpos( $_SERVER["REQUEST_URI"], "/chargemanage/" ) )
		{
?>
							<th><label for="kakaoId">스토어</label></th>
							<td>
								<select name="platform" id="platform">
									<option value="NAVER" <?php if ( $platform == "NAVER" ) echo " selected=\"true\""; ?>>Naver</option>
									<!--<option value="ANDROID" <?php if ( $platform == "ANDROID" ) echo " selected=\"true\""; ?>>Android</option>
									<option value="IOS" <?php if ( $platform == "IOS" ) echo " selected=\"true\""; ?>>IOS</option>-->
								</select>
							</td>
<?php
		}
	}
?>
							<td class="searchBtn alignL"><a href="javascript:void(0);" id="btn_search" class="btn_basic">조회</a></td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--// 검색영역 -->
