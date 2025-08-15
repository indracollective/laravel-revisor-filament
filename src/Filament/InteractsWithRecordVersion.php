<?php

declare(strict_types=1);

namespace Indra\RevisorFilament\Filament;

trait InteractsWithRecordVersion
{
    public int|string|null $version;

    public function mountInteractsWithRecordVersion(int|string $record, int|string $version): void
    {
        dd($record, $version);
    }

    // protected function resolveRecord(int|string $key): Model {}
}
