<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post">
<div class="tt">从其它模块的分类导入</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 所属模块
<select name="mid">
<option value="">请选择</option>
<?php foreach($MODULE as $k=>$v) {
	if($k > 4 && !$v['islink'] && $v['moduleid'] != $moduleid)  echo '<option value="'.$k.'"'.($k == $mid ? ' selected' : '').'>'.$v['name'].'</option>';
}
?>
</select>
<?php echo category_select('catid', '导入分类', 0, $moduleid);?>
<input type="submit" value=" 导入 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=import';"/>&nbsp;
</td>
</tr>
</table>
</form>
<form method="post">
<div class="tt">数据库检查</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td height="30">&nbsp;
<input type="submit" value=" 安装模块 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=install';" title="安装模块"/>&nbsp;&nbsp;
<input type="submit" value=" 删除数据 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete_all';" title="删除数据"/>&nbsp;&nbsp;
<input type="submit" value=" 生成分类 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show&update=2';" title="生成该模块所有信息分类"/>
</td>
</tr>
</table>
</form>
<form method="post">
<div class="tt">生成网页</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td height="30">&nbsp;
<input type="submit" value=" 一键生成 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=all';" title="生成该模块所有网页"/>&nbsp;&nbsp;
<input type="submit" value=" 生成首页 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=index';" title="生成该模块首页"/>&nbsp;&nbsp;
<input type="submit" value=" 生成列表 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=list';" title="生成该模块所有分类"/>&nbsp;&nbsp;
<input type="submit" value=" 生成内容 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show';" title="生成该模块所有内容页"/>&nbsp;&nbsp;
<input type="submit" value=" 更新信息 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show&update=1';" title="更新该模块所有信息地址等项目"/>
</td>
</tr>
</table>
</form>
<form method="post">
<div class="tt">生成<?php echo $MOD['name'];?></div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th>起始ID</th>
<th>结束ID</th>
<th width="200">每轮生成数量</th>
<th width="200">操作</th>
</tr>
<tr align="center">
<td><input type="text" size="6" name="fid" value="<?php echo $fid;?>"/></td>
<td><input type="text" size="6" name="tid" value="<?php echo $tid;?>"/></td>
<td><input type="text" size="5" name="num" value="100"/></td>
<td><input type="submit" value=" 生成内容 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show';"/>&nbsp;
<input type="submit" value=" 更新信息 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show&update=1';"/>
</td>
</tr>
</table>
</form>
<form method="post">
<div class="tt">分段生成</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th>选择分类</th>
<th width="200">每轮生成数量</th>
<th width="200">操作</th>
</tr>
<tr align="center">
<td>
<?php echo category_select('catid', '选择分类', 0, $moduleid);?>
&nbsp;&nbsp;&nbsp;&nbsp;
第 <input type="text" size="3" name="fpage" value="1"/> 页 至 <input type="text" size="3" name="tpage" value=""/> 页
</td>
<td><input type="text" size="5" name="num" value="100"/></td>
<td>
<input type="submit" value=" 生成列表 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=cate';"/>&nbsp;
<input type="submit" value=" 生成内容 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=item';"/>
</td>
</tr>
</table>
</form>
<script type="text/javascript">Menuon(0);</script>
<br/>
<?php include tpl('footer');?>
