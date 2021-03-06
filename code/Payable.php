<?php

class Payable extends DataObjectDecorator {
	function extraStatics(){
		return array(
			'db' => array(
				'Amount' => 'Money',
			),
		);
	}
	
	function updateCMSFields(&$fields){
		$money= $fields->datafieldByName('Amount');
		$money->allowedCurrencies = DPSAdapter::$allowed_currencies;
	}
	
	function PayableLink() {
		$payablesPage = DataObject::get_one('PayablesDemoPage');
		$id = $this->owner->ID?$this->owner->ID:"";
		return $payablesPage->Link()."payfor/".$this->owner->ClassName."/".$id;
	}
	
	function ConfirmLink($payment) {
		$payablesPage = DataObject::get_one('PayablesDemoPage');
		return $payablesPage->Link()."confirm/".$payment->ClassName."/".$payment->ID;
	}
	
	function processDPSPayment($data, $form) {
		$form->saveInto($this->owner);
		$this->owner->write();
		
		if(!$member = DataObject::get_one('Member', "\"Email\" = '".$data['Email']."'")){
			$member = new Member();
			$form->saveInto($member);
			$member->write();
		}else{
			$member->update($data);
			$member->write();
		}

		$payment = new DPSPayment();
		$payment->Amount->Amount = $this->owner->Amount->Amount;
		$payment->Amount->Currency = $this->owner->Amount->Currency;
		
		$payment->PaidByID = $member->ID;
		$payment->PaidForClass = $this->owner->ClassName;
		$payment->PaidForID = $this->owner->ID;
		$payment->MerchantReference = $this->owner->getMerchantReference();
		$payment->write();
		
		$payment->DPSHostedRedirectURL = $this->ConfirmLink($payment);
		$payment->write();
		$payment->dpshostedPurchase(array());
	}
	
	
}

?>