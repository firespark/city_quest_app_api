<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    
    public $status = 200;
    public $success = 0;
    public $data = [];
    public $error = '';

    public $statuses = [
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    ];


    public function setStatus($status)
    {
        $this->status = $status;
        
    }

    public function toggleSuccess()
    {
        $this->success = ($this->success == 1) ? 0 : 1;
        
    }

    public function setData($data)
    {
        $this->data = $data;
        
    }

    public function setError($error)
    {
        $this->error = $error;
        
    }

    protected function getErrorFromStatuses()
    {
        if ($this->status != 200 && $this->error == '')
        {
            $this->error = $this->statuses[$this->status];
        }
        
    }

    public function responseData()
    {
        $this->getErrorFromStatuses();

        return [
            'status' => $this->status,
            'success' => $this->success,
            'data' => $this->data,
            'error' => $this->error
        ];
        
    }

   
}
