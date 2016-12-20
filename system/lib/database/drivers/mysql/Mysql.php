<?php

class Mysql {
	private static $escape_char = '`';
	
	public static function init($config) {
		_C_App::assert ( isset ( $config ['database'] ), 'database must config' );
		$server = ! isset ( $config ['hostname'] ) ? 'localhost' : (is_numeric ( $config ['hostport'] ) ? $config ['hostname'] . ':' . $config ['hostport'] : $config ['hostname']);
		$username = isset ( $config ['username'] ) ? $config ['username'] : '';
		$password = isset ( $config ['password'] ) ? $config ['password'] : '';
		$database = $config ['database'];
		
		if (isset ( $config ['pconnect'] ) && $config ['pconnect'] === true) {
			$link = mysql_pconnect ( $server, $username, $password );
		} else {
			$link = mysql_connect ( $server, $username, $password, true );
		}
		
		_C_App::assert ( is_resource ( $link ), 'Database config error' );
		
		_C_App::assert ( mysql_select_db ( $database, $link ), 'Cant select db');
		
		$charset = isset ( $config ['charset'] ) ? $config ['charset'] : 'utf8';
		
		if (function_exists ( 'mysql_set_charset' )) {
			mysql_set_charset ( $charset, $link );
		} else {
			$charset_sql = 'SET NAMES ' . self::escapeString ( $charset, $link ) . (empty ( $config ['collate'] ) ? '' : ' COLLATE ' . self::escapeString ( $config ['collate'], $link ));
			mysql_query ( $charset_sql );
		}
		
		return $link;
	}
	
	public static function exec($sql, $link) {
		return mysql_query ( $sql, $link );
	}
	
	public static function insertId($link) {
		return mysql_insert_id ( $link );
	}
	
	public static function count($table, $link, $where=null) {
		$where = is_null ( $where ) ? '' : ' WHERE ' . (is_array ( $where ) ? self::getSqlEqualStr ( $where, ' and ' ) : $where);
		$sql = 'SELECT COUNT(*) FROM ' . $table . $where;
		$r = self::exec ( $sql, $link );
		if (! is_resource ( $r ) && ! is_object ( $r ))
			return 0;
		$row = mysql_fetch_row ( $r );
		return $row [0];
	}
	
	public static function desc($table, $link) {
		$sql = 'DESC ' . $table;
		$results = array ();
		$res = self::exec ( $sql, $link );
		if (is_resource ( $res ) || is_object ( $res )) {
			while ( ($row = mysql_fetch_array ( $res, MYSQL_ASSOC )) !== false ) {
				$results [] = $row;
			}
		}
		return $results;
	}
	
	/**
	 * 
	 * @param string $table
	 * @param array $data
	 * @param resource $link
	 * @return bool
	 */
	public static function insert($table, $link, $data) {
		$fields = '';
		$values = '';
		foreach ( $data as $field => $value ) {
			$fields .= self::$escape_char . $field . self::$escape_char . ',';
			$values .= '\'' . self::escapeString ( $value ) . '\',';
		}
		$sql = 'INSERT INTO ' . self::$escape_char . $table . self::$escape_char . ' (' . substr ( $fields, 0, - 1 ) . ')VALUES(' . substr ( $values, 0, - 1 ) . ')';
		return self::exec ( $sql, $link );
	}
	
	private static function getSqlEqualStr($arr, $sp = ',') {
		if (! is_array ( $arr ) || count ( $arr ) < 1)
			return '';
		$str = '';
		foreach ( $arr as $k => $v ) {
			$str .= self::$escape_char . $k . self::$escape_char . '=\'' . self::escapeString ( $v ) . '\'' . $sp;
		}
		return substr ( $str, 0, strlen ( $str ) - strlen ( $sp ) );
	}
	
	public static function affectedRows($link) {
		return mysql_affected_rows ( $link );
	}
	
	public static function update($table, $link, $data, $where = null) {
		$where = is_null ( $where ) ? '' : ' WHERE ' . (is_array ( $where ) ? self::getSqlEqualStr ( $where, ' and ' ) : $where);
		$sql = 'UPDATE ' . self::$escape_char . $table . self::$escape_char . ' SET ' . self::getSqlEqualStr ( $data ) . $where;
		return self::exec ( $sql, $link );
	}
	
	public static function delete($table, $link, $where = null, $limit = null) {
		$where = is_null ( $where ) ? '' : ' WHERE ' . (is_array ( $where ) ? self::getSqlEqualStr ( $where, ' and ' ) : $where);
		$limit = is_null ( $limit ) ? '' : ' LIMIT ' . $limit;
		$sql = 'DELETE FROM ' . self::$escape_char . $table . self::$escape_char . $where . ' ' . $limit;
		return self::exec ( $sql, $link );
	}
	
	/**
	 * 查询
	 * @param string $sql
	 * @param resource $link
	 * @return array
	 */
	public static function select($table, $link, $data = '*', $where = null, $order = null, $limit = null) {
		$where = is_null ( $where ) ? '' : ' WHERE ' . (is_array ( $where ) ? self::getSqlEqualStr ( $where, ' and ' ) : $where);
		$limit = is_null ( $limit ) ? '' : ' LIMIT ' . $limit;
		$order = is_null ( $order ) ? '' : ' ORDER BY ' . (is_array ( $order ) ? self::getSqlEqualStr ( $order, ' ' ) : $order);
		$sql = 'SELECT ' . $data . ' FROM ' . self::$escape_char . $table . self::$escape_char . $where . ' ' . $order . ' ' . $limit;
		$results = array ();
		$res = self::exec ( $sql, $link );
		if (is_object ( $res ) || is_resource ( $res )) {
			while ( ($row = mysql_fetch_array ( $res, MYSQL_ASSOC )) !== false ) {
				$results [] = $row;
			}
		}
		return $results;
	}
	
	public static function escapeString($str, $link = null) {
		if (is_array ( $str )) {
			foreach ( $str as $key => $val ) {
				$str [$key] = $this->escape_str ( $val );
			}
			
			return $str;
		}
		
		if (function_exists ( 'mysql_real_escape_string' ) && is_resource ( $link )) {
			$str = mysql_real_escape_string ( $str, $link );
		} else if (function_exists ( 'mysql_escape_string' )) {
			$str = mysql_escape_string ( $str );
		} else {
			$str = addslashes ( $str );
		}
		
		return $str;
	}
}

?>