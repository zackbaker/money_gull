<?php

use League\Fractal;

class ApiModel
{
    protected $db;
    protected $Fractal;

    public function __construct()
    {
        $this->db = $this->connect();
        $this->Fractal = new Fractal\Manager();
    }

    public function connect()
    {
        $connection = new DB\SQL(
            'mysql:host=localhost;port=3306;dbname=money_gull',
            'root',
            'money_gull'
        );

        return ($connection) ?
            $connection :
            false;
    }

    public function sanatizeData($data)
    {
        $additional_filter = '';

        foreach ($data as $field => $input) {
            switch ($field) {
                case 'email':
                    $filter = FILTER_SANITIZE_EMAIL;
                    break;

                case 'account_amount':
                case 'amount_needed':
                case 'amount_saved':
                    $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                    $additional_filter = FILTER_FLAG_ALLOW_FRACTION;
                    break;

                case 'page_num':
                    $filter = FILTER_SANITIZE_NUMBER_INT;
                    break;

                default:
                    $filter = FILTER_SANITIZE_STRING;
                    break;
            }

            $data[$field] = trim(filter_var($input, $filter, $additional_filter));
        }

        return $data;
    }

    protected function createTransformer(
        $data = [],
        $includes = [],
        $pagination = []
    ) {
        if (count($data['data']) > 1) {
            $transformer = new Fractal\Resource\Collection($data['data'], new $data['transformer']);
        } else {
            $transformer = new Fractal\Resource\Item($data['data'][0], new $data['transformer']);
        }

        $data = $this->Fractal->createData($transformer)->toArray();

        if (!empty($pagination)) {
            $data['meta'] = $pagination;
        }

        if (!empty($includes)) {
            foreach ($includes as $transform => $values) {
                $table = str_replace('transformer', '', strtolower($transform)) . 's';
                $transformer = new Fractal\Resource\Collection($values, new $transform);
                $data['data'][$table] = current($this->Fractal->createData($transformer)->toArray());
            }
        }

        return json_encode($data);
    }

    protected function getPagination($userId, $controller, $pageInfo = [])
    {
        $totalCount = $pageInfo['totalCount'];
        $pageNum = $pageInfo['pageNum'];
        $totalPages = ceil($totalCount / $this->pageCount);
        $nextPage = $pageNum + 1;
        $previousPage = $pageNum - 1;

        if ($nextPage < $totalPages) {
            $nextUrl = '/users/' . $userId . '/' . $controller . '?page_num=' . $nextPage;
        } else {
            $nextUrl = null;
        }

        if ($previousPage > 0) {
            $previousUrl = '/users/' . $userId . '/' . $controller . '?page_num=' . $previousPage;
        } else {
            $previousUrl = 'null';
        }

        return [
            'total' => (int) $totalCount,
            'count' => 'null',
            'per_page' => $this->pageCount,
            'current_page' => (int) $pageNum,
            'total_pages' => $totalPages,
            'next_url' => $nextUrl,
            'previous_url' => $previousUrl,
        ];
    }
}
