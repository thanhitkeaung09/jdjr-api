<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Questions;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewQuestion implements DataObjectContract
{
    public function __construct(
        public string $jobId,
        public string $userId,
        public string $question,
        public string|null $answer,
        public bool $isFavourited
    ) {
    }

    /**
     * @param array{job_id:string,user_id:string,question:string,answer?:string,is_favourited?:bool} $attributes
     *
     * @return NewQuestion
     */
    public static function of(array $attributes): NewQuestion
    {
        return new NewQuestion(
            jobId: $attributes['job_id'],
            userId: $attributes['user_id'],
            question: $attributes['question'],
            answer: $attributes['answer'] ?? null,
            isFavourited: $attributes['is_favourited'] ?? false,
        );
    }

    /**
     * @return array{job_id:string,user_id:string,question:string,answer?:string,is_favourited:bool}
     */
    public function toArray(): array
    {
        return [
            'job_id' => $this->jobId,
            'user_id' => $this->userId,
            'question' => $this->question,
            'answer' => $this->answer,
            'is_favourited' => $this->isFavourited,
        ];
    }
}
