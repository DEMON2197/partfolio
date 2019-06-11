<?php
/*
*  Корзина экспозитора
*/

$registered_functions['cart_mapcase'] = "Корзина экспозитора";

function cart_mapcase(){
	global $link, $current_category, $smarty, $site_dir;
	if(isset($_POST['cart']) && isset($_SESSION['user_info']['user_id']) && $_SESSION['user_info']['user_id'] > 0){
		array_map("mysql_real_escape_string", $_POST);
		mb_convert_variables('CP1251', 'CP1251', $_POST);
		require_once($site_dir.'_service/htmlMimeMail5/htmlMimeMail5.php');

		$recipients = array($GLOBALS['site_webmaster']);
        //$recipients = array('nik@novelsite.ru');
		
		$str_array = serialize($_SESSION['cart']);
		
		
		$mail = new htmlMimeMail5();

		$mail->setTextCharset('windows-1251');
		$mail->setHtmlCharset('windows-1251');
		$mail->setHeadCharset('windows-1251');

		$mail->setFrom($GLOBALS['site_webmaster']);
		$mail->setSubject('Экспозитор | заказ');

		$mail_html = "
				Организация: ".$_POST['name_epr']."<br>
				Ф.И.О.: ".$_POST['fio']."<br>
				Телефон: ".$_POST['phone']."<br>
				Email: ".$_POST['email']."<br>
				<br><table>";
		$count = 0;
		foreach($_SESSION['cart'] as $stand_name => $stand){
			$mail_html .= "<tr><td colspan='3'><h2 style='color: #074b87'>".$stand_name.":</h2></td></tr>";
			$mail_html .= "
						<tr>
							<th><strong>ID</strong></th>
							<th><strong>Тип</strong></th>
							<th><strong>Наименование</strong></th>
						</tr>				
				";
			
			foreach($stand as $map_id => $mapcase){
				$mail_html .= "
						<tr>
							<td>".$map_id."</td>
							<td>".$mapcase['type_name']."</td>
							<td>".$mapcase['name']."</td>
						</tr>				
				";
				$count++;
			}
		}
		$mail_html .= "</table>";
		$mail_html .= "<br><br><br><br>--------------------<br>".$GLOBALS['site_domain'];
		
		$mail->setHtml($mail_html);
		
		$sql = "insert into exp_zakaz (user_id, date, epr_name, fio, tel, email, count_plan, array_cart) values (".$_SESSION['user_info']['user_id'].", now(), '".$_POST['name_epr']."', '".$_POST['fio']."', '".$_POST['phone']."', '".$_POST['email']."', ".$count.", '".$str_array."')";
		$query = mysql_query($sql, $link) or sql_error($sql, $link, __FILE__, __LINE__);
		if($query){
			$result = $mail->send(/*array($to)*/$recipients);
			//echo ($result ? "+" : "-")."$to\n";
			if($result){
				session_start();
				$_SESSION['status_zakaz'] = 1;
			}else {
				session_start();
				$_SESSION['status_zakaz'] = 2;
			}
			return header('location: /catalog/cart_plan');
		}else{
			session_start();
			$_SESSION['status_zakaz'] = 2;
		}
	}
	
	if(count($_SESSION['cart']) == 0)
		return header('location: /catalog');
	else{
		return $smarty->fetch('cart_mapcase.tpl');
	}
}
?>