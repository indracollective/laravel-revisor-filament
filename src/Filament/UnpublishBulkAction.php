<?php

declare(strict_types=1);

namespace Indra\RevisorFilament\Filament;

use Filament\Actions\BulkAction;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Collection;
use Indra\Revisor\Contracts\HasRevisor;

class UnpublishBulkAction extends BulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Unpublish selected')
            ->icon(FilamentIcon::resolve('heroicon-o-arrow-down-tray') ?? 'heroicon-o-arrow-down-tray')
            ->color('warning')
            ->deselectRecordsAfterCompletion()
            ->modalHeading(
                fn (Collection $records, Page $livewire) => $records->count() === 1 ?
                    'Unpublish '.$livewire::getResource()::getRecordTitle($records->first()) :
                    'Unpublish '.$this->getPluralModelLabel()
            )
            ->modalIcon(FilamentIcon::resolve('heroicon-o-arrow-down-tray') ?? 'heroicon-o-arrow-down-tray')
            ->modalIconColor('warning')
            ->modalDescription(
                function (Collection $records) {
                    $count = $records->count();

                    return $count === 1 ?
                        'Are you sure you want to unpublish this '.$this->getModelLabel() :
                        "Are you sure you want to unpublish $count ".$this->getPluralModelLabel();
                }
            )
            ->modalAlignment(Alignment::Center)
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalSubmitActionLabel(__('filament-actions::modal.actions.confirm.label'))
            ->modalWidth(Width::Medium)
            ->action(function (Collection $records, array $data) {
                // @phpstan-ignore-next-line
                $records->fresh()->each(fn (HasRevisor $record) => $record->unpublish());
                $this->success();
            })
            ->successNotificationTitle(
                fn (array $data, Collection $records) => $records->count() > 1 ?
                    $this->getPluralModelLabel().' unpublished successfully' :
                    $this->getModelLabel().' unpublished successfully'
            );
    }

    public static function getDefaultName(): ?string
    {
        return 'unpublish';
    }
}
