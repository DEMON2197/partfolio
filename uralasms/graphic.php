<?php
/**
* Редактор графиков обучения
*/
require('../inc/editor.inc');

if (isset($_POST['search_year'])) {
		array_map("mysql_real_escape_string", $_POST);
		mb_convert_variables('cp1251', 'utf8', $_POST);
		$sql = "SELECT * 
				FROM grafik
				INNER JOIN grafik_thema ON grafik.grafik_thema_id = grafik_thema.grafik_thema_id
				where grafik.year=".(integer)$_POST['search_year']."
				ORDER BY year DESC,grafik.title
			";
		$query = mysql_query($sql, $link) or sql_error($sql, $link, __FILE__, __LINE__);
		if($query && mysql_num_rows($query)){
			$search = "
				<tr bgcolor=#d8d8d8 class=td1>
				<td class=td1 title=\"Идентификатор графика\"><b>id</b></td>
				<td class=td1 title=\"Название (заголовок)\"><b>Название</b></td>
				<td class=td1 title=\"Тема\"><b>Тема</b></td>
				<td class=td1 title=\"Стоимость\"><b>Стоимость</b></td>
				<td class=td1 title=\"Год\"><b>Год</b></td>
				<td class=td1 title=\"Полугодие\"><b>Полугодие</b></td>
				<td align=center class=td1 title=\"Удалить\"><img src=/editor/images/editor/delete_doc.gif alt=\"Удалить запись\" border=0></td>
				</tr>";
			while($row = mysql_fetch_array($query)){
				 $search .= "
					 <tr class=td1 bgcolor=#f1f1f1 onmouseout=\"this.style.backgroundColor='#eaeaea'\" onmouseover=\"this.style.backgroundColor='".$c_list_light."'\">
					 <td class=td1 nowrap>$row[0]</td>
							<td class=td1 nowrap><a href=".$PHP_SELF."?grafik_id=".$row['grafik_id'].">".$row[2]."</a></td>
							<td class=td1>".$row[14]."</td>
							<td class=td1>".$row[3]."</td>
							<td class=td1>".$row[4]."</td>
							<td class=td1>".($row[5]+1).'-е'."</td>

							<td align=center class=td1 nowrap>&nbsp;<a href=\"javascript: if (confirm('Действительно удалить?')) {location = '".$PHP_SELF."?grafik_id=".$row['grafik_id']."&act=delete';}\"><img src=/editor/images/editor/delete_doc.gif alt=\"Удалить запись\" border=0></a>&nbsp;</td></tr>";
			}
			die($search);
		}else {
			$sql = "SELECT * 
				FROM grafik
				INNER JOIN grafik_thema ON grafik.grafik_thema_id = grafik_thema.grafik_thema_id
				ORDER BY year DESC,grafik.title
			";
			$query = mysql_query($sql, $link) or sql_error($sql, $link, __FILE__, __LINE__);
			if($query && mysql_num_rows($query)){
				$search = "
					<tr bgcolor=#d8d8d8 class=td1>
					<td class=td1 title=\"Идентификатор графика\"><b>id</b></td>
					<td class=td1 title=\"Название (заголовок)\"><b>Название</b></td>
					<td class=td1 title=\"Тема\"><b>Тема</b></td>
					<td class=td1 title=\"Стоимость\"><b>Стоимость</b></td>
					<td class=td1 title=\"Год\"><b>Год</b></td>
					<td class=td1 title=\"Полугодие\"><b>Полугодие</b></td>
					<td align=center class=td1 title=\"Удалить\"><img src=/editor/images/editor/delete_doc.gif alt=\"Удалить запись\" border=0></td>
					</tr>";
				while($row = mysql_fetch_array($query)){
					 $search .= "
						 <tr class=td1 bgcolor=#f1f1f1 onmouseout=\"this.style.backgroundColor='#eaeaea'\" onmouseover=\"this.style.backgroundColor='".$c_list_light."'\">
						 <td class=td1 nowrap>$row[0]</td>
								<td class=td1 nowrap><a href=".$PHP_SELF."?grafik_id=".$row['grafik_id'].">".$row[2]."</a></td>
								<td class=td1>".$row[14]."</td>
								<td class=td1>".$row[3]."</td>
								<td class=td1>".$row[4]."</td>
								<td class=td1>".($row[5]+1).'-е'."</td>

								<td align=center class=td1 nowrap>&nbsp;<a href=\"javascript: if (confirm('Действительно удалить?')) {location = '".$PHP_SELF."?grafik_id=".$row['grafik_id']."&act=delete';}\"><img src=/editor/images/editor/delete_doc.gif alt=\"Удалить запись\" border=0></a>&nbsp;</td></tr>";
				}
				die($search);
			}
		}
}
$act = (isset($act)) ? $act : '';
$grafik_id = (isset($grafik_id)) ? (integer)$grafik_id : 0;
if($grafik_id != 0)
	$grafik_id_file = (integer)$grafik_id;
