<?php

namespace App\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class UserData
{
    public function __construct() {}

    public static function fromRequest(FormRequest $request): self
    {
        return new self();
    }

    public function toArray(): array
    {
        return [];
    }
}
