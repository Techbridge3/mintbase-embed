<?php

namespace TBMintbase\Model;

/**
 * Class PageTypeTemplateMapper
 *
 * @package TBMycredCoupons\Model
 */
class PageTypeTemplateMapper
{
    /**
     * @var PageTypeTemplateMapper
     */
    private static $instance;

    /**
     * @var array
     */
    protected $templates;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config $config
     * @param array $templates
     *
     * @return PageTypeTemplateMapper
     */
    public static function getInstance(Config $config, array $templates)
    {
        if (null == self::$instance ) {
            self::$instance = new PageTypeTemplateMapper($config, $templates);
        }
        return self::$instance;
    }

    /**
     * PageTypeTemplateMapper constructor.
     *
     * @param Config $config
     * @param array $templates
     */
    private function __construct(Config $config, array $templates)
    {

        add_filter('theme_page_templates',[$this, 'addNewTemplates']);
        add_filter('page_attributes_dropdown_pages_args', [$this, 'registerProjectTemplates']);
        add_filter('wp_insert_post_data', [$this, 'registerProjectTemplates']);
        add_filter('template_include', [$this, 'viewTemplate']);
        $this->config = $config;
        $this->templates = $templates;
    }

    /**
     * @param $postsTemplates
     *
     * @return array
     */
    public function addNewTemplates($postsTemplates)
    {
        $postsTemplates = array_merge($postsTemplates, $this->templates);
        return $postsTemplates;
    }

    /**
     * @param $args
     * @return mixed
     */
    public function registerProjectTemplates($args)
    {
        $cacheKey = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());
        $templates = wp_get_theme()->get_page_templates();
        if (empty($templates)) {
            $templates = [];
        }
        wp_cache_delete($cacheKey, 'themes');
        $templates = array_merge($templates, $this->templates);
        wp_cache_add($cacheKey, $templates, 'themes', 1800);
        return $args;
    }

    /**
     * @param $template
     * @return string
     */
    public function viewTemplate($template)
    {
        global $post;
        if (!$post) {
            return $template;
        }
        if (!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])) {
            return $template;
        }
        $file = $this->config->getTemplatesPath()
            . '/'
            . get_post_meta($post->ID, '_wp_page_template', true);
        if (file_exists($file)) {
            return $file;
        } else {
           $this->pageNotFound();
        }
        return $template;
    }

    protected function pageNotFound()
    {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        get_template_part(404);
        exit;
    }
}
