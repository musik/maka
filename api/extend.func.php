<?php
defined('IN_DESTOON') or exit('Access Denied');
#Your Functions
function pebug($arr,$exit = false){
    printf("<pre>%s</pre>",var_export($arr,true));
    if($exit) exit();
}
define('DEFAULT_CITY',false);
function to_pinyin($str,$type=null){
  if(!class_exists('Pinyin'))
    require DT_ROOT. '/extend/Pinyin/Pinyin.php';
  $arr = Pinyin::getPinyin($str,$type);
  return $arr[0];
}
//hacked start get_cat_by_dir
function get_cat_by_dir($catdir,$moduleid) {
	global $db;
	return $db->get_one("SELECT * FROM {$db->pre}category WHERE moduleid = $moduleid and catdir='$catdir'");
}
function pr($arr,$exit=false){
  if ($arr === false){
    echo 'false';
  }else{
    printf('<pre>%s</pre>',print_r($arr,true));
  }
  if($exit) exit();
}
function get_cats_for_detect($moduleid){
	global $db;
	$condition = "moduleid=$moduleid";
	$cat = array();
	$result = $db->query("SELECT catid,catname,parentid FROM {$db->pre}category WHERE $condition ORDER BY parentid desc,catid ASC", 'CACHE');
	while($r = $db->fetch_array($result)) {
    if($r['catname'] == '其它') continue;
		$cat[$r['catid']] = $r['catname'];
	}
	return $cat;
}

//ini_set("pcre.recursion_limit", "300000");
function detect_cat($post,$moduleid=5){
  if($post['catid']) return $post; 
  $cats = get_cats_for_detect($moduleid);
  $names =  str_replace('/','\/',(implode('|',array_unique(array_values($cats)))));

  $len = mb_strlen($names);
  while(mb_strlen($names) > 31550){
    $start = 0;
    $tmp = mb_substr($names,$start,31550);
    $limit = strrpos($tmp,'|');
    $tmp = mb_substr($names,$start,$limit);
    $names_arr[] = $tmp;
    $start += ($limit + 1);
    $names = mb_substr($names,$start);
  }
  $names_arr[] = $names;

  foreach($names_arr as $names){
    if(!preg_match('!'.$names.'!',$post['title'],$m)) continue;
    $catname = $m[0];
    $post['catid'] = array_search($catname,$cats);
    break;
  }
  return $post;
}
function bulk_fix_cats($last_id,$per = 100){
	global $db;
	$result = $db->query("SELECT title,itemid FROM {$db->pre}sell WHERE itemid < $last_id ORDER BY itemid desc limit $per");
	while($r = $db->fetch_array($result)) {
    $id = $r['itemid'];
    $r = detect_cat($r);
    if(array_key_exists('catid',$r)){
      $db->query("update {$db->pre}sell set catid = $r[catid] where itemid = $r[itemid]");
    }
	}
	return $id;
}
function update_cat_by_detect($id){
  global $db;
  $r = $db->get_one("select title from {$db->pre}sell where itemid = $id");
  $r = detect_cat($r);
  if(array_key_exists('catid',$r)){
    $db->query("update {$db->pre}sell set catid = $r[catid] where itemid = $id");
  }
}

function check_module($name){
  global $MODULE;
  if(!$name) return false;
  foreach($MODULE as $m){
    if($m['moduledir'] == $name) return $m;
  }
  return false;
}
function get_cats_az($mid,$where,$smid=25,$home=0){
	global $db,$MODULE;
	$condition = "moduleid=$mid";
  if($where)
    $condition .= " and $where";
  $stable = get_table($smid);
  $mod = cache_read('module-'.$mid.'.php');
  $smod = cache_read('module-'.$smid.'.php');

	$cats = $db->get_list("SELECT cat.catid,catdir,catname,letter,item,cat.linkurl,subject.itemid as subject_id FROM {$db->pre}category cat left join $stable subject on subject.slug = cat.catdir  WHERE $condition ORDER BY letter asc,cat.listorder desc",0, 'CACHE');
  foreach($cats as $cat){
    if($cat['subject_id']){
      $cat['linkurl'] = $smod['subdomain'] ? 
          "http://".$cat['catdir'].$smod['subdomain'] :
            $smod['linkurl'].$cat['catdir'];
      if(!$home) $cat['linkurl'] .= '/'.$mod['moduledir'];
    }else{
      $cat['linkurl'] = $mod['linkurl'].$cat['linkurl'];
    }
    $letter = $cat['letter'];
    if(is_numeric($letter)) $letter = "0-9";
    $az[$letter][] = $cat; 
  }
  return $az;
}
function get_subjects_az($mid=25,$where){
	global $db;
  if($where)
    $where = " where $where";
	$cats = $db->get_list("SELECT title,slug,linkurl FROM {$db->pre}subject_$mid  $where ORDER BY slug asc",0, 'CACHE');
  foreach($cats as $cat){
    $letter = substr($cat['slug'],0,1);
    if(is_numeric($letter)) $letter = "0-9";
    $az[$letter][] = $cat; 
  }
  return $az;
}
function get_cats_by_order($mid,$where){
	global $db;
	$condition = "moduleid=$mid";
  if($where)
    $condition .= " and $where";
	return $db->get_list("SELECT catid,catname,listorder,linkurl FROM {$db->pre}category WHERE $condition ORDER BY listorder desc",0, 'CACHE');
}
function subject_import_from_cats($mid,$catid){
	global $db;
	$condition = "moduleid=$mid";
	$cats = $db->get_list("SELECT catid,catname,listorder,catdir FROM {$db->pre}category WHERE $condition ORDER BY listorder desc",0, 'CACHE');
  if(!class_exists('subject'))
    require DT_ROOT.'/module/subject/subject.class.php';
  $do = new subject();
  foreach($cats as $cat){
    $data = array(
      'title'=>$cat['catname'],
      'slug' =>$cat['catdir'],
      'listorder' =>$cat['listorder'],
      'catid' => $catid,
      'status' => 3
    );  
    if($do->pass($data))
      $do->add($data);
  }
}
//hacked end
function subject_cat_pos($CAT, $str = ' &raquo; ', $target = '') {
	global $MOD, $db;
	if(!$CAT) return '';
  $smid = 25;
  $stable = get_table($smid);
  $subject = $db->get_one("SELECT * from $stable WHERE slug = '$CAT[catdir]'");
  if($subject){
    $pos =  '<a href="'.$subject['linkurl'].'">'.$subject['title'].'</a>';
    $pos .=  $str . '<a href="'.$subject['linkurl'].'/'.$MOD['moduledir'].'">'.$subject['title'].$MOD['name'].'</a>';
    return $pos;
  }
  return '<a href="'.$MOD['linkurl'].'">'.$MOD['name'].'</a>'. $str . cat_pos($CAT,$str,$target);

}
?>
