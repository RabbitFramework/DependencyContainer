<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 23/11/2018
 * Time: 10:32
 */

namespace Rabbit\DependencyInjector\Entities\Information;

class MethodInformation implements EntityInformationInterface
{

    private $_reflectionMethod;

    public function __construct(\ReflectionMethod $method) {
        $this->_reflectionMethod = $method;
        $this->name = $this->_reflectionMethod->getName();
        $this->parentClass = $this->_reflectionMethod->class;
        $this->namespace = ($this->hasNamespace()) ? $this->_reflectionMethod->getNamespaceName() : '';
        $this->parameters = ($this->hasParameters()) ? $this->_reflectionMethod->getParameters() : [];
        $this->staticParameters = ($this->hasParameters()) ? $this->_reflectionMethod->getStaticVariables() : [];
        $this->fileName = $this->_reflectionMethod->getFileName();
    }

    public function hasReturnType() {
        return $this->_reflectionMethod->hasReturnType();
    }

    public function hasStaticParameters() {
        return !empty($this->_reflectionMethod->getStaticVariables());
    }

    public function hasParameters() {
        return !empty($this->_reflectionMethod->getParameters());
    }

    public function hasNamespace() {
        return $this->_reflectionMethod->inNamespace();
    }

    public function isConstructor() {
        return $this->_reflectionMethod->isConstructor();
    }

    public function isDestructor() {
        return $this->_reflectionMethod->isDestructor();
    }

    public function isAbstract() {
        return $this->_reflectionMethod->isAbstract();
    }

    public function isFinal() {
        return $this->_reflectionMethod->isFinal();
    }

    public function isPrivate() {
        return $this->_reflectionMethod->isPrivate();
    }

    public function isProtected() {
        return $this->_reflectionMethod->isProtected();
    }

    public function isPublic() {
        return $this->_reflectionMethod->isPublic();
    }

    public function isStatic() {
        return $this->_reflectionMethod->isStatic();
    }

    public function isDrepecated() {
        return $this->_reflectionMethod->isDeprecated();
    }

}