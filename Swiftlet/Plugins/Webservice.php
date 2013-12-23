<?php

namespace Swiftlet\Plugins;

/**
 * Webservice plugin
 * @author Malitta Nanayakkara <malitta@gmail.com>
 */
class Webservice extends \Swiftlet\Plugin
{

	/**
	 * Hook to run before each action
	 */
	public function actionBefore()
	{

		// Only runs for controllers which have extended 'Webservice' controller
	    if(is_subclass_of('Swiftlet\Controllers\\'.$this->app->getControllerName(), 'Swiftlet\Controllers\Webservice')){
	    	
	    	// track starting time of webservice
	    	$this->controller->startTime = microtime(true);

		}

	}

	/**
	 * Hook to run after each action
	 */
	public function actionAfter()
	{

		// Only runs for controllers which have extended 'Webservice' controller
	    if(is_subclass_of('Swiftlet\Controllers\\'.$this->app->getControllerName(), 'Swiftlet\Controllers\Webservice')){
	    		    	
	    	$this->controller->returnResponse();

		}

	}
}
