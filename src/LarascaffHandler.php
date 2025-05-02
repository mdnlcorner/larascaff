<?php

namespace Mulaidarinull\Larascaff;

class LarascaffHandler
{
    public function content(array $data = [], array $mergeData = [])
    {
        return view('larascaff::main-content', $data, $mergeData);
    }
}
