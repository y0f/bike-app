<?php

use App\Models\User;

use Illuminate\Support\Facades\Storage;
use App\Filament\Owner\Resources\CustomerBikeResource;

use function Pest\Laravel\get;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed();
    $this->ownerUser = User::whereName('Owner')->first();
    actingAs($this->ownerUser);

    Storage::fake('avatars');
});

it('renders the index page', function () {
    get(CustomerBikeResource::getUrl('index', panel: 'vehicleowner'))
        ->assertOk();
});

it('renders the create page', function () {
    get(CustomerBikeResource::getUrl('create', panel: 'vehicleowner'))
        ->assertOk();
});
