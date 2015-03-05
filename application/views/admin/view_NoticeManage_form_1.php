<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include_once APPPATH."views/include/metainfo.php"; ?>
	<script type="text/javascript">
		$(document).ready(function () {
			var Lmenu = 2;
			var Smenu = 1;
			$("#lnb > ul > li").eq(Lmenu).addClass("on");
			$("#lnb > ul > li").eq(Lmenu).children("ul").children("li").eq(Smenu).addClass("on");

			$("#content_type").change(function () {
				if ( $(this).val() == "text" )
				{
					$("#ptext").show();
					$("#spanoci").hide();
					$("#spanci").hide();
					$("#spanlnk").hide()
				}
				else if ( $(this).val() == "link" )
				{
					$("#ptext").hide();
					$("#spanoci").hide();
					$("#spanci").hide();
					$("#spanlnk").show()
				}
				else
				{
					$("#ptext").hide();
					$("#spanoci").show();
					$("#spanci").show();
					$("#spanlnk").hide()
				}
			});

			$(document).on("click", "a[name=\"editThumbnail\"]", function () {
				$(this).parent().parent().children("p").toggle();
			});

			$(document).on("click", "a[name=\"editContentImage\"]", function () {
				$(this).parent().parent().children("p").toggle();
			});

			$("#btnUpload").click(function () {
				if ( $("#upload_image")[0].files.length )
				{

					var data = new FormData();
					$.each($("#upload_image")[0].files, function(i, file) {
			            data.append("upload_image", file);
			        });

					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/uploadImage/2",
						processData: false,
						contentType: false,
						dataType:"json",
						type:"POST",
						async:false,
						data: data,
						success:function(data)
						{
							var obj = eval(data);
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								$("#content_image").val(obj.arrResult.file_name);
								alert("업로드 되었습니다.");
							}
							else
							{
								alert(obj.resultText);
							}
						},
						error:function( request, status, error )
						{
				        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				       	}
					});
				}
				else
				{
					alert("파일이 없습니다.");
					return;
				}
			});

			$("#btnPreview").click(function () {
				if ( $("#event_title").val() == "" || $("#event_title").val() == null )
				{
					alert("제목");
					$("#event_title").focus();
					return;
				}
				if ( $("#content_type").val() == "image" )
				{
					if ( $("#content_image").val() == "" || $("#content_image").val() == null )
					{
						alert("업로드 후에 미리보기가 가능합니다.");
						$("#upload_image").focus();
						return;
					}
				}
				else
				{
					if ( $("#content_text").val() == "" || $("#content_text").val() == null )
					{
						alert("텍스트 입력 후에 미리보기가 가능합니다.");
						$("#content_text").focus();
						return;
					}
				}

				window.open("<?php echo URLBASE; ?>index.php/pages/notice/listnotice/preview/<?php echo $subnavi?>/", "previewpopup", "width=1200, height=600");
			});

			$("#btnInsert").click(function () {
				if ( $("#event_title").val() == "" || $("#event_title").val() == null )
				{
					alert("제목");
					$("#event_title").focus();
					return;
				}
/*
				if ( $("#thumbnail").val() == "" || $("#thumbnail").val() == null )
				{
					alert("썸네일");
					$("#thumbnail").focus();
					return;
				}
*/
				if ( $("#start_date").val() == "" || $("#start_date").val() == null )
				{
					alert("시작일");
					$("#start_date").focus();
					return;
				}

				if ( $("#start_hour").val() == "" || $("#start_hour").val() == null )
				{
					alert("시작시간");
					$("#start_hour").focus();
					return;
				}

				if ( $("#start_min").val() == "" || $("#start_min").val() == null )
				{
					alert("시작시간");
					$("#start_min").focus();
					return;
				}

				if ( $("#event_target").val() == "" || $("#event_target").val() == null )
				{
					alert("대상");
					$("#event_target").focus();
					return;
				}

				if ( $("#end_date").val() == "" || $("#end_date").val() == null )
				{
					alert("종료일");
					$("#end_date").focus();
					return;
				}

				if ( $("#end_hour").val() == "" || $("#end_hour").val() == null )
				{
					alert("종료시간");
					$("#end_hour").focus();
					return;
				}

				if ( $("#end_min").val() == "" || $("#end_min").val() == null )
				{
					alert("종료시간");
					$("#end_min").focus();
					return;
				}

				if ( $("#content_type").val() == "" || $("#content_type").val() == null )
				{
					alert("내용타입");
					$("#content_type").focus();
					return;
				}

				if ( ( $("#content_text").val() == "" || $("#content_text").val() == null ) && ( $("#content_image").val() == "" || $("#content_image").val() == null ) && ( $("#content_link").val() == "" || $("#content_link").val() == null ) )
				{
					alert("내용");
					$("#content_text:visible").focus();
					$("#content_image:visible").focus();
					$("#content_link:visible").focus();
					return;
				}

				var data = new FormData();
				$.each($('#thumbnail')[0].files, function(i, file) {
		            data.append("thumbnail", file);
		        });

				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/uploadImage/3",
					processData: false,
					contentType: false,
					dataType:"json",
					type:"POST",
					async:false,
					data: data,
					success:function(data)
					{
						var obj = eval(data);
						if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
						{
							insertEvent( obj.arrResult.file_name );
						}
						else
						{
							alert(obj.resultText);
						}
					},
					error:function( request, status, error )
					{
			        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			       	}
				});
			});

			$("#btnUpdate").click(function () {
				if ( $("#event_title").val() == "" || $("#event_title").val() == null )
				{
					alert("제목");
					$("#event_title").focus();
					return;
				}

				if ( $("#thumbnail:visible").length > 0 )
				{
					if ( $("#thumbnail").val() == "" || $("#thumbnail").val() == null )
					{
						alert("썸네일");
						$("#thumbnail").focus();
						return;
					}
				}
				else
				{
					if ( $("#othumbnail").val() == "" || $("#othumbnail").val() == null )
					{
						alert("썸네일");
						$("#thumbnail").focus();
						return;
					}
				}

				if ( $("#start_date").val() == "" || $("#start_date").val() == null )
				{
					alert("시작일");
					$("#start_date").focus();
					return;
				}

				if ( $("#start_hour").val() == "" || $("#start_hour").val() == null )
				{
					alert("시작시간");
					$("#start_hour").focus();
					return;
				}

				if ( $("#start_min").val() == "" || $("#start_min").val() == null )
				{
					alert("시작시간");
					$("#start_min").focus();
					return;
				}

				if ( $("#event_target").val() == "" || $("#event_target").val() == null )
				{
					alert("대상");
					$("#event_target").focus();
					return;
				}

				if ( $("#end_date").val() == "" || $("#end_date").val() == null )
				{
					alert("종료일");
					$("#end_date").focus();
					return;
				}

				if ( $("#end_hour").val() == "" || $("#end_hour").val() == null )
				{
					alert("종료시간");
					$("#end_hour").focus();
					return;
				}

				if ( $("#end_min").val() == "" || $("#end_min").val() == null )
				{
					alert("종료시간");
					$("#end_min").focus();
					return;
				}

				if ( $("#content_type").val() == "" || $("#content_type").val() == null )
				{
					alert("내용타입");
					$("#content_type").focus();
					return;
				}

				if ( $("#content_type").val() == "text" )
				{
					if ( $("#content_text").val() == "" || $("#content_text").val() == null )
					{
						alert("내용");
						$("#content_text").focus();
						return;
					}
				}
				else
				{
					if ( $("#upload_image:visible").length > 0 )
					{
						if ( $("#content_image").val() == "" || $("#content_image").val() == null )
						{
							alert("내용");
							$("#content_image").focus();
							return;
						}
					}
					else if ( $("#content_text:visible").length > 0 )
					{
						if ( $("#content_text").val() == "" || $("#content_text").val() == null )
						{
							alert("내용");
							$("#content_text").focus();
							return;
						}
					}
					else
					{
						if ( $("#content_link").val() == "" || $("#content_link").val() == null )
						{
							alert("내용");
							$("#content_link").focus();
							return;
						}
					}
				}

				if ( $("#thumbnail:visible").length > 0 )
				{
					var data = new FormData();
					$.each($("#thumbnail")[0].files, function(i, file) {
			            data.append("thumbnail", file);
			        });

					$.ajax({
						url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/uploadImage/3",
						processData: false,
						contentType: false,
						dataType:"json",
						type:"POST",
						async:false,
						data: data,
						success:function(data)
						{
							var obj = eval(data);
							if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
							{
								updateEvent( obj.arrResult.file_name );
							}
							else
							{
								alert(obj.resultText);
							}
						},
						error:function( request, status, error )
						{
				        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				       	}
					});
				}
				else
				{
					updateEvent( $("#othumbnail").val() );
				}
			});

			if ( <?php echo $idx?> != "" && <?php echo $idx?> != null && <?php echo $idx?> != 0 )
			{
				$.ajax({
					url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/requestEventListModi",
					type:"POST",
					dataType:"json",
					async:false,
					data: {"data":"\{\"idx\":\"<?php echo $idx?>\"\}"},
					success:function(data)
					{
						var obj = eval(data);
						if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
						{
							$("#event_title").val(obj.arrResult[0].event_title);
							$("#othumbnail").val(obj.arrResult[0].thumbnail);
							$("#othumbnail").parent().html("<a href=\"javascript:showthumb(\'" + obj.arrResult[0].thumbnail + "\');\">" + obj.arrResult[0].thumbnail + "</a>" + $("#othumbnail").parent().html());
							$("#start_date").val(obj.arrResult[0].start_date.substring(0, 10));
							$("#start_hour").val(obj.arrResult[0].start_date.substring(11, 13));
							$("#start_min").val(obj.arrResult[0].start_date.substring(14, 16));
							$("#end_date").val(obj.arrResult[0].end_date.substring(0, 10));
							$("#end_hour").val(obj.arrResult[0].end_date.substring(11, 13));
							$("#end_min").val(obj.arrResult[0].end_date.substring(14, 16));
							$("#event_target").val(obj.arrResult[0].event_target);
							$("#content_type").val(obj.arrResult[0].content_type);
							if ( obj.arrResult[0].content_type == "image" )
							{
								$("#content_type").parent().children("p").toggle();
								$("#ocontent_image").val(obj.arrResult[0].content_image);
								$("#ocontent_image").parent().html("<a href=\"javascript:showimage(\'" + obj.arrResult[0].content_image + "\');\">" + obj.arrResult[0].content_image + "</a>" + $("#ocontent_image").parent().html());
							}
							else if ( obj.arrResult[0].content_type == "text" )
							{
								$("#ptext").show();
								$("#spanoci").hide();
								$("#spanci").hide();
								$("#content_text").val(obj.arrResult[0].content_text.replace(/<br \/>/gi, "\n"));
							}
							else
							{
								$("#ptext").hide();
								$("#spanoci").hide();
								$("#spanci").hide();
								$("#spanlnk").show()
								$("#content_link").val(obj.arrResult[0].content_link);
							}
						}
						else
						{
							alert(obj.resultText);
						}
					},
					error:function( request, status, error )
					{
			        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			       	}
				});
			}
		});

		function insertEvent( file_name )
		{
			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/requestEventListInsert",
				type:"POST",
				dataType:"json",
				async:false,
				data: {"data":"\{\"event_title\":\"" + $("#event_title").val() + "\", \"thumbnail\":\"" + file_name + "\", \"start_date\":\"" + $("#start_date").val() + " " + $("#start_hour > option:selected").val() + ":" + $("#start_min > option:selected").val() + ":00\",\"end_date\":\"" + $("#end_date").val() + " " + $("#end_hour > option:selected").val() + ":" + $("#end_min > option:selected").val() + ":00\",\"event_target\":\"" + $("#event_target").val() + "\",\"content_type\":\"" + $("#content_type > option:selected").val() + "\",\"content_text\":\"" + $("#content_text").val().replace(/\n/gi, "<br />") + "\", \"content_image\":\"" + $("#content_image").val() + "\", \"content_link\":\"" + $("#content_link").val() + "\"\}"},
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						alert("등록되었습니다.");
						window.location = "<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/view/1";
					}
					else
					{
						alert("1" + obj.resultText);
					}
				},
				error:function( request, status, error )
				{
		        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		       	}
			});
		}

		function updateEvent( file_name )
		{
			if ( $("#thumbnail:visible").length > 0 )
			{
				file_name = file_name;
			}
			else
			{
				file_name = $("#othumbnail").val();
			}

			if ( $("#upload_image:visible").length > 0 )
			{
				alert($("#content_image").val());
				var file_name2 = $("#content_image").val();
			}
			else
			{
				var file_name2 = $("#ocontent_image").val();
			}

			$.ajax({
				url:"<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/requestEventListUpdate",
				type:"POST",
				dataType:"json",
				async:false,
				data: {"data":"\{\"event_title\":\"" + $("#event_title").val() + "\", \"thumbnail\":\"" + file_name + "\", \"start_date\":\"" + $("#start_date").val() + " " + $("#start_hour > option:selected").val() + ":" + $("#start_min > option:selected").val() + ":00\",\"end_date\":\"" + $("#end_date").val() + " " + $("#end_hour > option:selected").val() + ":" + $("#end_min > option:selected").val() + ":00\",\"event_target\":\"" + $("#event_target").val() + "\",\"content_type\":\"" + $("#content_type > option:selected").val() + "\",\"content_text\":\"" + $("#content_text").val().replace(/\n/gi, "<br />") + "\", \"content_image\":\"" + file_name2 + "\", \"content_link\":\"" + $("#content_link").val() + "\",\"idx\":\"<?php echo $idx?>\"\}"},
				success:function(data)
				{
					var obj = eval(data);
					if ( obj.resultCd == "<?php echo MY_Controller::STATUS_API_OK?>" )
					{
						alert("수정되었습니다.");
						window.location = "<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/view/1";
					}
					else
					{
						alert(obj.resultText);
					}
				},
				error:function( request, status, error )
				{
		        	alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		       	}
			});
		}

		function showthumb(url)
		{
			window.open("<?php echo URLBASE; ?>static/upload/event/thumbnail/" + url, "previewpopup", "width=1200, height=600");
		}

		function showimage(url)
		{
			window.open("<?php echo URLBASE; ?>static/upload/event/content/" + url, "previewpopup", "width=1200, height=600");
		}
	</script>
