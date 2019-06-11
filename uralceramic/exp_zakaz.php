<?php 
/*
* ������
*/
require('../inc/editor.inc');
require_once('../inc/PHPExcel.php');
$act = (isset($act)) ? $act : '';
$zakaz_id = (isset($zakaz_id)) ? (integer)$zakaz_id : 0;
$date_min = (isset($date_min)) ? $date_min : '';
$date_max = (isset($date_max)) ? $date_max : '';
$xls = (isset($xls)) ? $xls : 0;
if($xls == 1){

	//��������� ����� excel
	$pExcel = new PHPExcel();
	$pExcel->setActiveSheetIndex(0);
	$aSheet = $pExcel->getActiveSheet();
	// ���������� �������� �  ������ �����
	$aSheet->getPageSetup()
		->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$aSheet->getPageSetup()
		->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	// �������� �����
	//mb_convert_variables('UTF-8', "CP1251", $text);
	$aSheet->setTitle(iconv('CP1251', 'UTF-8', '������ ���������'));
	
	// ��������� ������
	$pExcel->getDefaultStyle()->getFont()->setName('Arial');
	$pExcel->getDefaultStyle()->getFont()->setSize(12);
	//������ ��������
	$aSheet->getColumnDimension('A')->setWidth(34);
	$aSheet->getColumnDimension('B')->setWidth(10);
	$aSheet->getColumnDimension('C')->setWidth(12);
	$aSheet->getColumnDimension('F')->setWidth(15);
	$aSheet->getColumnDimension('G')->setWidth(15);
	//����������� �����
	$aSheet->mergeCells('A1:E1');
	//����� ��� �����
	$style_price = array(
		// ������������
		'alignment' => array(
		'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
		),
		// �����
		'font'=>array(
			'bold' => true,
		),
	);
	$style_price2 = array(
		// ������������
		'alignment' => array(
		'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
		),
		// �����
		'font'=>array(
			'bold' => true,
		),
	);
	$aSheet->getStyle('A1:M1')->applyFromArray($style_price);
	//����� �����
	$aSheet->setCellValue('A1', iconv('CP1251', 'UTF-8', '������������. ���������� ���������'));
	$aSheet->setCellValue('F1', iconv('CP1251', 'UTF-8', "� ".$date_min));
	$aSheet->setCellValue('G1', iconv('CP1251', 'UTF-8', "�� ".$date_max));

	
	
	//�������� ������ ��������� � ��������� �� ����
	$sql = "select * from exp_zakaz";
	if($date_min != '' && $date_max != '')
		$sql .=  " WHERE DATE(date) BETWEEN '".$date_min."' AND '".$date_max."'";
	else $sql .= " WHERE week(`date`, 1) = week(NOW(), 1) AND YEAR(`date`) = YEAR(NOW())";
	$sql .= " order by date desc";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
	
	//������� � ����� ��������� �������
	$rows = array();
	while($rows[] = mysql_fetch_assoc($query));
	$i = 0; $j = 4;
	foreach($rows as $row){
		if($i == mysql_num_rows($query)) break;
		$array_zakaz = unserialize($row['array_cart']);
		mb_convert_variables('UTF-8', "CP1251", $array_zakaz);
		$aSheet->mergeCells('A'.($i+$j).':C'.($i+$j));
		$aSheet->getStyle("A".($i+$j).":"."A".($i+$j+3))->applyFromArray($style_price2);
		$aSheet->setCellValue('A'.($i+$j), iconv('CP1251', 'UTF-8', "�.�.�.: ".$row['fio'])); $j++;
		$aSheet->setCellValue('A'.($i+$j), iconv('CP1251', 'UTF-8', "���.: ".$row['tel'])); $j++;
		$aSheet->setCellValue('A'.($i+$j), iconv('CP1251', 'UTF-8', "Email: ".$row['email'])); $j+=2;
		foreach($array_zakaz as $stand_name => $stand){
			$aSheet->mergeCells('A'.($i+$j).':C'.($i+$j));
			$aSheet->getStyle('A'.($i+$j).':C'.($i+$j))->applyFromArray($style_price);
			$aSheet->setCellValue('A'.($i+$j), iconv('CP1251', 'UTF-8', "�����: ".$stand_name)); $j++;
			$aSheet->setCellValue('A'.($i+$j), iconv('CP1251', 'UTF-8', '������������'));
			$aSheet->setCellValue('B'.($i+$j), iconv('CP1251', 'UTF-8', '���'));
			$aSheet->setCellValue('C'.($i+$j), iconv('CP1251', 'UTF-8', 'ID')); $j++;
			
			foreach($stand as $map_id => $mapcase){
				$aSheet->setCellValue('A'.($i+$j), $mapcase['name']); 
				$aSheet->setCellValue('B'.($i+$j), $mapcase['type_name']);
				$aSheet->setCellValue('C'.($i+$j), $map_id);
				$j++;
			}
			$j++;
		}
		$j+=3;
		$i++;
	}

	header('Content-Type:application/vnd.ms-excel');
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	header("Content-Disposition:attachment;filename=\"Export planshet.xls\"");
	$objWriter = new PHPExcel_Writer_Excel5($pExcel);
	$objWriter->save('php://output');
}

