<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Options Class
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'WCGS_Options' ) ) {
	class WCGS_Options extends WCGS_Abstract {


		  // constans
		public $unique       = '';
		public $notice       = '';
		public $abstract     = 'options';
		public $sections     = array();
		public $options      = array();
		public $errors       = array();
		public $pre_tabs     = array();
		public $pre_fields   = array();
		public $pre_sections = array();
		public $args         = array(

			// framework title
			'framework_title'         => '',
			'framework_class'         => '', // menu settings
			'menu_title'              => '',
			'menu_slug'               => '',
			'menu_type'               => 'menu',
			'menu_capability'         => 'manage_options',
			'menu_icon'               => null,
			'menu_position'           => null,
			'menu_hidden'             => false,
			'menu_parent'             => '', // menu extras
			'show_bar_menu'           => true,
			'show_sub_menu'           => true,
			'show_network_menu'       => true,
			'show_in_customizer'      => false,
			'show_search'             => true,
			'show_reset_all'          => true,
			'show_reset_section'      => true,
			'show_footer'             => true,
			'show_all_options'        => true,
			'sticky_header'           => true,
			'save_defaults'           => true,
			'ajax_save'               => true, // admin bar menu settings
			'admin_bar_menu_icon'     => '',
			'admin_bar_menu_priority' => 80, // footer
			'footer_text'             => '',
			'footer_after'            => '',
			'footer_credit'           => '',

			// database model
			'database'                => '', // options, transient, theme_mod, network
			'transient_time'          => 0,

			// contextual help
			'contextual_help'         => array(),
			'contextual_help_sidebar' => '',

			// others
			'output_css'              => true,

			// theme
			   'theme'                => 'dark',
			'class'                   => '',

			// external default values
			'defaults'                => array(),

		);

		// run framework construct
		public function __construct( $key, $params = array() ) {

			$this->unique   = $key;
			$this->args     = apply_filters( "wcgs_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections = apply_filters( "wcgs_{$this->unique}_sections", $params['sections'], $this );

			// run only is admin panel options, avoid performance loss
			$this->pre_tabs     = $this->pre_tabs( $this->sections );
			$this->pre_fields   = $this->pre_fields( $this->sections );
			$this->pre_sections = $this->pre_sections( $this->sections );

			$this->get_options();
			$this->set_options();
			$this->save_defaults();

			add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );
			add_action( 'admin_bar_menu', array( &$this, 'add_admin_bar_menu' ), $this->args['admin_bar_menu_priority'] );
			add_action( 'wp_ajax_wcgs_' . $this->unique . '_ajax_save', array( &$this, 'ajax_save' ) );

			if ( ! empty( $this->args['show_network_menu'] ) ) {
				 add_action( 'network_admin_menu', array( &$this, 'add_admin_menu' ) );
			}

			// wp enqeueu for typography and output css
			parent::__construct();

		}

		 // instance
		public static function instance( $key, $params = array() ) {

			return new self( $key, $params );
		}

		public function pre_tabs( $sections ) {

			$result  = array();
			$parents = array();
			$count   = 100;

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['parent'] ) ) {
					$section['priority']             = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
					$parents[ $section['parent'] ][] = $section;
					unset( $sections[ $key ] );
				}
				$count++;
			}

			foreach ( $sections as $key => $section ) {
				$section['priority'] = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
				if ( ! empty( $section['id'] ) && ! empty( $parents[ $section['id'] ] ) ) {
					$section['subs'] = wp_list_sort( $parents[ $section['id'] ], array( 'priority' => 'ASC' ), 'ASC', true );
				}
				$result[] = $section;
				$count++;
			}
			return wp_list_sort( $result, array( 'priority' => 'ASC' ), 'ASC', true );
		}

		public function pre_fields( $sections ) {

			$result = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						  $result[] = $field;
					}
				}
			}

			return $result;
		}

		public function pre_sections( $sections ) {

			$result = array();

			foreach ( $this->pre_tabs as $tab ) {
				if ( ! empty( $tab['subs'] ) ) {
					foreach ( $tab['subs'] as $sub ) {
						$result[] = $sub;
					}
				}
				if ( empty( $tab['subs'] ) ) {
					$result[] = $tab;
				}
			}

			return $result;
		}

		// add admin bar menu
		public function add_admin_bar_menu( $wp_admin_bar ) {

			if ( ! empty( $this->args['show_bar_menu'] ) && empty( $this->args['menu_hidden'] ) ) {

				global $submenu;

				$menu_slug = $this->args['menu_slug'];
				$menu_icon = ( ! empty( $this->args['admin_bar_menu_icon'] ) ) ? '<span class="wcgs-ab-icon ab-icon ' . $this->args['admin_bar_menu_icon'] . '"></span>' : '';

				$wp_admin_bar->add_node(
					array(
						'id'    => $menu_slug,
						'title' => $menu_icon . $this->args['menu_title'],
						'href'  => ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_slug ) : admin_url( 'admin.php?page=' . $menu_slug ),
					)
				);

				if ( ! empty( $submenu[ $menu_slug ] ) ) {
					foreach ( $submenu[ $menu_slug ] as $key => $menu ) {
						$wp_admin_bar->add_node(
							array(
								'parent' => $menu_slug,
								'id'     => $menu_slug . '-' . $key,
								'title'  => $menu[0],
								'href'   => ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu[2] ) : admin_url( 'admin.php?page=' . $menu[2] ),
							)
						);
					}
				}

				if ( ! empty( $this->args['show_network_menu'] ) ) {
					$wp_admin_bar->add_node(
						array(
							'parent' => 'network-admin',
							'id'     => $menu_slug . '-network-admin',
							'title'  => $menu_icon . $this->args['menu_title'],
							'href'   => network_admin_url( 'admin.php?page=' . $menu_slug ),
						)
					);
				}
			}

		}

		public function ajax_save() {

			$result = $this->set_options( true );
		
			if ( ! $result ) {
				wp_send_json_error( array( 'success' => false, 'error' => esc_html__( 'Error while saving the changes.', 'woo-gallery-slider' ) ) );
			} else {
				wp_send_json_success( array( 'success' => true, 'notice' => $this->notice, 'errors' => $this->errors ) );
			}
	
		}

		// get default value
		public function get_default( $field, $options = array() ) {

			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : '';
			$default = ( isset( $field['default'] ) ) ? $field['default'] : $default;
			$default = ( isset( $options[ $field['id'] ] ) ) ? $options[ $field['id'] ] : $default;

			return $default;

		}

		// save defaults and set new fields value to main options
		public function save_defaults() {

			$tmp_options = $this->options;

			foreach ( $this->pre_fields as $field ) {
				if ( ! empty( $field['id'] ) ) {
					$this->options[ $field['id'] ] = $this->get_default( $field, $this->options );
				}
			}

			if ( $this->args['save_defaults'] && empty( $tmp_options ) ) {
				$this->save_options( $this->options );
			}

		}

		// set options
		public function set_options( $ajax = false ) {

			// XSS ok.
			// This "POST" requests is sanitizing in the below foreach. see #L331
			$response  = ( $ajax && ! empty( $_POST['data'] ) ) ? json_decode( wp_unslash( trim( $_POST['data'] ) ), true ) : $_POST;
	  
			// Set variables.
			$data      = array();
			$noncekey  = 'wcgs_options_nonce'. $this->unique;
			$nonce     = ( ! empty( $response[$noncekey] ) ) ? $response[$noncekey] : '';
			$options   = ( ! empty( $response[$this->unique] ) ) ? $response[$this->unique] : array();
			$transient = ( ! empty( $response['wcgs_transient'] ) ) ? $response['wcgs_transient'] : array();
	  
			if ( wp_verify_nonce( $nonce, 'wcgs_options_nonce' ) ) {
	  
			  $importing  = false;
			  $section_id = ( ! empty( $transient['section'] ) ) ? $transient['section'] : '';
	  
			  if ( ! $ajax && ! empty( $response[ 'wcgs_import_data' ] ) ) {
	  
				// XSS ok.
				// This "POST" requests is sanitizing in the below foreach. see #L331
				$import_data  = json_decode( wp_unslash( trim( $response[ 'wcgs_import_data' ] ) ), true );
				$options      = ( is_array( $import_data ) && ! empty( $import_data ) ) ? $import_data : array();
				$importing    = true;
				$this->notice = esc_html__( 'Settings successfully imported.', 'woo-gallery-slider' );
	  
			  }
	  
			  if ( ! empty( $transient['reset'] ) ) {
	  
				foreach ( $this->pre_fields as $field ) {
				  if ( ! empty( $field['id'] ) ) {
					$data[$field['id']] = $this->get_default( $field );
				  }
				}
	  
				$this->notice = esc_html__( 'Default settings restored.', 'woo-gallery-slider' );
	  
			  } else if ( ! empty( $transient['reset_section'] ) && ! empty( $section_id ) ) {
	  
				if ( ! empty( $this->pre_sections[$section_id - 1]['fields'] ) ) {
	  
				  foreach ( $this->pre_sections[$section_id - 1]['fields'] as $field ) {
					if ( ! empty( $field['id'] ) ) {
					  $data[$field['id']] = $this->get_default( $field );
					}
				  }
	  
				}
	  
				$data = wp_parse_args( $data, $this->options );
	  
				$this->notice = esc_html__( 'Default settings restored.', 'woo-gallery-slider' );
	  
			  } else {
	  
				// sanitize and validate
				foreach ( $this->pre_fields as $field ) {
	  
				  if ( ! empty( $field['id'] ) ) {
	  
					$field_id    = $field['id'];
					$field_value = isset( $options[$field_id] ) ? $options[$field_id] : '';
	  
					// Ajax and Importing doing wp_unslash already.
					if ( ! $ajax && ! $importing ) {
					  $field_value = wp_unslash( $field_value );
					}
	  
					// Sanitize "post" request of field.
					if ( ! isset( $field['sanitize'] ) ) {
	  
					  if( is_array( $field_value ) ) {
	  
						$data[$field_id] = wp_kses_post_deep( $field_value );
	  
					  } else {
	  
						$data[$field_id] = wp_kses_post( $field_value );
	  
					  }
	  
					} else if( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
	  
					  $data[$field_id] = call_user_func( $field['sanitize'], $field_value );
	  
					} else {
	  
					  $data[$field_id] = $field_value;
	  
					}
	  
					// Validate "post" request of field.
					if ( isset( $field['validate'] ) && is_callable( $field['validate'] ) ) {
	  
					  $has_validated = call_user_func( $field['validate'], $field_value );
	  
					  if ( ! empty( $has_validated ) ) {
	  
						$data[$field_id] = ( isset( $this->options[$field_id] ) ) ? $this->options[$field_id] : '';
						$this->errors[$field_id] = $has_validated;
	  
					  }
	  
					}
	  
				  }
	  
				}
	  
			  }
	  
			  $data = apply_filters( "wcgs_{$this->unique}_save", $data, $this );
	  
			  do_action( "wcgs_{$this->unique}_save_before", $data, $this );
	  
			  $this->options = $data;
	  
			  $this->save_options( $data );
	  
			  do_action( "wcgs_{$this->unique}_save_after", $data, $this );
	  
			  if ( empty( $this->notice ) ) {
				$this->notice = esc_html__( 'Settings saved.', 'woo-gallery-slider' );
			  }
	  
			  return true;
	  
			}
	  
			return false;
	  
		  }

		// save options database
		public function save_options( $request ) {

			if ( $this->args['database'] === 'transient' ) {
				   set_transient( $this->unique, $request, $this->args['transient_time'] );
			} elseif ( $this->args['database'] === 'theme_mod' ) {
				set_theme_mod( $this->unique, $request );
			} elseif ( $this->args['database'] === 'network' ) {
				update_site_option( $this->unique, $request );
			} else {
				update_option( $this->unique, $request );
			}

			do_action( "wcgs_{$this->unique}_saved", $request, $this );

		}

		// get options from database
		public function get_options() {

			if ( $this->args['database'] === 'transient' ) {
				   $this->options = get_transient( $this->unique );
			} elseif ( $this->args['database'] === 'theme_mod' ) {
				$this->options = get_theme_mod( $this->unique );
			} elseif ( $this->args['database'] === 'network' ) {
				$this->options = get_site_option( $this->unique );
			} else {
				$this->options = get_option( $this->unique );
			}

			if ( empty( $this->options ) ) {
				$this->options = array();
			}

			return $this->options;

		}

		// wp api: admin menu.
		public function add_admin_menu() {

			extract( $this->args );

			if ( $menu_type === 'submenu' ) {

				$menu_page = call_user_func( 'add_submenu_page', $menu_parent, $menu_title, $menu_title, $menu_capability, $menu_slug, array( &$this, 'add_options_html' ) );

			} else {

				$menu_page = call_user_func( 'add_menu_page', $menu_title, $menu_title, $menu_capability, $menu_slug, array( &$this, 'add_options_html' ), $menu_icon, $menu_position );

				if ( ! empty( $this->args['show_sub_menu'] ) && count( $this->pre_tabs ) > 1 ) {

					// create submenus
					$tab_key = 1;
					foreach ( $this->pre_tabs as $section ) {

						call_user_func( 'add_submenu_page', $menu_slug, $section['title'], $section['title'], $menu_capability, $menu_slug . '#tab=' . $tab_key, '__return_null' );

						if ( ! empty( $section['subs'] ) ) {
							$tab_key += ( count( $section['subs'] ) - 1 );
						}

						$tab_key++;

					}

					remove_submenu_page( $menu_slug, $menu_slug );

				}

				if ( ! empty( $menu_hidden ) ) {
					remove_menu_page( $menu_slug );
				}
			}

			add_action( 'load-' . $menu_page, array( &$this, 'add_page_on_load' ) );

		}

		public function add_page_on_load() {

			if ( ! empty( $this->args['contextual_help'] ) ) {

				$screen = get_current_screen();

				foreach ( $this->args['contextual_help'] as $tab ) {
					$screen->add_help_tab( $tab );
				}

				if ( ! empty( $this->args['contextual_help_sidebar'] ) ) {
					$screen->set_help_sidebar( $this->args['contextual_help_sidebar'] );
				}
			}

			add_filter( 'admin_footer_text', array( &$this, 'add_admin_footer_text' ) );

		}

		public function add_admin_footer_text() {

			$default = '';
			echo ( ! empty( $this->args['footer_credit'] ) ) ? $this->args['footer_credit'] : $default;
		}

		public function error_check( $sections, $err = '' ) {

			if ( ! $this->args['ajax_save'] ) {

				if ( ! empty( $sections['fields'] ) ) {
					foreach ( $sections['fields'] as $field ) {
						if ( ! empty( $field['id'] ) ) {
							if ( array_key_exists( $field['id'], $this->errors ) ) {
								$err = '<span class="wcgs-label-error">!</span>';
							}
						}
					}
				}

				if ( ! empty( $sections['subs'] ) ) {
					foreach ( $sections['subs'] as $sub ) {
						$err = $this->error_check( $sub, $err );
					}
				}

				if ( ! empty( $sections['id'] ) && array_key_exists( $sections['id'], $this->errors ) ) {
					$err = $this->errors[ $sections['id'] ];
				}
			}

			return $err;
		}

		// Option page html output.
		public function add_options_html() {

			$has_nav       = ( count( $this->pre_tabs ) > 1 ) ? true : false;
			$show_all      = ( ! $has_nav ) ? ' wcgs-show-all' : '';
			$ajax_class    = ( $this->args['ajax_save'] ) ? ' wcgs-save-ajax' : '';
			$sticky_class  = ( $this->args['sticky_header'] ) ? ' wcgs-sticky-header' : '';
			$wrapper_class = ( $this->args['framework_class'] ) ? ' ' . $this->args['framework_class'] : '';
			$theme         = ( $this->args['theme'] ) ? ' wcgs-theme-' . $this->args['theme'] : '';
			$class         = ( $this->args['class'] ) ? ' ' . $this->args['class'] : '';

			echo '<div class="wcgs wcgs-options' . $theme . $class . $wrapper_class . '" data-slug="' . $this->args['menu_slug'] . '" data-unique="' . $this->unique . '">';

			$notice_class = ( ! empty( $this->notice ) ) ? ' wcgs-form-show' : '';
			$notice_text  = ( ! empty( $this->notice ) ) ? $this->notice : '';

			echo '<div class="wcgs-form-result wcgs-form-success' . $notice_class . '">' . $notice_text . '</div>';

			$error_class = ( ! empty( $this->errors ) ) ? ' wcgs-form-show' : '';

			echo '<div class="wcgs-form-result wcgs-form-error' . $error_class . '">';
			if ( ! empty( $this->errors ) ) {
				foreach ( $this->errors as $error ) {
					echo '<i class="wcgs-label-error">!</i> ' . $error . '<br />';
				}
			}
			echo '</div>';

			echo '<div class="wcgs-container">';

			echo '<form method="post" action="" enctype="multipart/form-data" id="wcgs-form" autocomplete="off">';

			echo '<input type="hidden" class="wcgs-section-id" name="wcgs_transient[section]" value="1">';
			wp_nonce_field( 'wcgs_options_nonce', 'wcgs_options_nonce' . $this->unique );

			echo '<div class="wcgs-header' . esc_attr( $sticky_class ) . '">';
			echo '<div class="wcgs-header-inner">';

			echo '<div class="wcgs-header-left">';
			echo '<h1>' . $this->args['framework_title'] . '</h1>';
			echo '</div>';

			echo '<div class="wcgs-header-right">';

			echo ( $has_nav && $this->args['show_all_options'] ) ? '<div class="wcgs-expand-all" title="' . esc_html__( 'show all options', 'woo-gallery-slider' ) . '"><i class="fa fa-outdent"></i></div>' : '';

			echo ( $this->args['show_search'] ) ? '<div class="wcgs-search"><input type="text" name="wcgs-search" placeholder="' . esc_html__( 'Search option(s)', 'woo-gallery-slider' ) . '" autocomplete="off" /></div>' : '';

			echo '<div class="wcgs-buttons">';
			echo '<input type="submit" name="' . $this->unique . '[_nonce][save]" class="button button-primary wcgs-save' . $ajax_class . '" value="' . esc_html__( 'Save', 'woo-gallery-slider' ) . '" data-save="' . esc_html__( 'Saving...', 'woo-gallery-slider' ) . '">';
			echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="wcgs_transient[reset_section]" class="button button-secondary wcgs-reset-section wcgs-confirm" value="' . esc_html__( 'Reset Section', 'woo-gallery-slider' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset this section options?', 'woo-gallery-slider' ) . '">' : '';
			echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="wcgs_transient[reset]" class="button button-secondary wcgs-warning-primary wcgs-reset-all wcgs-confirm" value="' . esc_html__( 'Reset All', 'woo-gallery-slider' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset all options?', 'woo-gallery-slider' ) . '">' : '';
			echo '</div>';

			echo '</div>';

			echo '<div class="clear"></div>';
			echo '</div>';
			echo '</div>';

			echo '<div class="wcgs-wrapper' . $show_all . '">';

			if ( $has_nav ) {
				echo '<div class="wcgs-nav wcgs-nav-options">';

				echo '<ul>';

				$tab_key = 1;

				foreach ( $this->pre_tabs as $tab ) {

					$tab_error = $this->error_check( $tab );
					$tab_icon  = ( ! empty( $tab['icon'] ) ) ? '<i class="' . $tab['icon'] . '"></i>' : '';

					if ( ! empty( $tab['subs'] ) ) {

						echo '<li class="wcgs-tab-depth-0">';

						echo '<a href="#tab=' . $tab_key . '" class="wcgs-arrow">' . $tab_icon . $tab['title'] . $tab_error . '</a>';

						echo '<ul>';

						foreach ( $tab['subs'] as $sub ) {

							$sub_error = $this->error_check( $sub );
							$sub_icon  = ( ! empty( $sub['icon'] ) ) ? '<i class="' . $sub['icon'] . '"></i>' : '';

							echo '<li class="wcgs-tab-depth-1"><a id="wcgs-tab-link-' . $tab_key . '" href="#tab=' . $tab_key . '">' . $sub_icon . $sub['title'] . $sub_error . '</a></li>';

							$tab_key++;
						}

						echo '</ul>';

						echo '</li>';

					} else {
						$tb_id_text = $tab['name'];
						echo '<li class="wcgs-tab-depth-0"><a id="wcgs-tab-link-' . $tb_id_text . '" href="#tab=' . $tb_id_text . '">' . $tab_icon . $tab['title'] . $tab_error . '</a></li>';

						$tab_key++;
					}
				}

				echo '</ul>';

				echo '</div>';

			}

			echo '<div class="wcgs-content">';

			echo '<div class="wcgs-sections">';

			$section_key = 1;

			foreach ( $this->pre_sections as $section ) {

				$onload       = ( ! $has_nav ) ? ' wcgs-onload' : '';
				$section_icon = ( ! empty( $section['icon'] ) ) ? '<i class="wcgs-icon ' . $section['icon'] . '"></i>' : '';
				$sc_text      = $section['name'];
				echo '<div id="wcgs-section-' . $sc_text . '" class="wcgs-section' . $onload . '">';
				echo ( $has_nav ) ? '<div class="wcgs-section-title"><h3>' . $section_icon . $section['title'] . '</h3></div>' : '';
				echo ( ! empty( $section['description'] ) ) ? '<div class="wcgs-field wcgs-section-description">' . $section['description'] . '</div>' : '';

				if ( ! empty( $section['fields'] ) ) {

					foreach ( $section['fields'] as $field ) {

						$is_field_error = $this->error_check( $field );

						if ( ! empty( $is_field_error ) ) {
							$field['_error'] = $is_field_error;
						}

						$value = ( ! empty( $field['id'] ) && isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '';

						WCGS::field( $field, $value, $this->unique, 'options' );

					}
				} else {
								echo '<div class="wcgs-no-option wcgs-text-muted">' . esc_html__( 'No option provided by developer.', 'woo-gallery-slider' ) . '</div>';
				}

				echo '</div>';

				$section_key++;
			}

			echo '</div>';

			echo '<div class="clear"></div>';

			echo '</div>';

			echo '<div class="wcgs-nav-background"></div>';

			echo '</div>';

			if ( ! empty( $this->args['show_footer'] ) ) {

				echo '<div class="wcgs-footer">';

				echo '<div class="wcgs-buttons">';
				echo '<input type="submit" name="wcgs_transient[save]" class="button button-primary wcgs-save' . $ajax_class . '" value="' . esc_html__( 'Save', 'woo-gallery-slider' ) . '" data-save="' . esc_html__( 'Saving...', 'woo-gallery-slider' ) . '">';
				echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="wcgs_transient[reset_section]" class="button button-secondary wcgs-reset-section wcgs-confirm" value="' . esc_html__( 'Reset Section', 'woo-gallery-slider' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset this section options?', 'woo-gallery-slider' ) . '">' : '';
				echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="wcgs_transient[reset]" class="button button-secondary wcgs-warning-primary wcgs-reset-all wcgs-confirm" value="' . esc_html__( 'Reset All', 'woo-gallery-slider' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset all options?', 'woo-gallery-slider' ) . '">' : '';
				echo '</div>';

				echo ( ! empty( $this->args['footer_text'] ) ) ? '<div class="wcgs-copyright">' . $this->args['footer_text'] . '</div>' : '';

				echo '<div class="clear"></div>';
				echo '</div>';

			}

			echo '</form>';

			echo '</div>';

			echo '<div class="clear"></div>';

			echo ( ! empty( $this->args['footer_after'] ) ) ? $this->args['footer_after'] : '';

			echo '</div>';

		}
	}
}
