<?php
/** Theme functions for Uncrown Portfolio. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'CTP_VERSION', '1.4.4' );

function ctp_theme_setup() {
    load_theme_textdomain( 'uncrown-portfolio', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'responsive-embeds' );
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'uncrown-portfolio' ),
        'footer'  => __( 'Footer Menu', 'uncrown-portfolio' ),
    ) );
}
add_action( 'after_setup_theme', 'ctp_theme_setup' );

function ctp_enqueue_assets() {
    wp_enqueue_style( 'ctp-fonts', 'https://fonts.googleapis.com/css2?family=Instrument+Sans:wdth,wght@75..100,400..700&family=Outfit:wght@300..700&display=swap', array(), null );
    wp_enqueue_style( 'ctp-style', get_stylesheet_uri(), array( 'ctp-fonts' ), CTP_VERSION );
    wp_enqueue_script( 'ctp-scenes', get_template_directory_uri() . '/assets/js/scenes.js', array(), CTP_VERSION, true );
    wp_enqueue_script( 'ctp-main', get_template_directory_uri() . '/assets/js/main.js', array( 'ctp-scenes' ), CTP_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'ctp_enqueue_assets' );

function ctp_register_team_member_cpt() {
    $labels = array(
        'name' => __( 'Team Members', 'uncrown-portfolio' ),
        'singular_name' => __( 'Team Member', 'uncrown-portfolio' ),
        'add_new_item' => __( 'Add New Team Member', 'uncrown-portfolio' ),
        'edit_item' => __( 'Edit Team Member', 'uncrown-portfolio' ),
        'new_item' => __( 'New Team Member', 'uncrown-portfolio' ),
        'view_item' => __( 'View Team Member', 'uncrown-portfolio' ),
        'search_items' => __( 'Search Team Members', 'uncrown-portfolio' ),
        'not_found' => __( 'No team members found.', 'uncrown-portfolio' ),
        'menu_name' => __( 'Team Members', 'uncrown-portfolio' ),
    );
    register_post_type( 'ctp_member', array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-groups',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
        'rewrite' => array( 'slug' => 'team-member' ),
    ) );
}
add_action( 'init', 'ctp_register_team_member_cpt' );

function ctp_add_member_meta_box() {
    add_meta_box( 'ctp_member_details', __( 'Member Details', 'uncrown-portfolio' ), 'ctp_member_meta_box_html', 'ctp_member', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'ctp_add_member_meta_box' );

function ctp_member_meta_box_html( $post ) {
    wp_nonce_field( 'ctp_save_member_details', 'ctp_member_nonce' );
    $role = get_post_meta( $post->ID, '_ctp_member_role', true );
    $skills = get_post_meta( $post->ID, '_ctp_member_skills', true );
    $order = get_post_meta( $post->ID, '_ctp_member_order', true );
    $email = get_post_meta( $post->ID, '_ctp_member_email', true );
    $levels = get_post_meta( $post->ID, '_ctp_member_skill_levels', true );
    ?>
    <p><label for="ctp_member_role"><strong><?php esc_html_e( 'Role / Position', 'uncrown-portfolio' ); ?></strong></label><br><input type="text" id="ctp_member_role" name="ctp_member_role" value="<?php echo esc_attr( $role ); ?>" class="widefat" placeholder="e.g. Project Manager"></p>
    <p><label for="ctp_member_skills"><strong><?php esc_html_e( 'Skills / Specialization', 'uncrown-portfolio' ); ?></strong></label><br><input type="text" id="ctp_member_skills" name="ctp_member_skills" value="<?php echo esc_attr( $skills ); ?>" class="widefat" placeholder="e.g. UI/UX Design, Research, Programming"></p>
    <p><label for="ctp_member_skill_levels"><strong><?php esc_html_e( 'Skill levels for the charts (0–100)', 'uncrown-portfolio' ); ?></strong></label><br><textarea id="ctp_member_skill_levels" name="ctp_member_skill_levels" class="widefat" rows="6" placeholder="Java: 80&#10;HTML: 95&#10;CSS: 90&#10;JavaScript: 75&#10;PHP: 70"><?php echo esc_textarea( $levels ); ?></textarea><span class="description"><?php esc_html_e( 'One skill per line as Name: level. Shown as bars on this member’s profile and combined into the Team skills chart on the homepage. Leave empty to hide.', 'uncrown-portfolio' ); ?></span></p>
    <p><label for="ctp_member_order"><strong><?php esc_html_e( 'Display order', 'uncrown-portfolio' ); ?></strong></label><br><input type="number" min="1" id="ctp_member_order" name="ctp_member_order" value="<?php echo esc_attr( $order ); ?>" style="width:100px"></p>
    <p><label for="ctp_member_email"><strong><?php esc_html_e( 'Contact email (private)', 'uncrown-portfolio' ); ?></strong></label><br><input type="email" id="ctp_member_email" name="ctp_member_email" value="<?php echo esc_attr( $email ); ?>" class="widefat" placeholder="member@example.com"><span class="description"><?php esc_html_e( 'Used only to route contact form messages. This address is never displayed publicly.', 'uncrown-portfolio' ); ?></span></p>
    <p class="description"><?php esc_html_e( 'Use the Featured image box to add this member’s photo. The excerpt is shown as the short profile on the homepage.', 'uncrown-portfolio' ); ?></p>
    <?php
}

function ctp_save_member_details( $post_id ) {
    if ( ! isset( $_POST['ctp_member_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ctp_member_nonce'] ) ), 'ctp_save_member_details' ) ) { return; }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
    if ( get_post_type( $post_id ) !== 'ctp_member' ) { return; }
    $fields = array(
        'ctp_member_role' => '_ctp_member_role',
        'ctp_member_skills' => '_ctp_member_skills',
        'ctp_member_order' => '_ctp_member_order',
        'ctp_member_email' => '_ctp_member_email',
    );
    foreach ( $fields as $input => $meta_key ) {
        if ( isset( $_POST[ $input ] ) && is_scalar( $_POST[ $input ] ) ) {
            if ( 'ctp_member_order' === $input ) {
                $value = absint( $_POST[ $input ] );
            } elseif ( 'ctp_member_email' === $input ) {
                $value = sanitize_email( wp_unslash( $_POST[ $input ] ) );
            } else {
                $value = sanitize_text_field( wp_unslash( $_POST[ $input ] ) );
            }
            update_post_meta( $post_id, $meta_key, $value );
        }
    }
    if ( isset( $_POST['ctp_member_skill_levels'] ) && is_scalar( $_POST['ctp_member_skill_levels'] ) ) {
        $clean = array();
        foreach ( ctp_parse_skill_levels( sanitize_textarea_field( wp_unslash( $_POST['ctp_member_skill_levels'] ) ) ) as $name => $level ) {
            $clean[] = $name . ': ' . $level;
        }
        update_post_meta( $post_id, '_ctp_member_skill_levels', implode( "\n", $clean ) );
    }
}
add_action( 'save_post', 'ctp_save_member_details' );

function ctp_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'ctp_homepage', array( 'title' => __( 'Uncrown Homepage', 'uncrown-portfolio' ), 'priority' => 30 ) );
    $settings = array(
        'ctp_brand_name' => array( 'label' => 'Team name (header, footer and browser tab)', 'default' => 'Uncrown' ),
        'ctp_eyebrow' => array( 'label' => 'Hero label (leave empty to show the team name)', 'default' => '' ),
        'ctp_hero_title' => array( 'label' => 'Hero headline', 'default' => 'Earn every crown.' ),
        'ctp_hero_description' => array( 'label' => 'Hero description', 'default' => 'We are Uncrown, a capstone team that treats every title as something to earn. Meet the people, see the process and explore the work.' ),
        'ctp_project_title' => array( 'label' => 'Work section headline', 'default' => 'Things we have made.' ),
        'ctp_project_description' => array( 'label' => 'Work section description', 'default' => 'Projects, prototypes and research from our capstone journey. Scroll through, or filter by type.' ),
        'ctp_contact_email' => array( 'label' => 'Contact email', 'default' => get_option( 'admin_email' ) ),
    );
    foreach ( $settings as $id => $args ) {
        $wp_customize->add_setting( $id, array( 'default' => $args['default'], 'sanitize_callback' => ( 'ctp_contact_email' === $id ) ? 'sanitize_email' : 'sanitize_text_field' ) );
        $wp_customize->add_control( $id, array( 'label' => $args['label'], 'section' => 'ctp_homepage', 'type' => ( 'ctp_contact_email' === $id ) ? 'email' : 'text', 'description' => ( 'ctp_contact_email' === $id ) ? __( 'Team inbox for contact form inquiries. Individual member addresses are managed under Team Members.', 'uncrown-portfolio' ) : '' ) );
    }

    // Cinematic hero background video.
    $wp_customize->add_setting( 'ctp_hero_video', array( 'default' => '', 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'ctp_hero_video', array(
        'label' => __( 'Hero background video', 'uncrown-portfolio' ),
        'description' => __( 'Optional. Short silent MP4 (5–15 s, under ~8 MB). With the scroll intro on, it plays behind the first scene; otherwise it loops behind the simple hero.', 'uncrown-portfolio' ),
        'section' => 'ctp_homepage',
        'mime_type' => 'video',
    ) ) );
    $wp_customize->add_setting( 'ctp_hero_poster', array( 'default' => '', 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'ctp_hero_poster', array(
        'label' => __( 'Hero video still image', 'uncrown-portfolio' ),
        'description' => __( 'Shown while the video loads, and instead of it for visitors who reduce motion or save data.', 'uncrown-portfolio' ),
        'section' => 'ctp_homepage',
        'mime_type' => 'image',
    ) ) );
    // Cinematic scroll story.
    // Social links (leave empty to hide).
    $wp_customize->add_section( 'ctp_social', array( 'title' => __( 'Uncrown Social Links', 'uncrown-portfolio' ), 'priority' => 31, 'description' => __( 'Paste the full address of each profile. Empty ones are hidden.', 'uncrown-portfolio' ) ) );
    foreach ( ctp_social_networks() as $key => $net ) {
        $wp_customize->add_setting( 'ctp_social_' . $key, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( 'ctp_social_' . $key, array( 'label' => $net['label'], 'section' => 'ctp_social', 'type' => 'url', 'input_attrs' => array( 'placeholder' => $net['placeholder'] ) ) );
    }
    $wp_customize->add_setting( 'ctp_story_off', array( 'default' => false, 'sanitize_callback' => 'rest_sanitize_boolean' ) );
    $wp_customize->add_control( 'ctp_story_off', array( 'label' => __( 'Turn off the cinematic scroll intro (show the simple hero instead)', 'uncrown-portfolio' ), 'section' => 'ctp_homepage', 'type' => 'checkbox' ) );
    foreach ( ctp_story_chapters( true ) as $n => $chapter ) {
        $wp_customize->add_setting( 'ctp_story_line_' . ( $n + 1 ), array( 'default' => $chapter['line'], 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( 'ctp_story_line_' . ( $n + 1 ), array( 'label' => sprintf( __( 'Scroll intro — scene %d headline', 'uncrown-portfolio' ), $n + 1 ), 'section' => 'ctp_homepage', 'type' => 'text' ) );
    }
    $wp_customize->add_setting( 'ctp_hero_video_off', array( 'default' => false, 'sanitize_callback' => 'rest_sanitize_boolean' ) );
    $wp_customize->add_control( 'ctp_hero_video_off', array( 'label' => __( 'Turn off the hero video (use the animated signal lines only)', 'uncrown-portfolio' ), 'section' => 'ctp_homepage', 'type' => 'checkbox' ) );
}
add_action( 'customize_register', 'ctp_customize_register' );

function ctp_get_option( $key, $default = '' ) {
    return get_theme_mod( $key, $default );
}

function ctp_default_content_setup() {
    if ( get_option( 'ctp_default_content_created' ) ) { return; }
    $pages = array(
        'Home' => array( 'slug' => 'home', 'content' => '' ),
        'About Our Team' => array( 'slug' => 'about-our-team', 'content' => '<!-- wp:paragraph --><p>Introduce your team, your academic program, and the shared goals that brought you together.</p><!-- /wp:paragraph -->' ),
        'Our Capstone Project' => array( 'slug' => 'our-capstone-project', 'content' => '<!-- wp:paragraph --><p>Describe your capstone project, its problem statement, objectives, scope, methodology, and expected outcomes.</p><!-- /wp:paragraph -->' ),
        'Contact' => array( 'slug' => 'contact', 'content' => '<!-- wp:paragraph --><p>Add your team’s official contact information here.</p><!-- /wp:paragraph -->' ),
    );
    $home_id = 0;
    foreach ( $pages as $title => $data ) {
        $existing = get_page_by_path( $data['slug'] );
        if ( $existing ) { $page_id = $existing->ID; }
        else {
            $page_id = wp_insert_post( array( 'post_title' => $title, 'post_name' => $data['slug'], 'post_content' => $data['content'], 'post_status' => 'publish', 'post_type' => 'page' ) );
        }
        if ( 'Home' === $title && ! is_wp_error( $page_id ) ) { $home_id = (int) $page_id; }
    }
    if ( $home_id ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $home_id );
    }
    $sample_members = array(
        array( 'Member 1', 'Team Leader / Project Manager', 1 ),
        array( 'Member 2', 'Research and Documentation', 2 ),
        array( 'Member 3', 'Design and Development', 3 ),
        array( 'Member 4', 'Testing and Quality Assurance', 4 ),
    );
    foreach ( $sample_members as $member ) {
        $existing = get_page_by_title( $member[0], OBJECT, 'ctp_member' );
        if ( ! $existing ) {
            $member_id = wp_insert_post( array( 'post_type' => 'ctp_member', 'post_status' => 'publish', 'post_title' => $member[0], 'post_excerpt' => 'Replace this text with a short introduction describing this team member’s background and contribution.' ) );
            if ( $member_id && ! is_wp_error( $member_id ) ) {
                update_post_meta( $member_id, '_ctp_member_role', $member[1] );
                update_post_meta( $member_id, '_ctp_member_order', $member[2] );
            }
        }
    }
    update_option( 'ctp_default_content_created', 1 );
}
add_action( 'after_switch_theme', 'ctp_default_content_setup' );

function ctp_member_query() {
    return new WP_Query( array(
        'post_type' => 'ctp_member',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_key' => '_ctp_member_order',
        'orderby' => array( 'meta_value_num' => 'ASC', 'date' => 'ASC' ),
    ) );
}

/** Register the portfolio project content type and its filterable categories. */
function ctp_register_project_content() {
    register_post_type( 'ctp_project', array(
        'labels' => array(
            'name' => __( 'Projects', 'uncrown-portfolio' ),
            'singular_name' => __( 'Project', 'uncrown-portfolio' ),
            'add_new_item' => __( 'Add New Project', 'uncrown-portfolio' ),
            'edit_item' => __( 'Edit Project', 'uncrown-portfolio' ),
            'view_item' => __( 'View Project', 'uncrown-portfolio' ),
            'search_items' => __( 'Search Projects', 'uncrown-portfolio' ),
            'not_found' => __( 'No projects found.', 'uncrown-portfolio' ),
            'menu_name' => __( 'Projects', 'uncrown-portfolio' ),
        ),
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
        'rewrite' => array( 'slug' => 'projects' ),
    ) );

    register_taxonomy( 'ctp_project_category', 'ctp_project', array(
        'labels' => array(
            'name' => __( 'Project Categories', 'uncrown-portfolio' ),
            'singular_name' => __( 'Project Category', 'uncrown-portfolio' ),
            'add_new_item' => __( 'Add Project Category', 'uncrown-portfolio' ),
        ),
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => array( 'slug' => 'project-category' ),
    ) );
}
add_action( 'init', 'ctp_register_project_content', 11 );

