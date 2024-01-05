<?php

use App\Models\User;

use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\ActivityResource;

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
    get(ActivityResource::getUrl('index'))
        ->assertOk();
});

it('renders the view page', function () {
    // Retrieve the ID of the first created record
    // This assumes the existence of a record with ID 1 in Spatie\Activitylog\Models\Activity
    $recordId = 1;

    get(ActivityResource::getUrl('view', ['record' => $recordId]))
        ->assertOk();
});