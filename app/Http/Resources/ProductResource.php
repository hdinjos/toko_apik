<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public $success;
    public $message;
    public $resource;

    public function __construct($success, $message, $resource)
    {
        parent::__construct($resource);
        $this->success = $success;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->resource) {
            return [
                "success" => $this->success,
                "message" => $this->message,
                "data" => $this->resource,
            ];
        } else {
            return [
                "success" => $this->success,
                "message" => $this->message,
            ];
        }
    }
}
