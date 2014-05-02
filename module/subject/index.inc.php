<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($MOD['subdomain']){
  $host = get_env('host');
  if(substr($host, 0, 4) != 'www.') {
    $whost = $host;
    $slug = str_replace($MOD['subdomain'], '', $host);
		if(check_name($slug)) {
      if($smod){
        $SMOD = check_module($smod);
        if(!$SMOD)
          include load('404.inc');
      }
      $action = $SMOD ? $SMOD['module'] : 'home';
      require DT_ROOT.'/module/'.$module.'/item.inc.php';
      include DT_ROOT.'/module/'.$module.'/'.$action.'.inc.php';
      include template($template, $module);
      exit();
    }
  }
}
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($MOD['index_html']) {	
	$html_file = DT_ROOT.'/'.$MOD['moduledir'].'/'.$DT['index'].'.'.$DT['file_ext'];
	if(!is_file($html_file)) tohtml('index', $module);
	if(is_file($html_file)) exit(include($html_file));
}
if(!check_group($_groupid, $MOD['group_index'])) include load('403.inc');
$maincat = get_maincat(0, $moduleid, 1);
$seo_file = 'index';
include DT_ROOT.'/include/seo.inc.php';
$template = $MOD['template_index'] ? $MOD['template_index'] : 'index';
$destoon_task = "moduleid=$moduleid&html=index";
if($EXT['wap_enable']) $head_mobile = $EXT['wap_url'].'index.php?moduleid='.$moduleid.($page > 1 ? '&page='.$page : '');
if($_GET['url']){
  require DT_ROOT."/module/".$module."/".$module.".class.php";
  $template = 'url-list';
  $sn = new subject($moduleid);
  $pagesize = 1000;
  $subjects = $sn->get_list();
}
include template($template, $module);
?>
