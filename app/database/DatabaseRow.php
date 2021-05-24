<?php


namespace BoShop\Database;


use BoShop\Factory\DatabaseFactory;
use BoShop\Tools\SimpleTools;
use Medoo\Medoo;

class DatabaseRow extends \stdClass
{

    protected static string $tableName;
    protected static string $primaryKey;
    protected static array $ignoreVars = [];
    protected static ?array $callbacksLoad = null;

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

        //Fix if data was not found
        if($data ?? null)
            $this->setData($data);

        return $this;
    }

    public function getAllByWhere(array $where): array {
        $data = static::$database->select(static::$tableName, "*", $where);
        $classReady = [];

        foreach ($data as $dataParsed) {
            $classCloned = clone $this;
            $classCloned->setData($dataParsed);

            $classReady[] = $classCloned;
        }

        return $classReady;
    }

    public function save($forceInsert = false): bool {
        //Change stdClass to array and after that remove primaryKey update
        $dataUpdate = SimpleTools::stdClassToArray($this);
        unset($dataUpdate[static::$primaryKey]);

        //Ignored variables remove
        foreach (static::$ignoreVars as $key => $ignoredVar) {
            if(array_key_exists($ignoredVar, $dataUpdate)) {
                unset($dataUpdate[$ignoredVar]);
            }
        }

        //Update all dependencies (classes)
        $dataUpdate = $this->getDependencies($dataUpdate);

        //Adding force insert
        if($forceInsert) {
            static::$database->insert(static::$tableName, $dataUpdate);
            $this->{static::$primaryKey} = static::$database->id();

            return true;
        }

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

    public function getPrimaryKeyValue() {
        return $this->{static::$primaryKey};
    }

    private function setData(array $data): void {
        foreach ($data as $key => $value) {
            if(in_array($key, static::$ignoreVars, true)) {
                continue;
            }

            //Bug fix before fatal error (exception) if column is not defined in code
            if(!property_exists($this, $key)) {
                continue;
            }

            $propertyType = SimpleTools::getPropertyType($this, $key);
            //Fetch model by ID
            if(!in_array($propertyType, ["int", "string", "bool", "float", "double"]) && (new $propertyType()) instanceof DatabaseRow) {
                //null value fix
                if($value === null) {
                    $this->{$key} = $value;
                    continue;
                }

                /** @var DatabaseRow $class */
                $class = new $propertyType();
                $class->getById($value);
                $this->{$key} = $class;
            } else {
                $this->{$key} = $value;
            }
        }

        $this->runCallbacks();
    }

    private function getDependencies(array $vars): array {
        foreach ($vars as $key => $value) {
            /** @var DatabaseRow $var */
            $var = $this->{$key};

            if($var instanceof self) {
                $vars[$key] = $var->getPrimaryKeyValue();
            }
        }

        return $vars;
    }

    private function runCallbacks(): void {
        if(static::$callbacksLoad !== null) {
            foreach (static::$callbacksLoad as $callback) {
                $this->{$callback}();
            }
        }
    }
}