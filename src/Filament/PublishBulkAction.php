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

class PublishBulkAction extends BulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Publish selected')
            ->icon(FilamentIcon::resolve('heroicon-o-arrow-up-tray') ?? 'heroicon-o-arrow-up-tray')
            ->color('success')
            ->deselectRecordsAfterCompletion()
            ->modalHeading(
                fn (Collection $records, Page $livewire) => $records->count() === 1 ?
                'Publish '.$livewire::getResource()::getRecordTitle($records->first()) :
                'Publish pages'
            )
            ->modalIcon(FilamentIcon::resolve('heroicon-o-arrow-up-tray') ?? 'heroicon-o-arrow-up-tray')
            ->modalIconColor('success')
            ->modalDescription(
                function (Collection $records) {
                    $count = $records->count();

                    return $count === 1 ?
                        'Are you sure you want to publish this '.$this->getModelLabel() :
                        "Are you sure you want to publish $count ".$this->getPluralModelLabel();
                }
            )
            ->modalAlignment(Alignment::Center)
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalSubmitActionLabel(__('filament-actions::modal.actions.confirm.label'))
            ->modalWidth(Width::Medium)
            ->action(function (Collection $records, array $data) {
                // @phpstan-ignore-next-line
                $records->fresh()->each(fn (HasRevisor $record) => $record->publish());
                $this->success();
            })
            ->successNotificationTitle(
                fn (array $data, Collection $records) => isset($data['recursive']) || $records->count() > 1 ?
                    $this->getPluralModelLabel().' published successfully' :
                    $this->getModelLabel().' published successfully'
            );
    }

    public static function getDefaultName(): ?string
    {
        return 'publish';
    }
}
