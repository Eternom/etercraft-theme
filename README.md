# ThxClaude

Thème sombre et moderne pour [Azuriom](https://azuriom.com), pensé pour les serveurs Minecraft.
Tout se règle depuis l'administration, sans toucher au code.

## Fonctionnalités

- **Accueil plein écran** en trois dispositions (centrée, cinéma, partagée), avec un diaporama en fondu
  à partir des images d'Azuriom, le statut du serveur et la copie de l'adresse en un clic.
- **Section de présentation** en trois dispositions (éditoriale, mosaïque, vitrine). Cartes modifiables
  (icône, titre, texte, image) ; en mosaïque, la grande carte est tirée au hasard à chaque visite.
- **Section « Rejoindre le serveur »** en trois dispositions (cartes, frise, partagée).
- **Ordre des sections** de l'accueil réglable par glisser-déposer.
- **Plugins officiels pris en charge** quand ils sont installés :

  | Plugin | Section d'accueil | Page habillée |
  |---|---|---|
  | Vote | Podium des meilleurs voteurs du mois | Sites de vote, podium, objectif, récompenses |
  | Shop | Articles mis en avant | Toutes les pages (paiement PayPal : style du thème seulement) |
  | Wiki | Catégories et recherche | Accueil du wiki et en-tête des pages |
  | Forum | Discussions récentes (ou invitation à lancer la première) | Accueil, forums, discussions, fil d'Ariane |

- Couleur principale au choix, liens supplémentaires et mentions du pied de page, français et anglais.

Ce qu'Azuriom propose déjà (nom, logo, image de fond, description, copyright, réseaux sociaux) n'est pas
dupliqué : la configuration du thème renvoie vers ces réglages.

## Installation

Copier le dossier dans `resources/themes/thxclaude`, puis l'activer dans **Administration → Thèmes**.
La configuration est découpée en rubriques ; celles des plugins n'apparaissent que si le plugin est actif.

## Structure

```
config/                réglages du thème : formulaire (config.blade.php) et validation (rules.php)
lang/{fr,en}/          textes, y compris le contenu par défaut des sections
views/
  layouts/             squelette commun (base) et pages internes (app)
  home.blade.php       accueil : choisit le hero et enchaîne les sections dans l'ordre configuré
  elements/hero/       dispositions du hero et morceaux partagés (statut, boutons, diaporama)
  elements/features/   dispositions de la présentation
  elements/join/       dispositions de « Rejoindre le serveur »
  elements/home/       une vue par section d'accueil (dont celles des plugins)
  vendor/<plugin>/     pages de plugins remplacées par le thème
assets/css/style.css   tout le style, variables --tc-* en tête de fichier
assets/js/theme.js     interactions (apparition au défilement, diaporama, halo, vitrine…), sans dépendance
```

## Notes de développement

- **Pages de plugins remplacées** (`views/vendor/<plugin>/…`) : elles reprennent les variables et les
  repères HTML du plugin d'origine (identifiants utilisés par ses scripts, listés en tête de chaque vue).
  Après une mise à jour majeure d'un plugin, comparer avec sa vue d'origine.
- **Nouvelle option** : l'ajouter au formulaire, à `config/rules.php` (Azuriom n'enregistre que les champs
  validés) et lire sa valeur avec un repli : `config('theme.option') ?: valeur par défaut`.
- **Nouvelle section d'accueil** : créer `views/elements/home/<nom>.blade.php` et ajouter le nom à la liste
  des sections dans `home.blade.php`, `config/config.blade.php` et `rules.php`.
- Azuriom réécrit `config.json` à chaque enregistrement de la configuration : les valeurs par défaut vivent
  dans le code et les fichiers de langue, pas dans ce fichier.
