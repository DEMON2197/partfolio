<?
/**
* Список программ управления экспозитором
*/

require ('../inc/editor.inc');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Экспозитор</title>
<link href=/editor/styles2.css rel=stylesheet type=text/css>
</head>
<body class=txt2 leftmargin=4 topmargin=4 marginwidth=4 marginheight=4>
<script language="JavaScript" type="text/javascript">
<!--
    parent_frame = parent.document.getElementsByName("main_service");

    if (parent_frame) {
        parent_frame[0].cols = "25%,*";
    }
    else {
        parent.main_service.cols = "25%,*";
    }

    parent.service.location = 'empty.php';

//-->
</script>
<img src="/editor/images/editor/directory.gif" width="16" height="16" alt="" border="0" align=absmiddle>&nbsp;<b>Экспозитор</b>
<hr size=1>
<li><a href=stands.php target=service><img src="/editor/images/old-images/folder_open.gif" alt="" border="0" align=absmiddle><b>Редактор стендов</b></a>
<li><a href=stands_type.php target=service><img src="/editor/images/old-images/delimiter.gif" alt="" border="0" align=absmiddle><b>Редактор типов стендов</b></a>
<li><a href=tablets.php target=service><img src="/editor/images/editor/directory.gif" alt="" border="0" align=absmiddle>&nbsp;<b>Редактор планшетов</b></a>
<li><a href=tablets_type.php target=service><img src="/editor/images/old-images/delimiter.gif" alt="" border="0" align=absmiddle><b>Редактор типов планшетов</b></a>
</body>
</html>
