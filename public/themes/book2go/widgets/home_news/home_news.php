<?php

use Botble\Widget\AbstractWidget;

class HomeNewsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $frontendTemplate = 'frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'backend';

    /**
     * @var string
     */
    protected $widgetDirectory = 'home_news';

    /**
     * Widget constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Home News',
            'description' => __('This is Home News for Homepage'),
            'number' => ''
        ]);
    }
}