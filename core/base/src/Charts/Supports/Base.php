<?php

namespace Botble\Base\Charts\Supports;

use Assets;

/**
 * Main model class
 */
class Base
{

    /**
     * Type of chart. This value is used in Javascript Morris method
     *
     * @brief Chart
     *
     * @var string $__chart_type
     */
    protected $__chart_type = ChartTypes::LINE;

    /**
     * The ID of (or a reference to) the element into which to insert the graph.
     * Note: this element must have a width and height defined in its styling.
     *
     * @brief Element
     *
     * @var string $element
     */
    protected $element = '';

    /**
     * The data to plot. This is an array of objects, containing x and y attributes as described by the xkey and ykeys
     * options. Note: the order in which you provide the data is the order in which the bars are displayed.
     *
     * Note 2: if you need to update the plot, use the setData method on the object that Morris.Bar
     * returns (the same as with line charts).
     *
     * @brief Data
     *
     * @var array $data
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $functions = [
        'hoverCallback',
        'formatter',
        'dateFormat',
    ];

    /**
     * Create an instance of Morris class
     *
     * @brief Construct
     *
     * @param string $elementId The element id
     * @param string $chart Optional. Chart Type of chart. Default ChartTypes::LINE
     *
     * @return Base
     */
    public function __construct($chart = ChartTypes::LINE)
    {
        $this->__chart_type = $chart;
        $this->element = $chart . '_' . str_random(12);
    }

    /**
     * @param $elementId
     */
    public function setElementId($elementId)
    {
        $this->element = $elementId;
        return $this;
    }

    /**
     * @return string
     */
    public function getElementId()
    {
        return $this->element;
    }

    /**
     * Return the array of this object
     *
     * @brief Array
     *
     * @return array
     */
    public function toArray()
    {
        $return = [];
        foreach ($this as $property => $value) {
            if ('__' == substr($property, 0, 2) || '' === $value || empty($value) || (is_array($value) && empty($value))) {
                continue;
            }

            if (in_array($property, $this->functions) && substr($value, 0, 8) == 'function') {
                $value = '%' . $property . '%';
            }

            $return[$property] = $value;
        }

        return $return;
    }

    /**
     * Return the jSON encode of this chart
     *
     * @brief JSON
     *
     * @return string
     */
    public function toJSON()
    {
        $json = json_encode($this->toArray());

        return str_replace(
            [
                '"%hoverCallback%"',
                '"%formatter%"',
                '"%dateFormat%"',
            ],
            [
                $this->hoverCallback,
                $this->formatter,
                $this->dateFormat,
            ],
            $json
        );
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        foreach ($this as $key => $value) {
            if ($name == $key) {
                return $this->{$key};
            }
        }

        $method = 'get' . ucfirst($name) . 'Attribute';

        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }

        return null;
    }

    /**
     * @param $name
     * @param $arguments
     * @return Base|bool
     */
    public function __call($name, $arguments)
    {
        foreach ($this as $key => $value) {
            if ($name == $key) {
                $this->{$key} = $arguments[0];

                return $this;
            }
        }

        return false;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function renderChart()
    {
        Assets::addStylesheets(['morris']);
        Assets::addJavascript(['morris', 'raphael']);

        $this->init();

        $chart = $this;

        return view('core.base::charts.chart', compact('chart'))->render();
    }

    /**
     * @return $this
     */
    public function init()
    {
        return $this;
    }
}