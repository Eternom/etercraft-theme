<?php

return [
    'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
    'title' => ['nullable', 'string', 'max:120'],
    'subtitle' => ['nullable', 'string', 'max:300'],
    'discord' => ['nullable', 'url'],
];
