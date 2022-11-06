<?php

namespace App\Interfaces;

interface ApiRepoInterfaces
{
    public function updateSetting(array $data);

    public function storeEmployees(array $data);

    public function storeOvertimes(array $data);

    public function overtimePay(array $data);
}