else{
	$sql = "SELECT grafik_id FROM grafik ORDER BY grafik_id DESC LIMIT 1";
	$query = mysql_query($sql, $link) or sql_error($sql, $link, __FILE__, __LINE__);
	if($query && mysql_num_rows($query)) $grafik_id_file = mysql_fetch_array($query);
	$grafik_id_file = $grafik_id_file[0]+1;
}
$grafik_thema_id = (isset($grafik_thema_id)) ? (integer)$grafik_thema_id : 0;
$half_year = (isset($half_year)) ? (integer)$half_year : 0;
$year = (isset($year)) ? (integer)$year : 0;
$title = (isset($title)) ? $title : '';



if(isset($period)){
	$period_arr = array($period,
						$period1,
						$period2,
						$period3,
						$period4,
						$period5);
}else $period_arr = array();
$cost = (isset($cost)) ? (integer)$cost : 0;
$content = (isset($ed_description_top)) ? $ed_description_top : '';



//загрузка файлов
$pic_patch = $site_dir.'doc_graphics/'.$grafik_id_file;
if (!is_dir($pic_patch)) {
	mkdir ($pic_patch);
	chmod($pic_patch.'/', 0775);
}

$pic_patch = $pic_patch.'/';

for ($i=1; $i<=5; $i++) {
	$name_file = trim($_FILES['doc'.$i]['name']);
	if($name_file != '') {
		$ext = strtolower(substr(strrchr($name_file,'.'), 1));
		if($ext == 'doc' || $ext == 'docx' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'png' || $ext == 'jpg' || $ext == 'pdf'){
			move_uploaded_file($_FILES['doc'.$i]['tmp_name'], $pic_patch.$grafik_id_file.'_'.$i.'.'.$ext);
			chmod($pic_patch.$grafik_id_file.'_'.$i.'.'.$ext, 0664);
		}
	}
}



if(isset($_GET['act_file']) && $_GET['act_file'] == 'del_doc'){
	if(unlink($site_dir.'doc_graphics/'.$_GET['gr_id'].'/'.$_GET['file_name'])) echo 'Файл удалён';;
}

if ($act == 'update' && $grafik_id != 0 && trim($title) != '') {
    $sql = "update grafik set grafik_thema_id=$grafik_thema_id, polgoda=$half_year, year=$year, title='$title',
			period1='$period_arr[0]',
			period2='$period_arr[1]',
			period3='$period_arr[2]',
			period4='$period_arr[3]',
			period5='$period_arr[4]',
			period6='$period_arr[5]',
			price=$cost,
			content='$content'
			where grafik_id=$grafik_id and polgoda=$half_year";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if (!$query) {
        echo '<div>Ошибка</div>';
    }
    else {
		header("Location: graphic.php\n\n");
    }
}

elseif ($act == 'delete' && $grafik_id != 0) {
	removeDirectory($site_dir.'doc_graphics/'.$grafik_id);
    $sql = "delete from grafik where grafik_id = $grafik_id";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if ($query) {
        header("Location: graphic.php\n\n");
    }
    else {
        echo '<div>Ошибка</div>';
    }
}

