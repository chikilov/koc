<?php

	if ( $_SERVER['HTTP_HOST'] == 'production.address.co.kr' ) {
		define('ENVIRONMENT', 'production');
	} else if ( $_SERVER['HTTP_HOST'] == '54.64.86.88' ){
		define('ENVIRONMENT', 'staging');
	} else {
		define('ENVIRONMENT', 'development');
	}

	define( "ENCRYPTION_KEY", "abcdefghijklmnopqrstuvwxyz123456" );

	function AESEncrypt( $input, $key )
	{
		return base64_encode( openssl_encrypt( $input, "aes-256-cbc", $key, true, str_repeat( chr( 0 ), 16 ) ) );
		/*
		$padSize = 16 - ( strlen( $input ) % 16 );
		$input	 = $input.str_repeat( chr( $padSize ), $padSize );
		$output  = mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $key, $input, MCRYPT_MODE_CBC, str_repeat( chr( 0 ), 16 ) );
		return base64_encode( $output );
		*/
	}

	function AESDecrypt( $input, $key )
	{
		return openssl_decrypt( base64_decode( $input ), "aes-256-cbc", $key, true, str_repeat( chr( 0 ), 16 ) );
		/*
		$input  = base64_decode( $input );
		$output = mcrypt_decrypt( MCRYPT_RIJNDAEL_128, $key, $input, MCRYPT_MODE_CBC, str_repeat( chr( 0 ), 16 ) );

		$valueLen = strlen( $output );
		if( $valueLen % 16 > 0 )
			$output = "";

		$padSize = ord( $output{ $valueLen - 1 } );
		if( ( $padSize < 1 ) or ( $padSize > 16 ) )
			$output = "";                // Check padding.

		for( $i = 0; $i < $padSize; $i++ )
		{
			if( ord( $output{ $valueLen - $i - 1 } ) != $padSize )
				$output = "";
		}

		$output = substr( $output, 0, $valueLen - $padSize );
	    return $output;
		*/
	}

	function LOG_NORMAL( $message = "" )
	{
		echo $message."\n";
	}

	function LOG_DEBUG( $message = "" )
	{
		if (defined('ENVIRONMENT'))
		{
			switch (ENVIRONMENT)
			{
				case 'development':
					echo $message."\n";
				break;

				case 'staging':
					echo $message."\n";
				break;
				case 'production':
				break;

				default:
					exit('The application environment is not set correctly.');
			}
		}
	}

	abstract class NGClient
	{
		private $m_id;
		private $m_socket;
		private $m_authenticated = FALSE;

		function __construct( $id, $socket )
		{
			$this->m_id		= $id;
			$this->m_socket = $socket;
		}

		public function GetInstanceId()		{ return $this->m_id; }
		public function IsAuthenticated()	{ return $this->m_authenticated; }
		public function Authenticate()		{ $this->m_authenticated = TRUE; }

		public function Send( $message )
		{
			if ( socket_write( $this->m_socket, $message, strlen( $message ) ) === FALSE )
			{
				error_log(socket_strerror(socket_last_error()), 0);
				return false;
			}
			else
			{
				return true;
			}
		}
	}

	abstract class NGServer
	{
		private static $s_encryptionKey;
		public static function ENCRYPTION_KEY()	{ return NGServer::$s_encryptionKey; }


		private $m_hostAddress;
		private $m_hostPort;

		private $m_hostSocket;
		private $m_sockets = array();
		private $m_clients = array();

		function __construct( $address, $port, $encryptionKey )
		{
			$this->m_hostAddress   = $address;
			$this->m_hostPort	   = $port;

			// 데이터 암호화를 위한 키값 획득
			NGServer::$s_encryptionKey = $encryptionKey;
		}

		abstract protected function GetClientInstance( $socket );

		private function OnConnected( $socket )
		{
			$client = $this->GetClientInstance( $socket );
			$this->m_clients[ strval( $socket ) ] = $client;
			array_push( $this->m_sockets, $socket );

			LOG_NORMAL( count($this->m_clients)." CURRENT USER, ".$socket." CONNECTED! - ".date( "d/n/Y" )." at ".date( "H:i:s T" ) );
			$this->OnConnectedClient( $client );
		}

		/*
		protected function OnHandshake( $client, $buffer )
		{
			$client->Authenticate();
			return true;
		}
		*/

		abstract protected function Dispatch( $client, $command, $data );

		private function OnReceived( $client, $message )
		{
			$decodedData = json_decode( $message, true );
			$command	 = $decodedData[ 'cmd' ];
			if( 0 < strlen( $command ) )
			{
				$data = $decodedData[ 'dat' ];
				return $this->Dispatch( $client, $command, $data );
			}
			return false;
		}

		private function OnDisconnected( $socket )
		{
			$client = $this->m_clients[ strval( $socket ) ];
			$this->OnDisconnectedClient( $client );
			unset( $this->m_clients[ strval( $socket ) ] );
			//$index = array_search( $socket, $this->m_clients );
			//if( 0 <= $index ) array_splice( $this->m_clients, $index, 1 );

			$index = array_search( $socket, $this->m_sockets );
			if( 0 <= $index ) array_splice( $this->m_sockets, $index, 1 );
			else LOG_NORMAL( $socket."INVALID SOCKET INDEX IN ARRAY" );

			LOG_NORMAL( count($this->m_clients)." CURRENT USER, ".$socket." DISCONNECTED!" );
			socket_close( $socket );
		}

		public function Run()
		{
			LOG_NORMAL( "SERVER STARTED: ".date( 'Y-m-d H:i:s' ) );
			LOG_NORMAL( " - LISTENING ON : ".$this->m_hostAddress.", PORT: ".$this->m_hostPort );


			error_reporting( E_ALL );
			set_time_limit( 0 );
			ob_implicit_flush();

			$this->m_hostSocket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP ) or die( "NGServer::socket_create() failed" );
			socket_set_option( $this->m_hostSocket, SOL_SOCKET, SO_REUSEADDR, 1 ) or die("NGServer::socket_option() failed");
			socket_bind( $this->m_hostSocket, $this->m_hostAddress, $this->m_hostPort ) or die("NGServer::socket_bind() failed");
			socket_listen( $this->m_hostSocket, 20 ) or die("NGServer::socket_listen() failed");
			LOG_NORMAL( " - HOST SOCKER : ".$this->m_hostSocket."\n");

			$this->m_sockets[] = $this->m_hostSocket;

			while( true )
			{
				$changed = $this->m_sockets;
				$write = null;
				$except = null;
				socket_select( $changed, $write, $except, NULL );

				foreach( $changed as $socket )
				{
					if( $socket == $this->m_hostSocket )
					{
						$newSocket = socket_accept( $this->m_hostSocket );
						if( $newSocket < 0 )
						{
							LOG_NORMAL( "NGServer::socket_accept() failed" );
							continue;
						}
						else
						{
							$this->OnConnected( $newSocket );
						}
					}
					else
					{
						$bytes = @socket_recv( $socket, $buffer, 2048, 0 );
						if( $bytes == 0 )
						{
							$this->OnDisconnected( $socket );
						}
						else
						{
							$client = $this->m_clients[ strval( $socket ) ];
							if( null != $client )
							{
								$decrypted = AESDecrypt( $buffer, NGServer::ENCRYPTION_KEY() );
								if( false == $this->OnReceived( $client, $decrypted ) )
								{
									$this->OnDisconnected( $socket );
								}
							}
							else
							{
								LOG_NORMAL( "CAN'T FOUND CLIENT IN TBLAE - SOCKET: ".$socket );
							}
						}
					}
				}
			}
		}

		// ------------------------------------------------------------------------------------------------------------
		// 서버 전체 클라이언트에게 메세지를 전달한다.
		// ------------------------------------------------------------------------------------------------------------
		protected function Broadcast( $message )
		{
			$message = AESEncrypt( $message, NGServer::ENCRYPTION_KEY() );
			foreach( $this->m_clients as $client )
			{
				$client->Send( $message );
			}
		}

		protected function OnConnectedClient( NGClient $client )
		{
		}

		protected function OnDisconnectedClient( NGClient $client )
		{
		}
	}

	class NGChatClient extends NGClient
	{
		private $m_pid;
		private $m_name;
		private $m_channel;

		function __construct( $id, $socket )
		{
			parent::__construct( $id, $socket );
		}

		function SetChannel( $channel ) { $this->m_channel = $channel;	}
		function GetChannel()		    { return $this->m_channel;		}
	}

	class NGChatChannel
	{
		private $m_id;
		private $m_clients = array();

		function __construct( $channelId )
		{
			$this->m_id = $channelId;
		}

		function __get( $name )
		{
			switch( $name )
			{
				case 'ID'			: return $this->m_id;
			}
		}

		function Enter( NGChatClient $client )
		{
			$this->m_clients[ (int)$client->GetInstanceId() ] = $client;
			$client->SetChannel( $this );
		}

		function Leave( NGChatClient $client )
		{
			$client->SetChannel( null );
			unset( $this->m_clients[ $client->GetInstanceId() ] );
		}

		function Broadcast( $message )
		{
			$message = AESEncrypt( $message, NGServer::ENCRYPTION_KEY() );
			foreach( $this->m_clients as $client )
			{
				$client->Send( $message );
			}
		}
	}

	class NGChatServer extends NGServer
	{
		const MAX_CHANNEL	= 999;		// 최대 채널

		private $m_channelArray = array();

		function __construct( $address, $port, $encryptionKey )
		{
			parent::__construct( $address, $port, $encryptionKey );
			$this->PrepareChannel();
		}

		private function PrepareChannel()
		{
			LOG_DEBUG( "INITIALIZE CHANNEL TABLE - MAX: ".NGChatServer::MAX_CHANNEL );
			for( $i = 0; $i < NGChatServer::MAX_CHANNEL; $i++ )
			{
				$channelId = $i + 1;
				$this->m_channelArray[ $channelId ] = new NGChatChannel( $channelId );
				LOG_DEBUG( " > OPEN - ID: ".$channelId );
			}
		}

		protected function GetClientInstance( $socket )
		{
			return new NGChatClient( $socket, $socket );
		}

		/*
		protected function OnHandshake( $client, $buffer )
		{
			LOG_DEBUG( "REQUESTING HANDSHAKE..." );
			LOG_DEBUG( " - DATA: ".$buffer );

			list( $resource, $host, $origin, $key1, $key2, $l8b ) = $this->getheaders( $buffer );
			$this->log("Handshaking...");
			//$port = explode(":",$host);
			//$port = $port[1];
			//$this->log($origin."\r\n".$host);
			$upgrade  = "HTTP/1.1 101 WebSocket Protocol Handshake\r\n" .
                "Upgrade: WebSocket\r\n" .
                "Connection: Upgrade\r\n" .
                                //"WebSocket-Origin: " . $origin . "\r\n" .
                                //"WebSocket-Location: ws://" . $host . $resource . "\r\n" .
                "Sec-WebSocket-Origin: " . $origin . "\r\n" .
                    "Sec-WebSocket-Location: ws://" . $host . $resource . "\r\n" .
                    //"Sec-WebSocket-Protocol: icbmgame\r\n" . //Client doesn't send this
                "\r\n" .
                    $this->calcKey($key1,$key2,$l8b) . "\r\n";// .
                        //"\r\n";
			socket_write($user->socket,$upgrade.chr(0),strlen($upgrade.chr(0)));
			$user->handshake=true;
			$this->log($upgrade);
			$this->log("Done handshaking...");

			return false;


			$replyArray = array();
			$replyArray[ 'cmd' ] = "HANDSHAKE";
			$replyArray[ 'dat' ] = json_encode( array( 'cn'=>$client->GetChannel()->ID ) );
			$client->Send( json_encode( $replyArray ) );
			return parent::OnHandshake( $client, $buffer );
		}
		*/

		// ------------------------------------------------------------------------------------------------------------
		// 접속 후 핸드쉐이킹
		// ------------------------------------------------------------------------------------------------------------
		protected function OnRecvHandshake( $client, $data )
		{
			LOG_DEBUG( "HANDSHAKE - DATA: ".$data );

			$decodedData = json_decode( $data, true );
			if( null == $decodedData )
			{
				LOG_DEBUG( "INVALID HANDSHAKE PROTOCOL!!" );
				return false;
			}
			$userId		= $decodedData[ 'id' ];
			$userName	= $decodedData[ 'nm' ];
			LOG_DEBUG( " > COMPLETED - PID: ".$userId.", NAME: ".$userName );

			$channelId = rand( 1, NGChatServer::MAX_CHANNEL );
			LOG_DEBUG( " > ENTER TO CHANNEL: ".$channelId );

			$channel = $this->m_channelArray[ $channelId ];
			$channel->Enter( $client );

			$replyArray = array();
			$replyArray[ 'cmd' ] = "HANDSHAKE";
			$replyArray[ 'dat' ] = json_encode( array( 'cn'=>$channel->ID ) );
			$message = AESEncrypt( json_encode( $replyArray ), NGServer::ENCRYPTION_KEY() );
			if ( $client->Send( $message ) )
			{
				$client->Authenticate();
				return true;
			}
			else
			{
				LOG_DEBUG( "INVALID HANDSHAKE PROTOCOL!!" );
				return false;
			}
		}

		// ------------------------------------------------------------------------------------------------------------
		// 전체 유저에게 보내는 메세지 수신
		// ------------------------------------------------------------------------------------------------------------
		protected function OnRecvBroadcast( $client, $data )
		{
			LOG_DEBUG( "BROADCAST - DATA: ".$data );

			$replyArray = array();
			$replyArray[ 'cmd' ] = "BROADCAST";
			$replyArray[ 'dat' ] = $data;
			$this->Broadcast( json_encode( $replyArray ) );
			return true;
		}

		// ------------------------------------------------------------------------------------------------------------
		// 일반 채팅 메세지 수신
		// ------------------------------------------------------------------------------------------------------------
		protected function OnRecvMessage( $client, $data )
		{
			LOG_DEBUG( "MESSAGE - DATA: ".$data );

			$channel = $client->GetChannel();
			if( null != $channel )
			{
				$replyArray = array();
				$replyArray[ 'cmd' ] = "MESSAGE";
				$replyArray[ 'dat' ] = $data;
				$channel->Broadcast( json_encode( $replyArray ) );
				return true;
			}
			return false;
		}

		// ------------------------------------------------------------------------------------------------------------
		// 채널 변경 요청 수신
		// ------------------------------------------------------------------------------------------------------------
		private function OnRecvChangeChannel( $client, $data )
		{
			LOG_DEBUG( "CHANNEL - DATA: ".$data );

			$curChannel  = $client->GetChannel();
			$decodedData = json_decode( $data, true );
			$newChannelId  = $decodedData[ 'cn' ];

			// 현재 채널과 같거나 채널의 유효 범위를 벗어난 경우는 곧 바로 실패로 처리하도록 한다.
			// 클라이언트에서 정확한 값을 보낼 것을 강제하는 것이다.
			if( ( $newChannelId == $curChannel->ID ) ||
			    ( 0 >= $newChannelId || $newChannelId > NGChatServer::MAX_CHANNEL ) )
				return false;

			LOG_DEBUG( " > CHANGE CHANNEL - SRC: ".$curChannel->ID.", DST: ".$newChannelId );

			$curChannel->Leave( $client );
			$newChannel = $this->m_channelArray[ $newChannelId ];
			$newChannel->Enter( $client );

			$replyArray = array();
			$replyArray[ 'cmd' ] = "CHANNEL";
			$replyArray[ 'dat' ] = json_encode( array( 'cn'=>$newChannel->ID ) );

			$message = AESEncrypt( json_encode( $replyArray ), NGServer::ENCRYPTION_KEY() );
			$client->Send( $message );
			return true;
		}

		// ------------------------------------------------------------------------------------------------------------
		// 커맨드에 따른 메세지 디스패치
		// ------------------------------------------------------------------------------------------------------------
		protected function Dispatch( $client, $command, $data )
		{
			if( "HANDSHAKE" == $command  )
			{
				return $this->OnRecvHandshake( $client, $data );
			}
			else if( $client->IsAuthenticated() )
			{
				switch( $command )
				{
					case 'BROADCAST' : return $this->OnRecvBroadcast( $client, $data );
					case 'MESSAGE'	 : return $this->OnRecvMessage( $client, $data );
					case 'CHANNEL'	 : return $this->OnRecvChangeChannel( $client, $data );
				}
			}
			else
			{
				LOG_NORMAL( "IsAuthenticated - FALSE: ".$command.", DATA: ".$data );
			}
			LOG_NORMAL( "DISPATCH - COMMAND(UNKNOWN): ".$command.", DATA: ".$data );
			return false;
		}

		protected function OnConnectedClient( NGClient $client )
		{
			/*
			$channelId = rand( 1, NGChatServer::MAX_CHANNEL );
			LOG_DEBUG( "ENTER TO CHANNEL: ".$channelId );
			$channel   = $this->m_channelArray[ $channelId ];
			$channel->Enter( $client );
			*/
		}

		protected function OnDisconnectedClient( NGClient $client )
		{
			if( $client instanceof NGChatClient )
			{
				//$chatClient = (NGChatClient)$client;
				$channel = $client->GetChannel();
				if( null != $channel )
				{
					$channel->Leave( $client );
				}
			}
		}
	}

	$chatServer = new NGChatServer( "101.79.109.239", 9875, ENCRYPTION_KEY );
	$chatServer->Run();
?>