</head>
<body>
<!-- wrap -->
<div id="wrap">
	<?php include_once  APPPATH."views/include/header.php"; ?>
	<!-- contents -->
	<div id="contents" class="contents">
		<!--section-->
		<div class="section">
			<h2>공지관리 - 이벤트 공지</h2>
			<!-- 푸시알림 -->
			<div class="container">
			<!-- 이미지 공지 -->
			<div class="container">
				<div class="titArea">
					<h3>리스트 공지</h3>
				</div>
				<div class="board_view_st alignL">
					<table>
						<colgroup>
							<col width="10%" />
							<col width="" />
							<col width="10%" />
							<col width="40%" />
						</colgroup>
						<tbody>
							<tr>
								<th>제목</th>
								<td colspan="3"><input type="text" id="event_title" name="event_title" style="width: 96%;" /></td>
							</tr>
							<tr>
								<th>썸네일</th>
								<td colspan="3">
<?php
	if ( $idx )
	{
?>
									<p><input type="hidden" id="othumbnail" name="othumbnail" />&nbsp;<a name="editThumbnail" href="javascript:void(0);" class="btn_action">수정</a></p>
									<p style="display:none;"><input type="file" id="thumbnail" name="thumbnail" />&nbsp;<a name="editThumbnail" href="javascript:void(0);" class="btn_action">취소</a></p>
<?php
	}
	else
	{
?>
									<p style="display:none;"></p>
									<p><input type="file" id="thumbnail" name="thumbnail" /></p>
<?php
	}
