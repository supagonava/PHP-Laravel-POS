<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'barcode',
        'price',
        'quantity',
        'status'
    ];

    public function storeImage($image)
    {
        if (!is_dir(public_path('products'))) {
            mkdir(public_path('products'));
        }
        if ($this->image && is_file(public_path($this->image))) {
            unlink(public_path($this->image));
        }

        // Resize the image to a width of 500px and a height proportional to the original image
        $resizedImage = Image::make($image)->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Save the resized image to a storage location
        $image_path = '/products/' . $image->hashName();
        $resizedImage->save(public_path($image_path));
        return $image_path;
    }
}
