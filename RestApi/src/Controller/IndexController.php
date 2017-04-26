<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace RestApi\Controller;

class IndexController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function indexAction()
    {
        $request = $this->getRequest();
        $this->apiResponse1['get'] = $request->isGet();
        $this->apiResponse1['Post'] = $request->isPost();
        $this->apiResponse1['options'] = $request->isOptions();
        $this->apiResponse1['put'] = $request->isPut();
        $this->apiResponse1['delete'] = $request->isDelete();
        $this->apiResponse1['token'] = $request->getQuery('token');
        $this->apiResponse1['tpostoken'] = $request->getPost('token');
        
    	$this->apiResponse = array('data'=>'array');
        return $this->createResponse();
    }
}
