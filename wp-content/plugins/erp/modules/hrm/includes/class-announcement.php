<?php

namespace WeDevs\ERP\HRM;

use WeDevs\ERP\Framework\Traits\Hooker;

/**
 *  Announcement class HR
 *
 *  Announcement for employees
 *
 *  @since 0.1
 *
 *  @author weDevs <info@wedevs.com>
 */
class Announcement {
    use Hooker;

    private $post_type = 'erp_hr_announcement';

    private $assign_type = [];

    /**
     *  Load autometically all actions
     */
    public function __construct() {
        $this->assign_type = [
            ''                   => __( '-- Select --', 'erp' ),
            'all_employee'       => __( 'All Employees', 'erp' ),
            'selected_employee'  => __( 'Selected Employee', 'erp' ),
            'by_department'      => __( 'By Department', 'erp' ),
            'by_designation'     => __( 'By Designation', 'erp' ),
        ];

        $this->action( 'init', 'post_types' );
        $this->action( 'do_meta_boxes', 'do_metaboxes' );
        $this->action( 'save_post', 'save_announcement_meta', 10, 2 );
        $this->action( 'init', 'redirect_to_announcement_tab' );

        $this->filter( 'manage_edit-erp_hr_announcement_columns', 'add_type_columns' );
        $this->filter( 'manage_erp_hr_announcement_posts_custom_column', 'assign_type_edit_columns', 10, 2 );

        // $this->filter( 'parent_file', 'fix_parent_file', 999 );
        // $this->filter( 'submenu_file', 'submenu_file', 999 );

        $this->action( 'admin_head', 'filter_admin_sidebar_menu_items' );
    }

