<?php
/**
 * Translation.php
 *
 * @author  Michael Pratt <pratt@hablarmierda.net>
 * @link    http://www.michael-pratt.com/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace RelativeTime;

use \RelativeTime\Languages\LanguageAdapter;
use \RelativeTime\Languages\English;

/**
 * This class is responsible for translating
 * an array with time units into a string of
 * a given language
 */
class Translation
{
    /** @var array Array With configuration options **/
    protected $config = array();

    /**
     * Construct
     *
     * @param array $config Associative array with configuration directives
     *
     */
    public function __construct(array $config = array())
    {
        $this->config = array_merge(array(
            'language' => new English(),
            'separator' => ', ',
            'suffix' => true,
        ), $config);
    }

    /**
     * Actually translates the units into words
     *
     * @param array $units
     * @param int $direction
     * @return string
     */
    public function translate(array $units = array(), $direction = 0)
    {
        $lang = $this->loadLanguage();
        if (empty($units)) {
            return $lang->now();
        }

        $translation = array();
        foreach ($units as $unit => $v) {
            if ($v == 1) {
                $translation[] = sprintf($lang->singular($unit), $v);
            } else {
                $translation[] = sprintf($lang->plural($unit), $v);
            }
        }

        $string = implode($this->config['separator'], $translation);
        if (!$this->config['suffix']) {
            return $string;
        }

        if ($direction > 0) {
            return sprintf($lang->ago(), $string);
        } else {
            return sprintf($lang->left(), $string);
        }
    }

    /**
     * Loads the language definitions
     *
     * @return LanguageAdapter
     */
    protected function LoadLanguage()
    {
        if (is_object($this->config['language'])) {
            $l = $this->config['language'];
        } else {
            $languages = array(
                $this->config['language'],
                '\RelativeTime\Languages\\' . $this->config['language'],
            );

            $l = null;
            foreach ($languages as $lang) {
                if (class_exists($lang)) {
                    $l = new $lang();
                    break;
                }
            }
        }

        if ($l instanceof LanguageAdapter) {
            return $l;
        }

        return new English();
    }
}
