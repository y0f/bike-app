<?php

namespace App\Filament\Mechanic\Pages;

use Filament\Pages\Page;
use Filament\Facades\Filament;

class Faq extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.mechanic.pages.faq';

    protected $tenant;

    public $faqTitles = [
        'Hoe plan ik een fietsreparatie in?',
        'Hoe beheer ik mijn afspraken als monteur?',
        'Hoe schakel ik tussen servicepunten als monteur?',
    ];

    public $faqContent = [
        'Om een fietsreparatie in te plannen, navigeer naar het monteurspaneel en selecteer de gewenste datum en tijd voor de afspraak. Zorg ervoor dat alle benodigde informatie is ingevuld voordat je de afspraak bevestigt.',
        'Als monteur kun je je afspraken beheren door naar het monteurspaneel te gaan. Hier kun je je schema bekijken, afspraken toevoegen of wijzigen en specifieke informatie per servicepunt bekijken.',
        'Als je als monteur bij meerdere servicepunten werkt, klik dan eenvoudig op de naam van het servicepunt en selecteer het gewenste servicepunt om afspraken en schema\'s te beheren.',
    ];

    public function mount()
    {
        $this->tenant = Filament::getTenant();
    }


    public function getHeading(): string
    {
        return __('');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}

