<?php
$registered_functions['graphics'] = "¬ывод графика";

function graphics() {
    global $link, $smarty, $http_category_values, $current_category;
	//если выбрал полугодие текущего года
	$year_this = (integer)date('o');
	$year = $year_this;
	$smarty->assign('year', $year);
	$smarty->assign('year_this', $year_this);
	$sql = "select * from grafik group by year order by year DESC";
	$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
	$year_array = array();
	if($query && mysql_num_rows($query)){
		while($row = mysql_fetch_array($query)){
			$year_array[] = $row;
		}
	}
	if(isset($http_category_values[0])){
		$http_category_values = array_map("mysql_escape_string", $http_category_values);
		if($http_category_values[0] == 'archive')
			$sql = "select * from grafik inner join grafik_thema on grafik.grafik_thema_id = grafik_thema.grafik_thema_id  where grafik.grafik_id=$http_category_values[1]";
		else $sql = "select * from grafik inner join grafik_thema on grafik.grafik_thema_id = grafik_thema.grafik_thema_id  where grafik.grafik_id=$http_category_values[0]";
		$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
		if($query && mysql_num_rows($query)){
			$graphic_row = mysql_fetch_array($query);
			$smarty->assign('graphic_row', $graphic_row);
			if($http_category_values[0] == 'archive'){
				$smarty->assign('graphic_files', scandir($site_dir.'doc_graphics/'.$http_category_values[1]));
				$current_category['navy_array'][] = array('title' => 'јрхив', 'navy_tag' => $current_category['navy_tag'].'?archive=archive', 'type' => 0);
				$current_category['navy_array'][] = array('title' => $graphic_row['year'], 'navy_tag' => $current_category['navy_tag']."?archive=$graphic_row[4]", 'type' => 0);
				$current_category['navy_array'][] = array('title' => $graphic_row[14].' '.$graphic_row[2], 'navy_tag' => $current_category['navy_tag'].'-archive-'.$graphic_row['grafik_id'], 'type' => 0);
			}else {
				$smarty->assign('graphic_files', scandir($site_dir.'doc_graphics/'.$http_category_values[0]));
				$half_year = $graphic_row['polgoda'];
				$current_category['navy_array'][] = array('title' => ($half_year+1).'-е полугодие '.$year, 'navy_tag' => $current_category['navy_tag']."?half_year=$half_year", 'type' => 0);
				$current_category['navy_array'][] = array('title' => $graphic_row[14].' '.$graphic_row[2], 'navy_tag' => $current_category['navy_tag'].'-'.$graphic_row['grafik_id'], 'type' => 0);
			}
			return $smarty->fetch('graphic_content.tpl');
		}
	} else{
		$smarty->assign('year_array', $year_array);
			if(isset($_GET['half_year'])){
				//узнать текущий год
				$half_year = mysql_real_escape_string($_GET['half_year']);
				if($half_year != '1' && $half_year != '0') $half_year = (integer)date('n') < 7 ? '0' : '1';
				$sql = "select * from grafik_thema 
						inner join grafik on grafik_thema.grafik_thema_id = grafik.grafik_thema_id where polgoda=$half_year and year=$year";
				$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
				$graphic_array = array();
				if($query && mysql_num_rows($query)){
					while($row = mysql_fetch_array($query)){
						$graphic_array[] = $row;
					}
				}
				
				$graphic_display = graphic_struct_array($graphic_array, 6);
				
				$smarty->assign('type', 'this_year');
				$smarty->assign('half_year', ($half_year == '1' ? 'pol_two' : 'pol_one'));
				$smarty->assign('graphic_display', $graphic_display);
				$current_category['navy_array'][] = array('title' => ($half_year+1).'-е полугодие '.$year_this, 'navy_tag' => $current_category['navy_tag']."?half_year=$half_year", 'type' => 0);
				return $smarty->fetch('graphic_table.tpl');
			}
			//если выбрал архив архив
			elseif(isset($_GET['archive'])){
				//проверка на требуемое событие
				$year = mysql_real_escape_string($_GET['archive']);
				if($year != null) $year = $year == 'archive' ? (integer)date('o')-1 : (!preg_match('/\\d{4}/', $year) ? (integer)date('o')-1 : $year);
				else $year = (integer)date('o');
				
				
				
				$sql = "select * from grafik 
						inner join grafik_thema on grafik_thema.grafik_thema_id = grafik.grafik_thema_id where year=$year order by grafik_thema.title,polgoda";
				$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
				$graphic_array = array();
				if($query && mysql_num_rows($query)){
					while($row = mysql_fetch_array($query)){
						$graphic_array[] = $row;
					}
				}
				
				
				$graphic_display = graphic_struct_array($graphic_array, 12);
				
				
				$smarty->assign('year', $year);
				$smarty->assign('type', 'archive');
				$smarty->assign('graphic_display', $graphic_display);
				$current_category['navy_array'][] = array('title' => 'јрхив', 'navy_tag' => $current_category['navy_tag'].'?archive=archive', 'type' => 0);
				$current_category['navy_array'][] = array('title' => $year, 'navy_tag' => $current_category['navy_tag']."?archive=$year", 'type' => 0);
				return $smarty->fetch('graphic_table.tpl');
			}
			//в остальных случа€х
			else {
				$half_year = (integer)date('n') < 7 ? '0' : '1';
				$sql = "select * from grafik_thema 
						inner join grafik on grafik_thema.grafik_thema_id = grafik.grafik_thema_id where polgoda=$half_year and year=$year";
				$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
				$graphic_array = array();
				if($query && mysql_num_rows($query)){
					while($row = mysql_fetch_array($query)){
						$graphic_array[] = $row;
					}
				}
				
				$graphic_display = graphic_struct_array($graphic_array, 6);
				
				
				$smarty->assign('type', 'this_year');
				$smarty->assign('half_year', ($half_year == '1' ? 'pol_two' : 'pol_one'));
				$smarty->assign('graphic_display', $graphic_display);
				$current_category['navy_array'][] = array('title' => ($half_year+1).'-е полугодие '.$year_this, 'navy_tag' => $current_category['navy_tag']."?half_year=$half_year", 'type' => 0);
				return $smarty->fetch('graphic_table.tpl');
			}
	}
}

