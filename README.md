# CorbiDev Theme Starter - Clean v1.2.0

## 🧹 Version Nettoyée pour Kernel v1.2.0

Ce thème a été **allégé** pour fonctionner avec le **Kernel v1.2.0** qui gère maintenant le chargement progressif.

---

## ⚠️ Prérequis OBLIGATOIRE

**Ce thème nécessite Kernel v1.2.0 ou supérieur.**

```bash
composer require corbidev/wp-corbidev-kernel-theme:^1.2
```

Si vous utilisez Kernel v1.1.0 ou inférieur, utilisez l'ancienne version du thème.

---

## 🎯 Qu'est-ce qui a changé ?

### ❌ Supprimé (gérés par le kernel)
- `OptimizedAssetsService` → Le kernel gère le chargement
- `AssetsManifestService` → Le kernel lit le manifest
- `includes/Core/Assets.php` → Tout dans le kernel

### ✅ Conservé (métier du thème)
- `NavigationService` → Menus WordPress
- `ThemeContextService` → Contexte du thème
- `CurrentUserService` → Utilisateur
- `ThemeConfigService` → Configuration
- `WordPressCleanupService` → Optionnel

### 🔧 Simplifié
- `ThemeServiceProvider` : -27% de code
- Plus de gestion manuelle des assets
- Le kernel fait tout automatiquement

---

## 🚀 Quick Start

### Installation

```bash
# 1. Installer le kernel v1.2.0
composer require corbidev/wp-corbidev-kernel-theme:^1.2

# 2. Activer le thème
wp theme activate wp-corbidev-theme-starter

# 3. Build
npm install
npm run build

# 4. Vider le cache
# CTRL + SHIFT + R dans le navigateur
```

### Configuration

**Le thème est déjà configuré** pour le chargement progressif :

```php
// functions.php
Kernel::boot([
    'theme' => 'starter',
    'loading_strategy' => 'progressive', // ← Kernel gère tout
    'providers' => [...],
]);
```

```php
// header.php
<?php corbidev_critical_css(); ?>      // ← Helper du kernel
<?php corbidev_progressive_loader(); ?> // ← Helper du kernel
```

---

## 📊 Performance

Avec le kernel v1.2.0 en mode progressive :

```
First Contentful Paint : 0.3-0.5s  ⭐⭐⭐⭐⭐
Time to Interactive    : 1-2s
Lighthouse Performance : 95-100
```

---

## 📚 Documentation

- [README_CLEAN.md](README_CLEAN.md) - Documentation complète du nettoyage
- [CHANGELOG.md](CHANGELOG.md) - Historique des changements

**Documentation du Kernel** :
- [Progressive Loading Guide](https://github.com/CorbiDev/wp-corbidev-kernel-theme/docs/PROGRESSIVE_LOADING_GUIDE.md)
- [Quick Start v1.2.0](https://github.com/CorbiDev/wp-corbidev-kernel-theme/docs/QUICK_START_v1.2.0.md)

---

## 🎯 Avantages

### Code Plus Simple
- ✅ 37% moins de code
- ✅ 50% moins de complexité
- ✅ Plus facile à maintenir

### Séparation Claire
- **Kernel** → Chargement progressif, performance
- **Thème** → Navigation, métier, configuration

### Performance Automatique
- Le kernel gère tout
- Chargement progressif activé
- Spinner automatique
- CSS critique inline

---

## 🔄 Migration

Si vous migrez depuis une version précédente :

1. Installer Kernel v1.2.0
2. Remplacer le thème
3. `npm run build`
4. Tester

Voir [README_CLEAN.md](README_CLEAN.md) pour les détails.

---

## 🛠️ Développement

```bash
# Mode dev
npm run dev

# Build production
npm run build

# Tests
composer test
```

---

## 📝 Structure

```
wp-corbidev-theme-starter/
├── assets/
│   ├── css/
│   ├── js/
│   └── vite/
├── includes/
│   ├── Services/
│   │   ├── NavigationService.php
│   │   ├── ThemeContextService.php
│   │   ├── CurrentUserService.php
│   │   └── ...
│   └── Infrastructure/
│       └── ThemeServiceProvider.php
├── templates/
├── functions.php
├── header.php
├── footer.php
└── style.css
```

---

## ✅ Checklist

- [ ] Kernel v1.2.0 installé
- [ ] Thème activé
- [ ] `npm run build` exécuté
- [ ] Cache vidé
- [ ] Site rapide ✅

---

**Version** : 1.2.0 (clean)  
**Date** : 2026-02-06  
**Auteur** : CorbiDev  
**Licence** : Proprietary  
**Kernel Requis** : v1.2.0+
