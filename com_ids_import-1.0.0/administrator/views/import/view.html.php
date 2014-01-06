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

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class Ids_importViewImport extends JView
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
	

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
		}

		$this->article_categories = $this->get('JoomlaArticleCategories');
		$this->api_key = $this->get('APIKey');
		$this->params = $this->get('IDSImportParams');

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		
		//JFactory::getApplication()->input->set('hidemainmenu', true);


		JToolBarHelper::title(JText::_('Import Data from IDS API into you website'), 'expose.png');

	
		JToolBarHelper::cancel('cancel', 'JTOOLBAR_CANCEL');
	}
}
