<?php

namespace Mulaidarinull\Larascaff\Traits;

use Illuminate\Container\Container;
use Illuminate\Support\Reflector;

trait ParameterResolver
{
    protected function resolveParameters($method, $excepts = [])
    {
        $this->container ??= new Container;

        $reflectionMethod = new \ReflectionMethod($this, $method);
        $parameters = [];
        foreach ($reflectionMethod->getParameters() as $param) {
            $className = Reflector::getParameterClassName($param);
            $found = false;
            foreach ($excepts as $except) {
                if ($className == get_class($except)) {
                    $parameters[] = $except;
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                $parameters[] = $this->container->make($className);
            }
        }

        return $parameters;
    }
}
