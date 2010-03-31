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
	
	//Temporarily work aroud issue in subsite module 
	function onBeforeWrite(){
		parent::onBeforeWrite();
		$this->SubsiteID = 0;
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
		'Summary' => 'HTMLText',
	);
	
	static $has_one = array(
		'CoverPhoto' => 'CoverPhoto',
		'File' => 'EbookFile',
	);
	
	static $many_many = array(
		'Authors' => 'Author'
	);
	
	function getCMSFields(){
		$fields = parent::getCMSFields();
		$fields->removeByName('Authors');
		
		$authors = DataObject::get('Author');
		$fields->addFieldToTab('Root.Main',
			new CheckboxSetField("Authors", "Authors", $authors, $this->Authors())
		);
		
		return $fields;
	}
	
	function getPaymentFields() {
		$fields = new FieldSet(
			new HeaderField("Enter your details", 4),
			new TextField("FirstName", "First Name"),
			new EmailField("Email", "Email")
		);
		return $fields;
	}
	
	function getPaymentFieldRequired() {
		return array(
			'FirstName',
			'Email',
		);
	}
	
	function getMerchantReference(){
		$authors = $this->Authors();
		$authorstitle = "";
		if($authors->count()){
			foreach($authors as $author){
				$authorTitles[] = $author->Name;
			}
			$authorstitle = implode(", ", $authorTitles);
		}
		if($authorstitle){
			return substr("Ebook: ".$this->Title." by ".$authorstitle, 0, 63);
		}else {
			return substr("Ebook: ".$this->Title.", ".$this->Summary, 0, 63);
		}
	}
	
	function ConfirmationMessage(){
		$authors = $this->Authors();
		$authorstitle = "";
		if($authors->count()){
			foreach($authors as $author){
				$authorTitles[] = $author->Name;
			}
			$authorstitle = implode(", ", $authorTitles);
		}
		
		$message = "<h5>This is a confirmation of your Ebook: </h5><br /><h6><a href=\"".$this->File()->Link()."\">".$this->Title."</a></h6>";
		if($authorstitle){
			$message .= "<br />by ".$authorstitle;
		}
		$message .= "<p>".$this->Summary."</p>";

		return $message;
	}
}

class EbookFile extends File{
	//Temporarily work aroud issue in subsite module 
	function onBeforeWrite(){
		parent::onBeforeWrite();
		$this->SubsiteID = 0;
	}
}

class CoverPhoto extends Image {
	function generateFrontImage(GD $gd) {
		$gd->setQuality(90);
		return $gd->croppedResize(120,160);
	}
	
	//Temporarily work aroud issue in subsite module 
	function onBeforeWrite(){
		parent::onBeforeWrite();
		$this->SubsiteID = 0;
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
		'MovieTitle' => 'Varchar',
	);
	static $has_one = array(
		'Theatre' => 'Theatre'
	);
	
	function getPaymentFields() {
		$fields = new FieldSet(
			new HeaderField("Enter your details", 4),
			new TextField("FirstName", "First Name"),
			new TextField("Surname", "Last Name"),
			new EmailField("Email", "Email")
		);
		return $fields;
	}
	
	function getPaymentFieldRequired() {
		return array(
			'FirstName',
			'Surname',
			'Email',
		);
	}
	
	function getMerchantReference(){
		return substr("Ticket for ".$this->MovieTitle." in ".$this->Theatre()->Title, 0, 63);
	}
	
	function ConfirmationMessage(){
		$message = "<h5>This is a confirmation of your ticket for: </h5><br /><h6>".$this->MovieTitle."(".$this->Theatre()->Title." ".$this->Start." - ".$this->End.")</h6>";
		$message .= $this->Theatre()->renderWith('Theatre');
		return $message;
	}
}

class Theatre extends DataObject{
	static $db = array(
		'Title' => 'Varchar(128)',
		'Street' => 'Varchar',
		'CityTown' => 'Varchar',
		'Description' => 'HTMLText',
		'OtherInfo' => 'HTMLText',
	);
}
?>