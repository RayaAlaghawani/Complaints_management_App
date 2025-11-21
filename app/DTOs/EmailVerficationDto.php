<?php

namespace App\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class EmailVerficationDto extends \App\DTOs\AuthDoctorDto
{
    public function __construct(
        public ?string $email,
        public ?string $code,

    ) {

    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self();
    }

    public function toArray(): array
    {
        return [
            'email'    => ['required', 'email',],
            'password' => ['required', 'string'],

        ];
    }
}
