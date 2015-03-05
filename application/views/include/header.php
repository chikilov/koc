	<!-- lnb :: li class="on" 활성화-->
	<div id="lnb">
		<ul>
			<li><a href="javascript:void(0);">계정관리</a>
				<ul>
<?php
	if ( isset($searchParam) && isset($searchValue) )
	{
?>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/accountbasic/view<?php if ( $searchParam != "" && $searchParam != null ) echo "/".$searchParam; ?><?php if ( $searchValue != "" && $searchValue != null ) echo "/".$searchValue; ?>">기본 정보</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/accountdetail/view/0<?php if ( $searchParam != "" && $searchParam != null ) echo "/".$searchParam; ?><?php if ( $searchValue != "" && $searchValue != null ) echo "/".$searchValue; ?>">상세 정보</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/accountblock/view<?php if ( $searchParam != "" && $searchParam != null ) echo "/".$searchParam; ?><?php if ( $searchValue != "" && $searchValue != null ) echo "/".$searchValue; ?>">제제 관리(미)</a></li>
<?php
	}
	else
	{
?>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/accountbasic/view">기본 정보</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/accountdetail/view/0">상세 정보</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/accountblock/view">제제 관리(미)</a></li>
<?php
	}
?>
				</ul>
			</li>
			<li><a href="javascript:void(0);">결제관리</a>
				<ul>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/chargemanage/view">충전 내역</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/chargehistory/view">게임 거래내역</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/chargecoupon/view">쿠폰 관리</a></li>
				</ul>
			</li>
			<li><a href="javascript:void(0);">공지관리</a>
				<ul>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/noticemanage/view/0">공지사항</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/noticemanage/view/1">이벤트</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/noticemanage/view/2">이미지 배너</a></li>
				</ul>
			</li>
			<li><a href="javascript:void(0);">랭킹</a>
				<ul>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/rankthisweek/view/0">실시간 랭킹(미)</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/rankprevweek/view/0">지난 랭킹 조회(미)</a></li>
				</ul>
			</li>
			<li><a href="javascript:void(0);">로그 조회</a>
				<ul>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/loggame/view">게임 이용 로그(미)</a></li>
				</ul>
			</li>
			<li><a href="javascript:void(0);">이벤트 관리</a>
				<ul>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/eventmanage/view/0">이벤트 설정</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/eventpresent/view">지급 관리</a></li>
				</ul>
			</li>
			<li class="itemOne"><a href="#">통계(미)</a>
			</li>
			<li><a href="javascript:void(0);">권한 관리</a>
				<ul>
					<!--<li><a href="#">ID생성</a></li>-->
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/adminmanage/view">아이디 관리(미)</a></li>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/adminlog/view">로그 조회(미)</a></li>
				</ul>
			</li>
			<li><a href="javascript:void(0);">가차 관리</a>
				<ul>
					<li><a href="<?php echo URLBASE;?>index.php/pages/admin/gatchamanage/view">가차 정보</a></li>
				</ul>
			</li>
			<li class="itemOne"><a href="<?php echo URLBASE;?>index.php/pages/admin/login/logout">로그아웃</a>
		</ul>
	</div>
	<!--// lnb -->
