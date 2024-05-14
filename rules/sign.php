<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_signpost
 *
 * @copyright   Copyright (C) NPEU 2019.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/vendor/autoload.php';

use SVG\SVG;
use SVG\Nodes\Shapes\SVGRect;

class JFormRuleSign extends JFormRule
{

    public function tmpErrorHandler($errno, $errstr, $errfile, $errline) {
        if (E_RECOVERABLE_ERROR === $errno) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
        }
        return false;
    }

    public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null) {
        $values = (array) $value;

        foreach ($values as $name => $v) {
            #echo '<pre>'; var_dump($v); echo '</pre>';
            // Validate SVG:
            if (!empty($v->svg)) {
                $svg_is_valid = true;
                set_error_handler(array($this, 'tmpErrorHandler'));
                try {
                    $image = @SVG::fromString($v->svg);
                } catch(Exception $e) {
                    JFactory::getApplication()->enqueueMessage('One or more of the SVGs is invalid', 'error');
                    $svg_is_valid = false;
                }
                restore_error_handler();

                if (!$svg_is_valid) {
                    return false;
                }
            }
        }

        return true;
    }
}
