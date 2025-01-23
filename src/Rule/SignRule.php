<?php
namespace NPEU\Module\Signpost\Site\Rule;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use SVG\SVG;
use SVG\Nodes\Shapes\SVGRect;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormRule;
use Joomla\CMS\Form\Rule\OptionsRule;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;


/**
 * The ExecutionRulesRule Class.
 * Validates execution rules, with input for other fields as context.
 *
 * @since  4.1.0
 */
class SignRule extends FormRule
{
    /**
     * @var string  RULE_TYPE_FIELD   The field containing the rule type to test against
     * @since  4.1.0
     */
    private const RULE_TYPE_FIELD = "sign.rule-type";

    /**
     * @var string CUSTOM_RULE_GROUP  The field group containing custom execution rules
     * @since  4.1.0
     */
    private const CUSTOM_RULE_GROUP = "sign.custom";


    public function tmpErrorHandler($errno, $errstr, $errfile, $errline) {
        if (E_RECOVERABLE_ERROR === $errno) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
        }
        return false;
    }

    /**
     * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form
     *                                       field object.
     * @param   mixed              $value    The form field value to validate.
     * @param   ?string            $group    The field name group control value. This acts as an array container for the
     *                                       field. For example if the field has `name="foo"` and the group value is set
     *                                       to "bar" then the full field name would end up being "bar[foo]".
     * @param   ?Registry          $input    An optional Registry object with the entire data set to validate against
     *                                       the entire form.
     * @param   ?Form              $form     The form object for which the field is being tested.
     *
     * @return boolean
     *
     * @since  4.1.0
     */
    public function test(\SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null): bool
    {
        $values = (array) $value;

        foreach ($values as $name => $v) {
            #echo '<pre>'; var_dump($v); echo '</pre>';
            // Validate SVG:
            if (!empty($v->svg)) {
                $svg_is_valid = true;
                set_error_handler([$this, 'tmpErrorHandler']);
                try {
                    $image = @SVG::fromString($v->svg);
                } catch(Exception $e) {
                    Factory::getApplication()->enqueueMessage('One or more of the SVGs is invalid', 'error');
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
