<?php

namespace Mulaidarinull\Larascaff\Forms\Concerns;

trait ResolveClosureParam {
    public function resolveClosureParams(&$cb)
    {
        if (!$cb instanceof \Closure) {
            return $cb;
        }

        $parameters = [];

        foreach ((new \ReflectionFunction($cb))->getParameters() as $parameter) {
            $default = match ($parameter->getName()) {
                'record' => [$parameter->getName() => getRecord()],
                default => []
            };

            $type = match ($parameter->getType()?->getName()) {
                get_class(getRecord()) => [$parameter->getName() => getRecord()],
                default => []
            };

            $parameters = [...$parameters, ...$default, ...$type];
        }

        $cb = app()->call($cb, $parameters);
        
        return $cb;
    }
}