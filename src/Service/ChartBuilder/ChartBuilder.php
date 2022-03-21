<?php


namespace App\Service\ChartBuilder;


use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartBuilder
{
    /**
     * @var ChartBuilderInterface
     */
    protected $chartBuilder;

    protected $labels = [];
    protected $values = [];
    protected $colors = [];
    protected $fields  = '';
    protected $fieldsValue  = '';
    protected $chart = null;
    protected $title;
    protected $legendDisplay;
    protected $type;
    const TYPES = [
        'pie' => Chart::TYPE_PIE,
    ];
    private $fieldFunction = null;

    public function __construct(ChartBuilderInterface $chartBuilder)
    {
        $this->chartBuilder = $chartBuilder;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): self
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @param string $fields
     */
    public function setFields(string $fields): self
    {
        $this->fields = $fields;

        if (strstr($fields, '::')) {
            list ($this->fields, $this->fieldFunction) = explode('::', $fields);
        }

        return $this;
    }

    /**
     * @param string $fieldsValue
     */
    public function setFieldsValue(string $fieldsValue): self
    {
        $this->fieldsValue = $fieldsValue;
        return $this;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param mixed $legendDisplay
     */
    public function setLegendDisplay($legendDisplay): self
    {
        $this->legendDisplay = $legendDisplay;
        return $this;
    }

    protected function makeData(array $inputs)
    {
        foreach ($inputs as $input) {
            if ($this->fieldFunction) {
                $this->labels[] = $input[$this->fields]->{$this->fieldFunction}();
                $this->values[] = $input[$this->fieldsValue];
            } else {
                $this->labels[] = $input[$this->fields];
                $this->values[] = $input[$this->fieldsValue];
            }
        }
    }

    protected function makeChart()
    {
        $chart = $this->chartBuilder->createChart(self::TYPES[$this->type]);

        $chart->setData([
            'labels' => $this->labels,
            'hover' => false,
            'datasets' => [
                [
                    'label' => $this->title,
                    'backgroundColor' => [
                        'rgb(225, 100, 100)',
                        'rgb(255, 200, 0)',
                        'rgb(50, 200, 150)',
                    ],
                    'hoverBackgroundColor' => [
                        'rgb(225, 100, 100)',
                        'rgb(255, 200, 0)',
                        'rgb(50, 200, 150)',
                    ],
                    'borderColor' => 'rgb(255,255,255)',
                    'data' => $this->values,
                ]
            ]
        ]);

        $chart->setOptions([
            'legend' => [
                'display' => true,
                'position' => $this->legendDisplay,
            ]
        ]);

        $this->chart = $chart;
    }

    public function getChart($inputs): Chart
    {
        $this->makeData($inputs);
        $this->makeChart();
        return $this->chart;
    }
}
