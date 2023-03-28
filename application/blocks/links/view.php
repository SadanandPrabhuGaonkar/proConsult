<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php if (!empty($links_items)) { ?>
    <?php foreach ($links_items as $links_item_key => $links_item) { ?><?php
if (trim($links_item["linkdetail_URL"]) != "") { ?>
    <?php
	$links_itemlinkdetail_Attributes = [];
	$links_itemlinkdetail_Attributes['href'] = $links_item["linkdetail_URL"];
	$links_item["linkdetail_AttributesHtml"] = join(' ', array_map(function ($key) use ($links_itemlinkdetail_Attributes) {
		return $key . '="' . $links_itemlinkdetail_Attributes[$key] . '"';
	}, array_keys($links_itemlinkdetail_Attributes)));
	echo sprintf('<a %s>%s</a>', $links_item["linkdetail_AttributesHtml"], $links_item["linkdetail_Title"]); ?><?php
} ?><?php } ?><?php } ?>