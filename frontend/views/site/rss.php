<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title><?=$category['name']?></title>
    <link><?=$link_rss?></link>
    <atom:link href="<?=$link_rss?>" rel="self" type="application/rss+xml" />
    <language>vi</language>
    <pubDate><?=date('d/m/Y H:i:s')?></pubDate>
    <lastBuildDate><?=date('d/m/Y')?></lastBuildDate>
    <copyright>
      <![CDATA[<?=WEBNAME?>]]>
      </copyright>
    <docs><?=$link_feed?></docs>
    <generator>
      <![CDATA[<?=WEBNAME?> Version 4]]>
      </generator>
    <image>
      <url><?=$logo?></url>
      <title><?=$category['name']?></title>
      <link><?=HTTP_HOST . $category['link_main']?></link>
      <width>144</width>
      <height>46</height>
    </image>
    <?php if($listNews) { ?>
    <?php foreach($listNews as $item) { ?>
    <item>
      <title><?=$item['name']?></title>
      <link><?=$item['link_main']?></link>
      <guid isPermaLink="false">
        <![CDATA[news_<?=$item['id']?>]]>
        </guid>
      <description>
        <![CDATA[<img src="<?=$item['image']?>" width="100" align="left" border="0"><?=$item['price']?> <?=$item['description']?> ]]>
        </description>
      <pubDate><?=$item['created_time']?></pubDate>
    </item>
    <?php } ?>
    <?php } ?>
  </channel>
</rss>