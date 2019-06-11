<div class="catalog_card">

     <div class="row">
        <div class="span5">
            <div class="catalog_card_item_img">
                <p><img src="/gallery/{if $type eq 'stand'}pic_stands{elseif $type eq 'mapcase'}pic_tablets{/if}/{$stand.img}"></p>
            </div>
        </div>
 
        <div class="span7">
            <div class="catalog_card_item">
                <h3 class="text-center">{$stand.name}</h3>
                
                 {$stand.description}
             
                
                <p>
				
					{if $type eq 'stand'}
						<a href="/catalog/?select=mapcase&stand_id={$stand.id}" class="btn btn-danger btn-large"><i class="fa fa-plus-circle"></i> Выбрать</a>
					{elseif $type eq 'mapcase'}
							{assign var=key value=$stand.stand_name}
							{assign var=id value=$stand.id}
								<a href="#" class="btn btn-primary" data-act="deleteCart" data-mapcase-id="{$stand.id}" data-stand-name="{$stand.stand_name}"
									style="
										display: {if isset($smarty.session.cart.$key[$id])}inline-block{else}none{/if};
								
								">
									<i class="fa fa-minus-circle"></i>
									Добавлено
								</a> 
								<a href="#" class="btn btn-danger" data-act="addCart" data-mapcase-id="{$stand.id}"
									style="
										display: {if !isset($smarty.session.cart.$key[$id])}inline-block{else}none{/if};
								
								">
									<i class="fa fa-plus-circle"></i>
									Добавить
								</a> 
					{/if}
					
				</p>
            </div>
        </div>
    </div>

</div>