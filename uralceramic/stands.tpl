{* список стендов, главная страница *}
<div class="catalog_list text-center">
	{section name=row loop=$rows}
		<div class="row">
		{assign var=row_num value=$smarty.section.row.index*3}
		{section name=customer loop=$stands start=$row_num max=3}
		
				<div class="span4">
					<div class="item">
						{if $type eq 'stand'}
							{if $stands[customer].new eq 1}<span class="badge badge-important">Новинка</span>{/if}
							<a href="/catalog/?select=mapcase&stand_id={$stands[customer].id}">
							<h5>{$stands[customer].name}</h5>
							<p><img src="gallery/pic_stands/{$stands[customer].img}"></p>
							</a>
							<p><a href="/catalog/?select=mapcase&stand_id={$stands[customer].id}" class="btn btn-danger"><i class="fa fa-caret-right"></i> Выбрать</a> &nbsp; <a href="/catalog/?select=card&stand_id={$stands[customer].id}" class="btn btn-default"><i class="fa fa-list"></i></a></p>
						{else}
							{if $stands[customer].new eq 1}<span class="badge badge-important">Новинка</span>{/if}
							<a href="/catalog/?select=card&mapcase_id={$stands[customer][0]}&stand_id={$stands[customer].stand_id}">
							<h5>{$stands[customer][1]}</h5>
							<p><img src="gallery/pic_stands/{$stands[customer].img}"></p>
							</a>
							<p>
								{assign var=key value=$stands[customer].stand_name}
								{assign var=id value=$stands[customer][0]}
								<a href="#" class="btn btn-primary" data-act="deleteCart" data-mapcase-id="{$stands[customer][0]}" data-stand-name="{$stands[customer].stand_name}"
									style="
										display: {if isset($smarty.session.cart.$key[$id])}inline-block{else}none{/if};
								
								">
									<i class="fa fa-minus-circle"></i>
									Добавлено
								</a> 
								<a href="#" class="btn btn-danger" data-act="addCart" data-mapcase-id="{$stands[customer][0]}"
									style="
										display: {if !isset($smarty.session.cart.$key[$id])}inline-block{else}none{/if};
								
								">
									<i class="fa fa-plus-circle"></i>
									Добавить
								</a> 
								&nbsp; 
								<a href="/catalog/?select=card&mapcase_id={$stands[customer][0]}&stand_id={$stands[customer].stand_id}" class="btn btn-default" id="selectCard">
									<i class="fa fa-list"></i>
								</a>
							</p>
						{/if}
					</div>
				</div>

		{/section}
		</div>
	{/section}
</div>

<br clear="all">
