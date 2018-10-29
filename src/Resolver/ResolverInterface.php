<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24/10/2018
 * Time: 12:28
 */

namespace Xirion\DependencyInjector\Resolver;

use Xirion\DependencyInjector\ContainerInterface;

/**
 * Interface ResolverInterface
 * @package Xirion\DependencyInjector\Resolver
 */
interface ResolverInterface
{
    /**
     * ResolverInterface constructor.
     * @param string $class
     * @param ContainerInterface $container
     */
    public function __construct(string $class, ContainerInterface $container);

    /**
     * @return mixed
     */
    public function resolveConstructor();

    /**
     * @param string $methodName
     * @return mixed
     */
    public function resolveMethod(string $methodName);

    /**
     * @param string $methodName
     * @return bool
     */
    public function hasMethod(string $methodName) : bool;
}