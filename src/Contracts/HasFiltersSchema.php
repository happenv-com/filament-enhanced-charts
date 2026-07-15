<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Contracts;

use Filament\Schemas\Schema;

interface HasFiltersSchema
{
    public function getFiltersSchema(): Schema;
}
