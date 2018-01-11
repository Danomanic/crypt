<?php

include 'Crypt/Crypt.php';

$strId = "<FILE ID HERE>";
$strKey = "<DECRYPTION KEY HERE>";

$objSecure = new Crypt\Crypt;

$return = $objSecure->LoadDecrypt($strId, $strKey);

// string = decrypted string
print_r($return);

?>
