<?php
define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/handmadeCorner/');
define('CART_COOKIE','randomStrdjaG8');
define('CART_COOKIE_EXPIRE',time() + (86400 *30));

define('TAXRATE',0.000);//taxa vanzare - tva sau shipping tax

define('CURRENCY', 'ron');
define('CHECKOUTMODE','TEST');//change tets to live when you're reaady to go live

if(CHECKOUTMODE == 'TEST'){
  define('STRIPE_PRIVATE','sk_test_WPgmJNFjodplix6f44EmDOhw');
  define('STRIPE_PUBLIC','pk_test_VYhs6ozZroivd6Qf2C7GTuD0');
}

if(CHECKOUTMODE == 'LIVE'){
  define('STRIPE_PRIVATE','');
  define('STRIPE_PUBLIC','');
}
