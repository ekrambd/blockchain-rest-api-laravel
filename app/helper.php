<?php

use App\Models\Wallet;

 function wallet($id)
 {
 	$wallet = Wallet::find($id);
 	return $wallet;
 } 

 // function walletPrivate($id)
 // {
	// $wallet = wallet($id);
	// $privateKey = Crypt::decryptString($wallet->private_key);
	// return $privateKey;
 // }

  function emptyObject()
  {
  	  $data = new \stdClass();
  	  return $data;
  }