<?php

Object::add_extension('Product', 'Payable');
Object::add_extension('Donation', 'Payable');
Object::add_extension('Ebook', 'Payable');
Object::add_extension('MovieTicket', 'Payable');
Object::add_extension('Member', 'ProductBuyer');
?>