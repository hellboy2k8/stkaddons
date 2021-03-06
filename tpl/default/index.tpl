{config_load file="tpl/default/tpl.conf"}
{include file=#header#}

<div id="index-body">
    <img id="index-logo"
	src="{#tpl_image_dir#}logo.png"
	alt="SuperTuxKart Logo"
	title="SuperTuxKart Logo" />
    
    <div id="index-menu">
	{foreach $index_menu as $index}
	    <div>
		<a href="{$index.href}" class="{$index.type}" itemscope itemtype="http://schema.org/SiteNavigationElement">
		    <span itemprop="text">{$index.label}</span>
		    <meta itemprop="url" content="{$index.href}" />
		</a>
	    </div>
	{/foreach}
    </div>{* #index-menu *}
    <div id="index-news-shadow"></div>
    <div id="index-news">
	<noscript><div style="display: none;"></noscript>
	<ul id="news-messages">
	    {foreach $news_messages as $message}
		<li>{$message}</li>
	    {/foreach}
	</ul>
	<noscript></div></noscript>
    </div>
</div>{* #index-body *}
{include file=#footer#}