elseif ($act == 'add' && trim($title) != '') {
    $sql = "insert into grafik (grafik_thema_id, polgoda, year, title, period1, period2, period3, period4, period5, period6, price, content) 
			values('$grafik_thema_id', $half_year, $year, '$title',
			'$period_arr[0]',
			'$period_arr[1]',
			'$period_arr[2]',
			'$period_arr[3]',
			'$period_arr[4]',
			'$period_arr[5]',
			$cost,
			'$content')";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if ($query) {
        header("Location: graphic.php\n\n");
    }
    else {
        echo '<div>Ошибка</div>';
    }
}

function removeDirectory($dir){
  if ($objs = glob($dir."/*")) {
     foreach($objs as $obj) {
       is_dir($obj) ? removeDirectory($obj) : unlink($obj);
     }
  }
  if(rmdir($dir)) return true; else return false;
}
?>
<html>
<head>
<title>Редактор графиков обучения</title>
<link href=/editor/styles2.css rel=stylesheet type=text/css>
<script type="text/javascript" src="/editor/fckeditor/fckeditor.js"></script>
</head>
<body class=txt2 leftmargin=2 topmargin=2 marginwidth=2 marginheight=2>

<table class=table1 width=100% border=1 cellspacing=0 cellpadding=2>
<tr bgcolor=#ffffff>
<td class=td1 width=90%><b>Редактор графиков обучения</b></td>
<td class=td1 align=right nowrap><a href=<?=$REQUEST_URI?>><img src="/editor/images/editor/reload.gif" width="16" height="16" alt="Обновить содержимое страницы" border="0" hspace=4 align=texttop>Обновить&nbsp;</a></td>
</tr>
<tr bgcolor=#eaeaea>
<td class=td1 colspan=2>