if ($act == 'update' && $stand_type_id != 0 && trim($stand_type_name) != '') {
    $sql = "update stand_type set type_name='$stand_type_name' where id=$stand_type_id";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if (!$query) {
        echo '<div>������</div>';
    }
    else {
        header("Location: stands_type.php\n\n");
		echo $grafik_thema_title;
    }
}

?>
<html>
<head>
<title>������</title>
<link href=/editor/styles2.css rel=stylesheet type=text/css>
</head>
<body class=txt2 leftmargin=2 topmargin=2 marginwidth=2 marginheight=2>

<table class=table1 width=100% border=1 cellspacing=0 cellpadding=2>
<tr bgcolor=#ffffff>
<td class=td1 width=90%><b>������</b></td>
<td class=td1 align=right nowrap><a href=<?=$REQUEST_URI?>><img src="/editor/images/editor/reload.gif" width="16" height="16" alt="�������� ���������� ��������" border="0" hspace=4 align=texttop>��������&nbsp;</a></td>
</tr>
<tr bgcolor=#eaeaea>
<td class=td1 colspan=2>

<table border=0 class=txt2 cellpadding=3>
<?
if ($act == '' && $zakaz_id != 0) {
    $sql = "select exp_zakaz.*, user.name, user.firm, user.phone, user.email email_u from exp_zakaz inner join user on exp_zakaz.user_id=user.user_id where id=$zakaz_id";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if ($query) {
        $list_zakaz = mysql_fetch_array($query);
    }
}
?>

<tr><td><span style="font-size: medium;">������ ������������:</span></td></tr>
<tr><td class=txt2 colspan="2"><b>�.�.�.:</b></td><td><?=$list_zakaz['name']?></td></tr>
<tr><td class=txt2 colspan="2"><b>�����������:</b></td><td><?=$list_zakaz['firm']?></td></tr>
<tr><td class=txt2 colspan="2"><b>�������:</b></td><td><?=$list_zakaz['phone']?></td></tr>
<tr><td class=txt2 colspan="2"><b>Email:</b></td><td><?=$list_zakaz['email_u']?></td></tr>
<tr><td></td></tr>
<tr><td><span style="font-size: medium;">������ ������:</span></td></tr>
<tr><td class=txt2 colspan="2"><b>�������� �����������:</b></td><td><?=$list_zakaz['epr_name']?></td></tr>
<tr><td class=txt2 colspan="2"><b>�.�.�.:</b></td><td><?=$list_zakaz['fio']?></td></tr>
<tr><td class=txt2 colspan="2"><b>�������:</b></td><td><?=$list_zakaz['tel']?></td></tr>
<tr><td class=txt2 colspan="2"><b>���� ������:</b></td><td><?=$list_zakaz['date']?></td></tr>
<tr><td class=txt2 colspan="2"><b>Email:</b></td><td><?=$list_zakaz['email']?></td></tr>
<tr><td class=txt2 colspan="2"><b>���. ���������:</b></td><td><?=$list_zakaz['count_plan']?></td></tr>
<tr><td colspan="6"><?
	$array_zakaz = unserialize($list_zakaz['array_cart']);
	foreach($array_zakaz as $stand_name => $stand){?>
			<tr><td colspan='3'><h2 style='color: #074b87'><?=$stand_name?>:</h2></td></tr>
						<tr>
							<th><strong>ID</strong></th>
							<th><strong>���</strong></th>
							<th><strong>������������</strong></th>
						</tr>				
			<?
			foreach($stand as $map_id => $mapcase){?>
						<tr>
							<td align="center"><?=$map_id?></td>
							<td><?=$mapcase['type_name']?></td>
							<td><?=$mapcase['name']?></td>
						</tr>				
			<?
			}
		}
