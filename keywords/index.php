<?php
$tabs = array('全部','功效','怎么吃','价格','是什么','秘鲁','官网','益康','效果','怎么样');
define('KYPATH', dirname(__FILE__));
include KYPATH. '/functions.php';
$filename = KYPATH.'/makaindex.csv';
$tab = $_GET['tab'];
if($tab){
  $arr = parse_csv($filename);
  $keys = array_shift($arr);
  if($tab !== '全部')
    $arr = filter_tab($arr,$tab);
}else{
  $arr0 = parse_csv($filename);
  array_shift($arr0);
  //$keywords = array_map('select_first',$arr0);
  $arr[] = array('关键词','条数');
  foreach($tabs as $tab){
    $arrt = $tab == '全部' ? $arr0 : filter_tab($arr0,$tab);
    $arr[] = array($tab,count($arrt));  
  }
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="http://www.ndrc.ac.cn/assets/application.css"/>
<style>
body{
  background: #fff;
}
</style>
</head>
<body>
<p>
<a href="?">首页</a>
<?php foreach($tabs as $name){?>
<a href="?tab=<?php echo $name?>"><?php echo $name?></a>
<?php }?>
</p>
<?php if($arr){include(KYPATH.'/table.tpl.php');}?>
</body>
</html>
