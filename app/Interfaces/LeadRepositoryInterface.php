<?php
namespace App\Repositories;
use App\Models\Lead;


interface LeadRepositoryInterface {
    public function getAll();
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

}