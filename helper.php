<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_signpost
 *
 * @copyright   Copyright (C) NPEU 2019.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

use Joomla\String\StringHelper;

require_once __DIR__ . '/vendor/autoload.php';

use \Michelf\Markdown;

/**
 * Helper for mod_signpost
 */
class ModSignpostHelper
{
    /**
     * Gets a twig instance - useful as we don't have to re-declare customisations each time.
     *
     * @param  array    $tpls   Array of strings bound to template names
     * @return object
     */
    public static function getTwig($tpls)
    {
        $loader = new Twig_Loader_Array($tpls);
        $twig   = new Twig_Environment($loader);

        // Add markdown filter:
        $md_filter = new Twig_SimpleFilter('md', function ($string) {
            $new_string = '';
            // Parse md here
            $new_string = Markdown::defaultTransform($string);
            return $new_string;
        });

        $twig->addFilter($md_filter);
        // Use like {{ var|md|raw }}
        
        // Add blockless filter:
        // This is in case the the output needs to appear inside an inine element.
        // Block elements shouldn't really appear inside inline elements (except links).
        // Not we're only dealing with <p> here, not other blocks so may need work.
        $blockless_filter = new Twig_SimpleFilter('blockless', function ($string) {
            $new_string = $string;

            // Convert <p> to <br>:
            $new_string = trim(preg_replace('#</p>.*?<p>#m', "\n<br><br>\n", $new_string));
            // Remove wrapping <p>:
            $new_string = trim(preg_replace(array('#^<p>#', '#</p>$#'), '', $new_string));
            
            return $new_string;
        });

        $twig->addFilter($blockless_filter);

        // Add pad filter:
        /*$pad_filter = new Twig_SimpleFilter('pad', function ($string, $length, $pad = ' ', $type = 'right') {
            $new_string = '';
            switch ($type) {
                case 'right':
                    $type = STR_PAD_RIGHT;
                    break;
                case 'left':
                    $type = STR_PAD_LEFT;
                    break;
                case 'both':
                    break;
                    $type = STR_PAD_BOTH;
            }
            $length = (int) $length;
            $pad    = (string) $pad;
            $new_string = str_pad($string, $length, $pad, $type);

            return $new_string;
        });
        $twig->addFilter($pad_filter);*/

        // Add regex_replace filter:
        /*$regex_replace_filter = new Twig_SimpleFilter('regex_replace', function ($string, $search = '', $replace = '') {
            $new_string = '';

            $new_string = preg_replace($search, $replace, $string);

            return $new_string;
        });
        $twig->addFilter($regex_replace_filter);*/

        // Add html_id filter:
        /*$html_id_filter = new Twig_SimpleFilter('html_id', function ($string) {
            $new_string = '';

            $new_string = self::htmlID($string);

            return $new_string;
        });
        $twig->addFilter($html_id_filter);*/


        return $twig;
    }
}
