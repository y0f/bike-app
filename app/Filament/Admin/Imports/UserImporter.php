<?php

namespace App\Filament\Admin\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->label(__('admin-import.name')),
            ImportColumn::make('phone')
                ->rules(['max:255'])
                ->label(__('admin-import.phone')),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255'])
                ->label(__('admin-import.email')),
            ImportColumn::make('password')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->label(__('admin-import.password')),
        ];
    }

    public function resolveRecord(): ?User
    {
        $user = new User();

        // Set the default role_id to customer
        $user->role_id = 3;

        $user->name = $this->data['name'];
        $user->phone = $this->data['phone'];
        $user->email = $this->data['email'];
        $user->email_verified_at = null;
        $user->password = bcrypt($this->data['password']);

        return $user;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $importedRows = $import->successful_rows;
        $failedRowsCount = $import->getFailedRowsCount();

        $body = __('admin-import.import_completed', [
            'is_are' => $importedRows === 1 ? 'is' : 'are',
            'count' => number_format($importedRows),
        ]);

        if ($failedRowsCount > 0) {
            $body .= ' ' . __('admin-import.' . ($failedRowsCount === 1 ? 'failed_row_singular' : 'failed_row_plural'), ['count' => $failedRowsCount]);
        }

        return $body;
    }
}
