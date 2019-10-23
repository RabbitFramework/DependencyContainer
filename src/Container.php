<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24/11/2018
 * Time: 08:46
 */

namespace Rabbit\DependencyContainer;

use Psr\Container\ContainerInterface;
use Rabbit\DependencyContainer\Entities\ClassEntity;

class Container implements ContainerInterface
{

    private $_classEntities = [];

    private $_classAliases = [];

    public static $_instance;

    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function addAlias(string $class, string ...$alias){
        foreach ($alias as $as) {
            $this->_classAliases[$as] = $class;
        }
    }

    public function deleteAlias(string $alias) {
        unset($this->_classAliases[$alias]);
    }

    public function get($className) : ClassEntity {
        if(!$this->has($className)) {
            if(array_key_exists($className, $this->_classAliases)) $className = $this->_classAliases[$className];
            try {
                $this->_classEntities[$className] = new ClassEntity(new \ReflectionClass($className), $this);
            } catch (\ReflectionException $e) {
                throw new ContainerNotFoundException("[Rabbit => DependencyContainer::get()] The class $className doesn't exists");
            }
        }
        return $this->_classEntities[$className];
    }

    public function has($className) {
        return isset($this->_classEntities[$className]);
    }
}