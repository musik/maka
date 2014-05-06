<?php
$tabs = array('全部','功效','怎么吃','价格','是什么','秘鲁','官网','益康','效果','怎么样');
define('KYPATH', dirname(__FILE__));
include KYPATH. '/functions.php';
$filename = KYPATH.'/makaindex.csv';
$tab = $_GET['tab'];
$arr = parse_csv($filename);
if($tab){
  $keys = array_shift($arr);
  if($tab !== '全部')
    $arr = filter_tab($arr,$tab);
}else{
  $arr = parse_csv($filename);
  array_shift($arr);
  $keys = array('关键词','条数');
  foreach($tabs as $tab){
    $arrt = $tab == '全部' ? $arr : filter_tab($arr,$tab);
    $kws[] = array($tab,count($arrt));  
  }
  $arr = $kws;
}
?>
<html>
<head>
<meta charset="utf-8">
<title>玛卡关键词</title>
<link rel="stylesheet" type="text/css" href="http://www.ndrc.ac.cn/assets/application.css"/>
<style>
body{
  background: #fff;
}
</style>
</head>
<body>
<form methdo="GET">
<input type="text" width=100% class="input-lg">
</form>
<p>
<a href="?">首页</a>
<?php foreach($tabs as $name){?>
<a href="?tab=<?php echo $name?>"><?php echo $name?></a>
<?php }?>
</p>
<?php 
include(KYPATH.'/'. ($kws ? 'tabs' : 'table') .'.tpl.php');
?>
</body>
</html>