<table border=0 class=txt2 cellpadding=3>
	<form action=<? echo $PHP_SELF;?> method=post onSubmit="post.disabled=true;" enctype="multipart/form-data">
		<input type=hidden name=act value=<?=($grafik_id == 0) ? 'add' : 'update'?>>
		<?
		if ($act == '' && $grafik_id != 0) {
			$sql = "select * from grafik where grafik_id=$grafik_id";
			$query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
			if ($query) {
				$grafik = mysql_fetch_assoc($query);
			}
		?>
		<input type=hidden name=grafik_id value=<?=$grafik_id?>>
		<?
		}
		$sql = "select * from grafik_thema order by title";
			$query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
			if ($query) {
				while($row = mysql_fetch_array($query)){
					$grafik_thema[] = $row;
				}
			}
		?>
		<tr><td class=txt2 style="width: 150px;"><b>Название:</b></td><td><input type=text name=title size=50 value="<?=$grafik['title']?>"></td></tr>
		<tr><td><b>Тема:</b></td><td><select name=grafik_thema_id class=txt2 title="Привязка к теме">
			<?
				foreach($grafik_thema as $count){
					echo "<option value=\"".$count['grafik_thema_id']."\" ".(($count['grafik_thema_id'] == $grafik['grafik_thema_id']) ? 'selected' : '').">".$count['title']."</option>";
				}
			?>
		</select></td></tr>
		<tr><td class=txt2 width="200"><b>Год:</b></td><td><input type=text size=4 name=year value=<?=$grafik['year'];?>></td></tr>
		<tr><td class=txt2 width="200"><b>Полугодие:</b></td><td><span>Первое</span><input type=radio name=half_year value=0 <?=(isset($grafik['polgoda'])) ? (($grafik['polgoda'] == 0) ? 'checked' : '') : 'checked';?> onchange="change_half(this.value);"> /&nbsp;&nbsp;<span>Второе</span><input type=radio name=half_year value=1 <?=($grafik['polgoda'] == 1) ? 'checked' : '';?>  onchange="change_half(this.value);"></td></tr>
		<tr><td class=txt2 width="200"><b>Периоды обучения:</b></td></tr>

		<?
			if(isset($grafik['polgoda'])) $period_str = ($grafik['polgoda'] == 1) ? array('07', '08', '09', '10', '11', '12') : array('01', '02', '03', '04', '05', '06');
			else $period_str = array('01', '02', '03', '04', '05', '06');
			
		?>


		<tr>
			<td colspan="2">
				<table>
					<thead>
						<tr>
							<td class="period"><?=$period_str[0]?></td>
							<td class="period"><?=$period_str[1]?></td>
							<td class="period"><?=$period_str[2]?></td>
							<td class="period"><?=$period_str[3]?></td>
							<td class="period"><?=$period_str[4]?></td>
							<td class="period"><?=$period_str[5]?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input name="period" type="text" size=10 value=<?=$grafik['period1'];?>></td>
							<td><input name="period1" type="text" size=10 value=<?=$grafik['period2'];?>></td>
							<td><input name="period2" type="text" size=10 value=<?=$grafik['period3'];?>></td>
							<td><input name="period3" type="text" size=10 value=<?=$grafik['period4'];?>></td>
							<td><input name="period4" type="text" size=10 value=<?=$grafik['period5'];?>></td>
							<td><input name="period5" type="text" size=10 value=<?=$grafik['period6'];?>></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	<tr>
		<td colspan="2">Стоимость: <input name=cost type=text size=10 value=<?=$grafik['price'];?>>руб.</td>
	</tr>
	<tr>
		<td colspan="2">Информация:</td>
	</tr>
	<tr>
		<td colspan="3" bgcolor=#ffffff valign=top align=left width=900><b>Верхнее описание:</b><br>
			<textarea id="ed_description_top" name="ed_description_top"><?=preg_replace("/(<\/textarea>)/i", "&lt;/textarea&gt;", $grafik['content'])?></textarea>
			<script>
					var oFCKeditor = new FCKeditor('ed_description_top');
					oFCKeditor.Height = 400;
					oFCKeditor.Width = '98%';
					oFCKeditor.ReplaceTextarea();
			</script>
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<br>
				<fieldset>
					<legend><strong>Прикрепить документы</strong></legend>
					<?
						for ($i=1; $i<=5; $i++) {
							$file_name = $site_dir.'doc_graphics/'.$grafik_id_file.'/'.$grafik_id_file.'_'.$i.'.';
							if(file_exists($file_name.'doc')) $file_doc = 'doc_graphics/'.$grafik_id_file.'/'.$grafik_id_file.'_'.$i.'.doc';
							elseif(file_exists($file_name.'docx')) $file_doc = 'doc_graphics/'.$grafik_id_file.'/'.$grafik_id_file.'_'.$i.'.docx';
							elseif(file_exists($file_name.'xls')) $file_doc = 'doc_graphics/'.$grafik_id_file.'/'.$grafik_id_file.'_'.$i.'.xls';
							elseif(file_exists($file_name.'xlsx')) $file_doc = 'doc_graphics/'.$grafik_id_file.'/'.$grafik_id_file.'_'.$i.'.xlsx';
							elseif(file_exists($file_name.'png')) $file_doc = 'doc_graphics/'.$grafik_id_file.'/'.$grafik_id_file.'_'.$i.'.png';
							elseif(file_exists($file_name.'jpg')) $file_doc = 'doc_graphics/'.$grafik_id_file.'/'.$grafik_id_file.'_'.$i.'.jpg';
							elseif(file_exists($file_name.'pdf')) $file_doc = 'doc_graphics/'.$grafik_id_file.'/'.$grafik_id_file.'_'.$i.'.pdf';
							else $file_doc = '';
							$file_name = str_replace('doc_graphics/'.$grafik_id_file.'/', '', $file_doc);
					?>
							<b>Документ <?=$i?>:</b>&nbsp;<input name="doc<?=$i?>" type="file" class=txt2 title="Выбор файла <?=$i?> с локального диска" value=""><? if ($file_doc != '') { ?><?=$file_name?>&nbsp;&nbsp;<b><a target=service href="javascript: if (confirm('Удалить файл <?=$i?>?')) { parent.service.location = '<?=$PHP_SELF?>?act_file=del_doc&file_name=<?=$file_name?>&gr_id=<?=$grafik_id?>';}" title="Удалить файл <?=$i?>?">[x]</a><? } ?></b><br>
					<?
						}
					?>
				</fieldset>
			<br>
		</td>
	</tr>
	<tr>
		<td class=txt2 colspan=2>
			<input id=post class=txt2b type=submit value="<?=($grafik_id == 0) ? 'Добавить' : 'Изменить' ?>" title="<?=($grafik_id == 0) ? 'Добавить график' : 'Изменить данные' ?>">
			&nbsp;&nbsp;
			<input type=button onClick="javascript: parent.service.location = '/editor/graphic.php'" value="Вернуться" title="Вернутся к списку групп (изменения не сохраняются)">
		</td>
	</tr>
	</form>
