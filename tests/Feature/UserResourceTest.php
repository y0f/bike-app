<?php

use App\Models\User;

use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\UserResource;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed();
    $this->adminUser = User::whereName('Admin')->first();
    actingAs($this->adminUser);

    Storage::fake('avatars');
});

it('renders the index page', function () {
    get(UserResource::getUrl('index'))
        ->assertOk();
});

it('renders the create page', function () {
    get(UserResource::getUrl('create'))
        ->assertOk();
});