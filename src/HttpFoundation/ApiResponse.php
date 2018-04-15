<?php

declare(strict_types=1);

namespace App\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;

class ApiResponse extends Response
{
    protected $data;

    public function __construct($data = null, $status = 200, $headers = [])
    {
        parent::__construct('', $status, $headers);

        if (null === $data) {
            $data = new \ArrayObject();
        }

        $this->setData($data);
        $this->headers->set('Content-Type', 'application/json');
    }

    public function setData($data = [])
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
