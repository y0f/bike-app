<?php

namespace App\Console\Commands;

use App\Models\CustomerBike;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteUnlinkedVehicleImages extends Command
{
    protected $signature = 'images:delete';
    protected $description = 'Delete images that don\'t belong to any customer bike';

    public function handle()
    {
        $vehicleImages = CustomerBike::whereNotNull('image')->pluck('image')->toArray();

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
