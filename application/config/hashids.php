<?php

/**
 * CodeIgniter Hashids
 *
 * @package
 * @author
 * @copyright	Copyright (c) 2013, Sekati LLC.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://sekati.com
 * @version		v1.0.5
 */

// Custom value that will make your hashes unique to your salt.
// NOTE: If salt is changed, your hashes cannot be decrypted properly.
// Empty string by default.
$config['hashids_salt']             = 'Saeed Ullah Lashari - Arhamsoft Pvt. Ltd.';

// Minimum hash length to set for your hashes. Default is 0,
// meaning your hashes will be the shortest possible.
$config['hashids_min_hash_length']  = 7;

// Custom alphabet to set for your hashes. By default it's set
// to lower case letters, upper case letters, and numbers.
$config['hashids_alphabet']         = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

/* End of file hashids.php */