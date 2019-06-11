<?php
/*
динамически загружает периоды обучения из графиков
*/
require ('../inc/site.inc');


if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	if(isset($_POST)){
		array_map("mysql_real_escape_string", $_POST);
		mb_convert_variables('cp1251', 'utf8', $_POST);
		$year_this = (integer)date('o');
		$sql = "select * from grafik inner join grafik_thema on grafik.grafik_thema_id = grafik_thema.grafik_thema_id where CONCAT(grafik_thema.title,  ' ', grafik.title) = '".$_POST['program_name']."' and year=$year_this order by grafik_thema.title,grafik.title,polgoda";
			$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
			if($query && mysql_num_rows($query)){
				$graphic_array = array();
				while($row = mysql_fetch_array($query)){
					$graphic_array[] = $row;
				}
				
				$period = null;
				$assoc = '';
				if(!isset($graphic_array[1])){
					for($i = 6, $j = 0; $i < 12; $i++){
						if($graphic_array[0][$i][strlen($graphic_array[0][$i])-1] == '-') $assoc = $graphic_array[0][$i];
						elseif($graphic_array[0][$i] != '') {
							$period .= '<option value="'.$assoc.$graphic_array[0][$i].'">'.$assoc.$graphic_array[0][$i].'</option>';
							$assoc = '';
							$j++;
						}
					}
				}else{
					for($i = 6, $j = 0; $i < 12; $i++){
						if($graphic_array[0][$i][strlen($graphic_array[0][$i])-1] == '-') $assoc = $graphic_array[0][$i];
						elseif($graphic_array[0][$i] != '') {
							$period .= '<option value="'.$assoc.$graphic_array[0][$i].'">'.$assoc.$graphic_array[0][$i].'</option>';
							$assoc = '';
							$j++;
						}
					}
					for($i = 6; $i < 12; $i++){
						if($graphic_array[1][$i][strlen($graphic_array[1][$i])-1] == '-') $assoc = $graphic_array[1][$i];
						elseif($graphic_array[1][$i] != '') {
							$period .= '<option value="'.$assoc.$graphic_array[1][$i].'">'.$assoc.$graphic_array[1][$i].'</option>';
							$assoc = '';
							$j++;
						}
					}
				}
				print($period);
			}else
				exit('Ошибка');
	}
}
?>