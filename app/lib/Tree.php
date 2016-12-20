<?php
final class Tree {
	
	public static function nodes($table, $parent_id=0, $depth=0, $pre_key=0)
	{
		$data = DB::findAll($table, array('parent_id'=>$parent_id), 'id ASC', 'id, name');
		
		$nodes = array();
		
		$pre_key = $pre_key . '-';
		
		foreach ($data as $v)
		{
			$nodes[$pre_key . $v['id']] = $v;	
		}
		
		$data = $nodes;
		
		foreach ($data as $k=>$node)
		{
			$nodes[$k]['parent_id'] = $parent_id;
			$nodes[$k]['depth'] = $depth;
			$nodes = $nodes + self::nodes($table, $node['id'], $nodes[$k]['depth']+1, $k);	
		}
		return $nodes;
	}
	
	public static function options($table, $selected_id=0, $parent_id=0)
	{
		$nodes = Tree::nodes($table, $parent_id);
		ksort($nodes);
		$spacer='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$format = '<option%svalue="%d">%s%s</option>';
		$default_selected = $selected_id == 0 ? ' selected="selected" ' : ' ';
		$options = sprintf($format, $default_selected, 0, '', Lang::PLEASE . Lang::CHOOSE);
		foreach($nodes as $node)
		{
			$default_selected = $selected_id > 0 && $selected_id == $node['id'] ? ' selected="selected" ' : ' ';
			$options .= sprintf($format, $default_selected, $node['id'], str_repeat($spacer, $node['depth']), $node['name']);
		}
		return $options;
	}
	
	public static function groupOpts($table, $selected_id=0, $parent_id=0)
	{
		$data = DB::findAll($table, array('parent_id'=>$parent_id), 'id ASC', 'id, name');
		$cats = array();
		foreach ($data as $cat)
		{
			$str = sprintf('<optgroup label="%s">', $cat['name']);
			$childs = DB::findAll($table, array('parent_id'=>$cat['id']), 'id ASC', 'id, name');
			foreach ($childs as $node)
			{
				$default_selected = $selected_id > 0 && $selected_id == $node['id'] ? ' selected="selected" ' : ' ';
				$str .= sprintf('<option%svalue="%d">%s</option>', $default_selected, $node['id'], $node['name']);
			}
			$cats[] =  $str . '</optgroup>';
		}
		return implode('', $cats);
	}
}

?>