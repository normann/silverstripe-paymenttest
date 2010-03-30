<?php

class PayableAdmin extends ModelAdmin{
	static $menu_title = "Payables";
	static $url_segment = "payables";
	
	static $managed_models = array(
		"Product",
		"Donation",
		"Ebook",
		"MovieTicket"
	);
	
	static $allowed_actions = array(
		"Product",
		"Donation",
		"Ebook",
		"MovieTicket"
	);
}
?>