    /**
     * Redirect to announcement tab in people page after create/update of announcement
     *
     * @since 1.10.0
     *
     * @return void
     */
    public function redirect_to_announcement_tab() {
        $request_uri = ! empty( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

        if ( strpos( $request_uri, 'edit.php?post_type=erp_hr_announcement' ) !== false ) {
            wp_safe_redirect( admin_url( 'admin.php?page=erp-hr&section=people&sub-section=announcement' ) );
            exit;
        }
    }

    /**
     * Filter admin sidebar menu items
     *
     * Remove HR Announcement items from sidebar generated by register_post_type function.
     * Highlight Parent menu as active item when we are in questionnaire menu page and sub pages.
     *
     * @since 1.1.6
     *
     * @return void
     */
    public function filter_admin_sidebar_menu_items() {
        global $menu, $submenu_file, $typenow;

        $hr_menu = array_filter( $menu, function ( $item ) {
            return __( 'HR Announcement', 'erp' ) === $item[0];
        } );

        $announcement_pages = [
            'post-new.php?post_type=erp_hr_announcement',
            'edit.php?post_type=erp_hr_announcement',
        ];

        if ( in_array( $submenu_file, $announcement_pages ) ) {
            $submenu_file        = 'edit.php?post_type=erp_hr_announcement';
            $typenow             = null;
            $_SERVER['PHP_SELF'] = 'erp-hr';

            add_filter( 'parent_file', function () {
                return 'edit.php?post_type=erp_hr_announcement';
            } );
        }

        $hr_menu_position = key( $hr_menu );

        unset( $menu[ $hr_menu_position ] );
    }

    /**
     * Fix parent file
     *
     * @param string $parent_file
     *
     * @return string
     */
    public function fix_parent_file( $parent_file ) {
        global $current_screen;

        if ( $current_screen->post_type == $this->post_type ) {
            $parent_file = 'erp-hr';
        }

        return $parent_file;
    }

    /**
     * Set submenu file
     *
     * @param string $submenu_file
     *
     * @return string
     */
    public function submenu_file( $submenu_file ) {
        global $current_screen;

        if ( $current_screen->post_type == $this->post_type ) {
            $submenu_file = 'edit.php?post_type=erp_hr_announcement';
        }

        return $submenu_file;
    }

    /**
     * Register Announcement post type
     *
     * @since 0.1
     *
     * @return void
     */
    public function post_types() {
        $capability = 'erp_hr_manager';

        register_post_type( $this->post_type, [
            'label'               => __( 'Announcement', 'erp' ),
            'description'         => '',
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'rewrite'             => [ 'slug' => '' ],
            'query_var'           => false,
            'supports'            => [ 'title', 'editor' ],
            'capabilities'        => [
                'edit_post'          => $capability,
                'read_post'          => $capability,
                'delete_posts'       => $capability,
                'edit_posts'         => $capability,
                'edit_others_posts'  => $capability,
                'publish_posts'      => $capability,
                'read_private_posts' => $capability,
                'create_posts'       => $capability,
                'delete_post'        => $capability,
            ],
            'labels'          => [
                'name'               => __( 'Announcements', 'erp' ),
                'singular_name'      => __( 'Announcement', 'erp' ),
                'menu_name'          => __( 'HR Announcement', 'erp' ),
                'add_new'            => __( 'Add Announcement', 'erp' ),
                'add_new_item'       => __( 'Add New Announcement', 'erp' ),
                'edit'               => __( 'Edit', 'erp' ),
                'edit_item'          => __( 'Edit Announcement', 'erp' ),
                'new_item'           => __( 'New Announcement', 'erp' ),
                'view'               => __( 'View Announcement', 'erp' ),
                'view_item'          => __( 'View Announcement', 'erp' ),
                'search_items'       => __( 'Search Announcement', 'erp' ),
                'not_found'          => __( 'No Announcement Found', 'erp' ),
                'not_found_in_trash' => __( 'No Announcement found in trash', 'erp' ),
                'parent'             => __( 'Parent Announcement', 'erp' ),
            ],
        ] );
    }

    /**
     * Initialize metabox for ERP HR announcement post type
     *
     * @since 0.1
     *
     * @return void
     */
    public function do_metaboxes() {
        add_meta_box( 'erp-hr-announcement-meta-box', __( 'Announcement Settings', 'erp' ), [ $this, 'meta_boxes_cb' ], $this->post_type, 'advanced', 'high' );
    }

    /**
     * Announcement metabox callback function
     *
     * @param int $post_id
     *
     * @return void
     */
    public function meta_boxes_cb( $post_id ) {
        global $post;

        $employees    = erp_hr_get_employees( [ 'number' => -1, 'no_object' => true ] );
        $departments  = erp_hr_get_departments( [ 'number' => -1, 'no_object' => true ] );
        $designations = erp_hr_get_designations( [ 'number' => -1, 'no_object' => true ] );

        $announcement_type        = get_post_meta( $post->ID, '_announcement_type', true );
        $announcement_users       = get_post_meta( $post->ID, '_announcement_selected_user', true );
        $announce_departments     = get_post_meta( $post->ID, '_announcement_department', true );
        $announce_designations    = get_post_meta( $post->ID, '_announcement_designation', true );

        $announcement_employee    = ( $announcement_users ) ? $announcement_users : [];
        $announcement_department  = ( $announce_departments ) ? $announce_departments : [];
        $announcement_designation = ( $announce_designations ) ? $announce_designations : []; ?>
            <table class="form-table erp-hr-announcement-meta-wrap-table">
                <tr>
                    <th><?php esc_html_e( 'Send Announcement To', 'erp' ); ?></th>
                    <td>
                        <select name="hr_announcement_assign_type" id="hr_announcement_assign_type" style="width:60%">
                            <?php foreach ( $this->assign_type as $key => $type ) { ?>
                                <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $announcement_type, $key ); ?>><?php echo esc_attr( $type ); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr class="selected_employee_field">
                    <th><?php esc_html_e( 'Select Employees', 'erp' ); ?></th>
                    <td>
                        <select name="hr_announcement_assign_employee[]" data-placeholder= '<?php echo esc_html__( 'Select Employees...', 'erp' ); ?>' id="hr_announcement_assign_employee" class="erp-select2" multiple="multiple">
                            <?php
                            foreach ( $employees as $user ) {
                                if ( $user->user_id == get_current_user_id() ) {
                                    continue;
                                } ?>
                                    <option <?php echo in_array( $user->user_id, $announcement_employee ) ? 'selected="selected"' : ''; ?> value='<?php echo esc_attr( $user->user_id ); ?>'><?php echo esc_html( $user->display_name ); ?></option>
                                <?php
                            } ?>
                        </select>
                    </td>
                </tr>

                <tr class="by_department_field">
                    <th><?php esc_html_e( 'Select Departments', 'erp' ); ?></th>
                    <td>
                        <select name="hr_announcement_assign_department[]" data-placeholder= '<?php echo esc_html__( 'Select Departments...', 'erp' ); ?>' id="hr_announcement_assign_department" class="erp-select2" multiple="multiple">
                            <?php
                            foreach ( $departments as $department ) {
                                ?>
                                <option <?php echo in_array( $department->id, $announcement_department ) ? 'selected="selected"' : ''; ?> value='<?php echo esc_attr( $department->id ); ?>'><?php echo esc_html( $department->title ); ?></option>
                                <?php
                            } ?>
                        </select>
                    </td>
                </tr>

                <tr class="by_designation_field">
                    <th><?php esc_html_e( 'Select Designations', 'erp' ); ?></th>
                    <td>
                        <select name="hr_announcement_assign_designation[]" data-placeholder= '<?php echo esc_html__( 'Select Designations...', 'erp' ); ?>' id="hr_announcement_assign_designation" class="erp-select2" multiple="multiple">
                            <?php
                            foreach ( $designations as $designation ) {
                                ?>
                                <option <?php echo in_array( $designation->id, $announcement_designation ) ? 'selected="selected"' : ''; ?> value='<?php echo esc_attr( $designation->id ); ?>'><?php echo esc_html( $designation->title ); ?></option>
                                <?php
                            } ?>
                        </select>
                    </td>
                </tr>

                <?php do_action( 'hr_announcement_table_last', $post ); ?>

            </table>
            <?php wp_nonce_field( 'hr_announcement_meta_action', 'hr_announcement_meta_action_nonce' ); ?>

            <script>
                (function( $ ){
                    $( document ).ready( function() {

                        // Remove selected value other than the currently active one.
                        switch ( $('select#hr_announcement_assign_type').val() ) {
                            case 'selected_employee':
                                $( 'tr.by_department_field select' ).val(null).trigger('change');
                                $( 'tr.by_designation_field  select' ).val(null).trigger('change');
                                break;

                            case 'by_department':
                                $( 'tr.selected_employee_field  select' ).val(null).trigger('change');
                                $( 'tr.by_designation_field  select' ).val(null).trigger('change');
                                break;

                            case 'by_designation':
                                $( 'tr.selected_employee_field  select' ).val(null).trigger('change');
                                $( 'tr.by_department_field  select' ).val(null).trigger('change');
                        }

                        $('table.erp-hr-announcement-meta-wrap-table').on( 'change', 'select#hr_announcement_assign_type', function() {
                            var self = $( this );

                            switch ( self.val() ) {
                                case 'all_employee':
                                    $( 'tr.selected_employee_field' ).hide();
                                    $( 'tr.by_department_field' ).hide();
                                    $( 'tr.by_designation_field' ).hide();
                                    break;

                                case 'selected_employee':
                                    $( 'tr.by_department_field' ).hide();
                                    $( 'tr.by_designation_field' ).hide();
                                    $( 'tr.selected_employee_field' ).show();
                                    break;

                                case 'by_department':
                                    $( 'tr.selected_employee_field' ).hide();
                                    $( 'tr.by_department_field' ).show();
                                    $( 'tr.by_designation_field' ).hide();
                                    break;

                                case 'by_designation':
                                    $( 'tr.selected_employee_field' ).hide();
                                    $( 'tr.by_department_field' ).hide();
                                    $( 'tr.by_designation_field' ).show();
                            }
                        });

                        $( 'select#hr_announcement_assign_type' ).trigger( 'change' )
                    });
                })( jQuery );
            </script>
            <style>
                #hr_announcement_assign_employee,
                #hr_announcement_assign_department,
                #hr_announcement_assign_designation {
                    width: 315px;
                }

                tr.selected_employee_field,
                tr.by_department_field,
                tr.by_designation_field {
                    display: none;
                }

                .erp-hr-announcement-meta-wrap-table .select2-search__field {
                    width: 100% !important;
                }
            </style>
        <?php
    }

