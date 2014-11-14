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
<a href="?tab=<?php echo $word?>"><?php echo $word?></a>
</td>
<?php foreach($line as $name){?>
  <td><?php echo $name?></td>
<?php }?>
</tr>
<?php }?>
</table>

