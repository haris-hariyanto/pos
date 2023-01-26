<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Location\Place;
use App\Models\Location\Country;
use App\Models\Location\CategoryPlace;

class ChangeCountry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:change-country';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $countryName = $this->ask('Nama negara');
        $places = Place::where('address', 'like', '%' . $countryName)->get();

        if (count($places) > 0) {
            $this->line('[ * ] Ditemukan ' . count($places) . ' tempat');

            $changeToCountry = $this->ask('Negara baru');
            $changeToContinent = $this->ask('Benua baru');

            $country = Country::where('name', $changeToCountry)->where('continent', $changeToContinent)->first();

            if ($country) {
                foreach ($places as $place) {
                    $this->line('[ * ] Mengubah negara : ' . $place->name);
                    $place->update([
                        'country' => $country->name,
                        'continent' => $country->continent,
                    ]);
                    CategoryPlace::where('place_id', $place->id)->update([
                        'country' => $country->name,
                        'continent' => $country->continent,
                    ]);
                }
            }
            else {
                $this->info('[ * ] Negara tidak ditemukan');    
            }
        }
        else {
            $this->info('[ * ] Tidak ada tempat yang ditemukan');
        }
    }
}
