<?php

declare(strict_types=1);

namespace App\DataObjects\V1\AppVersions;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class AppVersionData implements DataObjectContract
{
    public function __construct(
        public string $version,
        public string $buildNo,
        public bool $isForcedUpdated,
        public string $iosLink,
        public string $androidLink,
    ) {
    }

    /**
     * @param array{version:string,build_no:string,is_forced_updated:bool,ios_link:string,android_link:string} $attributes
     *
     * @return AppVersionData
     */
    public static function of(array $attributes): AppVersionData
    {
        return new AppVersionData(
            version: $attributes['version'],
            buildNo: $attributes['build_no'],
            isForcedUpdated: $attributes['is_forced_updated'],
            iosLink: $attributes['ios_link'],
            androidLink: $attributes['android_link'],
        );
    }

    /**
     * @return array{version:string,build_no:string,is_forced_updated:bool,ios_link:string,android_link:string}
     */
    public function toArray(): array
    {
        return [
            'version' => $this->version,
            'build_no' => $this->buildNo,
            'is_forced_updated' => $this->isForcedUpdated,
            'ios_link' => $this->iosLink,
            'android_link' => $this->androidLink,
        ];
    }
}
