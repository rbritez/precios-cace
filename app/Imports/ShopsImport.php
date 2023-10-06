<?php

namespace App\Imports;

use App\Platform;
use App\Product;
use App\Shop;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use MadeITBelgium\Wappalyzer\WappalyzerFacade as Wappalyzer;

class ShopsImport implements ToModel,WithHeadingRow,WithChunkReading,ShouldQueue
{
    public $shop;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $tipo ;
    
    public function __construct($tipo)
    {
         $this->tipo = $tipo;        
    }

    public function model(array $row)
    {
/*         dump($row['tienda']); */

        $tipo = $this->tipo;
        Log::info('---------------CREANDO  TIENDA '.$row['tienda'].' ---------------------------------------');
        if(isset($row['plataforma']) && $row['plataforma'] != ''){
            $platforms = cache()->remember('platforms.prefix', now()->addMinutes(10), function () {
                $all = Platform::all();
                return $all->pluck('prefix')->toArray();
            });

            $plataformaRow = (strtolower(trim($row['plataforma'])) == 'tienda nube')? 'tiendanube': strtolower(trim($row['plataforma']));
            
            if(in_array($plataformaRow,$platforms)){
                $platform = $plataformaRow;
            }else{
                $platform = $this->searchPlatform(trim($row['url']));
            }
        }else{
            $platform = $this->searchPlatform(trim($row['url'])); 
        }
        
        try {
            //verificar si la tienda ya existe
            ($row['tienda'] == 0) ? null : $exist = Shop::whereName($row['tienda'])->first();
            if($exist){
                $this->shop = $exist;
            }else{
                $this->shop = Shop::create([ 'name' => $row['tienda'], 'url' => trim($row['url']), 'platform'  => strtolower(trim($platform)),]); 
            }
            Log::info('---------------SE CREO TIENDA '.$row['tienda'].' ---------------------------------------');
            if($tipo == 2){     
                $this->createProducts($this->shop,$row);
            }
            Log::info('SE CREARON LOS PRODUCTOS PARA LA TIENDA '.$row['tienda']);
            return $this->shop;
        } catch (Exception $e) {
            Log::info('Error al crear tienda ('.$row['tienda'].') : ERROR => '.$e->getMessage());
            return null;
        }
    }

    public function searchPlatform($url){
        try {
            $platforms = cache()->remember('platforms.prefix', now()->addMinutes(10), function () {
                $all = Platform::all();
                return $all->pluck('prefix')->toArray();
            }); 

            $arrayData = $this->SearchCMS($url);

            if($arrayData['status'] == 200){
                
                foreach ($arrayData['data'] as  $data) {
                    $cms = strtolower($data);
                    if(in_array($cms,$platforms)){
                        $returnPlatform = $cms;
                    }else{
                        $returnPlatform = null;
                    }
                }
            }else{
                $returnPlatform = null;
            }
            return $returnPlatform;
        } catch (Exception $th) {
            Log::info('ERROR EN SEARCH '. $th->getMessage());
            return null;
        }

    }


    public function SearchCMS($url){
        $url = $url;
        $data=[];
        $status = 200;
        $message ='';
        $urlparts= parse_url($url);
        $scheme = isset($urlparts['scheme'])?$urlparts['scheme']:null ;

        if ($scheme === 'https') {
            try {
                $result = Wappalyzer::analyze($url);
                if($result['detected']){
                    foreach ($result['detected'] as $key => $value) {
                        $data[]= $key;
                    }
                }else{
                    $status = 204;
                    $message = 'No se pudieron detectar tecnologías';
                    Log::info($message);
                }
            } catch (\Throwable $error) {
                $status = 404;
                $message ="no se pudo encontrar el sitio $url : ".$error->getMessage();
                Log::info($message);
            }
        } else {
            $status = 404;
            $message ="$url no es una URL válida";
            Log::info($message); 
        }
        
        $returnArray = ['status'=> $status ,'message' => $message,'data'=>$data];
        return $returnArray;
    }

    public function createProducts($shop,$row){
        for ($i=1; $i < 9; $i++) { 
            $producto = (isset($row['producto_'.$i]) && $row['producto_'.$i] != '')? $row['producto_'.$i] :null;
            if($producto != null){
                $url = $this->urlProduct($shop->url,$producto);
                $name = 'producto-'.$i.'-'.$shop->name.today()->format('d_m_Y');
                $newProduct = Product::create(['name'=> $name,'url'=>$url]);
                $shop->addProducts($newProduct->id,$url);
            }
        }
    }

    public function urlProduct($shopUrl,$productUrl){
        $url = str_replace($shopUrl,'',$productUrl);
        
        return $url;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}