    /**
     * Add custom column label
     *
     * @since  0.1
     *
     * @param array $columns
     */
    public function add_type_columns( $columns ) {
        unset( $columns['date'] );

        $columns['assign_type'] = __( 'Sent To', 'erp' );
        $columns['send_type']   = __( 'Type', 'erp' );
        $columns['date']        = __( 'Date', 'erp' );

        return $columns;
    }

    /**
     * Render custom column content
     *
     * @since  0.1
     *
     * @param string $column
     * @param int    $post_id
     *
     * @return void
     */
    public function assign_type_edit_columns( $column, $post_id ) {
        global $post;

        if ( $column == 'assign_type' ) {
            $assign_type = get_post_meta( $post_id, '_announcement_type', true );

            if ( $assign_type ) {
                echo wp_kses_post( $this->assign_type[$assign_type] );
            } else {
                esc_html_e( 'No employee assigned!', 'erp' );
            }
        }

        if ( 'send_type' == $column ) {
            echo '<i class="fa fa-envelope-o fa-lg"></i>';

            do_action( 'hr_announcement_send_type', $column, $post_id );
        }
    }

    /**
     * Save Announcement post meta
     *
     * @since  0.1
     *
     * @param int    $post_id
     * @param object $post
     *
     * @return void
     */
    public function save_announcement_meta( $post_id, $post ) {
        if ( ! isset( $_POST['hr_announcement_meta_action_nonce'] ) ) {
            return $post_id;
        }

        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hr_announcement_meta_action_nonce'] ) ), 'hr_announcement_meta_action' ) ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        $post_type = get_post_type_object( $post->post_type );

        if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
            return $post_id;
        }

