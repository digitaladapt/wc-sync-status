<?php
/**
 * Plugin Name: Sync Order Status
 * Plugin URI: https://github.com/digitaladapt/wc-sync-status
 * Description: Simple plugin to add "Sync" Order Status to WooCommerce (WordPress).
 * Author: Andrew Stowell
 * Author URI: https://www.digitaladapt.com/
 * Version: 1.0
 * Text Domain: wc-sync-status
 *
 * Copyright: (c) 2021-2021 Andrew Stowell (andrew@digitaladapt.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @author    Andrew Stowell (andrew@digitaladapt.com)
 * @copyright Copyright (c) 2021-2021, Andrew Stowell
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * Plugin Boilerplate
 * @see https://www.skyverge.com/blog/add-custom-code-to-wordpress/
 */

/* exit if accessed directly */
if ( ! defined('ABSPATH')) {
    exit; /* HARD STOP */
}

/* ensure all of our dependencies have been met */
if (function_exists('add_action') && function_exists('add_filter')) {
    /**
     * Register new status
     * @see https://jilt.com/blog/woocommerce-custom-order-status-2/
     */
    function register_sync_order_status()
    {
        /* ensure all of our dependencies have been met */
        if (function_exists('register_post_status') && function_exists('_n_noop')) {
            register_post_status('wc-sync', [
                'label' => 'Sync',
                'public' => true,
                'exclude_from_search' => false,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Sync (%s)', 'Sync (%s)')
            ]);
        }
    }
    add_action('init', 'register_sync_order_status');

    /**
     * Add to list of WC Order statuses
     * @see https://jilt.com/blog/woocommerce-custom-order-status-2/
     * @param iterable $order_statuses List of existing order statuses.
     * @return iterable Updated list of order statuses (we add "Sync", after "Processing").
     */
    function add_sync_to_order_statuses(iterable $order_statuses): iterable
    {
        $new_order_statuses = [];

        foreach ($order_statuses as $key => $status) {
            $new_order_statuses[$key] = $status;

            /* add new order status after processing */
            if ('wc-processing' === $key) {
                $new_order_statuses['wc-sync'] = 'Sync';
            }
        }

        return $new_order_statuses;
    }
    add_filter('wc_order_statuses', 'add_sync_to_order_statuses');
}