/** Add project details used by the project cards and single-project page. */
function ctp_add_project_meta_box() {
    add_meta_box( 'ctp_project_details', __( 'Project Details', 'uncrown-portfolio' ), 'ctp_project_meta_box_html', 'ctp_project', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'ctp_add_project_meta_box' );

function ctp_project_meta_box_html( $post ) {
    wp_nonce_field( 'ctp_save_project_details', 'ctp_project_nonce' );
    $year = get_post_meta( $post->ID, '_ctp_project_year', true );
    $url = get_post_meta( $post->ID, '_ctp_project_url', true );
    $video = get_post_meta( $post->ID, '_ctp_project_video', true );
    $stack = get_post_meta( $post->ID, '_ctp_project_stack', true );
    $features = get_post_meta( $post->ID, '_ctp_project_features', true );
    ?>
    <p><label for="ctp_project_year"><strong><?php esc_html_e( 'Year', 'uncrown-portfolio' ); ?></strong></label><br><input class="widefat" type="text" id="ctp_project_year" name="ctp_project_year" value="<?php echo esc_attr( $year ); ?>" placeholder="2026"></p>
    <p><label for="ctp_project_url"><strong><?php esc_html_e( 'External project link (optional)', 'uncrown-portfolio' ); ?></strong></label><br><input class="widefat" type="url" id="ctp_project_url" name="ctp_project_url" value="<?php echo esc_url( $url ); ?>" placeholder="https://"></p>
    <hr>
    <p class="description"><?php esc_html_e( 'App screens: add up to three short, silent MP4 recordings. Each one appears in a matching device frame (browser, tablet, phone) on the project page. For the labels, write "Title: one-sentence description".', 'uncrown-portfolio' ); ?></p>
    <?php foreach ( ctp_project_platform_slots() as $slot => $info ) : $v = get_post_meta( $post->ID, '_' . $info['video_key'], true ); $l = get_post_meta( $post->ID, '_' . $info['label_key'], true ); ?>
    <p><label for="<?php echo esc_attr( $info['video_key'] ); ?>"><strong><?php echo esc_html( $info['name'] ); ?></strong></label><br>
      <input class="widefat" type="url" id="<?php echo esc_attr( $info['video_key'] ); ?>" name="<?php echo esc_attr( $info['video_key'] ); ?>" value="<?php echo esc_url( $v ); ?>" placeholder="https://…/video.mp4">
      <button type="button" class="button ctp-pick-video" data-target="<?php echo esc_attr( $info['video_key'] ); ?>" style="margin:6px 0"><?php esc_html_e( 'Choose from Media Library', 'uncrown-portfolio' ); ?></button>
      <input class="widefat" type="text" name="<?php echo esc_attr( $info['label_key'] ); ?>" value="<?php echo esc_attr( $l ); ?>" placeholder="<?php echo esc_attr( $info['placeholder'] ); ?>"></p>
    <?php endforeach; ?>
    <p><label for="ctp_project_stack"><strong><?php esc_html_e( 'Built with', 'uncrown-portfolio' ); ?></strong></label><br><input class="widefat" type="text" id="ctp_project_stack" name="ctp_project_stack" value="<?php echo esc_attr( $stack ); ?>" placeholder="PHP, Laravel, MySQL, JavaScript"><span class="description"><?php esc_html_e( 'Comma-separated.', 'uncrown-portfolio' ); ?></span></p>
    <p><label for="ctp_project_features"><strong><?php esc_html_e( 'Key features', 'uncrown-portfolio' ); ?></strong></label><br><textarea class="widefat" rows="7" id="ctp_project_features" name="ctp_project_features" placeholder="Point of sale&#10;Inventory management&#10;Delivery tracking"><?php echo esc_textarea( $features ); ?></textarea><span class="description"><?php esc_html_e( 'One per line. Write "Title: short explanation" to add a description under the title.', 'uncrown-portfolio' ); ?></span></p>
    <?php
}

function ctp_save_project_details( $post_id ) {
    if ( ! isset( $_POST['ctp_project_nonce'] ) || ! is_scalar( $_POST['ctp_project_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ctp_project_nonce'] ) ), 'ctp_save_project_details' ) ) { return; }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) || 'ctp_project' !== get_post_type( $post_id ) ) { return; }
    if ( isset( $_POST['ctp_project_year'] ) && is_scalar( $_POST['ctp_project_year'] ) ) {
        update_post_meta( $post_id, '_ctp_project_year', sanitize_text_field( wp_unslash( $_POST['ctp_project_year'] ) ) );
    }
    if ( isset( $_POST['ctp_project_url'] ) && is_scalar( $_POST['ctp_project_url'] ) ) {
        update_post_meta( $post_id, '_ctp_project_url', esc_url_raw( wp_unslash( $_POST['ctp_project_url'] ) ) );
    }
    foreach ( ctp_project_platform_slots() as $info ) {
        if ( isset( $_POST[ $info['video_key'] ] ) && is_scalar( $_POST[ $info['video_key'] ] ) ) {
            update_post_meta( $post_id, '_' . $info['video_key'], esc_url_raw( wp_unslash( $_POST[ $info['video_key'] ] ) ) );
        }
        if ( isset( $_POST[ $info['label_key'] ] ) && is_scalar( $_POST[ $info['label_key'] ] ) ) {
            update_post_meta( $post_id, '_' . $info['label_key'], sanitize_text_field( wp_unslash( $_POST[ $info['label_key'] ] ) ) );
        }
    }
    if ( isset( $_POST['ctp_project_stack'] ) && is_scalar( $_POST['ctp_project_stack'] ) ) {
        update_post_meta( $post_id, '_ctp_project_stack', sanitize_text_field( wp_unslash( $_POST['ctp_project_stack'] ) ) );
    }
    if ( isset( $_POST['ctp_project_features'] ) && is_scalar( $_POST['ctp_project_features'] ) ) {
        update_post_meta( $post_id, '_ctp_project_features', sanitize_textarea_field( wp_unslash( $_POST['ctp_project_features'] ) ) );
    }
}

