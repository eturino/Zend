<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Input.php 22472 2010-06-20 07:36:16Z thomas $
 */

/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * @see Zend_Filter
 */
require_once 'Zend/Filter.php';

/**
 * @see Zend_Validate
 */
require_once 'Zend/Validate.php';

/**
 * @see Zend_Validate
 */
require_once 'Zend/Filter/Input.php';

/**
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class EtuDev_Zend_Filter_Input extends Zend_Filter_Input
{

    /**
     * besides the parent behaviour, looks for the field in the _unknownFields array, to get it in case there was any filter but no validator.
     *
     * @param string $fieldName OPTIONAL
     *
     * @return mixed
     */
    public function getUnescaped($fieldName = null)
    {
        $res = parent::getUnescaped($fieldName);
        if ($res !== null || $fieldName === null) {
            return $res;
        }

        if (array_key_exists($fieldName, $this->_unknownFields)) {
            return $this->_unknownFields[$fieldName];
        }

        return null;
    }

}
