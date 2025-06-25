<?php
namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class NewsValidator
{
    public static function validate(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:webp|max:2048',
        ], [
            'title.required' => 'Başlık alanı zorunludur.',
            'content.required' => 'İçerik alanı zorunludur.',
            'image.image' => 'Yüklenen dosya geçerli bir resim olmalıdır.',
            'image.mimes' => 'Resim dosyası webp formatında olmalıdır.',
            'image.max' => 'Resim dosyası maksimum 2MB olabilir.',
        ]);
    }
}
