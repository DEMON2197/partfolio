{* фильтр *}

	<div class="filtr">
	<form action="{$smarty.server.REQUEST_URI}" method="GET" id="select_filtr">
		<table class="table table-hover">
		<tr>
		<th colspan="2">Выбор типа</th>
		</tr>
		{section name="customer" loop="$types"}
				<tr>
					<td class="flitr_name"><label for="check1">{$types[customer].type_name}</label></td>
					<td class="flitr_check">
						<input 
							name="check{$smarty.section.customer.iteration}" 
							id="check{$smarty.section.customer.iteration}" 
							type="checkbox" 
							value="{$types[customer].id}"
							
								{foreach from=$smarty.get item=count key=k}
									{if $count eq $types[customer].id and $smarty.get.select neq 'card' and $k neq 'stand_id'}
										checked
									{elseif $smarty.get.select eq 'card' and $stand.type_id eq $types[customer].id and $k neq 'stand_id'}
										checked
									{/if}
								{/foreach}
							
							>
					</td>
				</tr>
		{/section}
		<tr class="new">
		<td class="flitr_name"><label for="check_new">Новинки</label></td>
		<td class="flitr_check"><input name="check_new" id="check_new" value="new" type="checkbox" {if $smarty.get.check_new eq 'new'}checked{/if}></td>
		</tr>

		</table>
		
		{if $smarty.get.select neq 'card'}<button type="button" class="btn btn-primary" onclick="document.getElementById('select_filtr').submit();">Применить</button>{/if}
		{if isset($smarty.get.select)}
			<input type="hidden" name="select" value="{$smarty.get.select}">
		{/if}
		{if isset($smarty.get.stand_id)}
			<input type="hidden" name="stand_id" value="{$smarty.get.stand_id}">
		{/if}
	</form>
	</div>

	<div class="filtr cart" id="cart_stand" style="display: {if $smarty.session.cart|@count eq 0}none{else}block{/if};">
		<table class="table table-hover">
		<tr>
		<th colspan="2">ВЫБРАННЫЕ</th>
		</tr>
		{foreach item=stand from=$smarty.session.cart key=k}
			<tr>
			<td colspan="2" class="cart_name">{$k}</td>
			</tr>
			{foreach item=mapcase from=$stand key=k_m}
				<tr>
					<td><b>{$mapcase}</b></td>
					<td><a href="#" data-act="deleteCart" data-mapcase-id="{$k_m}" data-stand-name="{$k}"><i class="fa fa-minus-circle"></i></a></td>
				</tr>
			{/foreach}
		{/foreach}
	   
		</table>
		
		<button class="btn btn-danger" href="#">Оформить заявку</button>
		
	</div>
