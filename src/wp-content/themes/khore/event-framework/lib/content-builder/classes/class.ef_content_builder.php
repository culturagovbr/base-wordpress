<?php
/*  Copyright 2015  Showthemes Content Builder  (email : info@showthemes.com)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (!class_exists('EF_Content_Builder')) {

    class EF_Content_Builder {

        //private $templates;
        private $sections;
        //private $entities;
        private $relationships;
        private $allowedPostTypes = array('page');

        function __construct() {
            $this->init();

            //$this->loadEntities();
            $this->loadSections();
            $this->loadSectionsRelationship();
            $this->loadTemplates();

            // actions
            add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
            add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
            add_action('add_meta_boxes', array($this, 'addBuilderMetabox'));
            add_action('save_post', array($this, 'savePost'), 10, 3);
            add_action('edit_form_after_title', array($this, 'addContentBeforeEditor'));
            add_filter('mce_external_plugins', array($this, 'mce_external_plugins'));

            // ajax actions
            add_action('wp_ajax_ef-cb-get-builder-data', array($this, 'getBuilderData'));
            add_action('wp_ajax_ef-cb-search-entities', array($this, 'getSearchEntities'));
            add_action('wp_ajax_ef-cb-get-entities', array($this, 'getEntities'));
        }

        private function init() {
            // fetching all components available
            $components_dir            = new RecursiveDirectoryIterator(EF_CONTENT_BUILDER_DIR . 'components/');
            $components_iterator       = new RecursiveIteratorIterator($components_dir);
            $components_regex_iterator = new RegexIterator($components_iterator, '/.php$/');
            // including components
            foreach ($components_regex_iterator as $component) {
                require $component->getPathName();
            }
        }

        private function loadSections() {
            $speakers       = new EF_Content_Builder_Section_Speakers();
            $fullspeakers   = new EF_Content_Builder_Section_FullSpeakers();
            $exhibitors     = new EF_Content_Builder_Section_Exhibitors();
            $fullexhibitors = new EF_Content_Builder_Section_FullExhibitors();
            $schedule       = new EF_Content_Builder_Section_Schedule();
            $fullschedule   = new EF_Content_Builder_Section_FullSchedule();
            $callToAction   = new EF_Content_Builder_Section_CallToAction();
            $fulltickets    = new EF_Content_Builder_Section_FullTickets();
            $registration   = new EF_Content_Builder_Section_Registration();
            $sponsors       = new EF_Content_Builder_Section_Sponsors();
            $fullsponsors   = new EF_Content_Builder_Section_FullSponsors();
            $map            = new EF_Content_Builder_Section_Map();
            $contact        = new EF_Content_Builder_Section_Contact();
            $facebook       = new EF_Content_Builder_Section_Facebook();
            $instagram      = new EF_Content_Builder_Section_Instagram();
            $media          = new EF_Content_Builder_Section_Media();
            $news           = new EF_Content_Builder_Section_News();
            $fullnews       = new EF_Content_Builder_Section_FullNews();
            $social         = new EF_Content_Builder_Section_Social();
            $timer          = new EF_Content_Builder_Section_Timer();
            $twitter        = new EF_Content_Builder_Section_Twitter();
            $headline       = new EF_Content_Builder_Section_Headline();
            $strapline      = new EF_Content_Builder_Section_Strapline();
            $heading        = new EF_Content_Builder_Section_Heading();
            $html           = new EF_Content_Builder_Section_Html();
            $picture        = new EF_Content_Builder_Section_Picture();
            $columns2       = new EF_Content_Builder_Section_Columns2();
            $columns3       = new EF_Content_Builder_Section_Columns3();
            $columns4       = new EF_Content_Builder_Section_Columns4();
            $button         = new EF_Content_Builder_Section_Button();
            $newsletter     = new EF_Content_Builder_Section_Newsletter();
            $samplepage     = new EF_Content_Builder_Section_SamplePage();

            $this->sections = apply_filters('ef_content_builder_sections', array(
                'speakers'       => $speakers->serialize(),
                'fullspeakers'   => $fullspeakers->serialize(),
                'exhibitors'     => $exhibitors->serialize(),
                'fullexhibitors' => $fullexhibitors->serialize(),
                'schedule'       => $schedule->serialize(),
                'fullschedule'   => $fullschedule->serialize(),
                'calltoaction'   => $callToAction->serialize(),
                'fulltickets'    => $fulltickets->serialize(),
                'registration'   => $registration->serialize(),
                'sponsors'       => $sponsors->serialize(),
                'fullsponsors'   => $fullsponsors->serialize(),
                'map'            => $map->serialize(),
                'contact'        => $contact->serialize(),
                'facebook'       => $facebook->serialize(),
                'instagram'      => $instagram->serialize(),
                'media'          => $media->serialize(),
                'news'           => $news->serialize(),
                'fullnews'       => $fullnews->serialize(),
                'social'         => $social->serialize(),
                'timer'          => $timer->serialize(),
                'twitter'        => $twitter->serialize(),
                'headline'       => $headline->serialize(),
                'strapline'      => $strapline->serialize(),
                'heading'        => $heading->serialize(),
                'html'           => $html->serialize(),
                'picture'        => $picture->serialize(),
                'columns-2'      => $columns2->serialize(),
                'columns-3'      => $columns3->serialize(),
                'columns-4'      => $columns4->serialize(),
                'button'         => $button->serialize(),
                'newsletter'     => $newsletter->serialize(),
                'samplepage'     => $samplepage->serialize()
            ));
        }

        private function loadTemplates() {
            $this->templates = apply_filters('ef_content_builder_templates', array(
                'template' => sprintf(EF_Content_Builder_Views::getViewContent('template-short'), 'ef-cb-template', '%s', 'fa fa-th-large', '%s'),
                'list'     => array(
                /* 'home' => array(
                  'speakers',
                  ...
                  ), */
                )
            ));
        }

        private function loadSectionsRelationship() {
            $this->relationships['sections'] = apply_filters('ef_content_builder_sections_relationship', array(
                'speakers'       => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'fullspeakers'   => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'exhibitors'     => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'fullexhibitors' => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'schedule'       => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'fullschedule'   => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'calltoaction'   => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'fulltickets'    => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'registration'   => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'sponsors'       => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'fullsponsors'   => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'map'            => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'contact'        => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'facebook-rsvp'  => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'instagram'      => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'media'          => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'news'           => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'fullnews'       => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'social'         => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'timer'          => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'twitter'        => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'headline'       => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'strapline'      => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'heading'        => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'html'           => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'picture'        => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'columns-2'      => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'columns-3'      => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'columns-4'      => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'button'         => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'newsletter'     => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
                'samplepage'     => array(
                    'template' => EF_Content_Builder_Views::getViewContent('entity-short')
                ),
            ));
        }

        /* private function loadEntities() {
          $this->entities = apply_filters('ef_content_builder_entities', array(
          'speakers' => $this->getEntityList('speaker'),
          'exhibitors' => $this->getEntityList('exhibitor'),
          'sessions' => $this->getEntityList('session'),
          'news' => $this->getEntityList('post')
          ));
          }

          private function getEntityList($postType) {
          $entities = array();
          $entities_posts = get_posts(array(
          'nopaging' => true,
          'post_type' => $postType
          ));
          foreach ($entities_posts as $entity) {
          $entities[$entity->ID] = array(
          'type' => $entity->post_type,
          'title' => $entity->post_title
          );
          }
          return $entities;
          } */

        public function getBuilderData() {
            $ret = array(
                'sections'     => $this->sections,
                'relationship' => $this->relationships,
                'templates'    => $this->templates
            );
            if (( defined('DOING_AJAX') && DOING_AJAX)) {
                echo json_encode($ret);
                exit;
            } else {
                return $ret;
            }
        }

        public function getEntities() {
            $ret       = array();
            $post_type = filter_input(INPUT_POST, 'type');
            $ids       = explode(',', filter_input(INPUT_POST, 'ids'));

            switch ($post_type) {
                case 'media':
                    $i = 0;
                    foreach ($ids as $id) {
                        $ret[$id] = array(
                            'type'  => null,
                            'title' => null,
                            'order' => $i++,
                        );
                        if (ctype_digit($id)) {
                            $thumbnail = wp_get_attachment_image_src($id);
                            if ($thumbnail && isset($thumbnail[0])) {
                                $ret[$id]['thumbnail'] = $thumbnail[0];
                            } else {
                                $ret[$id]['thumbnail'] = '';
                            }
                        } else {
                            $ret[$id]['thumbnail'] = EF_Framework_Helper::get_video_thumbnail($id, array('youtube' => 'default', 'vimeo' => 'thumbnail_small'));
                        }
                    }
                    break;
                case 'social':
                    $i       = 0;
                    $options = EF_Event_Options::get_theme_options();
                    foreach ($ids as $id) {
                        $title = '';
                        $icon  = '';

                        switch ($id) {
                            case 'ef_facebook':
                                $title = 'Facebook';
                                $icon  = 'facebook';
                                break;
                            case 'ef_twitter':
                                $title = 'Twitter';
                                $icon  = 'twitter';
                                break;
                            case 'ef_rss':
                                $title = 'Rss';
                                $icon  = 'rss';
                                break;
                            case 'ef_email':
                                $title = 'Email';
                                $icon  = 'envelope';
                                break;
                            case 'ef_google_plus':
                                $title = 'Google+';
                                $icon  = 'google-plus';
                                break;
                            case 'ef_flickr':
                                $title = 'Flickr';
                                $icon  = 'flickr';
                                break;
                            case 'ef_instagram':
                                $title = 'Instagram';
                                $icon  = 'instagram';
                                break;
                            case 'ef_pinterest':
                                $title = 'Pinterest';
                                $icon  = 'pinterest';
                                break;
                            case 'ef_linkedin':
                                $title = 'Linkedin';
                                $icon  = 'linkedin';
                                break;
                            case 'ef_add_this_pubid':
                                $title = 'AddThis';
                                $icon  = 'user-plus';
                                break;
                            case 'ef_youtube':
                                $title = 'Youtube';
                                $icon  = 'youtube';
                                break;
                            case 'ef_skype':
                                $title = 'Skype';
                                $icon  = 'skype';
                                break;
                            case 'ef_vimeo':
                                $title = 'Vimeo';
                                $icon  = 'vimeo-square';
                                break;
                        }
                        $ret[$id] = array(
                            'type'      => 'social',
                            'title'     => $title,
                            'order'     => $i++,
                            'thumbnail' => $icon
                        );
                    }
                    break;
                default:
                    $i            = 0;
                    $search_query = new WP_Query(array(
                        'post_type' => $post_type,
                        'nopaging'  => true,
                        'post__in'  => $ids,
                        'orderby'   => 'post__in'
                    ));
                    for ($i = 0; $i < count($search_query->posts); $i++) {
                        $entity           = $search_query->posts[$i];
                        $ret[$entity->ID] = array(
                            'type'  => $entity->post_type,
                            'title' => $entity->post_title,
                            'order' => $i
                        );
                    }
            }
            if (( defined('DOING_AJAX') && DOING_AJAX)) {
                echo json_encode($ret);
                exit;
            } else {
                return $ret;
            }
        }

        public function getSearchEntities() {
            $ret          = array();
            add_filter('posts_where', array($this, 'posts_where'), 10, 2);
            $search_query = new WP_Query(array(
                'post_type' => filter_input(INPUT_POST, 'type'),
                'nopaging'  => true,
                'title'     => filter_input(INPUT_POST, 'text')
            ));
            foreach ($search_query->posts as $entity) {
                $ret[$entity->ID] = array(
                    'type'  => $entity->post_type,
                    'title' => $entity->post_title
                );
            }
            remove_filter('posts_where', array($this, 'posts_where'));

            if (( defined('DOING_AJAX') && DOING_AJAX)) {
                echo json_encode($ret);
                exit;
            } else {
                return $ret;
            }
        }

        public function posts_where($where, $wp_query) {
            global $wpdb;
            $title = $wp_query->get('title');

            if (!empty($title)) {
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE "%' . esc_sql($wpdb->esc_like($title)) . '%"';
            }
            return $where;
        }

