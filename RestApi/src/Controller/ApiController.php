<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace RestApi\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Firebase\JWT\JWT;

class ApiController extends AbstractRestfulController
{
    public $httpStatusCode = 200;

    public $apiResponse;public $apiResponse1;

    public $jwtPayload;

    public $jwtToken;

    protected $secret;

    protected $algorithm;

    public function __construct()
    {
        $this->secret = "supersecretkeyyoushouldnotcommittogithub";
        $this->algorithm = "HS256";
    }

    protected function generateJwtToken($payload){
        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    protected function decodeJwtToken($token){
        return JWT::decode($token, $this->secret, [$this->algorithm]);
    }

    public function createResponse()
    {
    	$event = $this->getEvent();
        $request  = $event->getRequest();
        $response = $event->getResponse();
    	
        if(is_array($this->apiResponse)){
            $response->setStatusCode($this->httpStatusCode);
        } else {
            $response->setStatusCode(500);
            $this->apiResponse['error'] = "Something Missing.";
        }
        
        if($this->httpStatusCode == 200){
            $sendResponse['status'] = 'OK'; 
        } else {
            $sendResponse['status'] = 'NOK'; 
        }
    	$sendResponse['result'] = $this->apiResponse; 
        $sendResponse['result1'] = $this->apiResponse1; 

    	return new JsonModel($sendResponse);
    }
}
