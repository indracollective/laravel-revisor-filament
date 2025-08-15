<?php

declare(strict_types=1);

namespace Indra\RevisorFilament\Filament;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord as FilamentEditRecord;

class EditRecord extends FilamentEditRecord
{
    protected function getHeaderActions(): array
    {
        return [
            ListVersionsAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            ...$this->getRevisorActions(),
            $this->getCancelFormAction(),
        ];
    }

    public function getRevisorActions(): array
    {
        return [
            PublishAction::make(),
            UnpublishAction::make(),
        ];
    }
}
