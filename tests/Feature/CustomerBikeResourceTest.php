<?php

use App\Models\User;
use App\Models\CustomerBike;

use Illuminate\Support\Facades\Storage;
use App\Filament\Owner\Resources\CustomerBikeResource;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed();
    $this->ownerUser = User::whereName('Owner')->first();
    actingAs($this->ownerUser);

    Storage::fake('avatars');
});

it('renders the index page', function () {
    get(CustomerBikeResource::getUrl('index', panel: 'customer'))
        ->assertOk();
});

it('renders the create page', function () {
    get(CustomerBikeResource::getUrl('create', panel: 'customer'))
        ->assertOk();
});

it('renders the edit page', function () {
    $customerBike = CustomerBike::factory()
        ->for($this->ownerUser, relationship: 'owner')
        ->create();

    get(CustomerBikeResource::getUrl('edit', ['record' => $customerBike], panel: 'customer'))
        ->assertOk();
});
