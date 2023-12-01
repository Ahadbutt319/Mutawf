<?php

namespace App\Interfaces;

interface groundServiceRepositoryInterface 
{
    public function createGroundService(array $groundServiceDetails);
    public function getAllGroundServices();
    public function getDetailGroundServices(int $groundServiceId);
    public function searchGroundService(array $groundServicSearch);
    public function bookGroundService(array $bookingdetail);
}