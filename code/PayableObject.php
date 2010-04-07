<?php

class Product extends DataObject {
	static $db = array(
		'Title' => 'Varchar(256)',
		'Description' => 'HTMLText',
	);
	
	static $has_one = array(
		'Image' => 'ProductImage',
	);
	
	/**
	 * when doing the first dev/build, the record will be added as testing product
	 */
	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		$product1 = DataObject::get_one('Product', "\"Title\" = 'Daft Robot'");
		if(!($product1 && $product1->exists())) {
			$product1 = new Product();
			$product1->Title = 'Daft Robot';
			$product1->Description = <<<HTML
			<p>DVD, 2010<br />Striped Silver Pictures</p>
			<p>A stellar example of the type of product you might want to sell on your site.</p>
HTML;
			$productImage1 = DataObject::get_one('ProductImage', "\"Name\" = 'daft-robot.png'");
			if(!($productImage1 && $productImage1->exists())) {
				
				$uploadfolder = Folder::findOrMake("Uploads");
				$command = "cp ../payment-test/templates/Images/daft-robot.png ../".$uploadfolder->Filename;
				`$command`;
				$productImage1 = new ProductImage(array('ClassName' => 'ProductImage'));
				$productImage1->Name = 'daft-robot.png';
				$productImage1->Title = 'daft-robot';
				$productImage1->Filename = 'assets/Uploads/daft-robot.png';
				$productImage1->ParentID = $uploadfolder->ID;
				$productImage1->OwnerID = Member::currentUserID();
				$productImage1->write();
			}
			$product1->ImageID = $productImage1->ID;
			$product1->Amount->Amount = '8.99';
			$product1->Amount->Currency = 'USD';
			$product1->write();
			DB::alteration_message('product example \'Daft Robot\'', 'created');
		}
		
		$product2 = DataObject::get_one('Product', "\"Title\" = 'Bloody Knife'");
		if(!($product2 && $product2->exists())) {
			$product2 = new Product();
			$product2->Title = 'Bloody Knife';
			$product2->Description = <<<HTML
			<p>DVD, 1978<br />SilverSplatter Films</p>
			<p>A terrifying cult classic that has inspired many horror movies.</p>
HTML;
			$productImage2 = DataObject::get_one('ProductImage', "\"Name\" = 'bloody-knife.png'");
			if(!($productImage2 && $productImage2->exists())) {
				$uploadfolder = Folder::findOrMake("Uploads");
				$command = "cp ../payment-test/templates/Images/bloody-knife.png ../".$uploadfolder->Filename;
				`$command`;
				$productImage2 = new ProductImage(array('ClassName' =>'ProductImage'));
				$productImage2->Name = 'bloody-knife.png';
				$productImage2->Title = 'bloody-knife';
				$productImage2->Filename = 'assets/Uploads/bloody-knife.png';
				$productImage2->ParentID = $uploadfolder->ID;
				$productImage2->OwnerID = Member::currentUserID();
				$productImage2->write();
			}
			$product2->ImageID = $productImage2->ID;
			$product2->Amount->Amount = '19.99';
			$product2->Amount->Currency = 'NZD';
			$product2->write();
			DB::alteration_message('product example \'Blood Knife\'', 'created');
		}
	}
	
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
		return $gd->paddedResize(160,160);
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
	
	function requireDefaultRecords() {
		parent::requireDefaultRecords();

		$ebook = DataObject::get_one('Ebook', "\"Title\" = 'Silhouetted against the moon'");
		if(!($ebook && $ebook->exists())) {
			$ebook = new Ebook();
			$ebook->Title = 'Silhouetted against the moon';
			$ebook->Summary = <<<HTML
			<p>A frightful product that will keep you on the edge of your seat!</p>
			<p>Cover <a href="http://www.sxc.hu/photo/991793">image</a> courtesy of <a href="http://www.sxc.hu/profile/nazreth" target="_blank">nazreth</a> on <a href="http://www.sxc.hu/" target="_blank">sxc.hu</a></p>
HTML;

			$coverPhoto = DataObject::get_one('CoverPhoto', "\"Name\" = 'silhouetted-against-the-moon.png'");
			if(!($coverPhoto && $coverPhoto->exists())) {
				$uploadfolder = Folder::findOrMake("Uploads");
				$command = "cp ../payment-test/templates/Images/silhouetted-against-the-moon.png ../".$uploadfolder->Filename;
				`$command`;
				$coverPhoto = new CoverPhoto(array('ClassName' => 'CoverPhoto'));
				$coverPhoto->Name = 'silhouetted-against-the-moon.png';
				$coverPhoto->Title = 'silhouetted-against-the-moon';
				$coverPhoto->Filename = 'assets/Uploads/silhouetted-against-the-moon.png';
				$coverPhoto->ParentID = $uploadfolder->ID;
				$coverPhoto->OwnerID = Member::currentUserID();
				$coverPhoto->write();
			}
			
			$file = DataObject::get_one('EbookFile', "\"Name\" = 'silhouetted-against-th-moon.pdf'");
			if(!($file && $file->exists())) {
				$uploadfolder = Folder::findOrMake("Uploads");
				$command = "cp ../payment-test/templates/Images/silhouetted-against-th-moon.pdf ../".$uploadfolder->Filename;
				`$command`;
				$file = new EbookFile(array('ClassName' => 'EbookFile'));
				$file->Name = 'silhouetted-against-th-moon.pdf';
				$file->Title = 'silhouetted-against-th-moon';
				$file->Filename = 'assets/Uploads/silhouetted-against-th-moon.pdf';
				$file->ParentID = $uploadfolder->ID;
				$file->OwnerID = Member::currentUserID();
				$file->write();
			}
			
			$ebook->CoverPhotoID = $coverPhoto->ID;
			$ebook->FileID = $file->ID;
			
			$ebook->Amount->Amount = '5.99';
			$ebook->Amount->Currency = 'USD';
			$ebook->write();
			
			$author = DataObject::get_one('Author', "\"Name\" = 'Michael Lorenzo'");
			if(!($author && $author->exists())) {
				$author = new Author(array('ClassName' => 'Author'));
				$author->Name = 'Michael Lorenzo';
				$author->Introduction = <<<HTML
<p>Hi everyone! =) Thank you for viewing my gallery. My images are for free and I would love to see your projects or send me a link to your website so that I can see where you used nazreth's photos. It's a pleasure to see your artworks and it would inspire me to come up with more useful images. =)<br /><br />Thanks again and enjoy viewing my gallery! =)</p>
HTML;
				$author->write();
			}
			
			$ebook->Authors()->add($author);
			
			DB::alteration_message('payable Ebook example \'Silhouetted against the moon\'', 'created');
		}
	}
	
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
		return $gd->paddedResize(120,160);
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
		'Introduction' => "HTMLText",
	);
}