</table>
<?
if ($act == '' && $grafik_id == 0) {
		$sql = "SELECT * 
			FROM grafik
			INNER JOIN grafik_thema ON grafik.grafik_thema_id = grafik_thema.grafik_thema_id
			ORDER BY year DESC,grafik.title
		";
		$query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if ($query) {
?>
<table class=table1 id=search_year border=0 cellpadding=2 cellspacing=0 width=100%>
<tr><input name="select_search" id="select_search" value=""><button onclick="postResult();">Выбрать год</button></tr>
<tr bgcolor=#d8d8d8 class=td1>
<td class=td1 title="Идентификатор графика"><b>id</b></td>
<td class=td1 title="Название (заголовок)"><b>Название</b></td>
<td class=td1 title="Тема"><b>Тема</b></td>
<td class=td1 title="Стоимость"><b>Стоимость</b></td>
<td class=td1 title="Год"><b>Год</b></td>
<td class=td1 title="Полугодие"><b>Полугодие</b></td>
<td align=center class=td1 title="Удалить"><img src=/editor/images/editor/delete_doc.gif alt="Удалить запись" border=0></td>
</tr>
<?
        while ($row = mysql_fetch_array($query)) {
?>
<tr class=td1 bgcolor=#f1f1f1 onmouseout="this.style.backgroundColor='#eaeaea'" onmouseover="this.style.backgroundColor='<?=$c_list_light?>'">
<td class=td1 nowrap><?=$row[0]; ?></td>
<td class=td1 nowrap><a href=<?=$PHP_SELF;?>?grafik_id=<?=$row['grafik_id'];?>><?=$row[2];?></a></td>
<td class=td1><?=$row[14]; ?></td>
<td class=td1><?=$row[3]; ?></td>
<td class=td1><?=$row[4]; ?></td>
<td class=td1><?=($row[5]+1).'-е'; ?></td>

<td align=center class=td1 nowrap>&nbsp;<a href="javascript: if (confirm('Действительно удалить?')) {location = '<?=$PHP_SELF?>?grafik_id=<?=$row['grafik_id']?>&act=delete';}"><img src=/editor/images/editor/delete_doc.gif alt="Удалить запись" border=0></a>&nbsp;</td>
</tr>
<?
        }
?>
</table>
<?
    }
}
?>
<script type="text/javascript">
//Для динамического поиска
	function postResult(){
		var xhr_year = new XMLHttpRequest();
		var sel_cat=document.getElementById("select_search").value;
		var result_cat = encodeURIComponent(sel_cat);
		xhr_year.open("POST", "<?=$REQUEST_URI?>");
		xhr_year.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr_year.send("search_year=" + result_cat);
		xhr_year.onreadystatechange = function (){
			if(xhr_year.readyState == 4){
				if(xhr_year.responseText != 0)
					document.getElementById("search_year").innerHTML = xhr_year.responseText;
				xhr_year.result_cat;
			}
			
		}
	};
//Для динамического поиска



//Для смены месяцев
	function change_half(value){
		if(value == 0)
			for(var i = 0; i < 6; i++)
				document.getElementsByClassName("period")[i].innerHTML="0"+(i+1);
		else
			for(i = 6, j = 0; i < 12; i++, j++)
				if(i < 9)
					document.getElementsByClassName("period")[j].innerHTML="0"+(i+1);
				else document.getElementsByClassName("period")[j].innerHTML=i+1;
		return true;
	};
</script>
</body>
</html>