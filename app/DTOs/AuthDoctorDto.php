<?php

namespace App\DTOs;

use App\Helpers\StorageHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class AuthDoctorDto
{
    public function __construct(
        public ?string $specialization_id,
        public ?string $first_name,
        public ?string $last_name,
        public ?string $device_token,
        public ?string $email,
        public ?string $phone,
        public ?string $password,
        public ?string $DateOfBirth,
        public ?string $Nationality,
        public ?string $ClinicAddress,
        public ?string $consultation_fee,
        public ?UploadedFile $CurriculumVitae,
        public ?UploadedFile $ProfessionalAssociationPhoto,
        public ?UploadedFile $CertificateCopy,
        public ?UploadedFile $image,
    ) {}

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            specialization_id: $request->input('specialization_id'),
            first_name: $request->input('first_name'),
            last_name: $request->input('last_name'),
            device_token: $request->input('device_token'),
            email: $request->input('email'),
            phone: $request->input('phone'),
            password: $request->input('password'),
            DateOfBirth: $request->input('DateOfBirth'),
            Nationality: $request->input('Nationality'),
            ClinicAddress: $request->input('ClinicAddress'),
            consultation_fee: $request->input('consultation_fee'),
            CurriculumVitae: $request->file('CurriculumVitae'),
            ProfessionalAssociationPhoto: $request->file('ProfessionalAssociationPhoto'),
            CertificateCopy: $request->file('CertificateCopy'),
            image: $request->file('image'),
        );
    }

    public function toArray(): array
    {
        return [
            'specialization_id' => $this->specialization_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'device_token' => $this->device_token,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => $this->password,
            'DateOfBirth' => $this->DateOfBirth,
            'Nationality' => $this->Nationality,
            'ClinicAddress' => $this->ClinicAddress,
            'consultation_fee' => $this->consultation_fee,
            'image'=> $this->image ? StorageHelper::storeFile($this->image, 'doctor_image') : null,
            'ProfessionalAssociationPhoto'=> $this->ProfessionalAssociationPhoto ? StorageHelper::storeFile($this->ProfessionalAssociationPhoto, 'ProfessionalAssociationPhoto') : null,
            'CurriculumVitae'=> $this->CurriculumVitae ?
                StorageHelper::storeFile($this->CurriculumVitae, 'CurriculumVitae') : null,
            'CertificateCopy'=> $this->CertificateCopy ? StorageHelper::storeFile
            ($this->CertificateCopy, 'CertificateCopy') : null,

        ];
    }
}
