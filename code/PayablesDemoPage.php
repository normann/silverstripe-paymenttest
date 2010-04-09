<?php

class PayablesDemoPage extends Page {
	
	function requireDefaultRecords() {
		$paymentDemoPage = DataObject::get_one('PayablesDemoPage');
		if(!($paymentDemoPage && $paymentDemoPage->exists())) {
			$paymentDemoPage = new PayablesDemoPage();
			$paymentDemoPage->Title = _t('PayablesDemoPage.DEFAULTPAYMENTSDEMOPAGETITLE', 'Payments');
			$paymentDemoPage->Content = _t('PayablesDemoPage.DEFAULTPAYMENTSDEMOPAGECONTENT', '<p>The SilverStripe Payment Module can be used for different types of  products, for example, a physical product, a download, or a donation.</p>
			<p>To add or edit a product, go the the <a href="admin/payables/">Payables section in the CMS</a>.</p>');
			$paymentDemoPage->Status = 'New page';
			$paymentDemoPage->write();
			
			DB::alteration_message('Payments demo page created', 'created');
		}
	}

}

class PayablesDemoPage_Controller extends Page_Controller {
	
	function init(){
		parent::init();
		Requirements::css("payment-test/css/Payables.css");
	}
	
	function Products() {
		return DataObject::get('ProductObject');
	}
	
	function Donation() {
		return singleton('Donation');
	}
	
	function Tickets() {
		return DataObject::get('MovieTicket');
	}
	
	function Ebooks() {
		return DataObject::get('Ebook');
	}
	
	function payfor() {
		$object = $this->Object();
		$content = $object->renderWith($object->ClassName."_payable");
		$form = $this->ObjectForm();
		$cancel = "<div class=\"clear\"></div><a href=\"".$this->Link()."\" class=\"button\">I've changed mind, cancel.</a>";
		$customisedController = $this->customise(array(
			"Content" => $content.$form->forTemplate().$cancel,
			"Form" => '',
		));
		
		return $customisedController->renderWith("Page");
	}
	
	function confirm() {
		$payment = $this->Object();
		$content = $payment->renderWith($payment->ClassName."_confirmation");
		$goback = "<div class=\"clear\"></div><a href=\"".$this->Link()."\" class=\"button\">Go Back</a>";
		$customisedController = $this->customise(array(
			"Content" => $content.$goback,
			"Form" => '',
		));
		
		return $customisedController->renderWith("Page");
	}
	
	function Object() {
		if(isset($this->URLParams['ID'])){
			if(isset($this->URLParams['OtherID'])) {
				$object = DataObject::get_by_id($this->URLParams['ID'], $this->URLParams['OtherID']);
			}else{
				$object = singleton($this->URLParams['ID']);
			}
		} else if($_REQUEST['ObjectClass']){
			if($_REQUEST['ObjectID']){
				$object = DataObject::get_by_id($_REQUEST['ObjectClass'], $_REQUEST['ObjectID']);
			}else{
				$object = singleton($_REQUEST['ObjectClass']);
			}
		}
		return $object;
	}
	
	function ObjectForm(){
		$object = $this->Object();
		$fields = $object->getPaymentFields();
		$fields->push(new HiddenField('ObjectClass', 'ObjectClass', $object->ClassName));
		$fields->push(new HiddenField('ObjectID', 'ObjectID', $object->ID));
		$required = $object->getPaymentFieldRequired();
		
		$form = new Form($this,
			'ObjectForm',
			$fields,
			new FieldSet(
				new FormAction('processDPSPayment', "Yes, go and proceed to pay")
			),
			new RequiredFields($required)
		);
		return $form;
	}
	
	function processDPSPayment($data, $form, $request) {
		$object = $this->Object();
		$object->processDPSPayment($data, $form);
	}
}

?>