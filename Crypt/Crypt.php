<?php
/**
 * Crypt
 *
 * @package    Crypt
 * @author     Daniel Pomfret <@danoidx>
 * @copyright  Copyright (c) Daniel Pomfret
 * @version    $Id$
 * @link       https://crypt.store
 */

namespace Crypt;

class Crypt
{
    private $strDir = "../store/"; // The directory where the encrypted files are stored.
    private $strCipher = "aes-256-ecb"; // The cipher to use during encryption

    /*
     * EncryptSave - Encrypts then saves the encrypted text to file.
     * @param  $strText - required. The text to encrypt.
     * @return $array - associative array containing the id (unique id of the encryption result) and the key (to decode the encrypted file).
     */
    public function EncryptSave($strText)
    {
        // Clean the input
        $strText = $this->Clean($strText);

        // Encrypt the input
        $arrayEncrypted = $this->Encrypt($strText);

        // Save the encrypted text to file
        $arrayReturn['id'] = $this->Save($arrayEncrypted['encrypted']);

        // Set the return key
        $arrayReturn['key'] = $arrayEncrypted['key'];

        // Return the array
        return $arrayReturn;
    }

    /*
     * LoadDecrypt - Load the file, and decrypt the text
     * @param  $strId - required. The text to encrypt. $strKey - The decryption key
     * @return $string - The decrypted text
     */
    public function LoadDecrypt($strId, $strKey)
    {
        // Load the encrypted text
        $strEncrypted = $this->Load($strId);

        // Decode the encrypted text
        $strText = $this->Decrypt($strEncrypted, $strKey);

        // Return the decrypted text
        return $strText;
    }

    /*
     * Encrypt - Actually performs the encryption
     * @param  $strText - required. The text to encrypt.
     * @return $array - associative array containing the id (unique id of the encryption result) and the key (to decode the encrypted file).
     */
    public function Encrypt($strText)
    {
        // Get a random 4 digit number (the salt and decryption key)
        $strKey = $this->RandomPin(4);

        // Perform the encryption
        $strEncrypted = openssl_encrypt($strText, $this->strCipher, $strKey, true);

        // Return the key and hast (encrypted text)
        return array("key" => $strKey, "encrypted" => $strEncrypted);
    }

    /*
     * Decrypt - Reverse the encryption
     * @param  $strEncrypted - required. Encrypted Text
     * @return $string - Returns the decrypted string.
     */
    public function Decrypt($strEncrypted, $strKey)
    {
        // Decrypt the encrypted text
        $strText = openssl_decrypt($strEncrypted, $this->strCipher, $strKey, true);

        // Return the decrypted text
        return $strText;
    }

    /*
     * Save - Saves the encrypted text to a file.
     * @param  $strEncrypted - required. Encrypted Text
     * @return $string - Returns the filename of the saved file.
     */
    private function Save($strEncrypted)
    {
        $boolUnique = false;

        // Hack: Make sure the file doesn't already exist.
        while ($boolUnique == false) {
            $strFileName = $this->RandomString();

            if (file_exists($this->strDir . $strFileName)) {
                $boolUnique = false;
            } else {
                $boolUnique = true;
            }
        }

        // Write the encrypted text to file.
        file_put_contents($this->strDir . $strFileName, $strEncrypted, LOCK_EX);

        // Return the filename
        return $strFileName;
    }


    /*
     * Load - Loads the encrypted text from the file
     * @param  $strId - required. The filename
     * @return $string - Returns the encrypted text
     */
    private function Load($strId)
    {
        $strEncrypted = file_get_contents($this->strDir . $strId);

        return $strEncrypted;
    }

    /*
     * Clean - Cleans the input
     * @param  $strText - required. Text to clean
     * @return $string - Returns the cleaned text
     */
    private function Clean($strText)
    {
        // Todo: Clean better!
        $strText = htmlspecialchars($strText);

        // Return Cleaned Text
        return $strText;
    }

    /*
     * RandomString - Generates a random string
     * @param  $intLength - not required. Size of the string
     * @return $string - Returns the random string
     */
    private function RandomString($intLength = 10)
    {
        // Character set
        $strCharacters = '0123456789abcdefghijklmnopqrstuvwxyz';

        $strRandom = '';

        // Loop and generate a string
        for ($i = 0; $i < $intLength; $i++) {
            $strRandom .= $strCharacters[rand(0, strlen($strCharacters) - 1)];
        }

        // Return string
        return $strRandom;
    }

    /*
     * RandomPin - Generates a random pin number
     * @param  $intLength - not required. Size of the pin
     * @return $string - Returns the random pin
     */
    private function RandomPin($intLength = 10)
    {
        // Character set
        $strCharacters = '0123456789';

        $strRandom = '';

        // Loop and generate a pin
        for ($i = 0; $i < $intLength; $i++) {
            $strRandom .= $strCharacters[rand(0, strlen($strCharacters) - 1)];
        }

        // Return pin
        return $strRandom;
    }
}
