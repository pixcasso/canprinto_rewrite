<?php
declare(strict_types=1);

namespace Canprinto\Admin;

use Canprinto\Blueprint\Loader;

final class BlueprintMetaBox
{
    /** Meta-Key unter dem das JSON gespeichert wird */
    private const META_KEY = '_cpo_blueprint_json';

    public static function init(): void
    {
        add_action('add_meta_boxes',            [self::class, 'addMetaBox']);
        add_action('save_post_cpo_blueprint',   [self::class, 'saveMetaBox']);
        add_action('admin_enqueue_scripts',     [self::class, 'enqueueAssets']);
    }

    /* --------------------------------------------------------------------- */
    public static function addMetaBox(): void
    {
        add_meta_box(
            'cpo_blueprint_json',
            'Blueprint JSON',
            [self::class, 'renderMetaBox'],
            'cpo_blueprint',
            'normal',
            'default'
        );
    }

    public static function renderMetaBox(\WP_Post $post): void
    {
        $value = get_post_meta($post->ID, self::META_KEY, true) ?: '';

        wp_nonce_field('cpo_blueprint_save', 'cpo_blueprint_nonce');
        echo '<textarea id="cpo-blueprint-json" '
           . 'name="cpo_blueprint_json" '
           . 'style="width:100%;height:400px;font-family:monospace;">'
           . esc_textarea($value)
           . '</textarea>';

        echo '<p id="cpo-blueprint-validation" style="color:red;"></p>';
    }

    public static function saveMetaBox(int $postId): void
    {
        if (!isset($_POST['cpo_blueprint_nonce']) ||
            !wp_verify_nonce($_POST['cpo_blueprint_nonce'], 'cpo_blueprint_save')) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        $json = trim($_POST['cpo_blueprint_json'] ?? '');

        // Validierung mit unserem Loader
        try {
            $loader = new Loader(plugin_dir_path(__DIR__, 2) . 'assets/blueprint/schema.json');
            $loader->fromFileContent($json); // Hilfs-Methode s. unten
            update_post_meta($postId, self::META_KEY, $json);
        } catch (\Throwable $e) {
            // Fehler als Admin-Notice puffern
            add_filter('redirect_post_location', static function ($location) use ($e) {
                return add_query_arg('cpo_bp_error', urlencode($e->getMessage()), $location);
            });
        }
    }

    public static function enqueueAssets(string $hook): void
    {
        if ($hook !== 'post.php' && $hook !== 'post-new.php') {
            return;
        }
        if (get_post_type() !== 'cpo_blueprint') {
            return;
        }

        wp_enqueue_script(
            'codemirror',
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js',
            [],
            null
        );
        wp_enqueue_script(
            'codemirror-json',
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js',
            ['codemirror'],
            null
        );
        wp_enqueue_style(
            'codemirror',
            'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css'
        );

        wp_add_inline_script('codemirror-json', "
            document.addEventListener('DOMContentLoaded', function () {
                var textarea = document.getElementById('cpo-blueprint-json');
                if (!textarea) return;
                window.CodeMirror.fromTextArea(textarea, {
                    lineNumbers: true,
                    mode: 'application/json',
                    tabSize: 2
                });
            });
        ");
    }
}

/* -------- kleine Hilfs-Methode im Loader ergänzen -------- */
namespace Canprinto\Blueprint;

final class Loader
{
    // … bestehender Code …

    /** Validiert JSON-String direkt (für Metabox) */
    public function fromFileContent(string $json): Blueprint
    {
        $tmp = tempnam(sys_get_temp_dir(), 'cpo_bp_');
        file_put_contents($tmp, $json);
        $bp = $this->fromFile($tmp);
        unlink($tmp);
        return $bp;
    }
}
