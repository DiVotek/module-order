<?php

namespace Modules\Order\Admin\OrderHistoryResource\Pages;

use Modules\Order\Admin\OrderHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOrderHistories extends ManageRecords
{
    protected static string $resource = OrderHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
