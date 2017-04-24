<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Lib\PlaidApi;
use Cake\Http\Response;
use Cake\Event\Event;
/**
 * Plaid Controller
 */
class PlaidController extends AppController
{
	
	public function initialize() {
		parent::initialize();
		$this->loadComponent('WebserviceControllerUtility');
	}
	
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);
		$this->response->type('json');
		$func = $this->request->params["action"];
		$this->func = $func;
		$this->WebserviceControllerUtility->startFunc($func);
	}
	
	public function exchangeToken()
	{
		$data = $this->request->input('json_decode', true);
		$this->preFunctionRunTask($data);
// 		$resp = '';
		$plaid_obj = new PlaidApi();
		$resp = $plaid_obj->exchange($data);

		$resultJ = json_encode($resp);
		$this->response->body($resultJ);
		return $this->response;
	}
	
	public function getTransactions()
	{
		$data = $this->request->input('json_decode', true);
		$this->preFunctionRunTask($data);
		
		$plaid_obj = new PlaidApi();
		$resp = $plaid_obj->Transactions($data);
		
		$resultJ = json_encode($resp);
		$this->response->body($resultJ);
		return $this->response;
	}
	
	protected function preFunctionRunTask($data)
	{
		$get_fields = $this->WebserviceControllerUtility->getFields($data);
		if (!empty($get_fields['error'])) {
			$this->return_message = array(
					"success" => false,
					"data" => "",
					"error_msg" => $get_fields['message']
			);
	
			$this->exitWithResponse();
		}
// 		$this->fields = $get_fields;
	}
	
	function exitWithResponse($response = false, $responseCode = false)
	{
		header('Content-Type: application/json');
		if ($responseCode !== false && intval($responseCode) > 0) {
			$responseCode = intval($responseCode);
			switch ($responseCode) {
				case 202:
					header("HTTP/1.1 202 Accepted");
					break;
				case 404:
					header("HTTP/1.1 404 Not Found");
			}
		}
		if ($response !== false) {
			echo json_encode($response);
			exit();
		}
		echo json_encode($this->return_message);
		exit();
	}
}
