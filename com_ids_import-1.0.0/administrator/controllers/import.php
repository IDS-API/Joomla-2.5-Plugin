<?php
/**
 * @version     1.0.0
 * @package     com_ids_import
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      @iLabAfrica <ilabafrica@strathmore.edu> - ilabafrica.ac.ke
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Import controller class.
 */
class Ids_importControllerImport extends JControllerForm
{

    function __construct() {
        $this->view_list = 'imports';
        parent::__construct();
    }

}