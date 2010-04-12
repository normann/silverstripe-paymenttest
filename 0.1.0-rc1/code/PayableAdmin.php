<?php

class PayableAdmin extends ModelAdmin{
	static $menu_title = "Payables";
	static $url_segment = "payables";
	
	static $managed_models = array(
		"ProductObject",
		//"Donation",
		"MovieTicket",
		"Theatre",
		"Ebook",
		"Author"
	);
	
	static $allowed_actions = array(
		"ProductObject",
		//"Donation",
		"MovieTicket",
		"Theatre",
		"Ebook",
		"Author",
	);
}
?>