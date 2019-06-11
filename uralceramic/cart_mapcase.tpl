{* корзина *}
{if $smarty.session.status_zakaz eq 1}
	<div style="width: 100%; padding: 20px 0 20px 0;">
		<div style="display: inline-block;">
			<h3 style="color: green;">Ваш заказ отправлен</h3>
		</div>
	</div>
{elseif $smarty.session.cart.status_zakaz eq 2}
	<div style="width: 100%; padding: 20px 0 20px 0;">
		<div style="display: inline-block;">
			<h3 style="color: red;">Ошибка отправки, повторите попытку через несколько минут!</h3>
		</div>
	</div>
{/if}
{php}
	$_SESSION['status_zakaz'] = 0;
{/php}
<div class="catalog_list text-center">
	{foreach item=stand from=$smarty.session.cart key=stand_name name=loop_stand}
	{if $smarty.foreach.loop_stand.iteration neq 1}<br><br>{/if}
		<p><span style="font-size: large; color: #305a7f; "><strong>{$stand_name}</strong></p>
		<div class="row">
			{foreach item=mapcase from=$stand key=map_id name=loop_mapcase}
				<div class="span4" {if $smarty.foreach.loop_mapcase.iteration % 3}style="margin-left:0;"{/if}>
					<div class="item">
						{if $mapcase.new eq 1}<span class="badge badge-important">Новинка</span>{/if}
						<a href="/catalog/?select=card&mapcase_id={$map_id}&stand_name={$stand_name}">
							<h5>{$mapcase.name}</h5>
							<p><img src="gallery/pic_tablets/{$mapcase.img}" style="width: 200px; height: 250px;"></p>
						</a>
						<p>
							
							<a href="#" class="btn btn-primary" data-act="deleteCart" data-mapcase-id="{$map_id}" data-stand-name="{$stand_name}"
								style="
									display: {if isset($smarty.session.cart.$stand_name[$map_id])}inline-block{else}none{/if};
							
							">
								<i class="fa fa-minus-circle"></i>
								Добавлено
							</a> 
							<a href="#" class="btn btn-danger" data-act="addCart" data-mapcase-id="{$map_id}"
								style="
									display: {if !isset($smarty.session.cart.$stand_name[$map_id])}inline-block{else}none{/if};
								
							">
								<i class="fa fa-plus-circle"></i>
								Добавить
							</a> 
							&nbsp; 
							<a href="/catalog/?select=card&mapcase_id={$map_id}&stand_name={$stand_name}" class="btn btn-default" id="selectCard">
								<i class="fa fa-list"></i>
							</a>
						</p>
					</div>
				</div>
			{/foreach}
		</div>
	{/foreach}
	
</div>
	<div class="form_cart">
			<form action="{$current_category.navy_tag}" method="POST">
				<input type="hidden" value="1" name="cart">
				<p><span style="font-size: smoll; color: red; font-style: italic;">* - обязательные поля!</style></p>
				<div>
					<label for="name_epr"><span style="font-size:large;">Введите полное наименование организации:</span></label>
					<input type="text" name="name_epr" id="name_epr" style="width: 100%;border-color: #ccc;" placeholder="Название организации" value="{if isset($smarty.session.user_info.firm)}{$smarty.session.user_info.firm}{/if}" required>
				</div>
				<div>
					<label for="fio"><span style="font-size:large;">Введите Ф.И.О.:</span></label>
					<input type="text" name="fio" id="fio" style="width: 100%;border-color: #ccc;" placeholder="Ф.И.О." value="{if isset($smarty.session.user_info.name)}{$smarty.session.user_info.name}{/if}" required>
				</div>
				<div>
					<label for="phone"><span style="font-size:large;">Телефон:</span></label>
					<input type="tel" name="phone" id="phone" style="width: 100%;border-color: #ccc;" placeholder="Телефон" value="{if isset($smarty.session.user_info.phone)}{$smarty.session.user_info.phone}{/if}" required>
				</div>
				<div>
					<label for="email"><span style="font-size:large;">Введите ваш email адрес:</span></label>
					<input type="email" name="email" id="email" style="width: 100%;border-color: #ccc;" placeholder="Email адрес" value="{if isset($smarty.session.user_info.email)}{$smarty.session.user_info.email}{/if}" required>
				</div>
				<input type="submit" value="Отправить заявку" class="btn btn-primary">
			</form>
		</div>
