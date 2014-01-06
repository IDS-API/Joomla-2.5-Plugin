<?php
/**
 * @version     1.0.0
 * @package     com_ids_import
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      @iLabAfrica <ilabafrica@strathmore.edu> - ilabafrica.ac.ke
 */


// no direct access
defined('_JEXEC') or die;
?>
<form name="adminForm">
	<h3>To use the component, you must obtain a unique Token-GUID or key for the API. Please register for your API key <a href="http://api.ids.ac.uk/accounts/register/">here</a>. Once obtained, enter this key into the <em>API Token-GUID or key</em> section of the component parameters.</h3>The IDS KS API component allows access to IDS Knowledge Services content of thematically organised and hand selected academic research on poverty reduction in the developing world that is freely available to access online.<br /><br />It allows the administrator to select content from either <a href="http://www.eldis.org" target="_blank">Eldis</a> or <a href="http://www.bridge.ids.ac.uk" target="_blank">BRIDGE</a>, and import each document as new articles into the site. Once set up, the IDS plugin will automatically bring in new content to your site whenever it is added to the Eldis or BRIDGE websites at intervals specified in the component settings.<br /><br />The plug in allows you to select:<ul><li>Whether you want Eldis or BRIDGE documents</li><li>The number of documents to import</li><li>The year of publication</li><li>The name of the author</li><li>The name of the publisher</li><li>The country/ies that the document focuses on</li><li>Any keywords assigned to the document</li><li>Any number of themes that are associated with the document - "top level" or parent themes are highlighted in grey</li></ul>So, if you were the administrator of a website that highlighted recent research on ICTs and Gender in Kenya, you would be able to select specific criteria to suit your content. You would select the themes <strong>ICT</strong> and <strong>Gender</strong>, as well as the country <strong>Kenya</strong> and could also filter these results by a specific <strong>date range</strong> or <strong>publication date</strong>.<br /><br />Themes (or topics) are added where relevant to all documents in the collection. These are arranged in a hierarchical structure and the "top level" themes are highlighted in grey in the theme selection drop down box.</strong>

	<input type="hidden" name="task" id="task"/>
	<input type="hidden" name="option" value="com_ids_import"/>
	<input type="hidden" name="view" value="imports"/>
</form>

