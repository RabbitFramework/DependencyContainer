<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/11/2018
 * Time: 10:30
 */

namespace Xirion\DependencyInjector\Entities;

use Xirion\DependencyInjector\DependencyContainerInterface;
use Xirion\DependencyInjector\Entities\Information\ClassInformation;
use Xirion\DependencyInjector\Entities\Information\EntityInformationInterface;
use Xirion\DependencyInjector\Entities\Resolver\ClassResolver;

class ClassEntity implements EntityInterface
{

    public $_lastClass;

    public $information;

    public $container;

    public $rule = [];

    private $_methodEntities = [];

    private $_methodAliases = [];

    public $resolver;

    private $_reflectionClass;

    public function __construct(\ReflectionClass $className, DependencyContainerInterface $container)
    {
        $this->_reflectionClass = $className;

        $this->container = $container;

        $this->information = new ClassInformation($this->_reflectionClass);

        $this->resolver = new ClassResolver($this->_reflectionClass, $this);
    }

    public function setRule(array $rule) {
        $this->rule = array_replace($this->rule, $rule);
        return $this;
    }

    public function getRule() {
        return $this->rule;
    }

    public function addAlias(string $method, string $alias) {
        $this->_methodAliases[$alias] = $method;
    }

    public function deleteAlias(string $alias) {
        unset($this->_methodAliases[$alias]);
    }

    public function getMethod(string $methodName) : MethodEntity {
        if($this->information->hasMethod($methodName)) {
            if (!$this->hasMethod($methodName)) {
                if (array_key_exists($methodName, $this->_methodAliases)) $methodName = $this->_methodAliases[$methodName];
                try {
                    $this->_methodEntities[$methodName] = new MethodEntity(new \ReflectionMethod($this->information->name, $methodName), $this->container, $this);
                } catch (\ReflectionException $e) {

                }
            }
            return $this->_methodEntities[$methodName];
        } else {

        }
    }

    public function getInstance(array $parameters = []) {
        if(isset($this->_classRule['singleton']) && $this->_classRule['singleton'] === true) {
            if(!isset($this->singleInstance)) {
                $this->_lastClass = $this->resolver->getInstance($parameters);
                $this->singleInstance = $this->_lastClass;
            }
            return $this->singleInstance;
        }
        $this->_lastClass = $this->resolver->getInstance($parameters);
        return $this->_lastClass;
    }

    public function getInformation() : EntityInformationInterface {
        return $this->information;
    }

    public function hasMethod(string $methodName) {
        return isset($this->_methodEntities[$methodName]);
    }

}