/** Media Library picker for the project demo video. */
function ctp_project_admin_scripts( $hook ) {
    if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && 'ctp_project' === get_post_type() ) {
        wp_enqueue_media();
        wp_enqueue_script( 'ctp-admin-media', get_template_directory_uri() . '/assets/js/admin-media.js', array( 'jquery' ), CTP_VERSION, true );
    }
}
add_action( 'admin_enqueue_scripts', 'ctp_project_admin_scripts' );

/** The three app-screen slots a project can show, each with its own device frame. */
function ctp_project_platform_slots() {
    return array(
        'web' => array( 'name' => __( 'Web app video (browser frame)', 'uncrown-portfolio' ), 'video_key' => 'ctp_project_video', 'label_key' => 'ctp_project_video_label', 'placeholder' => __( 'Web dashboard: Owners track sales, stock and reports in real time.', 'uncrown-portfolio' ), 'device' => 'browser' ),
        'tablet' => array( 'name' => __( 'Tablet app video (tablet frame)', 'uncrown-portfolio' ), 'video_key' => 'ctp_project_tablet', 'label_key' => 'ctp_project_tablet_label', 'placeholder' => __( 'POS tablet app: Cashiers ring up sales and take cash, QR Ph or card.', 'uncrown-portfolio' ), 'device' => 'tablet' ),
        'phone' => array( 'name' => __( 'Phone app video (phone frame)', 'uncrown-portfolio' ), 'video_key' => 'ctp_project_phone', 'label_key' => 'ctp_project_phone_label', 'placeholder' => __( 'Delivery app: Drivers follow the route and capture proof of delivery.', 'uncrown-portfolio' ), 'device' => 'phone' ),
    );
}

