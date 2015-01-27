<?php
// Configuration
if (file_exists('config.php')) {
	require_once('config.php');
}  

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}


if($db->query("SHOW TABLES LIKE '". DB_PREFIX . "fnt_product_setting'")->num_rows){
	print("<h2>Fancy Product Designer already upgraded!</h2>");
} else {
	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_setting` (
		`product_design_id` int(11) NOT NULL,
		`parameters` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	$db->query($sql);
	
	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_setting` ADD PRIMARY KEY (`product_design_id`);";
	$db->query($sql);
	
	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_category_clipart` ADD `parameter` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `category_clipart_id`;";
	$db->query($sql);
	
	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_order_product_design` ADD `stage_width` INT(11) NOT NULL AFTER `price`, ADD `stage_height` INT(11) NOT NULL AFTER `stage_width`;";
	$db->query($sql);
	print("<h2>Success: You have upgraded Fancy Product Designer!</h2>");
}	
?>