<?php
defined('IN_DESTOON') or exit('Access Denied');
class article {
	var $moduleid;
	var $itemid;
	var $db;
	var $table;
	var $table_data;
	var $split;
	var $fields;
	var $errmsg = errmsg;

    function article($moduleid) {
		global $db, $table, $table_data, $MOD;
		$this->moduleid = $moduleid;
		$this->table = $table;
		$this->table_data = $table_data;
		$this->split = $MOD['split'];
		$this->db = &$db;
		$this->fields = array('catid','areaid','level','title','style','fee','subtitle','introduce','thumb','tag','author','copyfrom','fromurl','voteid','status','hits','username','addtime','editor','edittime','ip','template','islink','linkurl','filepath','note');
    }

	function pass($post) {
		if(!is_array($post)) return false;
		if(!$post['catid']) return $this->_(lang('message->pass_catid'));
		if(strlen($post['title']) < 3) return $this->_(lang('message->pass_title'));
		if(isset($post['islink'])) {
			if(!$post['linkurl']) return $this->_(lang('message->pass_linkurl'));
		} else {
			if(!$post['content']) return $this->_(lang('message->pass_content'));
		}
		return true;
	}

	function set($post) {
		global $MOD, $DT_TIME, $DT_IP, $_username, $_userid;
		$post['islink'] = isset($post['islink']) ? 1 : 0;
		$post['addtime'] = (isset($post['addtime']) && $post['addtime']) ? strtotime($post['addtime']) : $DT_TIME;
		$post['edittime'] = $DT_TIME;
		$post['title'] = trim($post['title']);
		$post['fee'] = dround($post['fee']);
		$post['content'] = stripslashes($post['content']);
		$post['content'] = save_local($post['content']);
		if($post['content'] && isset($post['clear_link']) && $post['clear_link']) $post['content'] = clear_link($post['content']);
		if($post['content'] && isset($post['save_remotepic']) && $post['save_remotepic']) $post['content'] = save_remote($post['content']);
		if($post['content'] && $post['thumb_no'] && !$post['thumb']) $post['thumb'] = save_thumb($post['content'], $post['thumb_no'], $MOD['thumb_width'], $MOD['thumb_height']);
		if(strpos($post['content'], 'pagebreak') !== false) $post['content'] = str_replace(array('[pagebreak]</p>', '<p>[pagebreak]', '[pagebreak]</div>', '<div>[pagebreak]'), array('</p>[pagebreak]', '[pagebreak]<p>', '</div>[pagebreak]', '[pagebreak]<div>'), $post['content']);
		if($post['content'] && !$post['introduce'] && $post['introduce_length']) $post['introduce'] = addslashes(get_intro($post['content'], $post['introduce_length']));
		if($this->itemid) {
			$post['editor'] = $_username;
			$new = $post['content'];
			if($post['thumb']) $new .= '<img src="'.$post['thumb'].'">';
			$r = $this->get_one();
			$old = $r['content'];
			if($r['thumb']) $old .= '<img src="'.$r['thumb'].'">';
			delete_diff($new, $old);
		} else {
			$post['username'] = $post['editor'] = $_username;
			$post['ip'] = $DT_IP;
		}
		$content = $post['content'];
		unset($post['content']);
		$post = dhtmlspecialchars($post);
		$post['content'] = addslashes(dsafe($content));
		return array_map("trim", $post);
	}

	function get_one() {
		$content_table = content_table($this->moduleid, $this->itemid, $this->split, $this->table_data);
        return $this->db->get_one("SELECT * FROM {$this->table} a,{$content_table} c WHERE a.itemid=c.itemid and a.itemid=$this->itemid");
	}
	function get_blogs($condition = 'status=3', $limit =10,$order = 'addtime DESC', $cache = '') {
    global $DT_PRE;
    $table = $DT_PRE."article".'_'.$this->moduleid;
		$content_table = content_table($this->moduleid, $this->itemid, $this->split, $this->table_data);
		$result = $this->db->query("SELECT * FROM {$table} a,{$content_table} c WHERE a.itemid = c.itemid and $condition ORDER BY $order LIMIT $limit", $cache);
		while($r = $this->db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			if(!$r['islink']) $r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
			$catids[$r['catid']] = $r['catid'];
      $r['description'] =$this->nice_description($r['content']);
			$lists[] = $r;
		}
    return $lists;
  }
  function nice_description($str){
    $str = strip_tags($str);
    $str = dsubstr($str,1200,'..');
    $str = str_replace("玛卡","<i></i>",$str);
    //$str = mb_substr($str,0,900);
    return $str; 
  }

