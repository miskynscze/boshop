<?php


namespace BoShop\Database;


use BoShop\factory\DatabaseFactory;
use BoShop\tools\SimpleTools;
use Medoo\Medoo;

class DatabaseRow extends \stdClass
{

    protected static string $tableName;
    protected static string $primaryKey;

    private static Medoo $database;

    public function __construct() {
        static::$database = DatabaseFactory::produce()->getDb();
    }

    public function getById(int $id): ?self {
        if(!$this->exists($id))
            return null;

        $data = static::$database->get(static::$tableName, "*", [
            static::$primaryKey => $id
        ]);

        $this->setData($data);
        return $this;
    }

    public function getByWhere(array $where): self {
        $data = static::$database->get(static::$tableName, "*", $where);

        $this->setData($data);
        return $this;
    }

    public function save(): bool {
        //Change stdClass to array and after that remove primaryKey update
        $dataUpdate = SimpleTools::stdClassToArray($this);
        unset($dataUpdate[static::$primaryKey]);

        if($this->{static::$primaryKey} ?? null) {
            static::$database->update(static::$tableName, $dataUpdate, [
                static::$primaryKey => $this->{static::$primaryKey}
            ]);
        } else {
            static::$database->insert(static::$tableName, $dataUpdate);
            $this->{static::$primaryKey} = static::$database->id();
        }

        return true;
    }

    public function delete(): bool {
        if($this->{static::$primaryKey} ?? null) {
            static::$database->delete(static::$tableName, [
                static::$primaryKey => $this->{static::$primaryKey}
            ]);

            return true;
        }

        return false;
    }

    public function exists($id = null): bool {
        if($id === null)
            $id = $this->{static::$primaryKey};

        if($id === null)
            return false;

        return static::$database->has(static::$tableName, [
            static::$primaryKey => $id
        ]);
    }

    private function setData(array $data): void {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}