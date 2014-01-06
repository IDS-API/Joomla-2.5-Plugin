<?php
/**
 * @version     1.0.0
 * @package     com_ids_import
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      @iLabAfrica <ilabafrica@strathmore.edu> - ilabafrica.ac.ke
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Ids_import model.
 */
class Ids_importModelimport extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_IDS_IMPORT';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Import', $prefix = 'Ids_importTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_ids_import.import', 'import', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_ids_import.edit.import.data', array());

		if (empty($data)) {
			$data = $this->getItem();
            
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed

		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM import');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}

	function getJoomlaArticleCategories(){
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT `id`,`title` FROM `#__categories` WHERE `extension`='com_content' ORDER BY `title` ASC");
		$categories = $db->LoadObjectList();
		return $categories;
	}

	//get the saved api key from idsplugin params
	function getAPIKey()
	{
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT `params` FROM `#__extensions` WHERE `element` = 'idsplugin'");
        $params = $db->LoadResult(); 
        $params = json_decode($params);
        $api_key = $params->api_key;

        return $api_key;
	}

	function getIDSImportParams(){
		
		$api_key = $this->getAPIKey();

		$db =& JFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__ids_import_config` WHERE `api_key`='$api_key'");
		$params = $db->LoadObject();
		return $params;
	}

}