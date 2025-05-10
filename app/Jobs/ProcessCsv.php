<?php

namespace App\Jobs;
use App\Models\Upload;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $upload;

    public function __construct(Upload $upload)
    {
        $this->upload = $upload;
    }

    public function handle()
    {
        $this->upload->update(['status' => 'processing']);

        try {
            Log::info('Starting to process CSV: ' . $this->upload->file_path);

            $stream = Storage::get($this->upload->file_path);
            Log::info('File read successfully. File content size: ' . strlen($stream));

            $cleaned = mb_convert_encoding($stream, 'UTF-8', 'UTF-8');
            $csv = Reader::createFromString($cleaned);
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();

            foreach ($records as $record) {
                Log::info('Processing record: ' . json_encode($record)); 

                Product::updateOrCreate(
                    ['unique_key' => $record['UNIQUE_KEY']],
                    [
                        'product_title' => $record['PRODUCT_TITLE'],
                        'product_description' => $record['PRODUCT_DESCRIPTION'],
                        'style' => $record['STYLE#'],
                        'sanmar_mainframe_color' => $record['SANMAR_MAINFRAME_COLOR'],
                        'size' => $record['SIZE'],
                        'color_name' => $record['COLOR_NAME'],
                        'piece_price' => $record['PIECE_PRICE'],
                    ]
                );
            }

            $this->upload->update(['status' => 'completed']);
        } catch (\Exception $e) {
            Log::error('Error occurred during CSV processing: ' . $e->getMessage());
            $this->upload->update(['status' => 'failed']);
            throw $e;
        }
    }
    
}