/** Platforms that have a video, with title and description split from "Title: description". */
function ctp_project_platforms( $post_id ) {
    $out = array();
    foreach ( ctp_project_platform_slots() as $slot => $info ) {
        $video = esc_url( get_post_meta( $post_id, '_' . $info['video_key'], true ) );
        if ( ! $video ) { continue; }
        $label = (string) get_post_meta( $post_id, '_' . $info['label_key'], true );
        $parts = preg_split( '/\s*:\s*/', $label, 2 );
        $defaults = array( 'web' => __( 'Web app', 'uncrown-portfolio' ), 'tablet' => __( 'Tablet app', 'uncrown-portfolio' ), 'phone' => __( 'Mobile app', 'uncrown-portfolio' ) );
        $out[ $slot ] = array( 'video' => $video, 'device' => $info['device'], 'title' => '' !== trim( $parts[0] ) ? $parts[0] : $defaults[ $slot ], 'text' => isset( $parts[1] ) ? $parts[1] : '' );
    }
    return $out;
}

/** Project helpers: main demo video URL, tech stack list and key features (title => description). */
function ctp_project_video( $post_id ) {
    $platforms = ctp_project_platforms( $post_id );
    return $platforms ? reset( $platforms )['video'] : '';
}
function ctp_project_stack( $post_id ) {
    return array_values( array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $post_id, '_ctp_project_stack', true ) ) ) ) );
}
function ctp_project_features( $post_id ) {
    $out = array();
    foreach ( preg_split( '/\r\n|\r|\n/', (string) get_post_meta( $post_id, '_ctp_project_features', true ) ) as $line ) {
        $line = trim( $line, " \t-•*" );
        if ( '' === $line ) { continue; }
        $parts = preg_split( '/\s*:\s*/', $line, 2 );
        $out[] = array( 'title' => $parts[0], 'text' => isset( $parts[1] ) ? $parts[1] : '' );
    }
    return $out;
}
add_action( 'save_post', 'ctp_save_project_details' );

/** Resolve the team inbox, falling back to the WordPress administrator email. */
function ctp_get_team_contact_email() {
    $email = sanitize_email( get_theme_mod( 'ctp_contact_email', get_option( 'admin_email' ) ) );
    if ( ! is_email( $email ) ) {
        $email = sanitize_email( get_option( 'admin_email' ) );
    }
    return is_email( $email ) ? $email : '';
}

