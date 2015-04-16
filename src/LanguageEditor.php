<?php

/**
 * Language editor
 * Copyright (C) 2010,2011 Tristan Lins, 2015 David Molineus
 *
 * @copyright  InfinitySoft 2012, netzmacht creative 2015
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @package    Language Editor
 * @license    LGPL
 * @filesource
 */

namespace Netzmacht\Contao\LanguageEditor;

/**
 * Class LanguageVariableSearch
 *
 * @copyright  InfinitySoft 2012, netzmacht creative 2015
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @package    Language Editor
 */
class LanguageEditor extends \Backend
{
    /**
     * Default language groups.
     *
     * @var array
     */
    public static $defaultGroups = array(
        'CNT'    => 'countries', // countries
        'ERR'    => 'default',   // Error messages
        'PTY'    => 'default',   // Page types
        'FOP'    => 'default',   // File operation permissions
        'CHMOD'  => 'default',   // CHMOD levels
        'DAYS'   => 'default',   // Day names
        'MONTHS' => 'default',   // Month names
        'MSC'    => 'default',   // Miscellaneous
        'UNITS'  => 'default',   // Units
        'XPL'    => 'explain',   // Explanations
        'LNG'    => 'languages', // Languages
        'MOD'    => 'modules',   // Back end modules
        'SEC'    => 'default',   // Security questions
        'CTE'    => 'default',   // Content elements
        'FMD'    => 'default'    // Front end modules
    );

    /**
     * Singleton instance
     */
    protected static $objInstance = null;

    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$objInstance === null) {
            self::$objInstance = new LanguageEditor();
        }

        return self::$objInstance;
    }

    /**
     * singleton constructor
     */
    protected function __construct() {}

    /**
     * Get the language file name for a language group.
     *
     * @param string $group The group name.
     *
     * @return string
     */
    public function getLanguageFileName($group)
    {
        if (isset(static::$defaultGroups[$group])) {
            return static::$defaultGroups[$group];
        }

        return $group;
    }

    /**
     * Get language value.
     *
     * @param array $parent The parent array.
     * @param array $path   The language path as array.
     * @param bool  $raw    Get value as raw value. Otherwise format it nicely.
     *
     * @return array|string
     */
    public function getLangValue(&$parent, $path, $raw = false)
    {
        $next = array_shift($path);

        // language path not found
        if (!isset($parent[$next])) {
            return 'not found!';
        }

        // walk deeper
        if (count($path)) {
            return $this->getLangValue($parent[$next], $path, $raw);
        }

        // return raw value
        if ($raw) {
            return $parent[$next];
        }

        // value is array (like label)
        if (is_array($parent[$next])) {
            return '&ndash; ' . implode('<br>&ndash; ', $this->plainEncode($parent[$next]));
        }

        // value is something else
        return $this->plainEncode($parent[$next]);
    }

    /**
     * Plain encode value.
     *
     * @param mixed $varValue String or array.
     *
     * @return array|string
     */
    public function plainEncode($varValue)
    {
        if (is_array($varValue)) {
            foreach ($varValue as $k=>$v) {
                $varValue[$k] = $this->plainEncode($v);
            }
            return $varValue;
        } else {
            return htmlentities($varValue, ENT_QUOTES | ENT_HTML401, 'UTF-8');
        }
    }
}
