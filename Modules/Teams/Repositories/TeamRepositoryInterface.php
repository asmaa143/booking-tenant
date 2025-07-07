<?php

namespace Modules\Teams\Repositories;

interface TeamRepositoryInterface
{
    public function allByTenant();
    public function createForTenant(array $data);

    public function setAvailability(int $teamId, array $availabilities);

    public function findById(int $id, array $with = []);



}
