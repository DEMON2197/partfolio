<?
/**
* Редактор тем графиков

*/
require('../inc/editor.inc');

$act = (isset($act)) ? $act : '';
$grafik_thema_id = (isset($grafik_thema_id)) ? (integer)$grafik_thema_id : 0;
$grafik_thema_title = isset($grafik_thema_title) ? $grafik_thema_title : 0;


if ($act == 'update' && $grafik_thema_id != 0 && trim($grafik_thema_title) != '') {
    $sql = "update grafik_thema set title='$grafik_thema_title' where grafik_thema_id=$grafik_thema_id";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if (!$query) {
        echo '<div>Ошибка</div>';
    }
    else {
        header("Location: graphic_thema.php\n\n");
		echo $grafik_thema_title;
    }
}

elseif ($act == 'delete' && $grafik_thema_id != 0) {
    $sql = "delete from grafik_thema where grafik_thema_id = $grafik_thema_id";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if ($query) {
        header("Location: graphic_thema.php\n\n");
    }
    else {
        echo '<div>Ошибка</div>';
    }
}

elseif ($act == 'add' && trim($grafik_thema_title) != '') {
    $sql = "insert into grafik_thema (title) values('$grafik_thema_title')";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if ($query) {
        header("Location: graphic_thema.php\n\n");
    }
    else {
        echo '<div>Ошибка</div>';
    }
}
?>
<html>
<head>
<title>Редактор тем графиков обучения</title>
<link href=/editor/styles2.css rel=stylesheet type=text/css>
</head>
<body class=txt2 leftmargin=2 topmargin=2 marginwidth=2 marginheight=2>

<table class=table1 width=100% border=1 cellspacing=0 cellpadding=2>
<tr bgcolor=#ffffff>
<td class=td1 width=90%><b>Редактор тем графиков обучения</b></td>
<td class=td1 align=right nowrap><a href=<?=$REQUEST_URI?>><img src="/editor/images/editor/reload.gif" width="16" height="16" alt="Обновить содержимое страницы" border="0" hspace=4 align=texttop>Обновить&nbsp;</a></td>
</tr>
<tr bgcolor=#eaeaea>
<td class=td1 colspan=2>

<table border=0 class=txt2 cellpadding=3>
<form action=<? echo $PHP_SELF;?> method=post onSubmit="post.disabled=true;">
<input type=hidden name=act value=<?=($grafik_thema_id == 0) ? 'add' : 'update'?>>
<?
if ($act == '' && $grafik_thema_id != 0) {
    $sql = "select * from grafik_thema where grafik_thema_id=$grafik_thema_id";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if ($query) {
        $grafik_thema = mysql_fetch_array($query);
    }
?>
<input type=hidden name=grafik_thema_id value=<?=$grafik_thema_id?>>
<?
}
?>
<tr><td class=txt2><b>Название:</b></td><td><input type=text name=grafik_thema_title size=30 value="<?=$grafik_thema['title']?>"></td></tr>
<tr><td class=txt2 colspan=2><input id=post class=txt2b type=submit value="<?=($grafik_thema_id == 0) ? 'Добавить' : 'Изменить' ?>" title="<?=($grafik_thema_id == 0) ? 'Добавить новую тему' : 'Изменить данные' ?>">&nbsp;&nbsp;<input type=button onClick="javascript: parent.service.location = '/editor/graphic_thema.php'" value="Вернуться" title="Вернутся к списку тем (изменения не сохраняются)"></td></tr>
</form>
</table>
<?
if ($act == '' && $grafik_thema_id == 0) {
    $sql = "select * from grafik_thema";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if ($query) {
?>
<table class=table1 border=0 cellpadding=2 cellspacing=0 width=100%>
<tr bgcolor=#d8d8d8 class=td1>
<td class=td1 title="Идентификатор темы"><b>id</b></td>
<td class=td1 title="Название темы"><b>Название</b></td>
<td align=center class=td1 title="Удалить"><img src=/editor/images/editor/delete_doc.gif alt="Удалить запись" border=0></td>
</tr>
<?
        while ($row = mysql_fetch_assoc($query)) {
?>
<tr class=td1 bgcolor=#f1f1f1 onmouseout="this.style.backgroundColor='#eaeaea'" onmouseover="this.style.backgroundColor='<?=$c_list_light?>'">
<td class=td1 nowrap><?=$row['grafik_thema_id']; ?></td>
<td class=td1 nowrap><a href=<?=$PHP_SELF;?>?grafik_thema_id=<?=$row['grafik_thema_id'];?>><?=$row['title'];?></a></td>
<td align=center class=td1 nowrap>&nbsp;<a href="javascript: if (confirm('Действительно удалить?')) {location = '<?=$PHP_SELF?>?grafik_thema_id=<?=$row['grafik_thema_id']?>&act=delete';}"><img src=/editor/images/editor/delete_doc.gif alt="Удалить запись" border=0></a>&nbsp;</td>
</tr>
<?
        }
?>
</table>
<?
    }
}
?>
</body>
</html>