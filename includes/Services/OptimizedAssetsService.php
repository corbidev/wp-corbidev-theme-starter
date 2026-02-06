<?php

declare(strict_types=1);

namespace CorbiDev\Theme\Services;

/**
 * Service de gestion optimisée des assets Vite
 *
 * Gère le chargement intelligent des assets avec :
 * - Cache du manifest
 * - Preload des ressources critiques
 * - Defer/Async automatique
 * - Support du versioning via hash
 */
final class OptimizedAssetsService
{
    /**
     * Chemin vers le manifest Vite
     */
    private string $manifestPath;

    /**
     * Cache en mémoire du manifest
     */
    private ?array $manifest = null;

    /**
     * URI de base des assets
     */
    private string $baseUri;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->manifestPath = get_template_directory() . '/assets/dist/.vite/manifest.json';
        $this->baseUri = get_template_directory_uri() . '/assets/dist/';
    }

    /**
     * Charge le manifest Vite avec mise en cache
     *
     * @return array<string, mixed>
     */
    private function getManifest(): array
    {
        // Retour du cache mémoire si disponible
        if ($this->manifest !== null) {
            return $this->manifest;
        }

        // Vérification de l'existence du fichier
        if (!file_exists($this->manifestPath)) {
            error_log('[CorbiDev] Manifest Vite not found at: ' . $this->manifestPath);
            return [];
        }

        // Lecture et décodage
        $content = file_get_contents($this->manifestPath);
        $this->manifest = json_decode($content, true) ?: [];

        return $this->manifest;
    }

    /**
     * Enqueue les assets frontend de manière optimisée
     *
     * @return void
     */
    public function enqueueFrontendAssets(): void
    {
        $manifest = $this->getManifest();

        if (empty($manifest)) {
            return;
        }

        $entryKey = 'assets/vite/front.js';

        // CSS Principal (priorité haute)
        if (isset($manifest[$entryKey]['css'])) {
            foreach ($manifest[$entryKey]['css'] as $index => $cssFile) {
                wp_enqueue_style(
                    'corbidev-front-' . $index,
                    $this->baseUri . $cssFile,
                    [],
                    null // Version dans le hash du fichier
                );
            }
        }

        // JavaScript Principal avec defer
        if (isset($manifest[$entryKey]['file'])) {
            wp_enqueue_script(
                'corbidev-front',
                $this->baseUri . $manifest[$entryKey]['file'],
                [],
                null,
                [
                    'strategy' => 'defer',
                    'in_footer' => true,
                ]
            );

            // Marquer comme module ES6
            add_filter('script_loader_tag', function ($tag, $handle) {
                if ($handle === 'corbidev-front') {
                    return str_replace(' src', ' type="module" src', $tag);
                }
                return $tag;
            }, 10, 2);
        }
    }

    /**
     * Enqueue les assets admin
     *
     * @return void
     */
    public function enqueueAdminAssets(): void
    {
        $manifest = $this->getManifest();

        if (empty($manifest)) {
            return;
        }

        $entryKey = 'assets/vite/admin.js';

        // CSS Admin
        if (isset($manifest[$entryKey]['css'])) {
            foreach ($manifest[$entryKey]['css'] as $index => $cssFile) {
                wp_enqueue_style(
                    'corbidev-admin-' . $index,
                    $this->baseUri . $cssFile,
                    [],
                    null
                );
            }
        }

        // JavaScript Admin
        if (isset($manifest[$entryKey]['file'])) {
            wp_enqueue_script(
                'corbidev-admin',
                $this->baseUri . $manifest[$entryKey]['file'],
                [],
                null,
                [
                    'strategy' => 'defer',
                    'in_footer' => true,
                ]
            );
        }
    }

    /**
     * Précharge les ressources critiques dans le <head>
     *
     * Améliore les performances en chargeant les assets critiques
     * avant qu'ils ne soient découverts par le navigateur.
     *
     * @return void
     */
    public function preloadCriticalAssets(): void
    {
        $manifest = $this->getManifest();

        if (empty($manifest)) {
            return;
        }

        $entryKey = 'assets/vite/front.js';

        // Précharger le CSS critique (first CSS file)
        if (isset($manifest[$entryKey]['css'][0])) {
            $cssFile = $manifest[$entryKey]['css'][0];
            printf(
                '<link rel="preload" href="%s" as="style">' . "\n",
                esc_url($this->baseUri . $cssFile)
            );
        }

        // Précharger le JavaScript principal (module)
        if (isset($manifest[$entryKey]['file'])) {
            printf(
                '<link rel="modulepreload" href="%s">' . "\n",
                esc_url($this->baseUri . $manifest[$entryKey]['file'])
            );
        }

        // Précharger les chunks Vue si séparés
        if (isset($manifest['vue-vendor']) && isset($manifest['vue-vendor']['file'])) {
            printf(
                '<link rel="modulepreload" href="%s">' . "\n",
                esc_url($this->baseUri . $manifest['vue-vendor']['file'])
            );
        }
    }

    /**
     * Récupère l'URL d'un asset depuis le manifest
     *
     * Utile pour charger des assets spécifiques (images, fonts, etc.)
     *
     * @param string $path Chemin relatif dans assets/
     * @return string URL complète ou chaîne vide si non trouvé
     */
    public function getAssetUrl(string $path): string
    {
        $manifest = $this->getManifest();

        if (!isset($manifest[$path]['file'])) {
            return '';
        }

        return $this->baseUri . $manifest[$path]['file'];
    }
}
