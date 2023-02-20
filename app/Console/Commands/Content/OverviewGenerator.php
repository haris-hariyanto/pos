<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Hotel\Hotel;

class OverviewGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:generate-overview';

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
        $client = \OpenAI::client('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');
        $hotels = Hotel::where('overview', '')->orWhereNull('overview')->limit(2)->get();

        foreach ($hotels as $hotel) {
            $result = $client->completions()->create([
                'model' => 'text-davinci-003',
                'temperature' => 0.7,
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
                'max_tokens' => 600,
                'prompt' => 'Buat deskripsi hotel dengan data berikut:' . "\n" .
                    'Nama hotel : ' . $hotel->name,
            ]);
            $this->info($result['choices'][0]['text']);
            $this->line('-------------------------');
        }
    }
}
