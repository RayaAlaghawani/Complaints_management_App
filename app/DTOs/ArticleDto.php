<?php

namespace App\DTOs;

//use App\Helpers\StorageHelper;
use App\Helpers\StorageHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class ArticleDto
{
    public function __construct(
        public ?string $title,
        public ?string $content,
        public ?string $category,
        public ?string $status,
        public ?string $published_at,
        public ?string $summary,
        public ?string $doctor_id,
        public ?string $specialization_id,

        public ?UploadedFile $image,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->input('title'),
            content: $request->input('content'),
            category: $request->input('category'),
            status: $request->input('status'),
            published_at: $request->file('published_at'),
            summary: $request->file('summary'),
            doctor_id: $request->file('doctor_id'),
            specialization_id: $request->file('specialization_id'),
            image: $request->file('image'),
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'published_at' => now(),
            'title'       => $this->title,
            'content'      => $this->content,
            'category'    => $this->category,
            'summary' => $this->summary,
            'doctor_id' => $this->doctor_id,
            'specialization_id' => Auth::user()->specialization->id,
            'status' => $this->status,
            'image'       => $this->image ? StorageHelper::storeFile($this->image, 'articles') : null,
        ], fn($value) => !is_null($value));
    }
}
