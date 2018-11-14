<?php

namespace Xirion\DependencyInjector\Utilities;

use Xirion\DependencyInjector\Container;
use Xirion\DependencyInjector\Maker;

$GLOBALS['dependencyContainer'] = Container::getInstance();
$GLOBALS['dependencyMaker'] = Maker::getInstance();