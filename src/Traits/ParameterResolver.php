<?php

namespace Mulaidarinull\Larascaff\Traits;

use Illuminate\Support\Reflector;

trait ParameterResolver
{
    protected function resolveParameters($method, $excepts = [])
    {
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
                $parameters[] = app($className);
            }
        }

        return $parameters;
    }
}