//        public function getTemplates() {
//            if (( defined('DOING_AJAX') && DOING_AJAX)) {
//                echo json_encode($this->templates);
//                exit;
//            } else {
//                return $this->templates;
//            }
//        }
//
//        public function getTemplateSections($template) {
//            if (( defined('DOING_AJAX') && DOING_AJAX)) {
//                echo json_encode($this->relationships['templates'][$template]);
//                exit;
//            } else {
//                return $this->relationships['templates'][$template];
//            }
//        }
//
//        public function getSections() {
//            if (( defined('DOING_AJAX') && DOING_AJAX)) {
//                echo json_encode($this->sections);
//                exit;
//            } else {
//                return $this->sections;
//            }
//        }
//
//        public function getSectionItems($section) {
//            if (( defined('DOING_AJAX') && DOING_AJAX)) {
//                echo json_encode($this->relationships['sections'][$section]);
//                exit;
//            } else {
//                return $this->relationships['sections'][$section];
//            }
//        }
//
//        public function getItemItems($item) {
//            if (( defined('DOING_AJAX') && DOING_AJAX)) {
//                echo json_encode($this->relationships['items'][$item]);
//                exit;
//            } else {
//                return $this->relationships['items'][$item];
//            }
//        }
//
//        public function getSectionsRelationship() {
//            if (( defined('DOING_AJAX') && DOING_AJAX)) {
//                echo json_encode($this->relationships);
//                exit;
//            } else {
//                return $this->relationships;
//            }
//        }

        public function adminEnqueueScripts($hook) {
            global $post;
            if (in_array($hook, array('post-new.php', 'post.php')) && in_array($post->post_type, $this->allowedPostTypes)) {
                wp_enqueue_script('wp-color-picker');
                wp_enqueue_script('sprintf', EF_CONTENT_BUILDER_URL . 'assets/js/sprintf.js?' . time(), null, null, true);
                wp_enqueue_script('jquery-ui', EF_CONTENT_BUILDER_URL . 'assets/js/jquery-ui.min.js?' . time(), array('jquery'), null, true);
                wp_enqueue_script('jquery-ui-timepicker', EF_CONTENT_BUILDER_URL . 'assets/js/jquery-ui-timepicker-addon.min.js', array('jquery-ui'), false, true);
                wp_enqueue_script('ef-cb-content-builder', EF_CONTENT_BUILDER_URL . 'assets/js/content-builder.js?' . time(), array('jquery', 'jquery-ui', 'sprintf', 'jquery-ui-timepicker'), null, true);
                wp_localize_script('ef-cb-content-builder', 'styleSheetDirectoryUri', get_template_directory_uri());
                wp_localize_script('ef-cb-content-builder', 'ef_strings', array(
                    'section_added'      => __('Section added', 'dxef'),
                    'edit'               => __('Edit', 'dxef'),
                    'close'              => __('Close', 'dxef'),
                    'hide_editor'        => __('Hide Editor', 'dxef'),
                    'show_editor'        => __('Show Editor', 'dxef'),
                    'columns'            => __('Columns', 'dxef'),
                    'add_columns'        => __('Add Columns', 'dxef'),
                    'columns_2'          => __('2 Columns', 'dxef'),
                    'columns_3'          => __('3 Columns', 'dxef'),
                    'columns_4'          => __('4 Columns', 'dxef'),
                    'buttons'            => __('Buttons', 'dxef'),
                    'add_button'         => __('Add Button', 'dxef'),
                    'large_ghost_button' => __('Large Ghost Button', 'dxef'),
                    'large_ghost_button' => __('Normal Ghost Button', 'dxef'),
                    'white_button'       => __('White Button', 'dxef'),
                    'black_button'       => __('Black Button', 'dxef'),
                    'hover_button'       => __('Hover Button', 'dxef'),
                    'remove_section'     => __('Do you want to remove this section=', 'dxef'),
                    'use_template'       => __('Do you want use this template, removing all existing sections?', 'dxef'),
                ));

                wp_enqueue_style('jquery-ui-timepicker', EF_CONTENT_BUILDER_URL . 'assets/css/jquery-ui-timepicker-addon.min.css');
                wp_enqueue_style('ef-cb-main', EF_CONTENT_BUILDER_URL . 'assets/css/content-builder.css?' . time());
                wp_enqueue_style('jquery-ui', EF_CONTENT_BUILDER_URL . 'assets/css/jquery-ui.min.css?' . time());
                wp_enqueue_style('font-awesome', EF_CONTENT_BUILDER_URL . 'assets/css/font-awesome/css/font-awesome.min.css');
                wp_enqueue_style('wp-color-picker');
            }
        }

        public function enqueueScripts() {
            global $post;

            if ($post) {
                if (in_array($post->post_type, $this->allowedPostTypes)) {
                    $customCss = get_post_meta($post->ID, 'ef-cb-custom-css', true);
                    if (!empty($customCss)) {
                        wp_add_inline_style('khore-style-main', $customCss);
                    }
                }
            }
        }

        public function savePost($post_id, $post, $update) {
            if (wp_is_post_revision($post_id))
                return;
            if (in_array($post->post_type, $this->allowedPostTypes)) {
                if (!empty($_REQUEST['ef-cb-custom-css'])) {
                    update_post_meta($post_id, 'ef-cb-custom-css', $_REQUEST['ef-cb-custom-css']);
                }
            }
        }

        public function addContentBeforeEditor() {
            ?>
            <div class="ef-cb-editor-button-container">
                <input type="button" class="button-primary ef-cb-editor-button" value="<?php _e('Show Editor', 'dxef'); ?>" data-editor-hidden="1" />
            </div>
            <?php
        }

        public function addBuilderMetabox() {
            foreach ($this->allowedPostTypes as $screen) {
                add_meta_box(
                        'ef-cb-main', __('Content Builder', 'dxef'), array($this, 'builderMetaboxContent'), $screen
                );
            }
        }

        public function mce_external_plugins($plugins) {
            $plugins['code'] = EF_CONTENT_BUILDER_URL . 'assets/js/tinymce.plugin.code.js';
            $plugins['link'] = EF_CONTENT_BUILDER_URL . 'assets/js/tinymce.plugin.link.js';
            return $plugins;
        }

        public function builderMetaboxContent() {
            global $post;
            $customCss = get_post_meta($post->ID, 'ef-cb-custom-css', true);
            ?>
            <div id="ef-cb-builder">
                <div class="selector">
                    <div class="fixed-selectors">
                        <input type="button" class="button-primary save-all" value="<?php _e('Save', 'dxef'); ?>" />
                        <input type="button" class="button-primary back-to-top" value="<?php _e('Back to top', 'dxef'); ?>" />
                        <input type="button" class="button-primary templates-selector" value="<?php _e('View Templates', 'dxef'); ?>" />
                        <input type="button" class="button-primary sections-selector" value="<?php _e('View Items', 'dxef'); ?>" />
                        <input type="button" class="button-primary custom-css" value="<?php _e('Custom CSS', 'dxef'); ?>" />
                    </div>
                    <input type="button" class="button-primary custom-css" value="<?php _e('Custom CSS', 'dxef'); ?>" />
                    <?php if (count($this->templates['list'])) { ?>
                        <h3 class="templates-title"><?php _e('Click on a template to use it in you content', 'dxef'); ?></h3>
                        <div id="ef-cb-templates">
                        </div>
                    <?php } ?>
                    <h3 class="sections-title"><?php _e('Click on a item to add it to the content', 'dxef'); ?></h3>
                    <div id="ef-cb-sections">
                    </div>
                </div>
                <div class="scrollable">
                    <div id="ef-cb-content">
                    </div>
                    <div id="ef-cb-sections-form">
                    </div>
                </div>
                <input type="hidden" name="ef-cb-custom-css" value="<?php echo $customCss; ?>" />
            </div>
            <?php
        }

    }

}