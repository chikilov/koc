			<!-- 검색영역 -->
			<div class="searchArea">
				<table class="line1Tb">
					<colgroup>
						<col width="" />
						<col width="" />
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
								<input type="text" class="inputTit" title="" name="searchValue" id="searchValue" value="<?php if ( $searchValue != "" && $searchValue != null ) echo $searchValue; ?>" />
								<a href="javascript:void(0);" class="btn_basic" id="btn_search">조회</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--// 검색영역 -->
