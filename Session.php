<?php 
	namespace thainv\phpmvc;

	class Session
	{

		public const KEY_FLASH = 'flash_messges';
		public function __construct()
		{
		    session_start();
		    $flashMessages = $_SESSION[self::KEY_FLASH] ?? [];
		    foreach ($flashMessages as $key => &$flashMessage) {
		    	$flashMessage['remove'] = true;
		    }
		    $_SESSION[self::KEY_FLASH] = $flashMessages;
		}

		public function getFlash($key)
		{
		    return $_SESSION[self::KEY_FLASH][$key]['value'] ?? false;
		}

		public function setFlash(string $key, string $message)
		{
		    $_SESSION[self::KEY_FLASH][$key] = [
		    	'remove' => false,
		    	'value' => $message

		    ];
		}

		public function get($key)
		{
		    return $_SESSION[$key] ?? false;
		}

		public function set($key, $value)
		{
		    $_SESSION[$key] = $value;
		}

		public function destroy($key)
		{
		   unset($_SESSION[$key]);
		}

		public function __destruct()
		{
			 $flashMessages = $_SESSION[self::KEY_FLASH] ?? [];
		    foreach ($flashMessages as $key => &$flashMessage) {
		    	if ($flashMessage['remove']) {
		    		unset($flashMessages[$key]);
		    	}
		    }
		    $_SESSION[self::KEY_FLASH] = $flashMessages;
		}
	}

?>