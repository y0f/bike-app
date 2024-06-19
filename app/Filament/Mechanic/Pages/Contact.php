<?php

namespace App\Filament\Mechanic\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\ServicePoint;
use Filament\Facades\Filament;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class Contact extends Page implements HasForms, HasTable, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithInfolists;

    protected $tenant;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.mechanic.pages.contact';


    public function mount()
    {
        $this->tenant = Filament::getTenant();
    }

    public function getHeading(): string
    {
        return __('Contactgegevens van ' . ($this->tenant ? $this->tenant->name : ''));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ServicePoint::query()->where('id', $this->tenant->id))
            ->columns([
                    TextColumn::make('name')
                        ->label(__('filament.name'))
                        ->icon('icon-service-point')
                        ->iconColor('primary')
                        ->weight(FontWeight::Bold),
                    TextColumn::make('address')
                        ->label('Adres')
                        ->icon('heroicon-o-map-pin')
                        ->iconColor('primary')
                        // Multiple record values in table so they can share an icon
                        ->formatStateUsing(function (ServicePoint $record) {
                            $address = optional($record)->address;
                            $zip     = optional($record)->zip;

                            return "{$address}, {$zip}";
                        })
                        ->weight(FontWeight::Bold),
                    TextColumn::make('phone')
                        ->label(__('filament.phone'))
                        ->icon('heroicon-o-phone')
                        ->iconColor('primary')
                        ->weight(FontWeight::Bold),

            ])->paginated(false);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