/** Route individual inquiries to a published member; otherwise use the team inbox. */
function ctp_get_contact_recipient( $contact_type, $member_id = 0 ) {
    $team_email = ctp_get_team_contact_email();
    if ( 'individual' !== sanitize_text_field( $contact_type ) ) {
        return $team_email;
    }

    $member_id = absint( $member_id );
    $member = $member_id ? get_post( $member_id ) : null;
    if ( $member && isset( $member->post_type, $member->post_status ) && 'ctp_member' === $member->post_type && 'publish' === $member->post_status ) {
        $member_email = sanitize_email( get_post_meta( $member_id, '_ctp_member_email', true ) );
        if ( is_email( $member_email ) ) {
            return $member_email;
        }
    }

    return $team_email;
}

/** Return the editable Contact page URL, or a stable fallback URL. */
function ctp_get_contact_page_url() {
    $contact_page = get_page_by_path( 'contact' );
    return $contact_page ? get_permalink( $contact_page->ID ) : home_url( '/contact/' );
}

/** Render the contact form. Email recipients are resolved only on the server. */
function ctp_render_contact_form() {
    $members = get_posts( array(
        'post_type' => 'ctp_member',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_key' => '_ctp_member_order',
        'orderby' => array( 'meta_value_num' => 'ASC', 'title' => 'ASC' ),
    ) );
    $selected_member = ( isset( $_GET['member'] ) && is_scalar( $_GET['member'] ) ) ? absint( wp_unslash( $_GET['member'] ) ) : 0;
    $status = ( isset( $_GET['ctp_contact'] ) && is_scalar( $_GET['ctp_contact'] ) ) ? sanitize_text_field( wp_unslash( $_GET['ctp_contact'] ) ) : '';
    ob_start();
    ?>
    <div class="contact-form-wrap" id="contact-form">
        <?php if ( 'sent' === $status ) : ?><div class="form-notice form-notice-success" role="status"><?php esc_html_e( 'Your message has been sent. Thank you for getting in touch!', 'uncrown-portfolio' ); ?></div><?php elseif ( 'error' === $status ) : ?><div class="form-notice form-notice-error" role="alert"><?php esc_html_e( 'We could not send your message. Please check the fields and try again.', 'uncrown-portfolio' ); ?></div><?php endif; ?>
        <form class="ctp-contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="ctp_submit_contact">
            <?php wp_nonce_field( 'ctp_submit_contact', 'ctp_contact_nonce' ); ?>
            <div class="form-field">
                <label for="ctp_contact_type"><?php esc_html_e( 'Who would you like to contact?', 'uncrown-portfolio' ); ?></label>
                <select id="ctp_contact_type" name="contact_type" required>
                    <option value="team"><?php esc_html_e( 'Contact the Team', 'uncrown-portfolio' ); ?></option>
                    <option value="individual" <?php selected( $selected_member > 0 ); ?>><?php esc_html_e( 'Contact an Individual Member', 'uncrown-portfolio' ); ?></option>
                </select>
            </div>
            <div class="form-field contact-member-field" <?php echo $selected_member ? '' : 'hidden'; ?>>
                <label for="ctp_member_id"><?php esc_html_e( 'Select a team member', 'uncrown-portfolio' ); ?></label>
                <select id="ctp_member_id" name="member_id" <?php echo $selected_member ? 'required' : ''; ?>>
                    <option value=""><?php esc_html_e( 'Choose a member', 'uncrown-portfolio' ); ?></option>
                    <?php foreach ( $members as $member ) : ?>
                        <option value="<?php echo esc_attr( $member->ID ); ?>" <?php selected( $selected_member, $member->ID ); ?>><?php echo esc_html( get_the_title( $member ) ); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ( empty( $members ) ) : ?><small><?php esc_html_e( 'No published team members are available yet. You can still contact the team.', 'uncrown-portfolio' ); ?></small><?php endif; ?>
            </div>
            <div class="form-grid">
                <div class="form-field"><label for="ctp_contact_name"><?php esc_html_e( 'Your name', 'uncrown-portfolio' ); ?></label><input id="ctp_contact_name" name="contact_name" type="text" autocomplete="name" maxlength="120" required></div>
                <div class="form-field"><label for="ctp_contact_email"><?php esc_html_e( 'Email address', 'uncrown-portfolio' ); ?></label><input id="ctp_contact_email" name="contact_email" type="email" autocomplete="email" maxlength="190" required></div>
            </div>
            <div class="form-field"><label for="ctp_contact_subject"><?php esc_html_e( 'Subject', 'uncrown-portfolio' ); ?></label><input id="ctp_contact_subject" name="contact_subject" type="text" maxlength="180" required></div>
            <div class="form-field"><label for="ctp_contact_message"><?php esc_html_e( 'Message', 'uncrown-portfolio' ); ?></label><textarea id="ctp_contact_message" name="contact_message" rows="6" maxlength="5000" required></textarea></div>
            <div class="ctp-honeypot" aria-hidden="true"><label for="ctp_website"><?php esc_html_e( 'Leave this field empty', 'uncrown-portfolio' ); ?></label><input id="ctp_website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
            <button class="button button-primary" type="submit"><?php esc_html_e( 'Send message', 'uncrown-portfolio' ); ?><span aria-hidden="true">→</span></button>
            <p class="form-privacy-note"><?php esc_html_e( 'Your email address is used only to respond to your inquiry. Team member email addresses are kept private.', 'uncrown-portfolio' ); ?></p>
        </form>
    </div>
    <?php
    return ob_get_clean();
}

function ctp_contact_redirect( $status ) {
    $redirect = wp_get_referer();
    if ( ! $redirect ) {
        $redirect = ctp_get_contact_page_url();
    }
    $redirect = remove_query_arg( 'ctp_contact', $redirect );
    wp_safe_redirect( add_query_arg( 'ctp_contact', $status, $redirect ) . '#contact-form' );
    exit;
}

