<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiJsonResponse;
use App\Models\Prize;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * @var ApiJsonResponse
     */
    protected $response;

    public function __construct()
    {
        $this->response = new ApiJsonResponse();
    }

    /**
     * Get Api Token Request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($errors = $validator->errors()->all()) {
            foreach ($errors as $message) {
                $this->response->addError($message);
            }

            return $this->response->response();
        }

        $user = User::getUserByMail($request->input('email'));

        if (empty($user)) {
            return $this->response->addError('User is not found')->response();
        }
        if (!\Hash::check($request->input('password'), $user->password)) {
            return $this->response->addError('Password is incorrect')->response();
        }

        return $this->response->setData($user->api_token)->response();
    }

    public function getPrize(): \Illuminate\Http\JsonResponse
    {
        if (!\Auth::id()) {
            Log::error('Undefined user for get prize');
            return $this->response->addError('Undefined user!')->response();
        }
        $randomPrize = (object)Prize::getRandomPrize(\Auth::id());
        if (!empty($randomPrize->type) && !empty($randomPrize->value)) {
            return $this->response->setData($randomPrize)->response();
        }
        Log::error('Undefined prize: ' . var_export($randomPrize, 1));
        return $this->response->addError('Undefined prize. Try please latter!')->response();

    }

    public function paidOutPrize($id): \Illuminate\Http\JsonResponse
    {
        $paidOutPrizeResult = Prize::paidOut($id);
        if ($paidOutPrizeResult === true) {
            return $this->response->response();
        } else {
            return $this->response
                ->addError((is_string($paidOutPrizeResult)) ? $paidOutPrizeResult : "Undefined error. Please try again later.")
                ->response();
        }

    }
}
