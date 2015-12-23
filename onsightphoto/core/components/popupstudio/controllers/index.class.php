<?php
/**
 * The abstract Manager Controller.
 * In this class, we define stuff we want on all of our controllers.
 */

require_once dirname(dirname(__FILE__)) . '/model/popupstudio/popupstudio.class.php';

abstract class PopupStudioManagerController extends modExtraManagerController {
    /**
     * Initializes the main manager controller. You may want to load certain classes,
     * assets that are shared across all controllers or configuration. 
     *
     * All your other controllers in this namespace should extend this one.
     *
     * In this case we don't do anything useful, but as you build up more complex
     * extras, it helps to enforce this structure to make it easier to maintain.
     */
    
    public $pop;
    
    public function initialize() {
	    
        $this->pop = new PopupStudio($this->modx);
        $this->addCss($this->pop->config['cssUrl'].'mgr.min.css');
        $this->addJavascript($this->pop->config['jsUrl'].'mgr/popupstudio.min.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Pop.config = '.$this->modx->toJSON($this->pop->config).';
            Pop.action = "'.(!empty($_REQUEST['action']) ? $_REQUEST['action'] : 0).'";
        });
        </script>');
        return parent::initialize();
    }
    /**
     * Defines the lexicon topics to load in our controller.
     * @return array
     */
    public function getLanguageTopics() {
        return array('popupstudio:default');
    }
    /**
     * We can use this to check if the user has permission to see this controller
     * @return bool
     */
    public function checkPermissions() {
        return true;
    }
}
/**
 * The PopupStudio Index Manager Controller is the default one that gets called when no
 * &action parameter is passed  We use it to define the default controller
 * which will then handle the actual processing.
 *
 * It is important to name this class "PopupStudioIndexManagerController" and making sure
 * it extends the abstract class we defined above 
 */
//class PopupStudioIndexManagerController extends PopupStudioManagerController {
    /**
     * Defines the name or path to the default controller to load.
     * @return string
     */
/*
    public static function getDefaultController() {
        return 'eventshome';
    }
*/
//}