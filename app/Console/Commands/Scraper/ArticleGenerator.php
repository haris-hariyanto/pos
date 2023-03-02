<?php

namespace App\Console\Commands\Scraper;

use Illuminate\Console\Command;
use App\Models\Hotel\Hotel;
use App\Models\Key;
use App\Helpers\Text;
use App\Helpers\CacheSystemDB;

class ArticleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:articles {--limit=}';

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
        if ($this->option('limit')) {
            $limit = $this->option('limit');
        }
        else {
            $this->line('[ * ] Menghitung hotel...');

            $hotelsTotal = Hotel::count();
            $hotelsWithoutArticle = Hotel::where('article', '')
                ->orWhereNull('article')
                ->count();
            $hotelsWithArticle = Hotel::where('article', '<>', '')
                ->whereNotNull('article')
                ->count();
            
            $this->info('[ * ] Jumlah hotel : ' . $hotelsTotal);
            $this->info('[ * ] Hotel dengan artikel : ' . $hotelsWithArticle);
            $this->info('[ * ] Hotel tanpa artikel : ' . $hotelsWithoutArticle);
            $this->line('--------------------');
            
            $limit = $this->ask('Limit Hotel', 1000);
        }

        $this->initializeKeys();

        for ($i = 1; $i <= $limit; $i++) {
            $hotel = Hotel::where('is_article_scraped', 'N')
                ->orderBy('total_views', 'DESC')
                ->orderBy('number_of_reviews')
                ->first();
            
            if (!$hotel) {
                $this->info('[ * ] Semua hotel sudah memiliki artikel');
                return 0;
            }

            $lastUsedKey = Key::select('id')
                ->where('is_last_used', 'Y')
                ->first();
            if ($lastUsedKey) {
                $lastUsedKeyID = $lastUsedKey->id;
            }
            else {
                $lastUsedKeyID = 0;
            }

            $key = Key::where('id', '>', $lastUsedKeyID)
                ->where('is_broken', 'N')
                ->first();
            if (!$key) {
                $key = Key::where('is_broken', 'N')->first();
                if (!$key) {
                    $this->error('[ * ] Tidak ada API key yang bisa digunakan');
                    return 0;
                }
            }

            $hotel->update([
                'is_article_scraped' => 'PROCESS',
            ]);

            $client = \OpenAI::client($key->key);
            try {
                $this->info('[ * ] ' . $i . '/' . $limit);
                $this->info('[ * ] Membuat artikel : ' . $hotel->name);
                $result = $client->completions()->create([
                    'model' => 'text-davinci-003',
                    'temperature' => config('services.openai.temperature'),
                    'top_p' => 1,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                    'max_tokens' => config('services.openai.max_tokens'),
                    'prompt' => $this->generatePrompt($hotel),
                ]);
            }
            catch (\OpenAI\Exceptions\ErrorException $error) {
                $this->error('[ * ] API key tidak bisa digunakan, mencoba API key lain');
                $this->line('--------------------');
                $key->update([
                    'is_broken' => 'Y',
                ]);
                continue;
            }

            $article = trim($result['choices'][0]['text']);
            $hotel->update([
                'article' => $article,
                'is_article_scraped' => 'Y',
            ]);

            Key::where('id', '>', 0)->update([
                'is_last_used' => 'N',
            ]);
            $key->update([
                'is_last_used' => 'Y',
            ]);

            CacheSystemDB::forget('hotel' . $hotel->slug);

            $this->info('[ * ] Jumlah kata : ' . str_word_count($article));
            $this->line('--------------------');
        } // [END] for
    }

    private function initializeKeys()
    {
        $path = base_path('openai_keys.txt');
        $keys = file_get_contents($path);
        $keys = preg_split('/\r\n|\r|\n/', $keys);

        Key::where('id', '>', 0)->update(['is_exists_in_file' => 'N']);

        foreach ($keys as $key) {
            if (empty(trim($key))) {
                continue;
            }
            
            if (Key::where('key', $key)->exists()) {
                Key::where('key', $key)->update(['is_exists_in_file' => 'Y']);
            }
            else {
                Key::create([
                    'key' => $key,
                    'is_broken' => 'N',
                    'is_last_used' => 'N',
                    'is_exists_in_file' => 'Y',
                ]);
            }
        } // [END] foreach

        Key::where('is_exists_in_file', 'N')->delete();
    }

    private function generatePrompt($hotel) {
        $prompt = '';
        if (config('app.locale') == 'id') {
            $prompt .= 'Saya ingin membuat artikel untuk keperluan SEO dan rangking di search engine Google. ';
            $prompt .= 'Buat sebuah artikel tentang hotel dengan bahasa Indonesia santai menggunakan data yang disediakan. ';
            $prompt .= 'Artikel harus berisi minimal 10 paragraf. ';
            $prompt .= 'Tiap paragraf harus berisi minimum 200 kata. ';
            $prompt .= 'Tulis dengan format HTML tanpa tag html dan body. ';
            $prompt .= 'Judul utama: <h2>. Sub judul: <h3>. Kesimpulan: <h4>. Paragraf: <p>.' . "\n";

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
            $prompt .= 'I want to create an article for SEO purposes and ranking on the Google search engine. ';
            $prompt .= 'Write an article about hotel in relaxed Indonesian language using the following data. ';
            $prompt .= 'The article consists of at least 10 paragraphs. ';
            $prompt .= 'Each paragraph must have a minimum of 200 words. ';
            $prompt .= 'Write in HTML without html and body tag. ';
            $prompt .= 'Primary title: <h2>. Subtitles: <h3>. Conclusion title: <h4>. Paragraphs: <p>.' . "\n";

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