?>
								</td>
							</tr>
							<tr>
								<th>시작일</th>
								<td>
									<input type="text" class="inputTit datepicker" name="start_date" id="start_date" style="width:100px" /> &nbsp;&nbsp;
									<span class="timePicker">
										<select name="start_hour" id="start_hour">
<?php
	for ( $i = 0; $i < 24; $i++ )
	{
		$strI = substr("0".$i, strlen( "0".$i ) - 2, 2);
		if ($strI == "00")
		{
			echo "<option value=\"".$strI."\" selected>".$strI."</option>\n";
		}
		else
		{
			echo "<option value=\"".$strI."\">".$strI."</option>\n";
		}
	}
?>
										</select> 시
										<select name="start_min" id="start_min">
<?php
	for ( $i = 0; $i < 60; $i++ )
	{
		$strI = substr("0".$i, strlen( "0".$i ) - 2, 2);
		if ($strI == "00")
		{
			echo "<option value=\"".$strI."\" selected>".$strI."</option>\n";
		}
		else
		{
			echo "<option value=\"".$strI."\">".$strI."</option>\n";
		}
	}
?>
										</select> 분
									</span>
								</td>
								<th>대상</th>
								<td>
									<select name="event_target" id="event_target">
										<option value="ALL">전체</option>
										<option value="AND">안드로이드</option>
										<option value="IOS">아이폰</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>종료일</th>
								<td colspan="3">
									<input type="text" class="inputTit datepicker" name="end_date" id="end_date" style="width:100px" /> &nbsp;&nbsp;
									<span class="timePicker">
										<select name="end_hour" id="end_hour">
