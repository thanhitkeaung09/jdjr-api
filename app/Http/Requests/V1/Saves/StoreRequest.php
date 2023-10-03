<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Saves;

use App\DataObjects\V1\Saves\SaveData;
use App\Enums\SavableType;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Rules\CheckOwnFolder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class StoreRequest extends FormRequest implements PayloadRequestContract
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'folder_id' => [
                'required',
                'string',
                'uuid',
                new CheckOwnFolder(),
            ],
            'savable_type' => [
                'required',
                'string',
                new Enum(SavableType::class),
            ],
            'savable_id' => [
                'required',
                'string',
                'uuid',
            ],
        ];
    }

    public function payload(): SaveData
    {
        return SaveData::of(
            attributes: [
                'folder_id' => $this->string('folder_id')->toString(),
                'savable_type' => SavableType::from($this->string('savable_type')->toString()),
                'savable_id' => $this->string('savable_id')->toString(),
                'user' => $this->user(),
            ],
        );
    }
}
