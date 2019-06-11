{config_load file=settings.conf section=$smarty.globals.site_design}
<div class="button-group">
		<a class="button button--small button--invert" href="javascript:history.go(-1)">< Вернуться</a>
</div> 	
<div style="margin-top: 10px;">
	<div class="text-center">
		<h2 class="title">
			Информация по программе: {$graphic_row[14]} {$graphic_row[2]}
			<br>
		</h2>
	</div>
	<div style="margin: 15px 0 10px 0">
		{$graphic_row.content}
	</div>
	<div>
	<h4>Прикреплённые документы</h4>
		{section name=customer loop=$graphic_files start=2}
			<a href="/_service/download.php?file_name={$graphic_files[customer]}&graphic_id={$graphic_row.grafik_id}" target="_blank">{$graphic_files[customer]}</a> <br>
		{/section}
	</div>
</div>