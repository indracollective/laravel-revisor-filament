<?php

declare(strict_types=1);

namespace Indra\RevisorFilament\Filament;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

class ListVersions extends ListRecords
{
    use InteractsWithRecord;

    public function mount(int|string|null $record = null): void
    {
        if ($record === null) {
            throw new InvalidArgumentException('Record is required');
        }

        $this->record = $this->resolveRecord($record);

        parent::mount();
    }

    // protected function authorizeAccess(): void
    // {
    //     abort_unless(static::getResource()::canView($this->getRecord()), 403);
    // }

    public function getHeading(): string
    {
        return static::$resource::getRecordTitle($this->record).' History';
    }

    public function table(Table $table): Table
    {
        $parent = $this->getRecord()->getKey();

        return $table
            ->columns([
                TextColumn::make('record_id'),
                TextColumn::make('version_number')
                    ->label('Version #'),
                IconColumn::make('is_current')
                    ->label('Current')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewVersionAction::make('view'),
                    RevertTableAction::make(),
                    DeleteAction::make(),
                ]),
            ])->modifyQueryUsing(function (Builder $query) use ($parent): Builder {
                // @phpstan-ignore-next-line
                return $query->withVersionContext()
                    ->where('record_id', $parent);
            })->recordAction('view');
    }

    public function getBreadcrumb(): ?string
    {
        return static::$breadcrumb ?? 'History';
    }
}
