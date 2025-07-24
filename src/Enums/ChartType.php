<?php

namespace Mulaidarinull\Larascaff\Enums;

enum ChartType: string
{
    case Bar = 'bar';

    case Line = 'line';

    case Doughnut = 'doughnut';

    case Bubble = 'bubble';

    case Pie = 'pie';

    case Radar = 'radar';

    case Scatter = 'scatter';

    case PolarArea = 'polarArea';
}
