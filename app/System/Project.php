<?php


namespace BoShop\System;


use BoShop\Database\DatabaseRow;

class Project extends DatabaseRow
{

    protected static string $primaryKey = "project_id";
    protected static string $tableName = "projects";

    public int $project_id;

    public string $name;

    public bool $activated = true;

    public function isActivated(): bool {
        return $this->activated;
    }

    public function getProjectName(): string {
        return $this->name;
    }

    public function deactivate(): void {
        $this->activated = false;
        $this->save();
    }

    public function activate(): void {
        $this->activated = true;
        $this->save();
    }
}