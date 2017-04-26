<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace RestApi;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use Zend\View\Model\JsonModel;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function onBootstrap(MvcEvent $e)
    {
    	$eventManager   = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'boforeDispatch'
        ), 100);
        
        // Set CORS headers to allow all requests
        $headers = $e->getResponse()->getHeaders();
        $headers->addHeaderLine('Access-Control-Allow-Origin: *');
        $headers->addHeaderLine('Access-Control-Allow-Methods: PUT, GET, POST, PATCH, DELETE, OPTIONS');
        $headers->addHeaderLine('Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept');
    }

    function boforeDispatch(MvcEvent $event)
    {
      	$request = $event->getRequest();
        $response = $event->getResponse();
        $auth = $event->getRouteMatch()->getParam('isauth');

        if($auth) {
            $token = $event->getRequest()->getHeaders("Authorization");
            if(!$token){
                if ($request->isGet()) {
                    $token = $request->getQuery('token');
                } else if ($request->isPOST()) {
                    $token = $request->getPost('token');
                }
            }
            if(!$token){
                $response->setStatusCode(401);
                $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $view = new JsonModel(array('status'=> 'NOK','result'=>array('error'=>'Required Authentication')));
                $response->setContent($view->serialize());
                return $response;		
            }
        }
    }
}
