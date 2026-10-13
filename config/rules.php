<?php

return [
    'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
    'shade' => ['required', 'integer', 'between:20,90'],
    'title' => ['nullable', 'string', 'max:120'],
    'subtitle' => ['nullable', 'string', 'max:300'],
    'background_pages' => ['boolean'],
    'show_features' => ['boolean'],
    'show_join' => ['boolean'],
    'footer_links' => ['nullable', 'array', 'max:8'],
    'footer_links.*.name' => ['required', 'string', 'max:40'],
    'footer_links.*.url' => ['required', 'url'],
    'show_mojang' => ['boolean'],
];
