<?php
/**
 * SimpleIMAP
 * PHP-written IMAP-library for easy usage
 *
 * @author Bailey Rud <info@bailey-rud.de>
 * @version 1.0
 */

class SimpleIMAP {

	private $connection;
	private $inbox;
	private $inbox_count;

	private $server_host;
	private $server_user;
	private $server_pass;
	private $server_port;
	private $server_ssl;
	private $server_ssl_verify;
	
	public function __construct($server="localhost", $user, $pass, $port=993, $ssl=true, $ssl_verify=true) {
		// set params
		$this->server_host = $server;
		$this->server_user = $user;
		$this->server_pass = $pass;
		$this->server_port = $port;
		$this->server_ssl = $ssl;
		$this->server_ssl_verify = $ssl_verify;

		// try connection to IMAP server
		$this->connect();
	}

	/**
	 * Connect to the IMAP server with configuration-data
	 */
	private function connect() {
		$connection = imap_open("{".$this->server_host.":".$this->server_port.($this->server_ssl !== false ? "/ssl".($this->server_ssl_verify !== false ? "" : "/novalidate-cert") : "")."}", $this->server_user, $this->server_pass);
		if($connection !== false){
			$this->connection = $connection;
			$this->inbox_count = imap_num_msg($connection);  // save number of messages
			$this->buildInbox();  // save inbox, must be called after inbox_count is set
			return true;
		} else{
			throw new \Exception("Connection to IMAP server failed!\n");
			die(1);
		}
	}

	/**
	 * Build the inbox for the class-method
	 */
	private function buildInbox() {
		$mails = array();
		for($i=1;$i<=$this->inbox_count;$i++){
			$mail = array();
			$mail['index'] = $i;
			$mail['struct'] = imap_fetchstructure($this->connection, $i);
			$mail['header'] = imap_headerinfo($this->connection, $i);
			$mail['body'] = imap_body($this->connection, $i);
			$mails[] = $mail; // save to return-array
		}
		$this->inbox = $mails;
	}

	/**
	 * Set the IMAP timeout value
	 * @param int $seconds Timeout in seconds.
	 */
	public function setTimeout($seconds) {
		$set_timeout = imap_timeout(IMAP_WRITETIMEOUT, (int) $seconds);
		return (bool) $set_timeout;
	}

	/**
	 * Get the current timeout value
	 */
	public function getTimeout() {
		$get_timeout = imap_timeout(IMAP_READTIMEOUT);
		return $get_timeout;
	}

	/**
	 * Get the number of all messages
	 */
	public function countMessages() {
		return (int) $this->inbox_count;
	}

	/**
	 * Get the inbox
	 * @param boolean $rebuild Rebuild the local-saved inbox for the very-last stand
	 */
	public function getInbox($rebuild=false) {
		if($rebuild !== false) $this->buildInbox();  // may take a long time
		return $this->inbox;
	}

}
