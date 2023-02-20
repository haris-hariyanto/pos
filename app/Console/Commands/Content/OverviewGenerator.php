<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Hotel\Hotel;
use App\Helpers\Text;

class OverviewGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:overviews';

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
        $OpenAIKey = config('services.openai.key');

        if (!$OpenAIKey) {
            $this->error('API Key OpenAI harus diisi!');
            return 0;
        }

        $hotelsTotal = Hotel::count();
        $hotelsWithOverview = Hotel::where('overview', '<>', '')
            ->whereNotNull('overview')
            ->count();
        $hotelsWithoutOverview = Hotel::where('overview', '')
            ->orWhereNull('overview')
            ->count();

        $this->line('Jumlah hotel : ' . $hotelsTotal);
        $this->line('Hotel dengan overview : ' . $hotelsWithOverview);
        $this->line('Hotel tanpa overview : ' . $hotelsWithoutOverview);

        $limit = $this->ask('Limit Hotel', 1000);

        $client = \OpenAI::client($OpenAIKey);

        for ($i = 1; $i <= $limit; $i++) {
            $hotel = Hotel::where('overview', '')
                ->orWhereNull('overview')
                ->orderBy('number_of_reviews', 'DESC')
                ->first();
            
            if (!$hotel) {
                $this->info('Semua hotel sudah memiliki overview');
                return 0;
            }

            $this->info('[ ' . $i . '/' . $limit .  ' ] Hotel : ' . $hotel->name);

            $result = $client->completions()->create([
                'model' => 'text-davinci-003',
                'temperature' => config('services.openai.temperature'),
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
                'max_tokens' => config('services.openai.max_tokens'),
                'prompt' => $this->generatePrompt($hotel),
            ]);
            $result = trim($result['choices'][0]['text']);

            $hotel->update([
                'overview' => $result,
            ]);

            // $this->info($result);
            $this->line('--------------------');
        }
    }

    private function generatePrompt($hotel) {
        $prompt = '';
        if (config('app.locale') == 'id') {
            $prompt .= 'Buat deskripsi hotel' . "\n";
            $prompt .= 'Nama hotel : ' . $hotel->name . "\n";

            if (!empty($hotel->formerly_name)) {
                $prompt .= 'Nama sebelumnya : ' . $hotel->formerly_name . "\n";
            }

            if (!empty($hotel->chain)) {
                $prompt .= 'Jaringan : ' . $hotel->chain . "\n";
            }

            if (!empty($hotel->brand)) {
                $prompt .= 'Brand : ' . $hotel->brand . "\n";
            }

            if (!empty($hotel->city)) {
                $prompt .= 'Kota : ' . $hotel->city . "\n";
            }

            if (!empty($hotel->state)) {
                $prompt .= 'State : ' . $hotel->state . "\n";;
            }

            if (!empty($hotel->country)) {
                $prompt .= 'Negara : ' . $hotel->country . "\n";
            }

            if (!empty($hotel->star_rating)) {
                $prompt .= 'Bintang : ' . $hotel->star_rating . "\n";
            }

            if (!empty($hotel->number_of_rooms)) {
                $prompt .= 'Jumlah kamar : ' . $hotel->number_of_rooms . "\n";
            }

            if (!empty($hotel->number_of_floors)) {
                $prompt .= 'Jumlah lantai : ' . $hotel->number_of_floors . "\n";
            }

            if (!empty($hotel->year_opened)) {
                $prompt .= 'Tahun dibuka : ' . $hotel->year_opened . "\n";
            }

            if (!empty($hotel->year_renovated)) {
                $prompt .= 'Tahun direnovasi : ' . $hotel->year_renovated . "\n";
            }

            if (!empty($hotel->price) && !empty($hotel->rates_currency)) {
                $prompt .= 'Harga mulai : ' . Text::price($hotel->price, $hotel->rates_currency) . "\n";
            }
        }
        if (config('app.locale') == 'en') {
            $prompt .= 'Write hotel description' . "\n";
            $prompt .= 'Hotel name : ' . $hotel->name . "\n";

            if (!empty($hotel->formerly_name)) {
                $prompt .= 'Formerly name : ' . $hotel->formerly_name . "\n";
            }

            if (!empty($hotel->chain)) {
                $prompt .= 'Chain : ' . $hotel->chain . "\n";
            }

            if (!empty($hotel->brand)) {
                $prompt .= 'Brand : ' . $hotel->brand . "\n";
            }

            if (!empty($hotel->city)) {
                $prompt .= 'City : ' . $hotel->city . "\n";
            }

            if (!empty($hotel->state)) {
                $prompt .= 'State : ' . $hotel->state . "\n";;
            }

            if (!empty($hotel->country)) {
                $prompt .= 'Country : ' . $hotel->country . "\n";
            }

            if (!empty($hotel->star_rating)) {
                $prompt .= 'Star rating : ' . $hotel->star_rating . "\n";
            }

            if (!empty($hotel->number_of_rooms)) {
                $prompt .= 'Number of rooms : ' . $hotel->number_of_rooms . "\n";
            }

            if (!empty($hotel->number_of_floors)) {
                $prompt .= 'Number of floors : ' . $hotel->number_of_floors . "\n";
            }

            if (!empty($hotel->year_opened)) {
                $prompt .= 'Year opened : ' . $hotel->year_opened . "\n";
            }

            if (!empty($hotel->year_renovated)) {
                $prompt .= 'Year renovated : ' . $hotel->year_renovated . "\n";
            }

            if (!empty($hotel->price) && !empty($hotel->rates_currency)) {
                $prompt .= 'Rates from : ' . Text::price($hotel->price, $hotel->rates_currency) . "\n";
            }
        }

        if (!empty($hotel->check_in)) {
            $prompt .= 'Check in : ' . $hotel->check_in . "\n";
        }

        if (!empty($hotel->check_out)) {
            $prompt .= 'Check out : ' . $hotel->check_out . "\n";
        }

        return $prompt;
    }
}
