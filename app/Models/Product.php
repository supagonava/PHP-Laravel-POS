<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

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

    public function storeImage(UploadedFile $image)
    {
        if (!is_dir(public_path('products'))) {
            mkdir(public_path('products'));
        }
        if ($this->image && is_file(public_path($this->image))) {
            unlink(public_path($this->image));
        }
        $fileName = Str::uuid();
        $image_path = '/products/' . $fileName  . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('/products/'), $fileName  . '.' . $image->getClientOriginalExtension());
        // $base64 = base64_encode(public_path($image_path));
        $imageContents = file_get_contents(public_path($image_path));
        $resizedImage = Image::make($imageContents);
        $resizedImage->resize(800, 800, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path($image_path));

        return $image_path;
    }
}
