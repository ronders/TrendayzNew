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


if($db->query("SHOW TABLES LIKE '". DB_PREFIX . "fnt_category_clipart'")->num_rows){
	print("<h2>Fancy Product Designer already installed!</h2>");
} else {
	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_category_clipart` (
	`category_clipart_id` int(12) NOT NULL,
	  `status` int(1) NOT NULL,
	  `date_added` datetime NOT NULL,
	  `sort_order` int(11) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_category_clipart_description` (
	  `category_clipart_id` int(12) NOT NULL,
	  `language_id` int(12) NOT NULL,
	  `name` varchar(255) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_category_design` (
	`category_design_id` int(12) NOT NULL,
	  `status` int(1) NOT NULL,
	  `date_added` datetime NOT NULL,
	  `sort_order` int(11) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_category_design_description` (
	  `category_design_id` int(12) NOT NULL,
	  `language_id` int(12) NOT NULL,
	  `name` varchar(255) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_clipart` (
	`clipart_id` int(12) NOT NULL,
	  `name` varchar(255) NOT NULL,
	  `image` varchar(255) NOT NULL,
	  `parameter` text NOT NULL,
	  `sort_order` int(1) NOT NULL,
	  `status` int(1) NOT NULL,
	  `date_added` datetime NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_clipart_to_category` (
	  `category_clipart_id` int(12) NOT NULL,
	  `clipart_id` int(12) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_order_product_design` (
	`order_product_design_id` int(11) NOT NULL,
	  `order_product_id` int(11) NOT NULL,
	  `order_id` int(11) NOT NULL,
	  `product_design_id` int(11) NOT NULL,
	  `design` longtext NOT NULL,
	  `price` decimal(15,4) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_customer_idea` (
	`product_customer_idea_id` int(12) NOT NULL,
	  `product_design_id` int(12) NOT NULL,
	  `data_design` longtext CHARACTER SET utf8 NOT NULL,
	  `customer_id` int(11) NOT NULL,
	  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
	  `status` int(1) NOT NULL,
	  `accept` int(1) NOT NULL,
	  `date_added` datetime NOT NULL
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_customer_idea_accept` (
	`product_customer_idea_accept_id` int(12) NOT NULL,
	  `product_customer_idea_id` int(12) NOT NULL,
	  `product_design_id` int(12) NOT NULL,
	  `data_design` longtext CHARACTER SET utf8 NOT NULL,
	  `customer_id` int(11) NOT NULL,
	  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
	  `image` varchar(125) CHARACTER SET utf8 NOT NULL,
	  `status` int(1) NOT NULL,
	  `date_added` datetime NOT NULL,
	  `date_edit` datetime NOT NULL
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_design` (
	`product_design_id` int(11) NOT NULL,
	  `name` varchar(128) NOT NULL,
	  `product_id` int(11) NOT NULL,
	  `status` int(1) NOT NULL,
	  `date_added` datetime NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_design_element` (
	`product_design_element_id` int(12) NOT NULL,
	  `name` varchar(128) NOT NULL,
	  `product_design_id` int(11) NOT NULL,
	  `image` varchar(128) NOT NULL,
	  `sort_order` int(2) DEFAULT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_design_element_detail` (
	`product_design_element_detail_id` int(12) NOT NULL,
	  `product_design_element_id` int(12) NOT NULL,
	  `type` varchar(10) NOT NULL,
	  `value` varchar(255) NOT NULL,
	  `parameters` text NOT NULL,
	  `sort_order` int(2) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_design_to_category_clipart` (
	  `product_design_id` int(11) NOT NULL,
	  `category_clipart_id` int(11) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_ideas` (
	`product_ideas_id` int(11) NOT NULL,
	  `product_design_id` int(11) NOT NULL,
	  `image` varchar(128) NOT NULL,
	  `date_added` datetime NOT NULL,
	  `status` int(1) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_ideas_description` (
	  `product_ideas_id` int(11) NOT NULL,
	  `language_id` int(1) NOT NULL,
	  `name` varchar(225) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_ideas_element` (
	`product_ideas_element_id` int(12) NOT NULL,
	  `name` varchar(128) NOT NULL,
	  `product_ideas_id` int(11) NOT NULL,
	  `image` varchar(128) NOT NULL,
	  `sort_order` int(2) DEFAULT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_ideas_element_detail` (
	`product_ideas_element_detail_id` int(12) NOT NULL,
	  `product_ideas_element_id` int(12) NOT NULL,
	  `type` varchar(10) NOT NULL,
	  `value` varchar(255) NOT NULL,
	  `parameters` text NOT NULL,
	  `sort_order` int(2) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;";
	$db->query($sql);

	$sql="CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fnt_product_to_category_design` (
	  `category_design_id` int(12) NOT NULL,
	  `product_design_id` int(12) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
	$db->query($sql);
	
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
	
	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_category_clipart`
	 ADD PRIMARY KEY (`category_clipart_id`);";
	$db->query($sql);
	
	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_category_clipart_description`
	 ADD PRIMARY KEY (`category_clipart_id`,`language_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_category_design`
	 ADD PRIMARY KEY (`category_design_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_category_design_description`
	 ADD PRIMARY KEY (`category_design_id`,`language_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_clipart`
	 ADD PRIMARY KEY (`clipart_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_clipart_to_category`
	 ADD PRIMARY KEY (`category_clipart_id`,`clipart_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_order_product_design`
	 ADD PRIMARY KEY (`order_product_design_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_customer_idea`
	 ADD PRIMARY KEY (`product_customer_idea_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_customer_idea_accept`
	 ADD PRIMARY KEY (`product_customer_idea_accept_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_design`
	 ADD PRIMARY KEY (`product_design_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_design_element`
	 ADD PRIMARY KEY (`product_design_element_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_design_element_detail`
	 ADD PRIMARY KEY (`product_design_element_detail_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_design_to_category_clipart`
	 ADD PRIMARY KEY (`product_design_id`,`category_clipart_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_ideas`
	 ADD PRIMARY KEY (`product_ideas_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_ideas_description`
	 ADD PRIMARY KEY (`product_ideas_id`,`language_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_ideas_element`
	 ADD PRIMARY KEY (`product_ideas_element_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_ideas_element_detail`
	 ADD PRIMARY KEY (`product_ideas_element_detail_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_to_category_design`
	 ADD PRIMARY KEY (`category_design_id`,`product_design_id`);";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_category_clipart`
	MODIFY `category_clipart_id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_category_design`
	MODIFY `category_design_id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_clipart`
	MODIFY `clipart_id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_order_product_design`
	MODIFY `order_product_design_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_customer_idea`
	MODIFY `product_customer_idea_id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_customer_idea_accept`
	MODIFY `product_customer_idea_accept_id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_design`
	MODIFY `product_design_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_design_element`
	MODIFY `product_design_element_id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_design_element_detail`
	MODIFY `product_design_element_detail_id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_ideas`
	MODIFY `product_ideas_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_ideas_element`
	MODIFY `product_ideas_element_id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);

	$sql="ALTER TABLE `" . DB_PREFIX . "fnt_product_ideas_element_detail`
	MODIFY `product_ideas_element_detail_id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;";
	$db->query($sql);
	$sql="DELETE FROM `" . DB_PREFIX . "setting` WHERE `group` LIKE '%config_design%'";
	$db->query($sql);
	$sql="INSERT INTO `" . DB_PREFIX . "setting` (`store_id`, `group`, `key`, `value`, `serialized`) VALUES
	(0, 'config_design', 'config_text_bounding_height', '', 0),
	(0, 'config_design', 'config_text_bounding_width', '', 0),
	(0, 'config_design', 'config_text_bounding_y_position', '', 0),
	(0, 'config_design', 'config_text_bounding_x_position', '', 0),
	(0, 'config_design', 'config_text_bounding_box_target', '', 0),
	(0, 'config_design', 'config_designs_text_bounding_box', '0', 0),
	(0, 'config_design', 'config_designs_text_clipping', '0', 0),
	(0, 'config_design', 'config_text_remove', '1', 0),
	(0, 'config_design', 'config_designs_text_autoselect', '0', 0),
	(0, 'config_design', 'config_text_replace', '', 0),
	(0, 'config_design', 'config_text_zchangeable', '1', 0),
	(0, 'config_design', 'config_text_rotatable', '1', 0),
	(0, 'config_design', 'config_text_resizeable', '1', 0),
	(0, 'config_design', 'config_text_auto_center', '1', 0),
	(0, 'config_design', 'config_text_draggable', '1', 0),
	(0, 'config_design', 'config_text_design_price', '', 0),
	(0, 'config_design', 'config_text_z_position', '-1', 0),
	(0, 'config_design', 'config_text_design_color', '#000000', 0),
	(0, 'config_design', 'config_text_y_position', '', 0),
	(0, 'config_design', 'config_resize_height', '200', 0),
	(0, 'config_design', 'config_text_x_position', '', 0),
	(0, 'config_design', 'config_resize_width', '200', 0),
	(0, 'config_design', 'config_max_width', '1000', 0),
	(0, 'config_design', 'config_max_height', '1000', 0),
	(0, 'config_design', 'config_min_height', '200', 0),
	(0, 'config_design', 'config_bounding_box_height', '200', 0),
	(0, 'config_design', 'config_min_width', '200', 0),
	(0, 'config_design', 'config_bounding_box_width', '200', 0),
	(0, 'config_design', 'config_bounding_box_x', '200', 0),
	(0, 'config_design', 'config_bounding_box_y', '200', 0),
	(0, 'config_design', 'config_designs_parameter_bounding_box', '0', 0),
	(0, 'config_design', 'config_bounding_box_target', '', 0),
	(0, 'config_design', 'config_designs_parameter_clipping', '0', 0),
	(0, 'config_design', 'config_designs_parameter_replace', '', 0),
	(0, 'config_design', 'config_designs_parameter_autoselect', '0', 0),
	(0, 'config_design', 'config_designs_parameter_zchangeable', '1', 0),
	(0, 'config_design', 'config_designs_parameter_remove', '1', 0),
	(0, 'config_design', 'config_designs_parameter_rotatable', '1', 0),
	(0, 'config_design', 'config_designs_parameter_resizable', '1', 0),
	(0, 'config_design', 'config_designs_parameter_draggable', '1', 0),
	(0, 'config_design', 'config_designs_parameter_auto_center', '1', 0),
	(0, 'config_design', 'config_designs_parameter_price', '0', 0),
	(0, 'config_design', 'config_designs_parameter_colors', '#63EFFF', 0),
	(0, 'config_design', 'config_designs_parameter_z', '-1', 0),
	(0, 'config_design', 'config_designs_parameter_y', '', 0),
	(0, 'config_design', 'config_designs_parameter_x', '', 0),
	(0, 'config_design', 'config_color_icon', '#FFFFFF', 0),
	(0, 'config_design', 'config_color_sidebar', '#2C3E50', 0),
	(0, 'config_design', 'config_out_boundary_color', '#DAE4EB', 0),
	(0, 'config_design', 'config_bounding_color', '#EB4726', 0),
	(0, 'config_design', 'config_selected_color', '#63FFFA', 0),
	(0, 'config_design', 'config_instagram_redirect_uri', 'http://yourdomain.com/catalog/controller/product/fancy_design/instagram_auth.php', 0),
	(0, 'config_design', 'config_instagram_client_id', '', 0),
	(0, 'config_design', 'config_facebook_app_id', '', 0),
	(0, 'config_design', 'config_zoom_max', '2', 0),
	(0, 'config_design', 'config_zoom_min', '0.2', 0),
	(0, 'config_design', 'config_zoom', '1.1', 0),
	(0, 'config_design', 'config_font_dropdown', 'on', 0),
	(0, 'config_design', 'config_reset_table', 'on', 0),
	(0, 'config_design', 'config_upload_text', 'on', 0),
	(0, 'config_design', 'config_upload_designs', 'on', 0),
	(0, 'config_design', 'config_center_in_bounding_box', 'on', 0),
	(0, 'config_design', 'config_print_button', 'on', 0),
	(0, 'config_design', 'config_pdf_button', 'on', 0),
	(0, 'config_design', 'config_download_image', 'on', 0),
	(0, 'config_design', 'config_allow_product_saving', 'on', 0),
	(0, 'config_design', 'config_view_tooltip', 'on', 0),
	(0, 'config_design', 'config_stage_max_width', '700', 0),
	(0, 'config_design', 'config_stage_height', '800', 0),
	(0, 'config_design', 'config_stage_width', '800', 0),
	(0, 'config_design', 'config_sidebar_content_width', '280', 0),
	(0, 'config_design', 'config_sidebar_nav_width', '280', 0),
	(0, 'config_design', 'config_theme', 'icon-sb-left', 0),
	(0, 'config_design', 'config_text_patternable', '1', 0),
	(0, 'config_design', 'config_text_curved', '1', 0),
	(0, 'config_design', 'config_default_text_size', '20', 0),
	(0, 'config_design', 'config_text_default', '', 0),
	(0, 'fonts', 'fonts_default', 'Arial,Helvetica,Times New Roman,Verdana', 0),
	(0, 'config_design', 'config_text_text_characters', '0', 0);";
	$db->query($sql);
	print("<h2>Success: You have installed Fancy Product Designer!</h2>");
}	
?>