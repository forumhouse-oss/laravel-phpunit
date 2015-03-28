<?php namespace FHTeam\LaravelPHPUnit\Assertions;

use Exception;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * Trait to test private and protected class members. WARNING: Testing an object's protected or private properties
 * / methods is generally a bad idea. Use with caution
 *
 * @mixin PHPUnit_Framework_TestCase
 */
trait HiddenMembersTestTrait
{
    /**
     * Compares members of a class to a given values disregarding visibility. This allows to test private and protected
     * properties. WARNING: Testing an object's protected or private properties is generally a bad idea. Use with
     * caution
     *
     * @param object $obj      Object, which properties needs testing
     * @param array  $expected Map of property names to expected property values
     *
     * @throws Exception
     */
    protected function assertMembersEqual($obj, array $expected)
    {
        $actual = $this->getObjectProperties($obj, array_keys($expected));
        $this->assertEquals($expected, $actual);
    }

    /**
     * Compares members of a class to a given values disregarding visibility. This allows to test private and protected
     * properties. WARNING: Testing an object's protected or private properties is generally a bad idea. Use with
     * caution
     *
     * @param object $obj
     * @param array  $expected
     */
    protected function assertMembersSame($obj, array $expected)
    {
        $actual = $this->getObjectProperties($obj, array_keys($expected));
        $this->assertSame($expected, $actual);
    }

    /**
     * Returns object property values disregarding visibility. WARNING: Testing an object's protected or private
     * methods is generally a bad idea. Use with caution
     *
     * @param object $obj     Object being inspected
     * @param array  $members Array of property names to return
     *
     * @return array A map of property names to property values
     * @throws Exception
     */
    protected function getObjectProperties($obj, array $members)
    {
        $stateMemberValues = [];
        $reflector = new ReflectionClass($obj);
        $absent = [];
        foreach ($members as $key) {
            if (!$reflector->hasProperty($key)) {
                $absent[] = $key;
                continue;
            }
            $property = $reflector->getProperty($key);
            $accessible = $property->isPublic();

            if (!$accessible) {
                $property->setAccessible(true);
            }

            $stateMemberValues[$key] = $property->getValue($obj);

            if (!$accessible) {
                $property->setAccessible(false);
            }
        }
        if (!empty($absent)) {
            throw new Exception("Given object does not have the following properties: ".json_encode($absent));
        }

        return $stateMemberValues;
    }

    /**
     * Returns the result of the method call disregarding visibility
     *
     * @param object $obj        Object which method should be called
     * @param string $methodName The name of the method to call
     * @param array  $params     Parameters to pass to method
     *
     * @return mixed The result of method call
     * @throws Exception
     */
    protected function getMethodCallResult($obj, $methodName, array $params)
    {
        $reflector = new ReflectionClass($obj);
        if (!$reflector->hasMethod($methodName)) {
            throw new Exception("Given object does not have method with name '$methodName'");
        }

        $method = $reflector->getMethod($methodName);

        if ($method->isAbstract()) {
            throw new Exception("Cannot call abstract method '$methodName'");
        }

        $accessible = $method->isPublic();

        if (!$accessible) {
            $method->setAccessible(true);
        }

        $method->setAccessible(true);

        $result = $method->invokeArgs($obj, $params);

        if (!$accessible) {
            $method->setAccessible(false);
        }

        return $result;
    }
}
