<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24/11/2018
 * Time: 08:46
 */

namespace Rabbit\DependencyInjector;

use Rabbit\DependencyInjector\Entities\ClassEntity;

class DependencyContainer implements DependencyContainerInterface
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

    public function addAlias(string $class, string $alias) {
        $this->_classAliases[$alias] = $class;
    }

    public function deleteAlias(string $alias) {
        unset($this->_classAliases[$alias]);
    }

    public function getClass(string $className) : ClassEntity {
        if(!$this->hasClass($className)) {
            if(array_key_exists($className, $this->_classAliases)) $className = $this->_classAliases[$className];
            try {
                $this->_classEntities[$className] = new ClassEntity(new \ReflectionClass($className), $this);
            } catch (\ReflectionException $e) {
                throw new DependencyContainerException("DependencyContainer => getClass; Error from ReflectionClass, class $className doesn't existss");
            }
        }
        return $this->_classEntities[$className];
    }

    public function hasClass(string $className) {
        return isset($this->_classEntities[$className]);
    }

}