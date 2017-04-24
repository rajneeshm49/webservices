<?php 
namespace App\Lib;

class PlaidAPI
{
	var $request_body = array();
	var $link;
	function __construct()
	{
		$this->link = PLAID_LINK;
		$this->request_body['client_id'] = PLAID_CLIENT_ID;
		$this->request_body['secret'] = PLAID_SECRET_KEY;
		
	}
	
	public function exchange($data)
	{
		$response = array(
				'success' => false,
				'data' => '',
				'error_msg' => ''
		);
		
		try{
			$this->request_body['public_token'] = $data['public_token'];

			$json_req = json_encode($this->request_body);
				
			$ch=curl_init($this->link . 'item/public_token/exchange');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_req);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				
			$api_response = curl_exec($ch) or die(curl_error($ch));
			$response['data'] = json_decode($api_response, true);
			$response['success'] = (!empty($response['data']['access_token']))?true:false;
			
			return $response;
		} catch(\Exception $e) {
			$response['error_msg'] = $e->getMessage();
		}
		return $response;
	}
	
	public function Transactions($data)
	{
		$response = array(
				'success' => false,
				'data' => '',
				'error_msg' => ''
		);
	
		try{
			$this->request_body = array_merge($this->request_body, $data);
			$json_req = json_encode($this->request_body);
	
			$ch=curl_init($this->link . 'transactions/get');
			//set options
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_req);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
						
			$api_response = curl_exec($ch) or die(curl_error($ch));
			$response['data'] = json_decode($api_response, true);
			$response['success'] = true;
			
			return $response;
		} catch(\Exception $e) {
			$response['error_msg'] = $e->getMessage();
		}
		return $response;
	}
}
?>