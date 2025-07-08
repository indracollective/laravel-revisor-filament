<?php

declare(strict_types=1);

namespace Indra\RevisorFilament\Filament;

use Filament\Tables\Columns\TextColumn;
use Indra\Revisor\Contracts\HasRevisor;

class StatusColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Status')
            ->badge()
            ->getStateUsing(fn (HasRevisor $record): string => implode(',', $record->getRevisorStatuses()))
            ->separator(',')
            ->color(fn (string $state): string => match ($state) {
                'revised' => 'warning',
                'published' => 'success',
                default => 'gray',
            });
    }
}
