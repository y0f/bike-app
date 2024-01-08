<?php

use App\Models\User;

use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\LoanBikeResource;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed();
    $this->adminUser = User::whereName('Admin')->first();
    actingAs($this->adminUser);

    Storage::fake('avatars');
});

it('renders the index page', function () {
    get(LoanBikeResource::getUrl('index'))
        ->assertOk();
});

it('renders the create page', function () {
    get(LoanBikeResource::getUrl('create'))
        ->assertOk();
});
