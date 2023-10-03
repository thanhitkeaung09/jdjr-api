<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Jobs;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewJob implements DataObjectContract
{
    /**
     * @param array<CareerPath> $careerPaths
     * @param array<Experience> $experiences
     * @param array<Qualification> $qualifications
     * @param array<Responsibility> $responsibilities
     */
    public function __construct(
        public string $title,
        public JobDescription $description,
        public UploadedFile|null $image,
        public string|null $toolsRemark,
        public string $locationId,
        public string $subcategoryId,
        public array $careerPaths,
        public array $experiences,
        public array $qualifications,
        public array $responsibilities,
        public array $skills,
        public array $tools,
    ) {
    }

    /**
     * @param array{title:string,description_title:string,description_body:string,image:UploadedFile,tools_remark?:string,subcategory_id:string,location_id:string,career_paths:array,experiences:array,qualifications:array,responsibilities:array,skills:array,tools:array} $attributes
     *
     * @return NewJob
     */
    public static function of(array $attributes): NewJob
    {
        return new NewJob(
            title: $attributes['title'],
            description: JobDescription::of([
                'title' => $attributes['description_title'],
                'body' => $attributes['description_body'],
            ]),
            image: $attributes['image'] ?? null,
            toolsRemark: 'undefined' === $attributes['tools_remark'] ? null : $attributes['tools_remark'],
            subcategoryId: $attributes['subcategory_id'],
            locationId: $attributes['location_id'],
            careerPaths: Arr::map($attributes['career_paths'], fn ($item) => CareerPath::of($item)),
            experiences: Arr::map($attributes['experiences'], fn ($item) => Experience::of($item)),
            qualifications: Arr::map($attributes['qualifications'] ?? [], fn ($item) => Qualification::of($item)),
            responsibilities: Arr::map($attributes['responsibilities'], fn ($item) => Responsibility::of($item)),
            skills: $attributes['skills'],
            tools: $attributes['tools'],
        );
    }

    /**
     * @return array{title:string,subcategory_id:string,location_id:string,tools_remark?:string}
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'subcategory_id' => $this->subcategoryId,
            'location_id' => $this->locationId,
            'tools_remark' => $this->toolsRemark,
        ];
    }
}
