<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/11/2018
 * Time: 12:00
 */

namespace Xirion\DependencyInjector\Entities\Information;

use \ReflectionProperty;

class ClassInformation implements EntityInformationInterface
{

    public $name;
    private $_reflectionClass;

    public function __construct(\ReflectionClass $reflectionClass) {
        $this->_reflectionClass = $reflectionClass;
        $this->name = $this->_reflectionClass->getName();
        $this->parentClass = $this->_reflectionClass->getParentClass();
        $this->fileName = $this->_reflectionClass->getFileName();
        $this->methods = $this->_reflectionClass->getMethods();
        $this->namespace = ($this->hasNamespace()) ? $this->_reflectionClass->getNamespaceName() : null;
        $this->interfaces = ($this->hasInterfaces()) ? $this->_reflectionClass->getInterfaceNames() : null;
        $this->traits = ($this->hasTraits()) ? $this->_reflectionClass->getTraitNames() : null;
        $this->constructor = ($this->hasConstructor()) ? new MethodInformation($this->_reflectionClass->getConstructor()) : null;
        $this->properties['all'] = $this->_reflectionClass->getProperties();
        $this->properties['public'] = $this->_reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        $this->properties['private'] = $this->_reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE);
        $this->properties['protected'] = $this->_reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED);
        $this->properties['static'] = $this->_reflectionClass->getProperties(ReflectionProperty::IS_STATIC);
    }

    public function hasTraits() {
        return !empty($this->_reflectionClass->getTraitNames());
    }

    public function hasInterfaces() {
        return !empty($this->_reflectionClass->getInterfaceNames());
    }

    public function hasNamespace() {
        return $this->_reflectionClass->inNamespace();
    }

    public function hasProperty(string $propertyName) {
        return $this->_reflectionClass->hasProperty($propertyName);
    }

    public function hasConstant(string $constantName) {
        return $this->_reflectionClass->hasConstant($constantName);
    }

    public function hasMethod($methodName) {
        return $this->_reflectionClass->hasMethod($methodName);
    }

    public function hasConstructor() {
        return !empty($this->_reflectionClass->getConstructor());
    }

    public function isAbstract() {
        return $this->_reflectionClass->isAbstract();
    }

    public function isInstantiable() {
        return $this->_reflectionClass->isInstantiable();
    }

    public function isUsingInterface(string $interfaceName) {
        return $this->_reflectionClass->implementsInterface($interfaceName);
    }

}