/** Validate and send contact submissions using WordPress mail configuration. */
function ctp_handle_contact_submission() {
    if ( 'POST' !== strtoupper( isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : '' ) ) {
        ctp_contact_redirect( 'error' );
    }
    if ( ! isset( $_POST['ctp_contact_nonce'] ) || ! is_scalar( $_POST['ctp_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ctp_contact_nonce'] ) ), 'ctp_submit_contact' ) ) {
        ctp_contact_redirect( 'error' );
    }

    $honeypot = ( isset( $_POST['website'] ) && is_scalar( $_POST['website'] ) ) ? sanitize_text_field( wp_unslash( $_POST['website'] ) ) : '';
    if ( '' !== $honeypot ) {
        ctp_contact_redirect( 'sent' );
    }

    $contact_type = ( isset( $_POST['contact_type'] ) && is_scalar( $_POST['contact_type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['contact_type'] ) ) : 'team';
    $member_id = ( isset( $_POST['member_id'] ) && is_scalar( $_POST['member_id'] ) ) ? absint( wp_unslash( $_POST['member_id'] ) ) : 0;
    $name = ( isset( $_POST['contact_name'] ) && is_scalar( $_POST['contact_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
    $email = ( isset( $_POST['contact_email'] ) && is_scalar( $_POST['contact_email'] ) ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
    $subject = ( isset( $_POST['contact_subject'] ) && is_scalar( $_POST['contact_subject'] ) ) ? sanitize_text_field( wp_unslash( $_POST['contact_subject'] ) ) : '';
    $message = ( isset( $_POST['contact_message'] ) && is_scalar( $_POST['contact_message'] ) ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';

    if ( ! in_array( $contact_type, array( 'team', 'individual' ), true ) || '' === $name || strlen( $name ) > 120 || ! is_email( $email ) || strlen( $email ) > 190 || '' === $subject || strlen( $subject ) > 180 || '' === $message || strlen( $message ) > 5000 ) {
        ctp_contact_redirect( 'error' );
    }
    if ( 'individual' === $contact_type ) {
        $member = $member_id ? get_post( $member_id ) : null;
        if ( ! $member || 'ctp_member' !== $member->post_type || 'publish' !== $member->post_status ) {
            ctp_contact_redirect( 'error' );
        }
    }

    $recipient = ctp_get_contact_recipient( $contact_type, $member_id );
    if ( ! is_email( $recipient ) ) {
        ctp_contact_redirect( 'error' );
    }

    $member_name = ( 'individual' === $contact_type ) ? get_the_title( $member_id ) : __( 'Entire team', 'uncrown-portfolio' );
    $body = sprintf(
        "New portfolio inquiry\n\nTo: %s\nFrom: %s\nEmail: %s\nSubject: %s\n\nMessage:\n%s\n",
        $member_name,
        $name,
        $email,
        $subject,
        $message
    );
    $sent = wp_mail( $recipient, '[Portfolio Contact] ' . $subject, $body, array( 'Reply-To: ' . $email ) );
    ctp_contact_redirect( $sent ? 'sent' : 'error' );
}
add_action( 'admin_post_nopriv_ctp_submit_contact', 'ctp_handle_contact_submission' );
add_action( 'admin_post_ctp_submit_contact', 'ctp_handle_contact_submission' );

/** Dashboard overview for editors and administrators. */
function ctp_register_dashboard_widget() {
    if ( current_user_can( 'edit_posts' ) ) {
        wp_add_dashboard_widget( 'ctp_portfolio_overview', __( 'Portfolio Overview', 'uncrown-portfolio' ), 'ctp_dashboard_widget_html' );
    }
}
add_action( 'wp_dashboard_setup', 'ctp_register_dashboard_widget' );

function ctp_dashboard_widget_html() {
    $member_counts = wp_count_posts( 'ctp_member' );
    $project_counts = wp_count_posts( 'ctp_project' );
    $members = isset( $member_counts->publish ) ? (int) $member_counts->publish : 0;
    $projects = isset( $project_counts->publish ) ? (int) $project_counts->publish : 0;
    ?>
    <div class="ctp-admin-overview">
        <div><strong><?php echo esc_html( number_format_i18n( $members ) ); ?></strong><span><?php esc_html_e( 'Published team members', 'uncrown-portfolio' ); ?></span></div>
        <div><strong><?php echo esc_html( number_format_i18n( $projects ) ); ?></strong><span><?php esc_html_e( 'Published projects', 'uncrown-portfolio' ); ?></span></div>
    </div>
    <p class="ctp-admin-actions"><a class="button button-primary" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=ctp_member' ) ); ?>"><?php esc_html_e( 'Add team member', 'uncrown-portfolio' ); ?></a> <a class="button" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=ctp_project' ) ); ?>"><?php esc_html_e( 'Add project', 'uncrown-portfolio' ); ?></a></p>
    <p><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=ctp_homepage' ) ); ?>"><?php esc_html_e( 'Edit homepage and team inbox settings', 'uncrown-portfolio' ); ?> →</a></p>
    <?php
}

function ctp_admin_enqueue_assets( $hook ) {
    if ( 'index.php' === $hook || 'edit.php' === $hook || 'post.php' === $hook || 'post-new.php' === $hook ) {
        wp_enqueue_style( 'ctp-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), CTP_VERSION );
    }
}
add_action( 'admin_enqueue_scripts', 'ctp_admin_enqueue_assets' );

/** Add useful content columns without exposing private member emails publicly. */
function ctp_member_admin_columns( $columns ) {
    $updated = array();
    foreach ( $columns as $key => $label ) {
        $updated[ $key ] = $label;
        if ( 'title' === $key ) {
            $updated['ctp_member_role'] = __( 'Role', 'uncrown-portfolio' );
            $updated['ctp_member_email'] = __( 'Private contact email', 'uncrown-portfolio' );
        }
    }
    return $updated;
}
add_filter( 'manage_ctp_member_posts_columns', 'ctp_member_admin_columns' );

function ctp_member_admin_column_content( $column, $post_id ) {
    if ( 'ctp_member_role' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_ctp_member_role', true ) ?: '—' );
    } elseif ( 'ctp_member_email' === $column ) {
        $email = get_post_meta( $post_id, '_ctp_member_email', true );
        echo $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '<span class="description">' . esc_html__( 'Team inbox fallback', 'uncrown-portfolio' ) . '</span>';
    }
}
add_action( 'manage_ctp_member_posts_custom_column', 'ctp_member_admin_column_content', 10, 2 );

function ctp_project_admin_columns( $columns ) {
    $updated = array();
    foreach ( $columns as $key => $label ) {
        $updated[ $key ] = $label;
        if ( 'title' === $key ) {
            $updated['ctp_project_year'] = __( 'Year', 'uncrown-portfolio' );
            $updated['ctp_project_categories'] = __( 'Categories', 'uncrown-portfolio' );
        }
    }
    return $updated;
}
add_filter( 'manage_ctp_project_posts_columns', 'ctp_project_admin_columns' );

function ctp_project_admin_column_content( $column, $post_id ) {
    if ( 'ctp_project_year' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_ctp_project_year', true ) ?: '—' );
    } elseif ( 'ctp_project_categories' === $column ) {
        $terms = get_the_term_list( $post_id, 'ctp_project_category', '', ', ' );
        echo $terms ? wp_kses_post( $terms ) : '—';
    }
}
add_action( 'manage_ctp_project_posts_custom_column', 'ctp_project_admin_column_content', 10, 2 );

/** Count only real content for the homepage statistics. */
function ctp_get_portfolio_stats() {
    $member_counts = wp_count_posts( 'ctp_member' );
    $project_counts = wp_count_posts( 'ctp_project' );
    $member_ids = get_posts( array( 'post_type' => 'ctp_member', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids' ) );
    // Unique skills across the team, from both the skill tags and the skill levels.
    $unique = array();
    foreach ( $member_ids as $member_id ) {
        $skills = get_post_meta( $member_id, '_ctp_member_skills', true );
        foreach ( array_filter( array_map( 'trim', explode( ',', (string) $skills ) ) ) as $skill ) { $unique[ strtolower( $skill ) ] = true; }
        foreach ( array_keys( ctp_member_skill_levels( $member_id ) ) as $skill ) { $unique[ strtolower( $skill ) ] = true; }
    }
    $skill_count = count( $unique );
    return array(
        'members' => isset( $member_counts->publish ) ? (int) $member_counts->publish : 0,
        'projects' => isset( $project_counts->publish ) ? (int) $project_counts->publish : 0,
        'skills' => $skill_count,
    );
}

/** Rewrites are flushed by WordPress on theme activation; project URLs remain stable afterwards. */

add_shortcode( 'ctp_contact_form', 'ctp_render_contact_form' );

/** Preconnect to Google Fonts for faster type loading. */
function ctp_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'ctp_resource_hints', 10, 2 );

/* Uncrown is a dark-only theme: the logo is designed for a black stage. */

/** Wrap each word of a heading in spans so it can animate in word by word. */
function ctp_split_words( $text, $accent_last = true ) {
    $words = preg_split( '/\s+/', trim( (string) $text ) );
    $out = array();
    $last = count( $words ) - 1;
    foreach ( $words as $i => $word ) {
        $class = ( $accent_last && $i === $last && $last > 0 ) ? 'word is-accent' : 'word';
        $out[] = '<span class="' . $class . '" style="--i:' . (int) $i . '"><span class="word-inner">' . esc_html( $word ) . '</span></span>';
    }
    return implode( ' ', $out );
}

/** Unique skills across all published members, for the homepage ticker. */
function ctp_get_all_skills() {
    $ids = get_posts( array( 'post_type' => 'ctp_member', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids' ) );
    $all = array();
    foreach ( $ids as $id ) {
        $skills = get_post_meta( $id, '_ctp_member_skills', true );
        if ( ! $skills ) { continue; }
        foreach ( array_filter( array_map( 'trim', explode( ',', $skills ) ) ) as $skill ) {
            $all[ strtolower( $skill ) ] = $skill;
        }
    }
    return array_values( $all );
}


/** Team name shown in the header, footer, page labels and browser tab. */
function ctp_brand_name() {
    $name = trim( (string) ctp_get_option( 'ctp_brand_name', 'Uncrown' ) );
    return '' !== $name ? $name : 'Uncrown';
}

/** Small label above the homepage headline; replaces the old default with the team name. */
function ctp_hero_eyebrow() {
    $eyebrow = trim( (string) ctp_get_option( 'ctp_eyebrow', '' ) );
    if ( '' === $eyebrow ) {
        return strtoupper( ctp_brand_name() );
    }
    return $eyebrow;
}

/** Use the team name in the browser tab title instead of the WordPress Site Title. */
function ctp_document_title_parts( $parts ) {
    if ( isset( $parts['site'] ) ) { $parts['site'] = ctp_brand_name(); }
    if ( ( is_front_page() || is_home() ) && isset( $parts['title'] ) && $parts['title'] === get_bloginfo( 'name' ) ) { $parts['title'] = ctp_brand_name(); }
    return $parts;
}
add_filter( 'document_title_parts', 'ctp_document_title_parts' );


/** Hero video sources: Customizer upload first, then the clip bundled with the theme. */
function ctp_hero_video() {
    if ( ctp_get_option( 'ctp_hero_video_off', false ) ) { return null; }
    $video = '';
    $poster = '';
    $video_id = absint( ctp_get_option( 'ctp_hero_video', 0 ) );
    $poster_id = absint( ctp_get_option( 'ctp_hero_poster', 0 ) );
    if ( $video_id ) { $video = wp_get_attachment_url( $video_id ); }
    if ( $poster_id ) { $poster = wp_get_attachment_image_url( $poster_id, 'full' ); }
    if ( ! $poster && file_exists( get_theme_file_path( '/assets/images/story-poster.jpg' ) ) ) {
        $poster = get_theme_file_uri( '/assets/images/story-poster.jpg' );
    }
    if ( ! $video && ! $poster ) { return null; }
    return array( 'video' => $video, 'poster' => $poster );
}


/** Scenes for the cinematic scroll intro. Headlines are editable in the Customizer. */
function ctp_story_chapters( $defaults_only = false ) {
    $chapters = array(
        array( 'kicker' => sprintf( __( 'Capstone %s', 'uncrown-portfolio' ), gmdate( 'Y' ) ), 'line' => __( 'No titles. Only the work.', 'uncrown-portfolio' ), 'sub' => __( 'A capstone team that earns its place one project at a time.', 'uncrown-portfolio' ), 'scene' => 'dust', 'pos' => 'left-bottom' ),
        array( 'kicker' => __( '01 — Discover', 'uncrown-portfolio' ), 'line' => __( 'We listen before we build.', 'uncrown-portfolio' ), 'sub' => __( 'Interviews, surveys and related studies come first.', 'uncrown-portfolio' ), 'scene' => 'orbits', 'pos' => 'left-top' ),
        array( 'kicker' => __( '02 — Define', 'uncrown-portfolio' ), 'line' => __( 'One problem, clearly framed.', 'uncrown-portfolio' ), 'sub' => __( 'Scope, objectives and how we will measure success.', 'uncrown-portfolio' ), 'scene' => 'peaks', 'pos' => 'right-bottom' ),
        array( 'kicker' => __( '03 — Build', 'uncrown-portfolio' ), 'line' => __( 'Then we build it, piece by piece.', 'uncrown-portfolio' ), 'sub' => __( 'Short cycles, shared reviews, steady progress.', 'uncrown-portfolio' ), 'scene' => 'arena', 'pos' => 'right-top' ),
        array( 'kicker' => __( '04 — Test & refine', 'uncrown-portfolio' ), 'line' => __( 'And test it until it holds.', 'uncrown-portfolio' ), 'sub' => __( 'Real users, real results, real fixes.', 'uncrown-portfolio' ), 'scene' => 'embers', 'pos' => 'left-bottom' ),
    );
    if ( $defaults_only ) { return $chapters; }
    foreach ( $chapters as $n => $chapter ) {
        $line = trim( (string) ctp_get_option( 'ctp_story_line_' . ( $n + 1 ), '' ) );
        if ( '' !== $line ) { $chapters[ $n ]['line'] = $line; }
    }
    return $chapters;
}

/** Split text into per-character spans (words kept together) for the scroll reveal. */
function ctp_split_chars( $text ) {
    $chars = preg_split( '//u', trim( (string) $text ), -1, PREG_SPLIT_NO_EMPTY );
    $total = max( 1, count( $chars ) );
    $html = '<span class="sw">';
    foreach ( $chars as $i => $char ) {
        if ( ' ' === $char ) { $html .= '</span> <span class="sw">'; continue; }
        $html .= '<span class="ch" style="--d:' . round( $i / $total, 3 ) . '">' . esc_html( $char ) . '</span>';
    }
    return $html . '</span>';
}


/** Parse "Name: level" lines (or comma-separated pairs) into name => 0–100, in the order written. */
function ctp_parse_skill_levels( $raw ) {
    $out = array();
    foreach ( preg_split( '/[\r\n,]+/', (string) $raw ) as $line ) {
        if ( ! preg_match( '/^\s*(.+?)\s*[:=\-]\s*(\d{1,3})\s*%?\s*$/u', $line, $m ) ) { continue; }
        $name = sanitize_text_field( $m[1] );
        if ( '' === $name ) { continue; }
        $out[ $name ] = max( 0, min( 100, (int) $m[2] ) );
    }
    return $out;
}

/** One member's skill levels. */
function ctp_member_skill_levels( $post_id ) {
    return ctp_parse_skill_levels( get_post_meta( $post_id, '_ctp_member_skill_levels', true ) );
}

/** Team-wide skill stats: for each skill, the average of members who listed it, plus each member's level. */
function ctp_team_skill_stats( $limit = 10 ) {
    $ids = get_posts( array( 'post_type' => 'ctp_member', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_ctp_member_order', 'orderby' => array( 'meta_value_num' => 'ASC', 'title' => 'ASC' ) ) );
    $stats = array();
    foreach ( $ids as $id ) {
        foreach ( ctp_member_skill_levels( $id ) as $name => $level ) {
            $key = strtolower( $name );
            if ( ! isset( $stats[ $key ] ) ) { $stats[ $key ] = array( 'name' => $name, 'members' => array() ); }
            $stats[ $key ]['members'][] = array( 'name' => get_the_title( $id ), 'level' => $level );
        }
    }
    foreach ( $stats as $key => $row ) {
        $sum = 0;
        foreach ( $row['members'] as $m ) { $sum += $m['level']; }
        $stats[ $key ]['average'] = (int) round( $sum / count( $row['members'] ) );
    }
    uasort( $stats, function ( $a, $b ) {
        if ( $a['average'] === $b['average'] ) { return count( $b['members'] ) - count( $a['members'] ); }
        return $b['average'] - $a['average'];
    } );
    return array_slice( array_values( $stats ), 0, $limit );
}


/** Social networks the theme can link to, with simple monochrome icons. */
function ctp_social_networks() {
    return array(
        'facebook' => array( 'label' => 'Facebook', 'placeholder' => 'https://facebook.com/yourpage', 'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M13.5 21v-7.6h2.6l.4-3h-3V8.5c0-.9.3-1.5 1.5-1.5h1.6V4.3c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4v2.2H7.8v3h2.6V21z"/></svg>' ),
        'instagram' => array( 'label' => 'Instagram', 'placeholder' => 'https://instagram.com/yourname', 'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="17" height="17" rx="5" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="17.3" cy="6.7" r="1.1" fill="currentColor"/></svg>' ),
        'x' => array( 'label' => 'X (Twitter)', 'placeholder' => 'https://x.com/yourname', 'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M17.6 3.5h3l-6.6 7.5 7.8 9.5h-6.1l-4.8-6-5.5 6H2.4l7-8L2 3.5h6.2l4.3 5.5zm-1 15.3h1.7L7.5 5.1H5.7z"/></svg>' ),
        'github' => array( 'label' => 'GitHub', 'placeholder' => 'https://github.com/yourname', 'icon' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2.5a9.7 9.7 0 0 0-3.1 18.9c.5.1.7-.2.7-.5v-1.7c-2.7.6-3.3-1.2-3.3-1.2-.4-1.1-1.1-1.4-1.1-1.4-.9-.6.1-.6.1-.6 1 .1 1.5 1 1.5 1 .9 1.5 2.3 1.1 2.9.8.1-.6.3-1.1.6-1.3-2.2-.2-4.4-1.1-4.4-4.8 0-1.1.4-1.9 1-2.6-.1-.3-.4-1.2.1-2.6 0 0 .8-.3 2.7 1a9.3 9.3 0 0 1 4.9 0c1.9-1.3 2.7-1 2.7-1 .5 1.4.2 2.3.1 2.6.6.7 1 1.5 1 2.6 0 3.7-2.3 4.6-4.4 4.8.4.3.7.9.7 1.8v2.7c0 .3.2.6.7.5A9.7 9.7 0 0 0 12 2.5z"/></svg>' ),
    );
}

/** Render the filled-in social links as round icon buttons. $class adds a modifier, e.g. "is-large". */
function ctp_social_links_html( $class = '' ) {
    $items = '';
    foreach ( ctp_social_networks() as $key => $net ) {
        $url = esc_url( ctp_get_option( 'ctp_social_' . $key, '' ) );
        if ( ! $url ) { continue; }
        $items .= '<li><a class="social-link social-' . esc_attr( $key ) . '" href="' . $url . '" target="_blank" rel="noopener noreferrer me" aria-label="' . esc_attr( sprintf( __( '%1$s on %2$s (opens in a new tab)', 'uncrown-portfolio' ), ctp_brand_name(), $net['label'] ) ) . '">' . $net['icon'] . '</a></li>';
    }
    return $items ? '<ul class="social-links ' . esc_attr( $class ) . '">' . $items . '</ul>' : '';
}
