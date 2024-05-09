<?php

namespace PIA\ModuleShared\ValueObjects;

class Knowledgebase
{
    public string $contact_title = 'Raadpleeg de knowledgebase';
    public string $contact_description = 'Op het intranet vind je documentatie en vaakgestelde vragen over PIA. Mogelijk dat je daar het antwoord vindt op je vraag!';
    public ?string $contact_link = null;
    public ?string $contact_link_txt = null;

    public function contact_data(): array
    {
        return [
            'title' => $this->contact_title,
            'description' => $this->contact_description,
            'link' => $this->contact_link,
            'link_txt' => $this->contact_link_txt,
        ];
    }
}