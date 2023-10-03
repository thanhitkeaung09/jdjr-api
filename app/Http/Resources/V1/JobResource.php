<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Folder;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @property-read Job $resource
 * @property-read string $id
 * @property-read string $title
 * @property-read string $icon
 * @property-read string|null $tools_remark
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
final class JobResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => route(
                name: 'api:v1:images:show',
                parameters: [
                    'path' => $this->icon,
                ],
            ),
            'created' => new DateResource(
                resource: $this->created_at,
            ),
            'updated' => new DateResource(
                resource: $this->updated_at
            ),
            'subcategory' => new SubcategoryResource(
                resource: $this->whenLoaded('subcategory'),
            ),
            'location' => new LocationResource(
                resource: $this->whenLoaded('location'),
            ),
            'description' => new DescriptionResource(
                resource: $this->whenLoaded('description'),
            ),
            'questions' => QuestionResource::collection(
                resource: $this->whenLoaded('questions'),
            ),
            'qualifications' => QualificationResource::collection(
                resource: $this->whenLoaded('qualifications'),
            ),
            'responsibilities' => ResponsibilityResource::collection(
                resource: $this->whenLoaded('responsibilities'),
            ),
            'skills' => SkillResource::collection(
                resource: $this->whenLoaded('skills'),
            ),
            'skillsMatch' => $this->when(
                condition: $this->whenLoaded('skills') instanceof Collection,
                value: $this->getSkillsMatch(),
            ),
            'toolsRemark' => $this->tools_remark,
            'tools' => ToolResource::collection(
                resource: $this->whenLoaded('tools'),
            ),
            'careerPaths' => CareerPathResource::collection(
                resource: $this->whenLoaded('careerPaths'),
            ),
            'experiences' => JobExperienceResource::collection(
                resource: $this->whenLoaded('experiences'),
            ),
            'saved' => $this->resource->currentUserSavedFolders()->get()->count() > 0,
            'savable' => $this->when(
                condition: $this->resource->currentUserSavedFolders()->first() instanceof Folder,
                value: fn () => new SavableResource(
                    resource: $this->resource->currentUserSavedFolders()->first()->savable
                ),
                default: null,
            ),
            'popular' => new PopularResource(
                resource: $this->whenLoaded('popular'),
            ),
        ];
    }

    private function getSkillsMatch(): string
    {
        /** @var Collection */
        $userSkills = Auth::user() instanceof User ? Auth::user()->skills : collect();
        $jobSkills = DB::table('jobs_skills')->where('job_id', $this->id)->get();
        $skillsMatch = $jobSkills->filter(fn ($js) => $userSkills->contains(fn ($skill) => $skill->id === $js->skill_id));

        return "{$skillsMatch->count()} out of {$jobSkills->count()} match with your profile";
    }
}
