<?php

namespace App\Filament\Pages;

use Illuminate\Support\Collection;
use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class AppointmentsKanbanBoard extends KanbanBoard
{
    protected string $editModalTitle = 'Edit Record';

    protected string $editModalWidth = '2xl';

    protected string $editModalSaveButtonLabel = 'Save';

    protected string $editModalCancelButtonLabel = 'Cancel';
    
    protected function statuses(): Collection
    {   
        return AppointmentStatus::statuses();
    }

    protected function records(): Collection
    {
         return Appointment::latest('updated_at')->get();
    }

    public function onStatusChanged(int $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
         Appointment::find($recordId)->update(['status' => $status]);
         Appointment::setNewOrder($toOrderedIds);
    }

    public function onSortChanged(int $recordId, string $status, array $orderedIds): void
    {
         Appointment::setNewOrder($orderedIds);
    }

}
