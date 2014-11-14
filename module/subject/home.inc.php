<?php 
$SCATS = $db->get_list("select * from {$db->pre}category where catdir='$item[slug]'",'moduleid');
?>
