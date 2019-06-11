<?php
/*
* Вывод форм на атестацию или обучение
*/
$registered_functions['form_at_tr'] = "Формы аттестации, обучения";

function form_at_tr($type_form) {
	global $link, $smarty;
	$year_this = (integer)date('o');
	if($type_form == 'training'){
		$sql = "select * from grafik inner join grafik_thema on grafik.grafik_thema_id = grafik_thema.grafik_thema_id where year=$year_this order by grafik_thema.title,grafik.title,polgoda";
		$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
		if($query && mysql_num_rows($query)){
			$graphic_array = array();
			while($row = mysql_fetch_array($query)){
				$graphic_array[] = $row;
			}
		}
		$period = array();
		$assoc = '';
		if($graphic_array[1] != $graphic_array[0]){
			for($i = 6, $j = 0; $i < 12; $i++){
				if($graphic_array[0][$i][strlen($graphic_array[0][$i])-1] == '-') $assoc = $graphic_array[0][$i];
				elseif($graphic_array[0][$i] != '') {
					$period[$j] = $assoc.$graphic_array[0][$i];
					$assoc = '';
					$j++;
				}
			}
		}else{
			for($i = 6, $j = 0; $i < 12; $i++){
				if($graphic_array[0][$i][strlen($graphic_array[0][$i])-1] == '-') $assoc = $graphic_array[0][$i];
				elseif($graphic_array[0][$i] != '') {
					$period[$j] = $assoc.$graphic_array[0][$i];
					$assoc = '';
					$j++;
				}
			}
			for($i = 6; $i < 12; $i++){
				if($graphic_array[1][$i][strlen($graphic_array[1][$i])-1] == '-') $assoc = $graphic_array[1][$i];
				elseif($graphic_array[1][$i] != '') {
					$period[$j] = $assoc.$graphic_array[1][$i];
					$assoc = '';
					$j++;
				}
			}
		}
		
		$smarty->assign('graphic_array', $graphic_array);
		$smarty->assign('period', $period);
		return $smarty->fetch('form_tr.tpl');
		
	}elseif($type_form == 'attestation'){
		$sql = "";
		
		
		return $smarty->fetch('form_at.tpl');
	}
	
}