<?php
	class Utils
	{ // To do: Utils:: => static:: or self::
		public static function CleanTemp($Dir = 'tmp')
		{
			$Files = glob("{$Dir}/*");

			foreach($Files as $File)
			{
				if(is_file($File))
					unlink($File);
				elseif(is_dir($File))
					Utils::ClearTmp($File);
			}

			if($Dir !== 'tmp')
				rmdir($Dir);

			return true;
		}

		/*public static function IsAdmin($From)
		{
			if(is_array($From))
				$From = Utils::GetNumberFromJID($From['u'], false);
			else if(substr_count($From, '@') > 0)
				$From = Utils::GetNumberFromJID($From, false);

			if($From !== false)
			{
				$Admins = Utils::GetJson('config/WhatsBot.json');

				if(isset($Admins['whatsapp']['admins']))
					return in_array($From, $Admins['whatsapp']['admins']);
			}

			return false;
		}*/

		public static function GetNumberFromJID($JID)
		{
			return substr($JID, 0, strpos($JID, '@'));
		}

		public static function GetGroupCreator($JID)
		{

		}

		public static function GetGroupID($JID)
		{

		}

		public static function IsGroupJID($JID)
		{
			return substr_count($JID, '@') == 1 && substr_count($JID, '-') == 1 && substr($JID, -5) === '@g.us';
		}

		public static function GetRemoteFile($URL, $SucessHeaders = array(200, 301, 302)) // $ParseURL = true? || $SuccessHeaders ==> $SuccessCodes?
		{
			if(!is_array($SucessHeaders))
				$SucessHeaders = array($SucessHeaders);

			$Headers = get_headers($URL);

			if($Headers !== false && isset($Headers[0]) && in_array(substr($Headers[0], 9, 3), $SucessHeaders))
			{
				$Data = file_get_contents($URL);

				if($Data !== false)
					return $Data;
			}

			return false;
		}

			public static function GetRemoteJson($URL, $SucessHeaders = array(200, 301, 302))
			{
				$Data = static::GetRemoteFile($URL, $SucessHeaders);

				if($Data !== false)
				{
					$Data = json_decode($Data, true);

					if($Data !== null)
						return $Data;
				}

				return false;
			}

		public static function GetRemoteFilesize($URL)
		{
			$Headers = get_headers($URL, 1);

			return isset($Headers['Content-Length']) ? (int)$Headers['Content-Length'] : false;
		}

		public static function CallFunction(&$Object, $Function, $Params = array())
		{
			if(!empty($Object) && !empty($Function))
				if(method_exists($Object, $Function) && is_callable(array($Object, $Function)))
					return call_user_func_array(array($Object, $Function), $Params);

			return false;
		}
	}