class MovieTicket extends DataObject{
	static $db = array(
		'StartTime' => 'Varchar',
		'EndTime' => 'Varchar',
		'Date' => 'Varchar',
		'MovieTitle' => 'Varchar',
	);
	static $has_one = array(
		'Theatre' => 'Theatre'
	);
	
	function requireDefaultRecords() {
		parent::requireDefaultRecords();

		$ticket = DataObject::get_one('MovieTicket', "\"MovieTitle\" = 'Daft Robot 4'");
		if(!($ticket && $ticket->exists())) {
			$ticket = new MovieTicket();
			$ticket->MovieTitle = 'Daft Robot 4';
			$ticket->StartTime = '8:00 PM';
			$ticket->EndTime = '10:00 PM';
			$ticket->Date = $date = date('Y-m-d', strtotime("1 month"));
			
			$theatre = DataObject::get_one('Theatre', "\"Title\" = 'Paramount'");
			if(!($theatre && $theatre->exists())) {
				$theatre = new Theatre(array('ClassName' => 'Theatre'));
				$theatre->Title = 'Paramount';
				$theatre->Street = '25 Main Street';
				$theatre->CityTown = 'Atown';
				$theatre->Description = <<<HTML
<p>Paramount is a first class, 10 screen, cinema  complex. The cinemas features wall-to-wall screens, digital sound,  stadium seating, luxury armchair comfort, first release movies and value  packed candy bar deals.</p>
HTML;
				$theatre->OtherInfo = <<<HTML
<p><em>Movieline - (04) 801 4600 			<br /> Gold Lounge - (04) 801 4610</em></p>
HTML;
				$theatre->write();
			}
			$ticket->TheatreID = $theatre->ID;
			$ticket->Amount->Amount = '21';
			$ticket->Amount->Currency = 'USD';
			$ticket->write();
			DB::alteration_message('payable ticket example \'Daft Robot 4\'', 'created');
		}
	}
	
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
		$message = "<h5>This is a confirmation of your ticket for: </h5><br /><h6>".$this->MovieTitle."</h6><h6>".$this->Theatre()->Title."</h6><h6>Time: from ".$this->StartTime." to ".$this->EndTime."<br />Date: ".$this->Date."</h6>";
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