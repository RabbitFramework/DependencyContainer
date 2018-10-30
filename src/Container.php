<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22/10/2018
 * Time: 14:12
 */

namespace Xirion\DependencyInjector;

use Xirion\Bags\ObjectBag;
use Xirion\DependencyInjector\Rule\RuleInterface;
use Xirion\DependencyInjector\Resolver\Resolver;

/**
 * Class Container
 * @package Xirion\DependencyInjector
 */
class Container implements ContainerInterface
{

    /**
     * @var array
     */
    private $_rules;

    /**
     * @var array
     */
    private $_closures;

    /**
     * @var array
     */
    private $_instances;

    /**
     * @var
     */
    private $_classes;

    public static $_instance;

    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->_rules = new ObjectBag();
        $this->_closures = new ObjectBag();
        $this->_instances = new ObjectBag();
    }

    /**
     * @param string $className
     * @param RuleInterface $rule
     * @return mixed|void
     */
    public function attachRule(string $className, RuleInterface $rule) {
        $this->_rules[$className] = $rule;
    }

    /**
     * @param array $classNames
     * @param array $rules
     */
    public function attachRules(array $classNames, array $rules) {
        if(count($classNames) === count($rules)) {
            foreach ($classNames as $id => $name) {
                if($rules[$id] instanceof RuleInterface) {
                    $this->_rules[$name] = $rules[$id];
                }
            }
        }
    }

    /**
     * @param string $className
     * @return mixed|void
     */
    public function detachRule(string $className) {
        if($this->hasRule($className)) {
            unset($this->_rules[$className]);
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getRule(string $name) {
        if($this->hasRule($name)) return $this->_rules[$name];
    }

    /**
     * @param string $class
     * @return mixed|object
     * @throws \ReflectionException
     */
    public function register(string $class) {
        if(!$this->hasClass($class)) {
            $resolver = new Resolver($class, $this);
            $this->_classes[$class] = $resolver->resolveConstructor();
            if($this->isClosure($class)) {
                if(!$this->hasClosure($class)) {
                    $this->_closures[$class] = $resolver->resolveConstructor();
                }
                return $this->_closures[$class];
            }
            return $this->_classes[$class];
        } else {
            return $this->getClass($class);
        }
    }

    /**
     * @param string $className
     * @return mixed|object
     * @throws \ReflectionException
     */
    public function getClass(string $className) {
        if($this->hasClass($className)) {
            $resolver = new Resolver($className, $this);
            if($this->isClosure($className)) {
                return $this->_closures[$className];
            }
            return $resolver->resolveConstructor();
        } else {
            return $this->register($className);
        }
    }

    /**
     * @param string $class
     * @return bool
     */
    public function hasClass($class) : bool {
        return isset($this->_classes[$class]);
    }

    /**
     * @param $class
     * @return bool
     */
    public function isClosure($class) {
        $rule = $this->getRule($class);
        if($rule)
            return $rule->isSingleton();
        return false;
    }

    /**
     * @param $class
     * @return bool
     */
    public function hasClosure($class) {
        return isset($this->_closures[$class]);
    }

    /**
     * @param string $className
     * @return bool
     */
    public function hasRule(string $className) : bool {
        return isset($this->_rules[$className]);
    }

}