<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Questions;

use App\Actions\V1\Questions\FetchQuestions;
use App\Http\Resources\V1\QuestionResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final readonly class IndexController
{
    public function __construct(
        private FetchQuestions $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new PaginatedResourceResponse(
            resource: QuestionResource::collection(
                resource: $this->query->handle(request('search'))->paginate(\config('database.pagination')),
            ),
        );
    }
}
