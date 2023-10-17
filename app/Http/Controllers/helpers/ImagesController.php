<?php

namespace App\Http\Controllers\helpers;

use App\Http\Controllers\Controller;
use App\Models\utils\CAT_FILES_IMAGENES;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use function PHPUnit\Framework\fileExists;

class ImagesController extends Controller
{
    
    public function eliminarImagen(Request $request)
    {
        $idImagen = $request->idImg;
        $delete = CAT_FILES_IMAGENES::WHERE('idImg', '=', $idImagen)->delete();

        if ($delete) {
            return response()->json(['status' => $delete, 'mensaje' => 'Imagen eliminada de la base de datos'], 200);
        } else {
            return response()->json(['status' => $delete, 'mensaje' => 'Imagen no eliminada de la base de datos'], 404);
        }
    }

    public function getPathLogoCompany(){
        $empresa = session('company');
        $pathImg = null;

        if ($empresa->logo && file_exists(storage_path($empresa->logo))) {
            $pathImg = storage_path($empresa->logo);
        } else {
            // validar si existe en el servidor
            if (file_exists(storage_path('app/public/empresas/' . $empresa->idEmpresa . '/logo.png'))) {
                $pathImg = storage_path('app/public/empresas/' . $empresa->idEmpresa . '/logo.png');
            }
        }
        return $pathImg;
    }

    public function resizeLogo(){
        $pathImg = $this->getPathLogoCompany();
        // dd($pathImg);
        if ($pathImg) {
            $resizedImage = Image::make($pathImg)->resize(300,null,function($constraint){
                $constraint->aspectRatio();
            });
            // Obtener el tipo de imagen (extensión)
            $typeImageE = pathinfo($pathImg, PATHINFO_EXTENSION);
            // Convertir la imagen redimensionada a base64
            $base64ImageE = 'data:image/' . $typeImageE . ';base64,' . base64_encode($resizedImage->encode($typeImageE, 75)->__toString());
            // return $resizedImage->response();
            return $base64ImageE;
        }
        return null;
        
    }

    public function resizeLogoProyect($path){
        $pathImg = $path;
        if ($pathImg) {
            $resizedImage = Image::make($pathImg)->resize(300,null,function($constraint){
                $constraint->aspectRatio();
            });
            // Obtener el tipo de imagen (extensión)
            $typeImageE = pathinfo($pathImg, PATHINFO_EXTENSION);
            // Convertir la imagen redimensionada a base64
            $base64ImageE = 'data:image/' . $typeImageE . ';base64,' . base64_encode($resizedImage->encode($typeImageE, 75)->__toString());
            return $base64ImageE;
        }
        return null;
    }

    public function resizeImage(Request $request){
        
        $pathImg = storage_path($request->path);

        if ($pathImg && file_exists($pathImg)) {
            $resizedImage = Image::make($pathImg)->resize(300,null,function($constraint){
                $constraint->aspectRatio();
            });
            // Obtener el tipo de imagen (extensión)
            $typeImageE = pathinfo($pathImg, PATHINFO_EXTENSION);
            // Convertir la imagen redimensionada a base64
            $base64ImageE = 'data:image/' . $typeImageE . ';base64,' . base64_encode($resizedImage->encode($typeImageE, 75)->__toString());
            return response()->json(['status' => true, 'base64' => $base64ImageE], 200);
        }
        return response()->json(['status' => false, 'base64' => null], 200);
    }


}
