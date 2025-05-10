<?php
declare(strict_types=1);

namespace Canprinto\Admin;

/**
 * Registriert den Custom Post Type »Blueprint«.
 */
final class CptBlueprint
{
    public static function init(): void
    {
        add_action('init', [self::class, 'registerCPT']);
    }

    public static function registerCPT(): void
    {
        register_post_type(
            'cpo_blueprint',
            [
                'labels' => [
                    'name'          => 'Blueprints',
                    'singular_name' => 'Blueprint',
                    'add_new_item'  => 'Neuen Blueprint anlegen',
                    'edit_item'     => 'Blueprint bearbeiten',
                ],
                'public'       => false,
                'show_ui'      => true,
                'menu_icon'    => 'dashicons-media-code',
                'supports'     => ['title'],
            ]
        );
    }
}
