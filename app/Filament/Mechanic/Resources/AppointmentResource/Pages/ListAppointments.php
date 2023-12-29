<?php

namespace App\Filament\Mechanic\Resources\AppointmentResource\Pages;

use Filament\Actions;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Mechanic\Resources\AppointmentResource;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'Alle afspraken' => Tab::make()->badge(Appointment::query()->count()),
        ];

        foreach (AppointmentStatus::cases() as $status) {
            $tabs[$status->getLabel()] = Tab::make()
                ->label($status->getLabel())
                ->badge(Appointment::query()->where('status', $status->value)->count())
                ->badgeColor($status->getColor())
                ->modifyQueryUsing(function (Builder $query) use ($status) {
                    $query->where('status', $status->value);
                });
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'Alle afspraken';
    }
}
