<?php

final class _C_Session {
	
	public static function init() {
		$config = _C_Config::vars('session');
		isset ( $config ['save_path'] ) && session_save_path ( $config ['save_path'] );
		isset ( $config ['set_cookie_params'] ) && call_user_func_array ( 'session_set_cookie_params', $config ['set_cookie_params'] );
		isset ( $config ['cache_expire'] ) && session_cache_expire ( $config ['cache_expire'] );
		isset ( $config ['cache_limiter'] ) && session_cache_limiter ( $config ['cache_limiter'] );
		@session_start ();
	}
	
	public static function destroy() {
		@session_destroy ();
	}
}

?>