?></td>
</tr>

<tr>
	<td class=txt2 colspan=2><input type=button onClick="javascript: parent.service.location = '/editor/exp_zakaz.php<?=$date_min != '' ? "?date_min=".$date_min."&date_max=".$date_max : ''?>'" value="���������" title="�������� � ������"></td>
	<?if($zakaz_id == 0){?>
	<td>
		<form action="<?=$REQUEST_URI?>" method="POST">
			<input name="date_min" type="hidden" value="<?=$date_min?>">
			<input name="date_max" type="hidden" value="<?=$date_max?>">
			<input name="xls" type="hidden" value="1">
			<button id="btn_ajax_xls">�������� ������� � xls</button>
		</form>
	</td>
	<?}?>
</tr>

</table>

<form action="<?=$REQUEST_URI?>" method="POST">
	<table>
		<tr>
			<td>
				� <input name="date_min" type="date" value="<?=$date_min?>">
			</td>
			<td>
				�� <input name="date_max" type="date" value="<?=$date_max?>">
			</td>
			<td><input type="submit" value="�������"></td>
		</tr>
	</table>
</form>

<?
if ($act == '' && $zakaz_id == 0) {
	$sql = "select * from exp_zakaz";
	if($date_min != '' && $date_max != '')
		$sql .=  " WHERE DATE(date) BETWEEN '".$date_min."' AND '".$date_max."'";
	else $sql .= " WHERE week(`date`, 1) = week(NOW(), 1) AND YEAR(`date`) = YEAR(NOW())";
	$sql .= " order by date desc";
    $query = mysql_query ($sql, $link) or sql_error ($sql, $link, __FILE__, __LINE__);
    if ($query) {
?>
<table class=table1 border=0 cellpadding=2 cellspacing=0 width=100%>
<tr bgcolor=#d8d8d8 class=td1>
<td class=td1 title="�������������"><b>id</b></td>
<td class=td1 title="����"><b>����</b></td>
<td class=td1 title="�������� �����������"><b>�������� �����������</b></td>
<td class=td1 title="�.�.�."><b>�.�.�.</b></td>
<td class=td1 title="�������"><b>�������</b></td>
<td class=td1 title="���. ���������"><b>���. ���������</b></td>
</tr>
<?
        while ($row = mysql_fetch_assoc($query)) {
?>
<tr class=td1 bgcolor=#f1f1f1 onmouseout="this.style.backgroundColor='#eaeaea'" onmouseover="this.style.backgroundColor='<?=$c_list_light?>'">
<td class=td1 title="�������������"><b><?=$row['id']?></b></td>
<td class=td1 title="����"><b><?=$row['date']?></b></td>
<td class=td1 title="�������� �����������"><b><a href="<?=$REQUEST_URL?>?zakaz_id=<?=$row['id']?><?=$date_min != '' ? "&date_min=".$date_min."&date_max=".$date_max : ''?>"><?=$row['epr_name']?></a></b></td>
<td class=td1 title="�.�.�."><b><?=$row['fio']?></b></td>
<td class=td1 title="�������"><b><?=$row['tel']?></b></td>
<td class=td1 title="���. ���������"><b><?=$row['count_plan']?></b></td>
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
