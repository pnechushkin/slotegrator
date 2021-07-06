<?php


namespace App\Services;


use App\Exceptions\PrizePaidOutExceptions;
use App\Interfaces\MoneyPaidOutInterface;
use App\Models\Prize;

class PaidOutMoney implements \App\Interfaces\PrizePaidOutInterface, MoneyPaidOutInterface
{

    /**
     * type for payd out money. Default type - bank
     * @var string
     */
    protected $type = Prize::BANK;

    /**
     * @inheritDoc
     */
    public function prizePaidOut($id): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function setTypePaidOut(string $type = Prize::BANK): bool
    {
        if (!in_array($type, Prize::TYPES_PAID_OUT_MONEY)) {
            throw new PrizePaidOutExceptions ("Undefined type $type for paid out money");
        }
        // TODO логика отправки запроса на банк по выводу денег.
        // TODO Сбор параметров и отправка запроса и обработка ответа.
        // TODO Лог запроса и ответа
        // TODO При положительном ответе возвращаем true
        // TODO При ошибках кидаем PrizePaidOutExceptions

        /*
        * Можно конечно вынести в отдельный класс, но в более поздних версиях уже есть клиент
         * https://laravel.com/docs/8.x/http-client
        */
        $url = env('API_URL_BANK');
        $headers = [
            "Content-Type: application/json",
            "Authorization: Basic ",
        ];
        $parameters = [
            'param1'=>'val1',
            'param2'=>'val2',
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        $output = curl_exec ($ch);
        $info = curl_getinfo($ch);
        $http_result = $info ['http_code'];
        curl_close ($ch);
        return true;

    }
}
