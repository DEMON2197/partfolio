<?php
//подключение Смарти
define('SMARTY_DIR', str_replace("\\", "/", getcwd()).'/inc/libs/');
require_once(SMARTY_DIR . 'Smarty.class.php');


$site_dir = dirname(dirname(__FILE__)).'/';

require_once($site_dir.'inc/settings.inc');
require_once($site_dir.'inc/libs/functions.inc');


$smarty = new Smarty;
$smarty->use_sub_dirs = false;
$smarty->_file_perms  = 0664;
$smarty->_dir_perms   = 0771;
$smarty->caching = false; // кэширование
$smarty->assign('lang', $lang);

$smarty->template_dir = $site_dir.'templates/'.$site_design;
$smarty->cache_dir = $site_dir.'cache/'.$site_design;
$smarty->compile_dir = $site_dir.'templates_c/'.$site_design;


//Подключение к базе данных
$link = mysql_connect($db_host, $db_user, $db_pass) or die ("<h1>SQL Error. Can't connect to MySQL</h1>");
mysql_select_db($db_name, $link) or die ("<h1>SQL Error. Can't select database: $db_name</h1>");
@mysql_query("set character_set_client='cp1251'", $link);
@mysql_query("set character_set_connection='cp1251'", $link);
@mysql_query("set character_set_results='cp1251'", $link);



unset($db_name);
unset($db_host);
unset($db_user);
unset($db_pass);


