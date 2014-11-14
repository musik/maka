<?php
/**
 * 拼音转换类测试程序
 */
require_once dirname(__FILE__) . "/Pinyin.php";

// 确定换行符
$s_br = (php_sapi_name() == "cli") ? "\n" : "<br>";

// 测试汉字转拼音
$keyword = "香茶藨子";
$str = Pinyin::getPinyin($keyword);
var_export($str);
echo $s_br;

// 测试获取汉字首字母
$keyword = "好联系";
$str = Pinyin::getPinyin($keyword, 1);
var_export($str);
echo $s_br;