        if ( !current_user_can( 'erp_manage_announcement' ) ) {
            return $post_id;
        }

        $type         = ( isset( $_POST['hr_announcement_assign_type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['hr_announcement_assign_type'] ) ) : '';
        $employees    = ( isset( $_POST['hr_announcement_assign_employee'] ) ) ? array_map( 'sanitize_text_field', $_POST['hr_announcement_assign_employee'] ) : [];
        $departments  = ( isset( $_POST['hr_announcement_assign_department'] ) ) ? array_map( 'sanitize_text_field', $_POST['hr_announcement_assign_department'] ) : [];
        $designations = ( isset( $_POST['hr_announcement_assign_designation'] ) ) ? array_map( 'sanitize_text_field', $_POST['hr_announcement_assign_designation'] ) : [];

        if ( $type == 'by_department' ) {
            $selected = $departments;
        } elseif ( $type == 'by_designation' ) {
            $selected = $designations;
        } else {
            $selected = $employees;
        }

        // Assign / Send announcements to the selected group
        erp_hr_assign_announcements_to_employees( $post_id, $type, $selected );

        //Redirect to announment list page
        wp_redirect( admin_url( 'edit.php?post_type=erp_hr_announcement' ) );
        exit;

        //do_action( 'hr_annoucement_save', $post_id, $selected );
    }
}
