<?php

declare(strict_types=1);

namespace Indra\RevisorFilament\Filament;

use Exception;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Indra\Revisor\Contracts\HasRevisor;

class ViewVersion extends ViewRecord
{
    public int | string $version;

    protected (Model & HasRevisor) | null $versionRecord = null;

    public function getHeaderActions(): array
    {
        return [
            RevertAction::make('revert'),
        ];
    }

    public function mount(int | string $record, int | string | null $version = null): void
    {
        parent::mount($record);

        if (! $version) {
            throw new Exception('No version provided.');
        }

        $this->version = $version;

        $this->versionRecord = $this->resolveVersion($version);
    }

    protected function resolveVersion(int | string $id): Model & HasRevisor
    {
        /** @var Model&HasRevisor $record */
        $record = $this->getRecord();

        /** @var (Model&HasRevisor)|null $version */
        $version = $record->versionRecords()->find($id);

        if (! $version) {
            abort(404);
        }

        return $version;
    }

    public function getVersionRecord(): Model & HasRevisor
    {
        if (! $this->versionRecord) {
            $this->versionRecord = $this->resolveVersion($this->version);
        }

        return $this->versionRecord;
    }

    protected function fillForm(): void
    {
        $this->fillFormWithDataAndCallHooks($this->getVersionRecord());
    }

    public function getBreadcrumb(): string
    {
        return static::$breadcrumb ?? 'Version #' . $this->getVersionRecord()->version_number;
    }
}
