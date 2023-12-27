<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Vehicle;

class DeleteUnlinkedVehicleImages extends Command
{
    protected $signature = 'images:delete';
    protected $description = 'Delete images that don\'t belong to any vehicle';

    public function handle()
    {
        $vehicleImages = Vehicle::whereNotNull('image')->pluck('image')->toArray();

        $vehicleImagesFlipped = array_flip($vehicleImages);

        $folderPath = 'asset-images';

        $files = Storage::disk('public')->files($folderPath);

        // 100 is good for now
        $chunkSize = 100;

        // Process files in chunks
        foreach (array_chunk($files, $chunkSize) as $chunk) {
            foreach ($chunk as $file) {
                if (!isset($vehicleImagesFlipped[$file])) {
                    Storage::disk('public')->delete($file);
                    $this->info("Deleted: $file");
                }
            }
        }

        $this->info('Unlinked images deletion completed.');
    }
}
