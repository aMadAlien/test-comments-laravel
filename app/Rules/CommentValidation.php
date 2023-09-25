<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class CommentValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!($value instanceof UploadedFile) || !$value->isValid()) {
            $fail('The file is invalid.');
        }

        if (!Validator::make([$attribute => $value], $this->getRulesForExtension($value->extension()))) {
            $fail('The file is invalid.');
        }
    }

    protected function getRulesForExtension($extension)
    {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
                return ['image', 'max_width:320', 'max_height:240'];
            case 'txt':
                return ['max:102400'];
            default:
                return [];
        }
    }
}
