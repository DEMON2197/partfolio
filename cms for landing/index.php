<?php
require_once('inc/ini.inc');

$sql = "select * from block left join list on block.id = list.block_id";
$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
if($query && mysql_num_rows($query)){
	$repeat = null;
	$iter = 0;
	$array_data = array();
	while($row = mysql_fetch_array($query)){
		//структурирование массива блоков-листов
		if($repeat != $row[0]){
			$repeat = $row[0];
			$array_data[] = array(
				//данные блока
				'id' => $row[0],
				'block_name' => $row[1],
				'keys' => unserialize($row[2]),
				'data' => unserialize($row[3]),
				'tpl' => $row[4]			
			);
			$iter++;
		}
		$array_data[$iter]['list'][] = array(
			'id' => $row[5],
			'list_name' => $row[6],
			'keys' => unserialize($row[7]),
			'data' => unserialize($row[8]));
	};
}
$smarty->assign('array', $array_data);
$smarty->display('layout.tpl');
?>