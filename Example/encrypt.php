<?php

include 'Crypt/Crypt.php';

$strText = "Text to encrypt goes here!";

$objSecure = new Crypt\Crypt;

$return = $objSecure->EncryptSave($strText);

// id = the files id
// key = decryption key
print_r($return);

?>
