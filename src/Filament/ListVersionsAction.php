<?php

declare(strict_types=1);

namespace Indra\RevisorFilament\Filament;

use Exception;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class ListVersionsAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('History')
            ->icon('heroicon-o-clock')
            ->url(function (Model $record, Page $livewire) {
                $resource = $livewire->getResource();
                if (! $resource::hasPage('versions')) {
                    throw new Exception("$resource does not have a versions page defined on the Resource");
                }

                return $resource::getUrl('versions', ['record' => $record->{$record->getRouteKeyName()}]);
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'versions';
    }
}
