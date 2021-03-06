<?php
/**
 * This file is a part of MyWebSQL package
 *
 * @file:      modules/configuration.php
 * @author     Samnan ur Rehman
 * @copyright  (c) 2008-2011 Samnan ur Rehman
 * @web        http://mywebsql.net
 * @license    http://mywebsql.net/license
 */

 	// called very early during initialization of the application
 	// bProcess suggests that the whole application needs to init, otherwise just the basics
	function initConfiguration($bProcess = true) {
		include_once("config/constants.php");

		// theme setup
		include("config/themes.php");
		if ($bProcess && isset($_GET["theme"]) && array_key_exists($_GET["theme"], $THEMES)) {
			define("THEME_PATH", $_GET["theme"]);
			setcookie("theme", $_GET["theme"], time()+(COOKIE_LIFETIME*60*60), EXTERNAL_PATH);
			echo '<div id="results">1</div>';
			exit();
		}
		else if (isset($_COOKIE["theme"]) && array_key_exists($_COOKIE["theme"], $THEMES))
			define("THEME_PATH", $_COOKIE["theme"]);
			
		// language setup		
		include('config/lang.php');  // we have to include language first for proper settings
		if ($bProcess && isset($_REQUEST["lang"]) && array_key_exists($_REQUEST["lang"], $_LANGUAGES) && file_exists('lang/'.$_REQUEST["lang"].'.php')) {
			define('LANGUAGE', $_REQUEST["lang"]);
			setcookie("lang", $_REQUEST["lang"], time()+(COOKIE_LIFETIME*60*60), EXTERNAL_PATH);
			// if this is false, we are at login screen
			if (v($_GET["x"]) == 1) {
				echo '<div id="results">1</div>';
				exit();
			}
		}
		else if (isset($_COOKIE["lang"]) && array_key_exists($_COOKIE["lang"], $_LANGUAGES) && file_exists('lang/'.$_COOKIE["lang"].'.php'))
			define('LANGUAGE', $_COOKIE["lang"]);
		else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$_user_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			if (array_key_exists($_user_lang, $_LANGUAGES) && file_exists('lang/'.$_user_lang.'.php'))
				define('LANGUAGE', $_user_lang);
			unset($_user_lang);
		}
		
		// sql editor setup
		include("config/editors.php");
		if ($bProcess && isset($_GET["editor"]) && array_key_exists($_GET["editor"], $CODE_EDITORS)) {
			define("SQL_EDITORTYPE", $_GET["editor"]);
			setcookie("editor", $_GET["editor"], time()+(COOKIE_LIFETIME*60*60), EXTERNAL_PATH);
			if (v($_GET["x"]) == 1) {
				echo '<div id="results">1</div>';
				exit();
			}
		}
		else if (isset($_COOKIE["editor"]) && array_key_exists($_COOKIE["editor"], $CODE_EDITORS))
			define("SQL_EDITORTYPE", $_COOKIE["editor"]);	

		// initialize rest of the configuration to defaults
		include_once ("config/config.php");
		
		if(!defined('LANGUAGE'))
			define("LANGUAGE", DEFAULT_LANGUAGE);
		
		if(!defined('THEME_PATH'))
			define('THEME_PATH', DEFAULT_THEME);
		
		if(!defined('SQL_EDITORTYPE'))
			define('SQL_EDITORTYPE', DEFAULT_EDITOR);

		if ($bProcess) {
			include('config/auth.php');
			include('config/keys.php');
		}
	
		return true;
	}

	function getKeyCodes() {
		include('config/keys.php');
		return $KEY_CODES;
	}
?>