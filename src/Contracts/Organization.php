<?php

namespace PIA\ModuleShared\Contracts;

use PIA\ModuleShared\ValueObjects\Knowledgebase;
use PIA\ModuleShared\ValueObjects\Support;
use PIA\ModuleShared\ValueObjects\Theme;

interface Organization
{
    public function name(): string;

    public function theme(): Theme;

    public function knowledgebase(): Knowledgebase;
    
    public function support(): Support;
}
