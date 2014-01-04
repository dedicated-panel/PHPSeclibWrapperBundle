<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Connection\OsSpecific;

use Dedipanel\PHPSeclibWrapperBundle\Connection\OsSpecific\Exception\MethodNotImplementedException;
use Dedipanel\PHPSeclibWrapperBundle\Connection\OsSpecific\Exception\UnavailableMethodException;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
interface OsSpecificConnectionInterface
{
    /**
     * Gets the user home
     *
     * @api
     *
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     *
     * @return string
     */
    public function getHome();

    /**
     * Determine whether the os is a 64 bit system
     *
     * @api
     *
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     *
     * @return boolean
     */
    public function is64BitSystem();

    /**
     * Determine whether the $program is installed
     *
     * @api
     *
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     *
     * @return boolean
     */
    public function isInstalled($program);

    /**
     * Determine whether java is installed
     *
     * @api
     *
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     *
     * @return boolean
     */
    public function isJavaInstalled();

    /**
     * Determine whether if the 32/64 bits compatability library is installed (ia32-libs)
     *
     * @api
     *
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     *
     * @return boolean
     */
    public function hasCompatLib();

    /**
     * Gets the screen $screenName content
     *
     * @api
     *
     * @param $screenName string The screen name
     *
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     *
     * @return string
     */
    public function getScreenContent($screenName);
}
