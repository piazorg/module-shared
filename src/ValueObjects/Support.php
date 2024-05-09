<?php

namespace PIA\ModuleShared\ValueObjects;

class Support
{
    public string $department = 'PIA-Support';
    public ?string $email = 'support@pia-zorg.nl';
    public ?string $phone = null;

    public function data(): array
    {
        return [
            'department' => $this->department,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
