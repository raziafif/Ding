<?php
/**
 * This class will test the XML aop driver.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Aop.Xml
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @version    SVN: $Id$
 * @link       http://marcelog.github.com/
 *
 * Copyright 2011 Marcelo Gornstein <marcelog@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

use Ding\Container\Impl\ContainerImpl;
use Ding\Aspect\MethodInvocation;

/**
 * This class will test the XML aop driver.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Aop.Xml
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @link       http://marcelog.github.com/
 */
class Test_XML_AOP extends PHPUnit_Framework_TestCase
{
    private $_properties = array();

    public function setUp()
    {
        $this->_properties = array(
            'ding' => array(
                'log4php.properties' => RESOURCES_DIR . DIRECTORY_SEPARATOR . 'log4php.properties',
                'factory' => array(
                    'bdef' => array(
                        'xml' => array(
                        	'filename' => 'aop-xml-simple.xml', 'directories' => array(RESOURCES_DIR)
                        )
                    )
                )
            )
        );
    }

    /**
     * @test
     */
    public function can_intercept_method_from_bean_aop()
    {
        $container = ContainerImpl::getInstance($this->_properties);
        $bean = $container->getBean('methodIntercepted');
        $this->assertEquals($bean->targetMethod('aSd'), 'BEFOREmethodReturnForaSdAFTER');
    }

    /**
     * @test
     */
    public function can_intercept_multiple_methods_from_bean_aop()
    {
        $container = ContainerImpl::getInstance($this->_properties);
        $bean = $container->getBean('multipleMethodIntercepted');
        $this->assertEquals($bean->getSomething('aSd'), 'BEFOREmethodReturnForaSdAFTER');
        $this->assertEquals($bean->getSomethingElse('aSd'), 'BEFOREmethodReturnForaSdAFTER');
    }
}

class ClassSimpleAOPXML
{
    public function targetMethod($a)
    {
        return 'methodReturnFor' . $a;
    }
}
class ClassSimpleAOPXMLAspect
{
    public function invoke(MethodInvocation $invocation)
    {
        return 'BEFORE' . $invocation->proceed() . 'AFTER';
    }
}

class ClassSimpleAOPXML2
{
    public function getSomething($a)
    {
        return 'methodReturnFor' . $a;
    }
    public function getSomethingElse($a)
    {
        return 'methodReturnFor' . $a;
    }
}
