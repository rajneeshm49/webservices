<?php 
namespace App\Controller\Component;
use Cake\Controller\Component;
class WebserviceControllerUtilityComponent extends Component
{
    protected $fields;
    
    protected $mandatoryFields;
    
    protected $func;
    
    public $mandatoryFieldMissing;
    
    public $mandatoryFieldMissingMessage;
    
    public function startFunc($func)
    {
        $this->func = $func;
        $this->setAllFieldsAndMandatoryFields();
        $this->mandatoryFieldMissing = '';
        $this->mandatoryFieldMissingMessage = '';
    }
    
    public function getFields($request_fields)
    {
        $error_message = array('error' => 0, 'message' => '');
        $isTrue = $this->isValidJSON($request_fields);
        if(empty($isTrue)) {
            $error_message['error'] = 1;
            $error_message['message'] = 'Data passed is not valid json data';
            return $error_message;
        }
        
        if(!empty($this->mandatoryFields[$this->func])) {
            $this->mandatoryFieldMissingMessage[$this->func] = $this->mandatoryFields[$this->func];
            $this->mandatoryFields[$this->func] = array_keys($this->mandatoryFields[$this->func]);
        }
        
        if ($this->checkRequiredFields($request_fields)) {
            $fields = $this->fields[$this->func];
            $fieldsWithVal = array();
            foreach ($fields as $field) {
                if (isset($request_fields[$field])) {
                    $fieldsWithVal[$field] = $request_fields[$field];
                }
            }
            return $fieldsWithVal;
        }
        $error_message['error'] = 1;
        $error_message['message'] = $this->mandatoryFieldMissing . " field is missing";
        return $error_message;
    }
    
    protected function setAllFieldsAndMandatoryFields()
    {
        $this->fields["exchangeToken"] = array(
            'public_token',
            'user_id',
            'institution'
        );
    
        $this->mandatoryFields["exchangeToken"] = array(
            'public_token' => 'Public Token',
            'user_id' => 'User Id'
        );
        
        $this->fields["getTransactions"] = array(
        		'access_token',
        		'start_date',
        		'end_date'
        );
        
        $this->mandatoryFields["getTransactions"] = array(
        		'access_token' => 'Access Token',
        		'start_date' => 'Start Date',
        		'end_date' => 'End Date'
        );
    }
    
    protected function checkRequiredFields($request_fields)
    {
        if (! isset($this->mandatoryFields[$this->func])) {
            $this->mandatoryFields[$this->func] = array();
            $this->fields[$this->func] = array();
            return true;
        }
        $mandatoryFields = $this->mandatoryFields[$this->func];
        foreach ($mandatoryFields as $field) {
                if (! isset($request_fields[$field])) {
                    $this->mandatoryFieldMissing = $this->mandatoryFieldMissingMessage[$this->func][$field];
                    // echo $this->mandatoryFieldMissing;exit;
                    return false;
                }
                if (isset($request_fields[$field]) && empty($request_fields[$field])) {
                    $this->mandatoryFieldMissing = $this->mandatoryFieldMissingMessage[$this->func][$field];
                    return false;
                }
        }
        
        return true;
    }
    
    public function isValidJSON($data)
    {
        return json_decode(json_encode($data));
    }
}
?>