<?php
	for ( $i = 0; $i < 24; $i++ )
	{
		$strI = substr("0".$i, strlen( "0".$i ) - 2, 2);
		if ($strI == "00")
		{
			echo "<option value=\"".$strI."\" selected>".$strI."</option>\n";
		}
		else
		{
			echo "<option value=\"".$strI."\">".$strI."</option>\n";
		}
	}
?>
										</select> 시
										<select name="end_min" id="end_min">
<?php
	for ( $i = 0; $i < 60; $i++ )
	{
		$strI = substr("0".$i, strlen( "0".$i ) - 2, 2);
		if ($strI == "00")
		{
			echo "<option value=\"".$strI."\" selected>".$strI."</option>\n";
		}
		else
		{
			echo "<option value=\"".$strI."\">".$strI."</option>\n";
		}
	}
?>
										</select> 분
									</span>
								</td>
							</tr>
							<tr>
								<th>내용</th>
								<td colspan="3">
									<select name="content_type" id="content_type">
										<option value="text" selected="true">텍스트</option>
										<option value="image">이미지</option>
										<option value="link">링크주소</option>
									</select><br /><br />
									<p id="ptext"><textarea name="content_text" id="content_text" class="textarea" style="width:97%; height:50px"></textarea></p>
<?php
	if ( $idx )
	{
?>
									<span id="spanoci"><p><input type="hidden" id="ocontent_image" name="ocontent_image" />&nbsp;<a name="editContentImage" href="javascript:void(0);" class="btn_action">수정</a></p>
									<p style="display:none;"><input type="file" id="upload_image" name="upload_image" />&nbsp;<a href="#" id="btnUpload" class="btn_action">업로드</a><input type="hidden" name="content_image" id="content_image" value="" />&nbsp;<a name="editContentImage" href="javascript:void(0);" class="btn_action">취소</a></p></span>
									<input type="hidden" name="content_image" id="content_image" value="" /></span>
									<span id="spanlnk" style="display: none;"><p><input type="text" style="width:400px;" id="content_link" name="content_link" /></p></span>
<?php
	}
	else
	{
?>
									<span id="spanci" style="display: none;"><p><input type="file" name="upload_image" id="upload_image" />&nbsp;<a href="#" id="btnUpload" class="btn_action"><span>업로드</span></a></p></span>
									<input type="hidden" name="content_image" id="content_image" value="" />
									<span id="spanlnk" style="display: none;"><p><input type="text" style="width:400px;" id="content_link" name="content_link" /></p></span>
<?php
	}
?>								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!--// 이미지 공지 -->
			<!-- Paging -->
			<div class="paging">
<?php
	if ( $idx )
	{
?>
				<div class="btnPosR"><a href="javascript:void(0);" id="btnUpdate" class="btn_action"><span>수정</span></a>&nbsp;<a href="javascript:void(0);" id="btnPreview" class="btn_action"><span>미리보기</span></a>&nbsp;<a href="<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/view/1" class="btn_action"><span>취소</span></a></div>
<?php
	}
	else
	{
?>
				<div class="btnPosR"><a href="javascript:void(0);" id="btnInsert" class="btn_action"><span>등록</span></a>&nbsp;<a href="javascript:void(0);" id="btnPreview" class="btn_action"><span>미리보기</span></a>&nbsp;<a href="<?php echo URLBASE; ?>index.php/pages/admin/noticemanage/view/1" class="btn_action"><span>취소</span></a></div>
<?php
	}
?>
			</div>
		</div>
		<!--//section-->
	</div>
	<!--//contents -->
</div>
</body>
</html>
