<?php

declare(strict_types=1);

namespace CorbiDev\Theme\Services;

/**
 * Service de nettoyage et optimisation WordPress
 *
 * Retire les scripts, styles et balises inutiles du head WordPress
 * pour améliorer les performances et réduire la taille des pages.
 */
final class WordPressCleanupService
{
    /**
     * Nettoie le <head> WordPress des éléments inutiles
     *
     * @return void
     */
    public function cleanHead(): void
    {
        // Retirer les scripts de détection d'emoji
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');

        // Retirer les feeds RSS (si non utilisés)
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'feed_links_extra', 3);

        // Retirer RSD (Really Simple Discovery) pour éditeurs externes
        remove_action('wp_head', 'rsd_link');

        // Retirer Windows Live Writer manifest
        remove_action('wp_head', 'wlwmanifest_link');

        // Retirer le générateur WordPress (sécurité + performance)
        remove_action('wp_head', 'wp_generator');

        // Retirer le shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');

        // Retirer les liens relationnels (prev/next)
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

        // Retirer REST API link (si API non utilisée en frontend)
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');

        // Retirer DNS prefetch vers s.w.org
        remove_action('wp_head', 'wp_resource_hints', 2);
    }

    /**
     * Désactive les scripts WordPress inutiles
     *
     * @return void
     */
    public function disableUnusedScripts(): void
    {
        // Désactiver jQuery Migrate (économie de ~10kb)
        add_filter('wp_default_scripts', function ($scripts): void {
            if (!is_admin() && isset($scripts->registered['jquery'])) {
                $script = $scripts->registered['jquery'];

                if ($script->deps) {
                    $script->deps = array_diff(
                        $script->deps,
                        ['jquery-migrate']
                    );
                }
            }
        });
    }

    /**
     * Désactive les styles Gutenberg inutiles (si Gutenberg non utilisé)
     *
     * @return void
     */
    public function disableGutenbergStyles(): void
    {
        add_action('wp_enqueue_scripts', function (): void {
            // Block library CSS (~50kb)
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');

            // WooCommerce blocks (si WC non utilisé)
            wp_dequeue_style('wc-blocks-style');

            // Global styles
            wp_dequeue_style('global-styles');

            // Classic themes
            wp_dequeue_style('classic-theme-styles');
        }, 100);
    }

    /**
     * Désactive les styles d'emoji
     *
     * @return void
     */
    public function disableEmojiStyles(): void
    {
        add_filter('emoji_svg_url', '__return_false');
    }

    /**
     * Désactive les embed scripts
     *
     * @return void
     */
    public function disableEmbeds(): void
    {
        add_action('wp_footer', function (): void {
            wp_deregister_script('wp-embed');
        });
    }

    /**
     * Optimise les requêtes de chargement des scripts
     *
     * @return void
     */
    public function optimizeScriptLoading(): void
    {
        // Déplacer jQuery dans le footer
        add_action('wp_enqueue_scripts', function (): void {
            if (!is_admin()) {
                wp_scripts()->add_data('jquery', 'group', 1);
                wp_scripts()->add_data('jquery-core', 'group', 1);
            }
        });
    }

    /**
     * Nettoie les classes body inutiles
     *
     * @return void
     */
    public function cleanBodyClasses(): void
    {
        add_filter('body_class', function (array $classes): array {
            // Retirer les classes inutiles
            $remove = [
                'page-template-default',
                'post-template-default',
            ];

            return array_diff($classes, $remove);
        });
    }

    /**
     * Active toutes les optimisations
     *
     * Méthode de convenance pour activer tous les nettoyages
     * d'un seul coup.
     *
     * @return void
     */
    public function enableAllOptimizations(): void
    {
        $this->cleanHead();
        $this->disableUnusedScripts();
        $this->disableGutenbergStyles();
        $this->disableEmojiStyles();
        $this->disableEmbeds();
        $this->optimizeScriptLoading();
        $this->cleanBodyClasses();
    }

    /**
     * Désactive XML-RPC (sécurité + performance)
     *
     * @return void
     */
    public function disableXmlRpc(): void
    {
        add_filter('xmlrpc_enabled', '__return_false');
    }

    /**
     * Limite les révisions WordPress
     *
     * @param int $limit Nombre maximum de révisions
     * @return void
     */
    public function limitRevisions(int $limit = 3): void
    {
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', $limit);
        }
    }

    /**
     * Augmente l'intervalle de vidage automatique de la corbeille
     *
     * @param int $days Nombre de jours avant vidage
     * @return void
     */
    public function setTrashEmptyInterval(int $days = 30): void
    {
        if (!defined('EMPTY_TRASH_DAYS')) {
            define('EMPTY_TRASH_DAYS', $days);
        }
    }
}
