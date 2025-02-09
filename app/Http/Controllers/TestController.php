<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Purchase;
use Carbon\Carbon;
use App\Services\OpenAIService;
use thiagoalessio\TesseractOCR\TesseractOCR;

class TestController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function readImages()
    {
        $path = public_path('images/to-read');
    
        if (!File::exists($path))
        {
            return "A pasta não existe.";
        }
    
        $files = File::files($path);
        $allFiles = array_filter($files, function ($file) {
            return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });
    
        $prompt = "Preciso que me passe informações dessa nota de compra. Preciso saber o mercado que foi comprado, o valor total da compra e a data da compra. Também preciso de dados tabulados dos produtos comprados. Preciso do nome dos produtos, dos valores unitários, total de produtos e valor total para cada produto. Retorne somente os dados e em JSON válido.";
        $images = array_map(fn($file) => $file->getFilename(), $allFiles);

        foreach($images as $key => $imageFileName)
        {
            if($key <= 5)
            {
                $from = public_path('images/to-read/'.$imageFileName);
                $to = public_path('images/readed/'.$imageFileName);
    
                $image = file_get_contents($from);
                $imagemBase64 = base64_encode($image);
                
                $response = $this->openAIService->readImage($prompt, $imagemBase64);
                Storage::put('jsons/to-import/'.$imageFileName.'.json', $response);            
                $this->moveFile($from, $to);

                sleep(10);
            }
        }
        return 'ok';
    }

    public function importJsonFiles()
    {
        $path = storage_path('app/private/jsons/to-import');
        $jsonFiles = File::files($path);
        
        $jsons = array_filter($jsonFiles, function ($file) {
            return in_array(strtolower($file->getExtension()), ['json']);
        });

        foreach($jsons as $fileJson)
        {
            $jsonData = $this->readJsonFile($fileJson->getFilename());
            
            if(!is_array($jsonData))
            {
                dd($fileJson->getFilename());
            }
            
            if(!array_key_exists('nome_supermercado', $jsonData))
            {
                dd($jsonData);
            }
            
            $purchase = new Purchase;
            $purchase->market = $jsonData['nome_supermercado'];
            $purchase->value = $jsonData['valor_total'];
            $purchase->date = Carbon::createFromFormat('d/m/Y', $jsonData['data_compra']);
            $purchase->save();
            
            foreach ($jsonData['produtos'] as $produto)
            {
                $product = Product::where('barcode', $produto['codigo_barras'])->first();
                if(!$product)
                {
                    $product = new Product;
                    $product->name = $produto['nome'];
                    $product->barcode = $produto['codigo_barras'];
                    $product->save();
                }

                $purchase->products()->attach($product->id, ['quantity' => $produto['quantidade'], 'price' => $produto['valor_unitario'], 'total' => $produto['valor_total']]);
            }

            $this->moveFile($fileJson->getPathname(), storage_path('app/private/jsons/readed/' . $fileJson->getFilename()));
        }

        return 'ok';
    }

    function readJsonFile($fileName)
    {
        $filePath = 'jsons/to-import/' . $fileName;
        
        if (Storage::exists($filePath))
        {
            $jsonContent = Storage::get($filePath);
            $data = json_decode($jsonContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return "Erro ao decodificar o JSON: " . json_last_error_msg();
            }
            return $data;
        }
        else
        {
            return "Arquivo não encontrado.";
        }
    }

    public function moveFile($from, $to)
    {
        if (File::exists($from))
        {
            File::move($from, $to);
        }
        else
        {
            dd("Arquivo não encontrado: $from");
        }
    }

    public function testTesseract()
    {
        $tesseract = new TesseractOCR();

        // VERSION
        // return $tesseract->version();

        // AVALIABLE LANGUAGES
        // $langs = [];
        // foreach($tesseract->availableLanguages() as $lang)
        // {
        //     $langs[] = $lang;
        // }

        // dd($langs);

        // // IMAGE TO TEXT
        $data = $tesseract->lang('por')->image(public_path('images/readed/1D43196B-81D5-44C9-9FA2-02A85C3F4057_1_102_a.jpeg'))->run();
        dd($data);
    }
}
