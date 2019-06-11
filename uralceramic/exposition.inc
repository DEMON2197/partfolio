<?php
/*
вывод стендов и планшетов, добавление в корзину
*/


$registered_functions['exposition'] = "Ёкспозитор";

function exposition(){
    global $link, $current_category, $smarty;
	$_POST = array_map("mysql_real_escape_string", $_POST);
	$_GET = array_map("mysql_real_escape_string", $_GET);
	if(!isset($_GET['stand_id']) && !isset($_GET['select'])){
		//Если нужен список стендов
		$sql = "select * from stand where ";
		$keys['check'] = preg_grep("/^check\d/i", array_keys($_GET));
		if(count($keys['check']) || isset($_GET['check_new'])){
			for($i = 0; $i < count($keys['check']); $i++){
				if($i == count($keys['check'])-1 && count($keys['check'])-1 != 0) $sql .= " type_id=".$_GET[$keys['check'][$i]].")";
				elseif(count($keys['check'])-1 == 0) $sql .= " type_id=".$_GET[$keys['check'][$i]];
				elseif($i == 0)
					$sql .= " (type_id=".$_GET[$keys['check'][$i]]." or";
				else 
					$sql .= " type_id=".$_GET[$keys['check'][$i]]." or";
			}
			$sql .= isset($_GET['check_new']) ? (count($keys['check']) ? " and new=1" : "new=1") : '';
		}else $sql .= '1';
		$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
		if($query){
			$stand_array = array();
			while($row = mysql_fetch_assoc($query)){
				$stand_array[] = $row;
			}
		}
		$sql = "select * from stand_type";
		$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
		if($query){
			$types = array();
			while($row = mysql_fetch_assoc($query)){
				$types[] = $row;
			}
		}
		$smarty->assign("type", 'stand');
		$smarty->assign("rows", ceil(count($stand_array)/3));
		$smarty->assign("types", $types);
		$smarty->assign("stands", $stand_array);
		return $smarty->fetch('stands.tpl');
		
	}elseif(isset($_GET['stand_id']) && $_GET['select'] == 'mapcase'){
		//Выбран список планшетов стенда
		$sql = "select mapcase.*, mapcase_type.*, stand.name stand_name from mapcase left join mapcase_type on mapcase_type.id=mapcase.type_id
				left join stand on stand.id=mapcase.stand_id
				where stand_id=".$_GET['stand_id']." and ";
		$keys['check'] = preg_grep("/^check\d/i", array_keys($_GET));
		if(count($keys['check']) || isset($_GET['check_new'])){
			for($i = 0; $i < count($keys['check']); $i++){
				if($i == count($keys['check'])-1 && count($keys['check'])-1 != 0) $sql .= " mapcase.type_id=".$_GET[$keys['check'][$i]].")";
				elseif(count($keys['check'])-1 == 0) $sql .= " mapcase.type_id=".$_GET[$keys['check'][$i]];
				elseif($i == 0)
					$sql .= " (mapcase.type_id=".$_GET[$keys['check'][$i]]." or";
				else 
					$sql .= " mapcase.type_id=".$_GET[$keys['check'][$i]]." or";
			}
			$i = 0;
			foreach($keys['check'] as $check){
				
			}
			$sql .= isset($_GET['check_new']) ? (count($keys['check']) ? " and mapcase.new=1" : "mapcase.new=1") : '';
		}else $sql .= '1';
		$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
		if($query){
			$stand_array = array();
			while($row = mysql_fetch_array($query)){
				$stand_array[] = $row;
			}
			$sql = "select * from mapcase_type left join mapcase on mapcase_type.id=mapcase.type_id where stand_id=".$_GET['stand_id']." group by type_id";
			$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
			if($query){
				$types = array();
				while($row = mysql_fetch_array($query))
					$types[] = array(
						'id' => $row['type_id'],
						'type_name' => $row['type_name']
					);
			}
		}
		$current_category['title'] .= ' '.$stand_array[0]['stand_name'];
		$current_category['navy_array'][] = array('title' => $stand_array[0]['stand_name'], 'navy_tag' => $current_category['navy_tag'].'?select=mapcase&stand_id='.$stand_array['stand_id'], 'type' => 0);
		$smarty->assign("current_category", $current_category);
		$smarty->assign("type", 'mapcase');
		$smarty->assign("rows", ceil(count($stand_array)/3));
		$smarty->assign("types", $types);
		$smarty->assign("stands", $stand_array);
		return $smarty->fetch('stands.tpl');
		
		
	}elseif(isset($_GET['stand_id']) && $_GET['select'] == 'card' && !isset($_GET['mapcase_id'])){
		//Если нужна карточка стенда
		$sql = "select * from stand where id=".$_GET['stand_id'];
		$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
		if($query){
			$stand = mysql_fetch_assoc($query);
		}
		$sql = "select * from stand_type";
		$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
		if($query){
			$types = array();
			while($row = mysql_fetch_assoc($query)){
				$types[] = $row;
			}
		}
		$current_category['title'] .= ' '.$stand['name'];
		$smarty->assign("current_category", $current_category);
		$smarty->assign("type", 'stand');
		$smarty->assign("stand", $stand);
		$smarty->assign("types", $types);
		return $smarty->fetch('stand_card.tpl');

	}elseif(isset($_GET['mapcase_id']) && $_GET['select'] == 'card' && isset($_GET['stand_id'])){
		//Если нужна карточка планшета
		$sql = "select mapcase.*, stand.name stand_name from mapcase
				left join stand on stand.id=mapcase.stand_id
				where mapcase.id=".$_GET['mapcase_id'];
		$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
		if($query){
			$stand = mysql_fetch_assoc($query);
			$sql = "select * from mapcase_type left join mapcase on mapcase_type.id=mapcase.type_id
					where stand_id=".$_GET['stand_id']." group by type_id";
			$query = mysql_query($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
			if($query){
				$types = array();
				while($row = mysql_fetch_array($query))
					$types[] = array(
						'id' => $row['type_id'],
						'type_name' => $row['type_name']
					);
			}
		$current_category['title'] .= ' '.$stand['name'];
		$current_category['navy_array'][] = array('title' => $stand['stand_name'], 'navy_tag' => $current_category['navy_tag'].'?select=mapcase&stand_id='.$stand['stand_id'], 'type' => 0);
		$current_category['navy_array'][] = array('title' => $stand['name'],
													'navy_tag' => $current_category['navy_tag'].'?select=card&mapcase_id='.$stand['id'], 
													'type' => 0);
		$smarty->assign("current_category", $current_category);
		$smarty->assign("type", 'mapcase');
		$smarty->assign("stand", $stand);
		$smarty->assign("types", $types);
		return $smarty->fetch('stand_card.tpl');
	}
}
}