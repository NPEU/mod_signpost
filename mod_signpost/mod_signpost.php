<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_signpost
 *
 * @copyright   Copyright (C) NPEU 2019.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

// Include the signpost functions only once
JLoader::register('ModSignpostHelper', __DIR__ . '/helper.php');

#$thing = trim($params->get('thing'));

#$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_signpost', $params->get('layout', 'default'));