function graphic_struct_array($graphic_array, $num){
	if($num == 6){
		$graphic_display = array();
		$i = 0;
		$str = null;
		foreach($graphic_array as $count){
			if($count[1] != $str){
				$str = $count[1];
				$graphic_display[$i] = array(
					$count[1],
					array(
						$count[4],
						$count[5],
						$count[8],
						$count[9],
						$count[10],
						$count[11],
						$count[12],
						$count[13],
						'grafik_id' => $count['grafik_id']
					)
				);
			}
			else array_push($graphic_display[$i], 
					array(
						$count[4],
						$count[5],
						$count[8],
						$count[9],
						$count[10],
						$count[11],
						$count[12],
						$count[13],
						'grafik_id' => $count['grafik_id']
					)
				);
			$i++;
		}
	}elseif($num == 12){
		$graphic_display = array();
		$str = null;
		for($i = 0; $i < count($graphic_array); $i += 2){
			if($graphic_array[$i][14] != $str){
				$str = $graphic_array[$i][14];
				$graphic_display[$i] = array(
					$graphic_array[$i][14],
					array(
						$graphic_array[$i][2],
						$graphic_array[$i][3],
						$graphic_array[$i][6],
						$graphic_array[$i][7],
						$graphic_array[$i][8],
						$graphic_array[$i][9],
						$graphic_array[$i][10],
						$graphic_array[$i][11],
						$graphic_array[$i+1][6],
						$graphic_array[$i+1][7],
						$graphic_array[$i+1][8],
						$graphic_array[$i+1][9],
						$graphic_array[$i+1][10],
						$graphic_array[$i+1][11],
						'grafik_id' => $graphic_array[$i]['grafik_id']
					)
				);
			}
			else array_push($graphic_display[$i], 
					array(
						$graphic_array[$i][2],
						$graphic_array[$i][3],
						$graphic_array[$i][6],
						$graphic_array[$i][7],
						$graphic_array[$i][8],
						$graphic_array[$i][9],
						$graphic_array[$i][10],
						$graphic_array[$i][11],
						$graphic_array[$i+1][6],
						$graphic_array[$i+1][7],
						$graphic_array[$i+1][8],
						$graphic_array[$i+1][9],
						$graphic_array[$i+1][10],
						$graphic_array[$i+1][11],
						'grafik_id' => $graphic_array[$i]['grafik_id']
					)
			);
		}
	}
	return $graphic_display;
}
