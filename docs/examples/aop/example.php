<?php
/**
 * Example using ding. See also beans.xml.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Examples
 * @subpackage Aop
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://www.noneyet.ar/ Apache License 2.0
 * @version    SVN: $Id$
 * @link       http://www.noneyet.ar/
 */

////////////////////////////////////////////////////////////////////////////////
// Mandatory stuff to bootstrap ding. (START)
////////////////////////////////////////////////////////////////////////////////
declare(ticks=1);
ini_set(
    'include_path',
    implode(
        PATH_SEPARATOR,
        array(
            ini_get('include_path'),
            implode(DIRECTORY_SEPARATOR, array('..', '..', '..', 'src', 'mg'))
        )
    )
);
require_once 'Ding/Autoloader/Ding_Autoloader.php'; // Include ding autoloader.
Ding_Autoloader::register(); // Call autoloader register for ding autoloader.
use Ding\Container\Impl\ContainerImpl;
use Ding\Aspect\MethodInvocation;
use Ding\Aspect\Interceptor\IMethodInterceptor;
use Ding\Aspect\Interceptor\IExceptionInterceptor;

error_reporting(E_ALL);
ini_set('display_errors', 1);
////////////////////////////////////////////////////////////////////////////////
class ComponentA
{
    public function targetMethod($a, $b, $c)
    {
        echo "Hello world $a $b $c \n";
    }

    public function targetException($a, $b, $c)
    {
        throw new Exception('an exception occured');
    }

    public function __construct()
    {
    }
}


class AspectA implements IMethodInterceptor
{
    public function invoke(MethodInvocation $invocation)
    {
        try
        {
            echo "Before: " . $invocation->getOriginalInvocation() . "\n";
            $invocation->proceed(array('b', 'c', 'd'));
            echo "After\n";
        } catch(Exception $e) {
            echo "Move along, nothing happened here.. \n";
        }
    }

    public function __construct()
    {
    }
}

class AspectB implements IExceptionInterceptor
{
    public function invoke(MethodInvocation $invocation)
    {
        echo "With exception: " . $invocation->getException() . "\n";
        echo "After with exception\n";
        $invocation->proceed();
    }

    public function __construct()
    {
    }
}
////////////////////////////////////////////////////////////////////////////////
try
{
    $properties = array(
        'ding' => array(
            'log4php.properties' => './log4php.properties',
            'factory' => array(
                'bdef' => array(
                	'xml' => array('filename' => 'beans.xml'),
                    'annotation' => array('scanDir' => array(realpath(__DIR__)))
                ),
            ),
    		'cache' => array(
    			'proxy' => array('impl' => 'dummy'),
            	'bdef' => array('impl' => 'dummy'),
              	'beans' => array('impl' => 'dummy')
            )
        )
    );
    $a = ContainerImpl::getInstance($properties);
    $bean = $a->getBean('ComponentA');
    $bean->targetMethod('a', 1, array('1', 'a'));
    $bean->targetException('a', 1, array('1', 'a'));
} catch(Exception $exception) {
    echo $exception . "\n";
}
////////////////////////////////////////////////////////////////////////////////
