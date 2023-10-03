<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Folders;

use App\DataObjects\V1\Folders\NewFolderData;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Rules\CheckFolders;
use Illuminate\Foundation\Http\FormRequest;

final class UpsertRequest extends FormRequest implements PayloadRequestContract
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                new CheckFolders($this->folder),
            ],
        ];
    }

    public function payload(): NewFolderData
    {
        return NewFolderData::of([
            'name' => $this->string('name')->toString(),
            'user' => $this->user(),
        ]);
    }
}