	function get_list($condition = 'status=3', $order = 'addtime DESC', $cache = '') {
		global $MOD, $pages, $page, $pagesize, $offset, $items, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $this->db->get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE $condition", $cache);
			$items = $r['num'];
		}
		$pages = defined('CATID') ? listpages(1, CATID, $items, $page, $pagesize, 10, $MOD['linkurl']) : pages($items, $page, $pagesize);
		$lists = $catids = $CATS = array();
		$result = $this->db->query("SELECT * FROM {$this->table} WHERE $condition ORDER BY $order LIMIT $offset,$pagesize", $cache);
		while($r = $this->db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['alt'] = $r['title'];
			$r['title'] = set_style($r['title'], $r['style']);
			if(!$r['islink']) $r['linkurl'] = $MOD['linkurl'].$r['linkurl'];
			$catids[$r['catid']] = $r['catid'];
			$lists[] = $r;
		}
		if($catids) {
			$result = $this->db->query("SELECT catid,catname,linkurl FROM {$this->db->pre}category WHERE catid IN (".implode(',', $catids).")");
			while($r = $this->db->fetch_array($result)) {
				$CATS[$r['catid']] = $r;
			}
			if($CATS) {
				foreach($lists as $k=>$v) {
					$lists[$k]['catname'] = $v['catid'] ? $CATS[$v['catid']]['catname'] : '';
					$lists[$k]['caturl'] = $v['catid'] ? $MOD['linkurl'].$CATS[$v['catid']]['linkurl'] : '';
				}
			}
		}
		return $lists;
	}

	function add($post) {
		global $MOD;
		$post = $this->set($post);
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
        $sqlk = substr($sqlk, 1);
        $sqlv = substr($sqlv, 1);
		$this->db->query("INSERT INTO {$this->table} ($sqlk) VALUES ($sqlv)");
		$this->itemid = $this->db->insert_id();
		$content_table = content_table($this->moduleid, $this->itemid, $this->split, $this->table_data);
		$this->db->query("INSERT INTO {$content_table} (itemid,content) VALUES ('$this->itemid', '$post[content]')");
		$this->update($this->itemid);
		if($post['status'] == 3 && $post['username'] && $MOD['credit_add']) {
			credit_add($post['username'], $MOD['credit_add']);
			credit_record($post['username'], $MOD['credit_add'], 'system', lang('my->credit_record_add', array($MOD['name'])), 'ID:'.$this->itemid);
		}
		clear_upload($post['content'].$post['thumb'], $this->itemid);
		return $this->itemid;
	}

	function edit($post) {
		$this->delete($this->itemid, false);
		$post = $this->set($post);
		$sql = '';
		foreach($post as $k=>$v) {
			if(in_array($k, $this->fields)) $sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    $this->db->query("UPDATE {$this->table} SET $sql WHERE itemid=$this->itemid");
		$content_table = content_table($this->moduleid, $this->itemid, $this->split, $this->table_data);
	    $this->db->query("UPDATE {$content_table} SET content='$post[content]' WHERE itemid=$this->itemid");
		$this->update($this->itemid);
		clear_upload($post['content'].$post['thumb'], $this->itemid);
		if($post['status'] == 3) $this->tohtml($this->itemid, $post['catid']);
		return true;
	}

	function tohtml($itemid = 0, $catid = 0) {
		global $module, $MOD;
		if($MOD['show_html'] && $itemid) tohtml('show', $module, "itemid=$itemid");
	}

	function update($itemid) {
		$item = $this->db->get_one("SELECT * FROM {$this->table} WHERE itemid=$itemid");
		$update = '';
		$keyword = $item['title'].','.($item['tag'] ? str_replace(' ', ',', trim($item['tag'])).',' : '').strip_tags(cat_pos(get_cat($item['catid']), ','));
		if($keyword != $item['keyword']) {
			$keyword = str_replace("//", '', addslashes($keyword));
			$update .= ",keyword='$keyword'";
		}
		$item['itemid'] = $itemid;
		$linkurl = $item['islink'] ? $item['linkurl'] : itemurl($item);
		if($linkurl != $item['linkurl']) $update .= ",linkurl='$linkurl'";
		if($update) $this->db->query("UPDATE {$this->table} SET ".(substr($update, 1))." WHERE itemid=$itemid");
	}

	function recycle($itemid) {
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->recycle($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=0 WHERE itemid=$itemid");
			$this->delete($itemid, false);
			return true;
		}		
	}

	function restore($itemid) {
		global $module, $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->restore($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=3 WHERE itemid=$itemid");
			if($MOD['show_html']) tohtml('show', $module, "itemid=$itemid");
			return true;
		}		
	}

	function delete($itemid, $all = true) {
		global $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { 
				$this->delete($v, $all);
			}
		} else {
			$this->itemid = $itemid;
			$r = $this->get_one();
			if($MOD['show_html'] && !$r['islink']) {
				$_file = DT_ROOT.'/'.$MOD['moduledir'].'/'.$r['linkurl'];
				if(is_file($_file)) unlink($_file);
				$i = 1;
				while($i) {
					$_file = DT_ROOT.'/'.$MOD['moduledir'].'/'.itemurl($r, $i);
					if(is_file($_file)) {
						unlink($_file);
						$i++;
					} else {
						break;
					}
				}
			}
			if($all) {
				$userid = get_user($r['username']);
				if($r['thumb']) delete_upload($r['thumb'], $userid);
				if($r['content']) delete_local($r['content'], $userid);
				$this->db->query("DELETE FROM {$this->table} WHERE itemid=$itemid");
				$content_table = content_table($this->moduleid, $this->itemid, $this->split, $this->table_data);
				$this->db->query("DELETE FROM {$content_table} WHERE itemid=$itemid");
				if($MOD['cat_property']) $this->db->query("DELETE FROM {$this->db->pre}category_value WHERE moduleid=$this->moduleid AND itemid=$itemid");
				if($r['username'] && $MOD['credit_del']) {
					credit_add($r['username'], -$MOD['credit_del']);
					credit_record($r['username'], -$MOD['credit_del'], 'system', lang('my->credit_record_del', array($MOD['name'])), 'ID:'.$this->itemid);
				}
			}
		}
	}

	function check($itemid) {
		global $_username, $DT_TIME, $MOD;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->check($v); }
		} else {
			$this->itemid = $itemid;
			$item = $this->get_one();
			if($MOD['credit_add'] && $item['username'] && $item['hits'] < 1) {
				credit_add($item['username'], $MOD['credit_add']);
				credit_record($item['username'], $MOD['credit_add'], 'system', lang('my->credit_record_add', array($MOD['name'])), 'ID:'.$this->itemid);
			}
			$this->db->query("UPDATE {$this->table} SET status=3,hits=hits+1,editor='$_username',edittime=$DT_TIME WHERE itemid=$itemid");
			$this->tohtml($itemid);
			return true;
		}
	}

	function reject($itemid) {
		global $_username, $DT_TIME;
		if(is_array($itemid)) {
			foreach($itemid as $v) { $this->reject($v); }
		} else {
			$this->db->query("UPDATE {$this->table} SET status=1,editor='$_username' WHERE itemid=$itemid");
			return true;
		}
	}

	function clear($condition = 'status=0') {		
		$result = $this->db->query("SELECT itemid FROM {$this->table} WHERE $condition ");
		while($r = $this->db->fetch_array($result)) {
			$this->delete($r['itemid']);
		}
	}

	function level($itemid, $level) {
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$this->db->query("UPDATE {$this->table} SET level=$level WHERE itemid IN ($itemids)");
	}

	function _($e) {
		$this->errmsg = $e;
		return false;
	}
}
?>
