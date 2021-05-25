<?php


namespace BoShop\System\Users;


use BoShop\Database\DatabaseRow;
use BoShop\Tools\ProjectTools;
use function _HumbugBoxa991b62ce91e\React\Promise\Stream\first;

class User extends DatabaseRow
{

    protected static string $primaryKey = "user_id";
    protected static string $tableName = "users";
    protected static array $ignoreVars = [];

    public int $user_id;

    public UserAddress $deliveryAddress;
    public UserAddress $invoiceAddress;

    public string $firstname;
    public string $lastname;

    public string $email;
    public string $password;

    public bool $activated = false;
    public bool $blocked = false;
    public bool $verified = false;

    public ?string $verifyCode = null;

    public string $dateCreated;
    public string $dateUpdated;

    public function createUser(): bool
    {
        $this->password = (string)password_hash($this->password, PASSWORD_DEFAULT);
        return $this->save();
    }

    public function getFullname(): string {
        return $this->getFirstname() . " " . $this->getLastname();
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function getByEmail(string $emailAddress): self {
        $this->getByWhere([
            "email" => $emailAddress
        ]);

        return $this;
    }

    public function login(string $emailAddress, string $password): bool {
        $this->getByEmail($emailAddress);

        if($this->blocked || !$this->activated) {
            return false;
        }

        if(password_verify($this->password, $password) !== false) {
            ProjectTools::setLoggedUser($this);
            return true;
        }

        return false;
    }

    public function verify(string $verifyCode): bool {
        if($this->verified) {
            return false;
        }

        if($this->verifyCode === $verifyCode) {
            $this->verified = true;
        }

        $this->verifyCode = null;
        $this->save();

        return true;
    }
}