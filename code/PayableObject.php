<?php

class Product extends DataObject {
	static $db = array(
		'Title' => 'Varchar(256)',
		'Description' => 'HTMLText',
	);
	
	static $has_one = array(
		'Image' => 'ProductImage',
	);
	
	function getPaymentFields() {
		$fields = new FieldSet(
			new HeaderField("Enter your details", 4),
			new TextField("FirstName", "First Name"),
			new TextField("Surname", "Last Name"),
			new EmailField("Email", "Email"),
			new TextField("Street", "Street"),
			new TextField("Suburb", "Suburb"),
			new TextField("CityTown", "City / Town"),
			new TextField("Country", "Country")
		);
		return $fields;
	}
	
	function getPaymentFieldRequired() {
		return array(
			'FirstName',
			'Surname',
			'Email',
			'Street',
			'CityTown',
			'Country'
		);
	}
	
	function getMerchantReference(){
		return substr("Payment for purchasing ".$this->Title, 0, 63);
	}
	
	function ConfirmationMessage(){
		$this->Mode = 'Confirmation';
		return $message = "<h5>This is a confirmation of your purchase:</h5><br />".$this->renderWith($this->ClassName);
	}
}


class ProductImage extends Image {
	function generateFrontImage(GD $gd) {
		$gd->setQuality(90);
		return $gd->croppedResize(160,160);
	}
}

class Donation extends DataObject {
	function getPaymentFields() {
		$fields = new FieldSet(
			new HeaderField("Enter your details", 4),
			new TextField("FirstName", "First Name"),
			new TextField("Surname", "Last Name"),
			new EmailField("Email", "Email"),
			$money = new MoneyField('Amount', '')
		);
		$money->allowedCurrencies = DPSAdapter::$allowed_currencies;
		return $fields;
	}
	
	function getPaymentFieldRequired() {
		return array(
			'FirstName',
			'Surname',
			'Email',
			'Amount'
		);
	}
	
	function getMerchantReference(){
		return substr("Donation ".$this->Amount->Nice()."(".$this->Amount->Currency.")", 0, 63);
	}
	
	function ConfirmationMessage(){
		return $message = "<h5>This is a confirmation of your donation: </h5><br /><h6>".$this->Amount->Nice()."(".$this->Amount->Currency.")</h6>";
	}
}

class Ebook extends DataObject {
	static $db = array(
		'Title' => 'Varchar(256)',
		'Summury' => 'HTMLText',
	);
	
	static $has_one = array(
		'CoverPhoto' => 'CoverPhoto',
	);
	
	static $many_many = array(
		'Authors' => 'Author'
	);
}

class CoverPhoto extends Image {
	function generateFrontImage(GD $gd) {
		$gd->setQuality(90);
		return $gd->croppedResize(160,160);
	}
}

class Author extends DataObject {
	static $db = array(
		'Name' => "Varchar(128)",
		'Indroduction' => "HTMLText",
	);
}

class MovieTicket extends DataObject{
	static $db = array(
		'Start' => 'Datetime',
		'End' => 'Datetime',
	);
	static $has_one = array(
		'BookedBy' => 'Member',
		'Theatre' => 'Theatre'
	);
}

class Theatre extends DataObject{
	static $db = array(
		'Title' => 'Varchar(128)',
		'Street' => 'Varchar',
		'CityTown' => 'Varchar',
	);
}
?>