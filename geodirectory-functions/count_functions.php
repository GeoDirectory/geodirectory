<?php
/*-----------------------------------------------------------------------------------*/
/*  Review count functions
/*-----------------------------------------------------------------------------------*/
function geodir_count_reviews_by_term_id($term_id, $taxonomy, $post_type) {

    global $wpdb, $plugin_prefix;
    $detail_table =  $plugin_prefix . $post_type . '_detail';

    $sql =  "SELECT rating_count FROM " . $detail_table . " WHERE post_status = 'publish' AND rating_count > 0 AND FIND_IN_SET(" . $term_id . ", ".$taxonomy.")";
    $rows = $wpdb->get_results($sql);
    $count = 0;
    foreach($rows as $row) {
        $count = $count + (int) $row->rating_count;
    }
    return $count;
}

function geodir_count_reviews_by_terms($force_update=false) {

    $option_data = get_option('geodir_global_review_count');

    if(!$option_data OR $force_update) {
        $post_types = geodir_get_posttypes();
        $term_array = array();
        foreach ($post_types as $post_type) {

            $taxonomy = geodir_get_taxonomies($post_type);
            $taxonomy = $taxonomy[0];

            $args = array(
                'hide_empty' => false
            );

            $terms = get_terms($taxonomy, $args);

            foreach ($terms as $term) {
                $count = geodir_count_reviews_by_term_id($term->term_id, $taxonomy, $post_type);
                $children = get_term_children($term->term_id, $taxonomy);
                if ( is_array( $children ) ) {
                    foreach ( $children as $child_id ) {
                        $child_count = geodir_count_reviews_by_term_id($child_id, $taxonomy, $post_type);
                        $count = $count + $child_count;
                    }
                }
                $term_array[$term->term_id] = $count;
            }
        }
        $data = serialize($term_array);
        update_option('geodir_global_review_count', $data);
        //clear cache
        wp_cache_delete('geodir_global_review_count');
        return $term_array;
    } else {
        $term_array = unserialize($option_data);
        return $term_array;
    }
}

function geodir_term_review_count_force_update() {
    geodir_count_reviews_by_terms(true);
    return true;
}
add_action( 'geodir_update_postrating', 'geodir_term_review_count_force_update', 100);
add_action( 'transition_post_status',  'geodir_term_review_count_force_update', 100 );
add_action( 'created_term',  'geodir_term_review_count_force_update', 100 );
add_action( 'edited_term',  'geodir_term_review_count_force_update', 100 );
add_action( 'delete_term',  'geodir_term_review_count_force_update', 100 );

/*-----------------------------------------------------------------------------------*/
/*  Term count functions
/*-----------------------------------------------------------------------------------*/
function geodir_count_posts_by_term($data, $term) {
    if ($data) {
        if(isset($data[$term->term_id])) {
            return $data[$term->term_id];
        } else {
            return 0;
        }
    } else {
        return $term->count;
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Utils
/*-----------------------------------------------------------------------------------*/
function geodir_sort_by_count($a, $b) {
    return $a['count'] - $b['count'];
}
