<?php

interface LeadRepositoryInterface {
    public function getAll();
    public function create(array $data);
}