<?php
final class View {
	/**
	*
	*@param $info 直接调用DB::pages()方法返回的info
	*'info' => array(
	*			'page_now' => $page,
	*			'page_size' => $page_size,
	×			'records_count' => $records_count,
	×			'pages_count' => $pages_count,	
	×		),
	*/
	public static function pagesList($info, $link, $max_list=10)
	{
		if (strpos($link, '?') !== false)
		{
			$link = preg_replace('/(.*\?)(.*)/', '\\1page={__PAGE__}&\\2', $link);	
		}
		else if (strpos($link, '#') !== false)
		{
			$link = preg_replace('/(.*)(#.*)/', '\\1?page={__PAGE__}\\2', $link);	
		}
		else
		{
			$link .= '?page={__PAGE__}';	
		}
		
		$max_list = max($max_list, 5);
		
		$page_start = max(1, min(floor($info['page_now']/$max_list) * $max_list, $info['pages_count']));
		
		$page_end = min($page_start+$max_list, $info['pages_count']+1);
		
		$links = array();
		
		for ($i = $page_start; $i<$page_end; ++$i)
		{
			$links[$i] = str_replace('{__PAGE__}', $i, $link); 	
		}
		
		$info['links'] = $links;
		
		$str =  _C_View::layout('pagesList', $info);
		
		return $str === false ? '' : $str;
	}
	
	public static function artSlides($pos_id, $total=10)
	{	
		$recommends = DB::findAll(DB::ARTPOS, array('id'=>$pos_id), array('sort'=>'DESC'), '*', $total);
		
		$tail_count = $total - count($recommends);
		
		if ($tail_count > 0)
		{
			$find_recommends = DB::findAll(DB::ARTICLE, array('status'=>'0'), array('hits'=>'DESC'), 'id, title, author, guide, img, description, url, add_time, hits', $tail_count);
		
			$recommends = array_merge($recommends, $find_recommends);
		}
		$format['slides'] 		= '<div class="slides_container">%s</div><ul class="pagination">%s</ul>';
		$format['container']    = '<div><h2>%s</h2>%s</div>';
		$format['pagination']   = '<li><a href="#">%d %s</a></li>';
		$slides['container']    = '';
		$slides['pagination']   = '';
		foreach ($recommends as $i => $article)
		{
			$slides['container'] .= sprintf($format['container'], $article['title'], $article['guide']); 
			$slides['pagination'] .= sprintf($format['pagination'], $i+1, $article['title']);
		}
		
		return sprintf($format['slides'], $slides['container'], $slides['pagination']);
	}
}
?>