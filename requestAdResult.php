<?php
	$decoded = json_decode ( stripslashes ( $_REQUEST['snuid'] ), TRUE );

	if ( strcmp( $decoded['server'], '0' ) == 0 )
	{
		header( 'Location:http://'.$_SERVER['HTTP_HOST'].'/kocG/index.php/request/api/requestAdResult/?snuid='.$_REQUEST['snuid'].'&pub_tracking_data=&mac_address=&display_multiplier='.$_REQUEST['display_multiplier'].'&currency='.$_REQUEST['currency'].'&id='.$_REQUEST['id'].'&verifier='.$_REQUEST['verifier'] );
	}
	else if ( strcmp( $decoded['server'], '1' ) == 0 )
	{
		header( 'Location:http://'.$_SERVER['HTTP_HOST'].'/kocG/index.php/request/api/requestAdResult/?snuid='.$_REQUEST['snuid'].'&pub_tracking_data=&mac_address=&display_multiplier='.$_REQUEST['display_multiplier'].'&currency='.$_REQUEST['currency'].'&id='.$_REQUEST['id'].'&verifier='.$_REQUEST['verifier'] );
	}
	else
	{
		echo 'error';
	}
?>