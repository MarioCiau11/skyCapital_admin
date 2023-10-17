<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $image = $request->file('upload');


        // Generar un nombre de archivo único
        $fileName = 'image_' . time() . '.' . $image->getClientOriginalExtension();

        // Guardar la imagen redimensionada en el directorio de almacenamiento público
        $resizedImage = Image::make($image)->resize(250, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        Storage::disk('public')->put($fileName, $resizedImage->stream());

        // Devolver la URL de la imagen guardada como respuesta
        $imageUrl = asset('storage/' . $fileName);

        return response()->json([
            'default' => $imageUrl,
            'url' => $imageUrl,
        ]);
    }
}
