<?php
function explode_line($str,$c="\t"){
  return explode($c,$str);
}
function slice_line($arr){
  unset($arr[8],$arr[10]);
  return array_slice($arr,0,15);
}

function parse_csv($filename){
  $text =file_get_contents($filename);
  $lines = explode("\n",$text);
  $lines = array_map('explode_line',$lines);
  $lines = array_map('slice_line',$lines);
  return $lines;
  $keys = array_shift($lines);
  foreach($lines as $v){
    $new[$v[0]] = array_combine($keys,$v);
  }
  return $new;
}
function select_first($arr){
  return $arr[0];
}
function filter_tab($arr,$tab){
  foreach($arr as $k=>$v){
    if(strpos($v[0],$tab) === false)
      unset($arr[$k]);
  }
  return $arr;
}
