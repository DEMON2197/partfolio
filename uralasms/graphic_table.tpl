{config_load file=settings.conf section=$smarty.globals.site_design}
<div class="button-group">
	{if $type eq 'this_year'}
		<a class="button button--small{if $half_year neq 'pol_one'} button--invert{/if}" href="/grafik_obucheniya/?half_year=0">������ ��������� {$year}</a> 
		<a class="button button--small{if $half_year neq 'pol_two'} button--invert{/if}" href="/grafik_obucheniya/?half_year=1">������ ��������� {$year}</a> 							
		<a class="button button--small button--invert" href="/grafik_obucheniya/?archive=archive">�����</a>
	{elseif $type eq 'archive'}	
	<a class="button button--small{if $half_year neq 'pol_one'} button--invert{/if}" href="/grafik_obucheniya/?half_year=0">������ ��������� {$year_this}</a> 
		<a class="button button--small{if $half_year neq 'pol_two'} button--invert{/if}" href="/grafik_obucheniya/?half_year=1">������ ��������� {$year_this}</a>
		<a class="button button--small button--invert" href="javascript:history.go(-1)">< ���������</a>
		<br>
		{section name=customer loop=$year_array}
			{if $year_array[customer].year < $year_this}
				<a class="button button--small{if $year_array[customer].year neq $year} button--invert{/if}" href="/grafik_obucheniya/?archive={$year_array[customer].year}">{$year_array[customer].year}</a>
			{/if}
		{/section}
	{/if}
</div> 						
<div class="title title--small title--alone title--gray text-uppercase">������ �������� ��{if $type eq 'this_year'}
																								{if $half_year eq 'pol_one'} ������{/if}
																								{if $half_year eq 'pol_two'} ������{/if}
																								��������� {$year}
																						  {elseif $type eq 'archive'}
																								{$year} ���
																						  {/if}
</div> 						
<div class="table">
	<table> 								
		<thead> 		
			{if $type eq 'this_year'}		
				<tr> 										
					<th>������������ ���������, ����</th> 										
					<th>���������</th> 
					{if $half_year eq 'pol_one'}
						<th>01.{$year}</th> 										
						<th>02.{$year}</th>
						<th>03.{$year}</th> 										
						<th>04.{$year}</th> 										
						<th>05.{$year}</th> 										
						<th>06.{$year}</th> 
					{/if}
					{if $half_year eq 'pol_two'} 
						<th>07.{$year}</th> 										
						<th>08.{$year}</th>
						<th>09.{$year}</th> 										
						<th>10.{$year}</th> 										
						<th>11.{$year}</th> 										
						<th>12.{$year}</th> 	
					{/if}
				</tr> 	
			{elseif $type eq 'archive'}
				<tr> 										
					<th>������������ ���������, ����</th> 										
					<th>���������</th> 										
					<th>01.{$year}</th> 										
					<th>02.{$year}</th>
					<th>03.{$year}</th> 										
					<th>04.{$year}</th> 										
					<th>05.{$year}</th> 										
					<th>06.{$year}</th> 	
					<th>07.{$year}</th> 
					<th>08.{$year}</th> 
					<th>09.{$year}</th> 
					<th>10.{$year}</th> 
					<th>11.{$year}</th> 
					<th>12.{$year}</th> 
				</tr> 	
			{/if}
		</thead> 								
		<tbody> 
			{section name=customer loop=$graphic_display}		
				<tr> 										
					<td colspan="{if $type eq 'this_year'}8{elseif $type eq 'archive'}14{/if}">{$smarty.section.customer.iteration}. {$graphic_display[customer][0]}</td> 									
				</tr> 									
				<tr> 
					{section name=customer2 loop=$graphic_display[customer][1]}
						{if $smarty.section.customer2.iteration eq 1}
							<td>
								{if $type eq 'archive'}
									<a href="/grafik_obucheniya-archive-{$graphic_display[customer][1].grafik_id}">{$graphic_display[customer][1][customer2]}</a>
								{else}
									<a href="/grafik_obucheniya-{$graphic_display[customer][1].grafik_id}">{$graphic_display[customer][1][customer2]}</a>
								{/if}
							</td> 	
						{else}
							<td>{$graphic_display[customer][1][customer2]}</td> 
						{/if}
					{/section}
				</tr> 
			{/section}				
		</tbody> 							
	</table>
</div>