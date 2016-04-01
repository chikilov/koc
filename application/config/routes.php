<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

/*API 시작*/
$route['request/api/(:any)'] = 'api/Con_ApiProcess/$1';
$route['request/api/'] = 'api/con_ApiProcess';

$route['request/apiProcessor/(:any)'] = 'api/Con_ApiProcessor/$1';
$route['request/apiProcessor/'] = 'api/Con_ApiProcessor';

$route['request/apiUpdate/(:any)'] = 'api/con_ApiUpdate/$1';
$route['request/apiUpdate/'] = 'api/con_ApiUpdate';

$route['request/apiRequest/(:any)'] = 'api/con_ApiRequest/$1';
$route['request/apiRequest/'] = 'api/con_ApiRequest';
/*API 끝*/

/*Admin 시작*/
$route['pages/admin/login/(:any)'] = 'admin/con_Login/$1';
$route['pages/admin/login/'] = 'admin/con_Login';

$route['pages/admin/accountbasic/(:any)'] = 'admin/con_AccountBasic/$1';
$route['pages/admin/accountbasic/'] = 'admin/con_AccountBasic';

$route['pages/admin/accountdetail/(:any)'] = 'admin/con_AccountDetail/$1';
$route['pages/admin/accountdetail/'] = 'admin/con_AccountDetail/';

$route['pages/admin/accountblock/(:any)'] = 'admin/con_AccountBlock/$1';
$route['pages/admin/accountblock/'] = 'admin/con_AccountBlock/';

$route['pages/admin/chargemanage/(:any)'] = 'admin/con_ChargeManage/$1';
$route['pages/admin/chargemanage/'] = 'admin/con_ChargeManage/';

$route['pages/admin/chargehistory/(:any)'] = 'admin/con_ChargeHistory/$1';
$route['pages/admin/chargehistory/'] = 'admin/con_ChargeHistory/';

$route['pages/admin/chargecoupon/(:any)'] = 'admin/con_ChargeCoupon/$1';
$route['pages/admin/chargecoupon/'] = 'admin/con_ChargeCoupon/';

$route['pages/admin/noticemanage/(:any)'] = 'admin/con_NoticeManage/$1';
$route['pages/admin/noticemanage/'] = 'admin/con_NoticeManage/';

$route['pages/admin/rankthisweek/(:any)'] = 'admin/con_RankThisweek/$1';
$route['pages/admin/rankthisweek/'] = 'admin/con_RankThisweek/';

$route['pages/admin/rankprevweek/(:any)'] = 'admin/con_RankPrevweek/$1';
$route['pages/admin/rankprevweek/'] = 'admin/con_RankPrevweek/';

$route['pages/admin/loggame/(:any)'] = 'admin/con_LogGame/$1';
$route['pages/admin/loggame/'] = 'admin/con_LogGame/';

$route['pages/admin/eventmanage/(:any)'] = 'admin/con_EventManage/$1';
$route['pages/admin/eventmanage/'] = 'admin/con_EventManage/';

$route['pages/admin/eventpresent/(:any)'] = 'admin/con_EventPresent/$1';
$route['pages/admin/eventpresent/'] = 'admin/con_EventPresent/';

$route['pages/admin/gatchamanage/(:any)'] = 'admin/con_GatchaManage/$1';
$route['pages/admin/gatchamanage/'] = 'admin/con_GatchaManage/';

$route['pages/admin/adminmanage/(:any)'] = 'admin/con_AdminManage/$1';
$route['pages/admin/adminmanage/'] = 'admin/con_AdminManage/';

$route['pages/admin/adminlog/(:any)'] = 'admin/con_AdminLog/$1';
$route['pages/admin/adminlog/'] = 'admin/con_AdminLog/';
/*Admin 끝*/

/*Notice 시작*/
$route['pages/notice/listnotice/(:any)'] = 'notice/con_ListNotice/$1';
$route['pages/notice/listnotice/'] = 'notice/con_ListNotice/';

$route['pages/notice/imagenotice/(:any)'] = 'notice/con_ImageNotice/$1';
$route['pages/notice/imagenotice/'] = 'notice/con_ImageNotice/';
/*Notice 끝*/

/*CouponView 시작*/
$route['pages/webview/couponview/(:any)'] = 'webview/con_CouponView/$1';
$route['pages/webview/couponview/'] = 'webview/con_CouponView/';
/*CouponView 끝*/

$route['default_controller'] = "index";
$route['404_override'] = 'error/404_PageNotFound';
/* End of file routes.php */
/* Location: ./application/config/routes.php */
