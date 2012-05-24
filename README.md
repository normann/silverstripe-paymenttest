###############################################
Payment-test Module
===============================================
###############################################

Maintainer Contact
-----------------------------------------------
Normann LOU (Nickname: nlou, cooldaddy)
<normann (at) silverstripe (dot) com>

Requirements

SilverStripe 2.4
paymment 0.3+

Configuration:
Besides what need to be set for configuration of payment module, the module need to set:
DPSAdapter::set_receipt_from('email address that your like the payment receipt send from');

-----------------------------------------------
This module is a showcase of how to use payment module, therefor it is dependent on payment module.
Developers could check out this module and use it as a base to make your customised on-line
payment section. It contains example of UIs and necessary data objects to to make example front end
Pages. 

Currently, it only work with DPSPayment, but we should extend it to also applicable for all other
Payment method.

This module contains an Interface PayableObjectInterface and a data object decorator Payable,
Any DataObject that implements PayableObjectInterface and has extension of Payable could be paid
through a page of PayablesDemoPage page type.

This module also simply contains PayableAdmin to just make creating those payable objects easy.
