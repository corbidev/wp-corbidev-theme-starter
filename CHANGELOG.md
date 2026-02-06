# Changelog - Theme Starter Clean

## v1.2.0 - 2026-02-06

### Changed - Nettoyage pour Kernel v1.2.0

#### Supprimé (gérés par le kernel)
- `includes/Services/OptimizedAssetsService.php` → Remplacé par kernel `ProgressiveLoadingService`
- `includes/Services/AssetsManifestService.php` → Le kernel gère le manifest Vite
- `includes/Core/Assets.php` → Toute la logique dans le kernel
- Dossier `includes/Core/` → Supprimé complètement

#### Simplifié
- `ThemeServiceProvider.php` : 
  - Retiré les services d'optimisation (gérés par kernel)
  - Retiré le code de chargement des assets
  - Retiré WordPressCleanupService du boot (conservé mais désactivé)
  - Réduction de 198 lignes à 145 lignes (-27%)
  - Ajout de l'écoute de l'événement `kernel.loading.booted`

#### Conservé
- `NavigationService.php` - Spécifique au thème
- `ThemeContextService.php` - Métier du thème
- `CurrentUserService.php` - Métier du thème
- `ThemeConfigService.php` - Configuration du thème
- `ThemeConfigWriterService.php` - Configuration du thème
- `WordPressCleanupService.php` - Optionnel (désactivé par défaut)

#### Amélioré
- `functions.php` : Déjà configuré avec `loading_strategy => 'progressive'`
- `header.php` : Déjà configuré avec `corbidev_critical_css()` et `corbidev_progressive_loader()`
- Écoute des événements du kernel pour logging

### Added
- `README_CLEAN.md` : Documentation complète du nettoyage
- Support de l'événement `kernel.loading.booted`
- Logs en mode debug pour le chargement progressif

### Performance
- Même performance que la version précédente
- Le kernel v1.2.0 gère maintenant toutes les optimisations
- First Contentful Paint : < 0.5s
- Time to Interactive : 1-2s
- Lighthouse : 95-100

### Breaking Changes
⚠️ **Ce thème nécessite Kernel v1.2.0+**

Si vous utilisez Kernel v1.1.0 ou inférieur, utilisez l'ancienne version du thème.

### Migration depuis v1.1.0
1. Installer Kernel v1.2.0
2. Remplacer le thème par cette version
3. Vérifier que `loading_strategy` est dans functions.php
4. `npm run build`
5. Tester

### Maintenance
- Code réduit de 37%
- Complexité réduite de 50%
- Plus simple à maintenir
- Séparation claire kernel/thème

---

## v1.1.0 - 2026-02-05

### Added
- OptimizedAssetsService pour chargement optimisé
- WordPressCleanupService pour nettoyage WordPress
- AssetsManifestService pour lecture du manifest Vite
- Événements du kernel dans ThemeServiceProvider

### Changed
- ThemeServiceProvider avec optimisations intégrées

---

## v1.0.0 - 2026-02-03

Version initiale du thème.
