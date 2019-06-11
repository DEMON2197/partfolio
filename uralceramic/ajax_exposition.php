<?php
/*
* Добавление и удаление товаров из карзины экспозитора
*/
require ('../inc/site.inc');
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	array_map("mysql_real_escape_string", $_POST);
	mb_convert_variables('CP1251', 'UTF8', $_POST);
	$_POST['id'] = (integer)$_POST['id'];
	session_start();
	//unset($_SESSION['cart']);
		//Если добавить
		if($_POST['act'] == 'add'){
			$sql = "select * from mapcase inner join stand on mapcase.stand_id=stand.id where mapcase.id=".$_POST['id'];
			$query = mysql_query($sql, $link) or sql_error($sql, $link, __FILE__, __LINE__);
			if($query){
				$mapcase = mysql_fetch_array($query);
				$_SESSION['cart'][$mapcase[8]][$mapcase[0]] = $mapcase[1];
			}
		}
		//Если удалить
		elseif($_POST['act'] == 'delete'){
			unset($_SESSION['cart'][$_POST['stand_name']][$_POST['id']]);
			if(count($_SESSION['cart'][$_POST['stand_name']]) == 0)
				unset($_SESSION['cart'][$_POST['stand_name']]);
		}
	?><tr>
		<th colspan="2">ВЫБРАННЫЕ</th>
		</tr><?
	foreach($_SESSION['cart'] as $k => $stand){?>
		
		<tr>
		<td colspan="2" class="cart_name"><?=$k?></td>
		</tr>
		<?foreach($stand as $k_m => $mapcase){?>
			<tr>
				<td><b><?=$mapcase?></b></td>
				<td><a href="#" data-act="deleteCart" data-mapcase-id="<?=$k_m?>" data-stand-name="<?=$k?>"><i class="fa fa-minus-circle"></i></a></td>
			</tr>
		<?}
	}
}


?>