<table border=0 width=100% cellpadding=4 cellspacing=2 class=txt>
<tr bgcolor=#c1c1c1><td colspan={$fcols} class=txt2>&nbsp;</td></tr>
<tr bgcolor=#f1f1f1>
{section name=numloop loop=$items}
<td align=center class=txt2>
<a href="{$SCRIPT_NAME}{$PATH_INFO}/{$items[numloop].name}">{if $items[numloop].tn != ''}<img border=0 align=top src="{$items[numloop].tn}"><br>{/if}{$items[numloop].name}</a>
{if $items[numloop].description != ''}<br>{$items[numloop].description}{/if}
<br>{$items[numloop].size}{if $items[numloop].width != 0}, {$items[numloop].width} x {$items[numloop].height}{/if}
<br><a href="javascript:copyIt(document.all.f_{$smarty.section.numloop.iteration});" title="Скопировать в буфер обмена"><span id=f_{$smarty.section.numloop.iteration}>{$PATH_INFO}/{$items[numloop].name}</span></a>
<br><b><a title="Удалить" href="javascript: if (confirm('Удалить?')) {literal}{{/literal}location = '{$SCRIPT_NAME}{$PATH_INFO}/{$items[numloop].name}?browse_act=delete';{literal}}{/literal}">[x]</a></b>
</td>
    {* see if we should go to the next row *}
    {if not ($smarty.section.numloop.rownum mod $fcols)}
        {if not $smarty.section.numloop.last}
</tr>
<tr bgcolor=#f1f1f1>
        {/if}
    {/if}
    {if $smarty.section.numloop.last}
        {* pad the cells not yet created *}
        {math equation = "n - a % n" n=$fcols a=$items|@count assign="cells"}
        {if $cells ne $fcols}
        {section name=pad loop=$cells}
<td>&nbsp;</td>
        {/section}
        {/if}
</tr>
    {/if}
{/section}
</table>
