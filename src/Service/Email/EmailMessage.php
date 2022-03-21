<?php


namespace App\Service\Email;


class EmailMessage
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data    = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

}
