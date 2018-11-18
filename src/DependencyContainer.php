<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15/11/2018
 * Time: 17:54
 */

namespace Xirion\DependencyInjector;

use Xirion\DependencyInjector\Resolver\ConstructorResolver;
use Xirion\DependencyInjector\Resolver\MethodResolver;

/**
 * Class DependencyContainer
 * @package Xirion\DependencyInjector
 */
class DependencyContainer implements DepedencyContainerInterface
{

    /**
     * @var array
     */
    private $_classRules = [];

    /**
     * @var array
     */
    private $_methodRules = [];

    /**
     * @var
     */
    private $_classResolvers = [];

    /**
     * @var array
     */
    private $_classAliases = [];

    /**
     * @var array
     */
    private $_methodResolvers = [];

    /**
     * @var
     */
    private $_closures;

    /**
     * @var
     */
    public static $_instance;

    /**
     * @return DependencyContainer
     */
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * @param string $className
     * @param array $parameters
     * @param array $rule
     * @return mixed
     * @throws \ReflectionException
     */
    public function register(string $className, array $parameters = [], $rule = []) {
        if(!$this->hasClassResolver($className))
        {
            if(array_key_exists($className, $this->_classAliases)) {
                $className = $this->_classAliases[$className];
            }
            $this->_classResolvers[$className] = new ConstructorResolver($className, $this);
            if(!empty($rule)) $this->attachClassRule($className, $rule);
            if(isset($this->_classRules[$className]) && isset($this->_classRules[$className]['singleton']) && $this->_classRules[$className]['singleton'] === true) {
                if(!$this->hasClosure($className)) {
                    $this->_closures[$className] = $this->_classResolvers[$className]->getInstance($parameters);
                }
                return $this->_closures[$className];
            }
            return $this->_classResolvers[$className]->getInstance($parameters);
        }
    }

    /**
     * @param string $className
     * @param array $parameters
     * @param array $rule
     * @return mixed
     * @throws \ReflectionException
     */
    public function getClass(string $className, array $parameters = [], $rule = []) {
        if($this->hasClassResolver($className)) {
            if(array_key_exists($className, $this->_classAliases)) $className = $this->_classAliases[array_search($className, $this->_classAliases)];
            if(!empty($rule)) $this->attachClassRule($className, $rule);
            if(isset($this->_classRules[$className]) && isset($this->_classRules[$className]['singleton']) && $this->_classRules[$className]['singleton'] === true)
                return $this->_closures[$className];
            return $this->_classResolvers[$className]->getInstance($parameters);
        } else {
            return $this->register($className, $parameters, $rule);
        }
    }


    /**
     * @param string $className
     * @param string $alias
     */
    public function setAlias(string $className, string $alias) {
        $this->_classAliases[$alias] = $className;
        return $this;
    }

    public function setAliases(string $className, array $aliases) {
        foreach ($aliases as $alias) {
            $this->_classAliases[$alias] = $className;
        }
        return $this;
    }

    /**
     * @param string $class
     * @param string $methodName
     * @param array $parameters
     * @param array $rule
     * @return mixed
     * @throws \ReflectionException
     */
    public function registerMethod(string $class, string $methodName, array $parameters = [], $rule = []) {
        if(!$this->hasMethodResolver($class, $methodName)) {
            $this->_methodResolvers[$class][$methodName] = new MethodResolver($class, $methodName, $this);
            if(!empty($rule)) $this->attachMethodRule($class, $methodName, $rule);
            return $this->_methodResolvers[$class][$methodName]->invoke($this->getClass($class), $parameters);
        } else {
            return $this->executeMethod($class, $methodName, $parameters, $rule);
        }
    }

    /**
     * @param string $class
     * @param string $methodName
     * @param array $parameters
     * @param array $rule
     * @return MethodResolver
     * @throws \ReflectionException
     */
    public function executeMethod(string $class, string $methodName, array $parameters = [], $rule = []) {
        if($this->hasMethodResolver($class, $methodName)) {
            if(!empty($rule)) $this->attachMethodRule($class, $methodName, $rule);
            return $this->_methodResolvers[$class][$methodName]->invoke($this->getClass($class), $parameters);
        } else {
            return $this->registerMethod($class, $methodName, $parameters, $rule);
        }
    }

    /**
     * @param string $class
     * @param string $methodName
     * @return bool
     */
    public function hasMethodResolver(string $class, string $methodName) {
        return isset($this->_methodResolvers[$class][$methodName]);
    }

    /**
     * @param string $className
     * @return mixed
     */
    public function getClassRule(string $className) {
        if($this->hasClassRule($className)) {
            return $this->_classRules[$className];
        }
    }

    /**
     * @param string $className
     * @return bool
     */
    public function hasClassRule(string $className) {
        return isset($this->_classRules[$className]);
    }

    /**
     * @param $className
     * @param $rule
     */
    public function attachClassRule(string $className, $rule) {
        if(!$this->hasClassRule($className)) {
            $this->_classRules[$className] = $rule;
        }
        $this->_classRules[$className] = array_replace($this->_classRules[$className], $rule);
        return $this;
    }

    /**
     * @param string $className
     * @param string $methodName
     * @return mixed
     */
    public function getMethodRule(string $className, string $methodName) {
        if($this->hasMethodRule($className, $methodName)) {
            return $this->_methodRules[$className];
        }
    }

    /**
     * @param string $className
     * @param string $methodName
     * @return bool
     */
    public function hasMethodRule(string $className, string $methodName) {
        return isset($this->_methodRules[$className][$methodName]);
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $rule
     */
    public function attachMethodRule(string $className, string $methodName, $rule) {
        if(!$this->hasMethodRule($className, $methodName)) {
            $this->_methodRules[$className][$methodName] = $rule;
        }
        $this->_methodRules[$className][$methodName] = array_replace($this->_methodRules[$className][$methodName], $rule);
        return $this;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function hasClassResolver($class) : bool {
        return isset($this->_classResolvers[$class]);
    }

    /**
     * @param $class
     * @return bool
     */
    public function hasClosure($class) {
        return isset($this->_closures[$class]);
    }

}