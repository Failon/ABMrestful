<?php
	class Connection{
		protected $conexion;
		protected $host;
		protected $db;
		protected $user;
		protected $password;

		function __construct($data){			
			$this->host = $data['host'];
			$this->db = $data['db'];
			$this->user = $data['user'];
			$this->password = $data['password'];
		}


		public function connect(){
			$this->conexion = new PDO("mysql:host=".$this->host.";dbname=".$this->db,$this->user,$this->password);
			return $this->conexion;
		}

	}
