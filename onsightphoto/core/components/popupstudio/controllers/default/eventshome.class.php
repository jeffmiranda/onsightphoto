<?php
/**
 * The name of the controller is based on the action (eventshome) and the
 * namespace. This eventshome controller is loaded by default because of
 * our IndexManagerController.
 */

require_once dirname(dirname(__FILE__)) . '/index.class.php';

class PopupStudioEventsHomeManagerController extends PopupStudioManagerController {
    /**
     * Any specific processing we want to do here. Return a string of html.
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = array()) {
		
    }
    /**
     * The pagetitle to put in the <title> attribute.
     * @return null|string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('pop.popupstudio_events');
    }
    /**
     * Register needed assets. Using this method, it will automagically
     * combine and compress them if that is enabled in system settings.
     */
    public function loadCustomCssJs() {
	    $this->addCss($this->pop->config['cssUrl'].'mgr/mgr.min.css');
	    $this->addJavascript($this->pop->config['jsUrl'].'mgr/events/widgets/eventgroups.container.min.js');
        $this->addJavascript($this->pop->config['jsUrl'].'mgr/events/widgets/events.grid.min.js');
        $this->addJavascript($this->pop->config['jsUrl'].'mgr/events/widgets/home.panel.min.js');
        $this->addLastJavascript($this->pop->config['jsUrl'].'mgr/events/sections/index.min.js');
/*
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            // We could run some javascript here
        });
        </script>');
*/
    }
    public function getTemplateFile() {
        return $this->pop->config['templatesPath'].'events/home.tpl';
    }
}