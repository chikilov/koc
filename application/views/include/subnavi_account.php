<div class="section">
			<h2>계정관리 - 상세정보</h2>
			<?php include APPPATH."views/include/searchinfo_account.php"; ?>
			<?php include APPPATH."views/include/accountinfo_account.php"; ?>
			<!--subNavi-->
			<ul class="subNav">
				<li><a href="<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/view/0<?php if ( $searchParam != "" && $searchParam != null ) echo "/".$searchParam; ?><?php if ( $searchValue != "" && $searchValue != null ) echo "/".$searchValue; ?>">보유기체</a></li>
				<li><a href="<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/view/1<?php if ( $searchParam != "" && $searchParam != null ) echo "/".$searchParam; ?><?php if ( $searchValue != "" && $searchValue != null ) echo "/".$searchValue; ?>">보유장비</a></li>
				<li><a href="<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/view/2<?php if ( $searchParam != "" && $searchParam != null ) echo "/".$searchParam; ?><?php if ( $searchValue != "" && $searchValue != null ) echo "/".$searchValue; ?>">보유파일럿</a></li>
				<li><a href="<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/view/3<?php if ( $searchParam != "" && $searchParam != null ) echo "/".$searchParam; ?><?php if ( $searchValue != "" && $searchValue != null ) echo "/".$searchValue; ?>">오퍼레이터</a></li>
				<li><a href="<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/view/4<?php if ( $searchParam != "" && $searchParam != null ) echo "/".$searchParam; ?><?php if ( $searchValue != "" && $searchValue != null ) echo "/".$searchValue; ?>">플레이정보</a></li>
				<li><a href="<?php echo URLBASE; ?>index.php/pages/admin/accountdetail/view/5<?php if ( $searchParam != "" && $searchParam != null ) echo "/".$searchParam; ?><?php if ( $searchValue != "" && $searchValue != null ) echo "/".$searchValue; ?>">친구정보</a></li>
			</ul>
			<!--//subNavi-->
