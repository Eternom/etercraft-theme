<?php

/*
 * Validation de la page de configuration du thème. Azuriom n'enregistre que les champs validés ici :
 * toute nouvelle option doit y figurer.
 */
return [
    // Accueil
    'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
    'shade' => ['required', 'integer', 'between:20,90'],
    'title' => ['nullable', 'string', 'max:120'],
    'subtitle' => ['nullable', 'string', 'max:300'],
    'hero_layout' => ['nullable', 'in:centered,cinematic,split'],
    'hero_images' => ['nullable', 'array', 'max:10'],
    'hero_images.*' => ['string', 'max:100'],
    'background_pages' => ['boolean'],
    'sections' => ['nullable', 'array'],
    'sections.*' => ['distinct', 'in:features,join,news,vote,shop,wiki,forum'],

    // Présentation
    'show_features' => ['boolean'],
    'features_layout' => ['nullable', 'in:list,bento,showcase'],
    'features_title' => ['nullable', 'string', 'max:80'],
    'features_subtitle' => ['nullable', 'string', 'max:200'],
    'features' => ['nullable', 'array', 'max:9'],
    'features.*.icon' => ['required', 'regex:/^(bi-)?[a-z0-9-]+$/'],
    'features.*.title' => ['required', 'string', 'max:60'],
    'features.*.text' => ['required', 'string', 'max:300'],
    'features.*.image' => ['nullable', 'string', 'max:100'],

    // Rejoindre le serveur
    'show_join' => ['boolean'],
    'join_layout' => ['nullable', 'in:cards,timeline,split'],
    'join_title' => ['nullable', 'string', 'max:80'],
    'join_steps' => ['nullable', 'array', 'max:4'],
    'join_steps.*.title' => ['required', 'string', 'max:60'],
    'join_steps.*.text' => ['required', 'string', 'max:300'],
    'join_steps.*.image' => ['nullable', 'string', 'max:100'],

    // Sections des plugins (ignorées tant que le plugin n'est pas installé)
    'show_vote' => ['boolean'],
    'vote_title' => ['nullable', 'string', 'max:80'],
    'show_shop' => ['boolean'],
    'shop_title' => ['nullable', 'string', 'max:80'],
    'shop_count' => ['nullable', 'integer', 'between:1,8'],
    'show_wiki' => ['boolean'],
    'wiki_title' => ['nullable', 'string', 'max:80'],
    'show_forum' => ['boolean'],
    'forum_title' => ['nullable', 'string', 'max:80'],
    'forum_count' => ['nullable', 'integer', 'between:1,10'],

    // Pied de page
    'footer_links' => ['nullable', 'array', 'max:8'],
    'footer_links.*.name' => ['required', 'string', 'max:40'],
    'footer_links.*.url' => ['required', 'url'],
    'show_mojang' => ['boolean'],
];
