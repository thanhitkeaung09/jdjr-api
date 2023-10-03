<?php

declare(strict_types=1);

namespace App\Actions\V1\Questions;

use App\DataObjects\V1\Questions\NewQuestion;
use App\Enums\NotificationType;
use App\Jobs\SendFirebaseMessage;
use App\Models\Notification as ModelsNotification;
use App\Models\Question;
use Illuminate\Support\Facades\Log;

final readonly class UpdateQuestion
{
    public function handle(Question $question, NewQuestion $data): bool
    {
        Log::debug('answer', ["answer" => $data->answer, 'fav' => $data->isFavourited]);
        if (isset($data->answer) && $data->isFavourited) {
            $this->sendNotifications($question, $data);
        }

        return $question->update($data->toArray());
    }

    private function sendNotifications(Question $question, NewQuestion $data): void
    {
        if (null !== $question->user->device_token) {
            ModelsNotification::query()->updateOrCreate(
                [
                    'notifiable_type' => Question::class,
                    'notifiable_id' => $question->id,
                ],
                [
                    'user_id' => $question->user_id,
                    'updated_at' => \now()
                ]
            );

            SendFirebaseMessage::dispatch(
                $question->user->device_token,
                $question->id,
                $data->question,
                "Hey! Check out the answer of your question.",
                NotificationType::QUESTION->value
            );
        }
    }
}
