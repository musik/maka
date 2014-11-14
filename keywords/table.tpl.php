<p>共<?php echo count($arr)?>条</p>
<table class="table">
  <tr>
  <td></th>
<?php
foreach($keys as $name){?>
  <th><?php echo $name?></th>
<?php }?>
</tr>
<?php foreach($arr as $line){ $i++;?>
<tr>
<th><?php echo $i?></th>
  <td>
<?php $word = array_shift($line);?>
<a target="_blank" href="http://baidu.com/s?wd=<?php echo $word?>"><?php echo $word?></a>
</td>
<?php foreach($line as $name){?>
  <td><?php echo $name?></td>
<?php }?>
</tr>
<?php }?>
</table>

