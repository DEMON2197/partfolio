<?php
/*
	Отправка заявки на аттестацию и обучение
*/

require ('../inc/site.inc');

if(!function_exists('mime_content_type')) {
    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}


if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	if(isset($_POST)){

		array_map("mysql_real_escape_string", $_POST);
		mb_convert_variables('cp1251', 'utf8', $_POST);
		
		require_once ('../inc/htmlMimeMail.php');
		
		$mail = new htmlMimeMail();
		$mail->setSMTPParams($GLOBALS['site_mail_relay'], 25, $GLOBALS['site_domain']);
		$mail->setTextCharset('windows-1251');
		$mail->setHtmlCharset('windows-1251');
		$mail->setHeadCharset('windows-1251');
		$mail->setFrom($GLOBALS['site_from']);
		
		
		if($_POST['form_id'] == 'form_popup3' || $_POST['form_id'] == 'form_popup4'){
			if($_POST['form_id'] == 'form_popup3')
				$mail->setSubject('Завка на аттестацию с сайта АСМС ');
			else $mail->setSubject('Завка на обучение с сайта АСМС ');
			$keys['surname'] = array_order(preg_grep("/^surname\d/i", array_keys($_POST)));
			$keys['name'] = array_order(preg_grep("/^name\d/i", array_keys($_POST)));
			$keys['patronymic'] = array_order(preg_grep("/^patronymic\d/i", array_keys($_POST)));
			$keys['date-att'] = array_order(preg_grep("/^date-att\d/i", array_keys($_POST)));
			for($i = 0; $i < count($keys['surname']); $i++){
				$keys['view_izmer'] = array_order(preg_grep("/^view_izmer".($i+1)."\d/iu", array_keys($_POST)));
				$mail_html .= 'Слушатель №'.($i+1).'<br>';
				$mail_html .= '&#160;&#160;&#160;Фамилия: '.$_POST[$keys['surname'][$i]].'<br>';
				$mail_html .= '&#160;&#160;&#160;Имя: '.$_POST[$keys['name'][$i]].'<br>';
				$mail_html .= '&#160;&#160;&#160;Отчество: '.$_POST[$keys['patronymic'][$i]].'<br>';
				if($_POST['form_id'] == 'form_popup3'){
					$mail_html .= '&#160;&#160;&#160;Дата аттестации: '.$_POST[$keys['date-att'][$i]].'<br>';
					$mail_html .= '&#160;&#160;&#160;Перечень средств измерений: '.'<br>';
					for($j = 0; $j < count($keys['view_izmer']); $j++){
						$mail_html .= '&#160;&#160;&#160;&#160;&#160;'.$_POST[$keys['view_izmer'][$j]].'<br>';
					}
				}
			}
		}
		if($_POST['view_face'] == 'enterprise' && ($_POST['uradress'] != null || $_POST['uradress'] != '')){
			$mail_html .= '<br><br><br>Данные предприятия:';
			$mail_html .= '&#160;&#160;&#160;Юридический адрес предприятия (с почтовым индексом): '.$_POST['uradress'].'<br>';
			$mail_html .= '&#160;&#160;&#160;Почтовый адрес предприятия (с почтовым индексом): '.$_POST['postadress'].'<br>';
			$mail_html .= '&#160;&#160;&#160;ОГРН: '.$_POST['ogrn'].'<br>';
			$mail_html .= '&#160;&#160;&#160;ИНН: '.$_POST['inn'].'<br>';
			$mail_html .= '&#160;&#160;&#160;КПП: '.$_POST['kpp'].'<br>';
			$mail_html .= '<br>Банковские реквизиты<br>';
			$mail_html .= '&#160;&#160;&#160;Расчетный счет: '.$_POST['rschet'].'<br>';
			$mail_html .= '&#160;&#160;&#160;Банк: '.$_POST['bank'].'<br>';
			$mail_html .= '&#160;&#160;&#160;Кор. счет: '.$_POST['kschet'].'<br>';
			$mail_html .= '&#160;&#160;&#160;БИК: '.$_POST['bik'].'<br>';
		}
		$mail_html .= '<br><br><br>Лицо, подписывающее договор<br>';
		$mail_html .= '&#160;&#160;&#160;Должность: '.$_POST['ddolzhnost'].'<br>';
		$mail_html .= '&#160;&#160;&#160;Фамилия, имя, отчество: '.$_POST['dfio'].'<br>';
		$mail_html .= '&#160;&#160;&#160;Основание на подписание договора (Устав, Положение, Доверенность №___от___): <br>
			&#160;&#160;&#160;&#160;&#160;&#160;'.$_POST['dosnovanie'].'<br>';
		$mail_html .= '<br><br><br>Контактное лицо (Ф.И.О.; должность; телефон с указанием кода города; E-mail)<br>';
		$mail_html .= '&#160;&#160;&#160;Должность: '.$_POST['kont_dolzhnost'].'<br>';
		$mail_html .= '&#160;&#160;&#160;Ф.И.О.: '.$_POST['kont_fio'].'<br>';
		$mail_html .= '&#160;&#160;&#160;Телефон (с указанием кода города): '.$_POST['kont_phone'].'<br>';
		$mail_html .= '&#160;&#160;&#160;E-mail: '.$_POST['kont_email'].'<br>';
		
		$ext = strtolower(substr(strrchr($_POST['file_name'],'.'), 1));
		$name_md5 = md5($_POST['file_name']);
		$file1 = '../temp/'.$name_md5.'.'.$ext;
		if (file_exists('../temp/'.$file1)) {
			$mail->addAttachment(file_get_contents('../temp/'.$file1), basename('../temp/'.$file1), mime_content_type('../temp/'.$file1));
		}
		
		$mail_html .= '<br>'.$GLOBALS['site_domain'];
		$mail->setHtml($mail_html);
		$recipients = array($GLOBALS['site_webmaster']);

		$result = $mail->send($recipients, $GLOBALS['site_mail_protocol']);
		
		if ($result) {
			echo "1";
		}
		else {
			echo "0";
		}
	}
}

function array_order($array){
	$i = 0;
	$array_order = array();
	foreach($array as $count){
		$array_order[$i] = $count;
		$i++;
	}
	return $array_order;
}