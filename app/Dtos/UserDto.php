<?php 

namespace App\Dtos;

use App\Interfaces\DtoInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class UserDto implements DtoInterface 
{
    private ?int $id;
    private string $email;
    private string $name;
    private string $phone_number;

    private ?string $pin;
    private string $password;

    private ?Carbon $created_at;

    private ?Carbon $updated_at;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }
    public function setName(string $name) : void {
        $this->name = $name;
    }

    public function getEmail(): string {
        return $this->email;
    }
    public function setEmail(string $email) : void {
        $this->email = $email;
    }

    public function getPhoneNumber(): string {
        return $this->phone_number;
    }
    public function setPhoneNumber(string $phone_number) : void {
        $this->phone_number = $phone_number;
    }

    public function getPin(): string {
        return $this->pin;
    }
    public function setPin(string $pin) : void {
        $this->pin = $pin;
    }

    public function getPassword(): string {
        return $this->password;
    }
    public function setPassword(string $password) : void {
        $this->password = $password;
    }

    public function getCreatedAt(): Carbon {
        return $this->created_at;
    }
    public function setCreatedAt(Carbon $created_at) : void {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): Carbon {
        return $this->updated_at;
    }
    public function setUpdatedAt(Carbon $updated_at) : void {
        $this->updated_at = $updated_at;
    }

    /**
     * Creates a new instance from the given FormRequest.
     * 
     * The FormRequest should contain the following fields:
     * - email
     * - name
     * - phone_number
     * - password
     * - pin (optional)
     *
     * @param FormRequest $request
     * @return DtoInterface
     */
    public static function fromApiFormRequest(FormRequest $request): DtoInterface
    {
        $userDto = new UserDto();

        $userDto->setName($request->name);
        $userDto->setEmail($request->email);
        $userDto->setPhoneNumber($request->phone_number);
        $userDto->setPassword($request->password);
        return $userDto;
    }

    public static function fromModel(Model $model): DtoInterface{
        $userDto = new UserDto();

        $userDto->setId($model->id);
        $userDto->setName($model->name);
        $userDto->setEmail($model->email);
        $userDto->setPhoneNumber($model->phone_number);
        $userDto->setPassword($model->password);
        $userDto->setPin($model->pin);
        $userDto->setCreatedAt($model->created_at);
        $userDto->setUpdatedAt($model->updated_at);
        return $userDto;
    }

    public static function toArray(Model $model): array{
        return [
            'id' => $model->id,
            'name' => $model->name,
            'email' => $model->email,
            'phone_number' => $model->phone_number,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ];
    }

}