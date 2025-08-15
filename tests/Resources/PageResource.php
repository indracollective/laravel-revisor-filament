<?php

declare(strict_types=1);

namespace Indra\RevisorFilament\Tests\Resources;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Indra\RevisorFilament\Filament\ListVersionsAction;
use Indra\RevisorFilament\Filament\PublishBulkAction;
use Indra\RevisorFilament\Filament\PublishInfoColumn;
use Indra\RevisorFilament\Filament\StatusColumn;
use Indra\RevisorFilament\Filament\UnpublishBulkAction;
use Indra\RevisorFilament\Tests\Models\Page;
use Indra\RevisorFilament\Tests\Resources\PageResource\Pages\EditPage;
use Indra\RevisorFilament\Tests\Resources\PageResource\Pages\ListPages;
use Indra\RevisorFilament\Tests\Resources\PageResource\Pages\ListPageVersions;
use Indra\RevisorFilament\Tests\Resources\PageResource\Pages\ViewPageVersion;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Page';

    protected static string | \UnitEnum | null $navigationGroup = 'Revisor';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        $schema->components([
            TextInput::make('title')->required(),
        ]);

        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                StatusColumn::make('status'),
                PublishInfoColumn::make('publish_info'),
            ])
            ->configure()
            ->filters([
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    ActionGroup::make([
                        PublishTableAction::make(),
                        UnpublishTableAction::make(),
                        ListVersionsTableAction::make(),
                    ])->dropdown(false),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    PublishBulkAction::class::make(),
                    UnpublishBulkAction::class::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'edit' => EditPage::route('/{record}/edit'),
            'versions' => ListPageVersions::route('/{record?}/versions'),
            'view_version' => ViewPageVersion::route('/{record}/versions/{version}'),
        ];
    }
}
