<?php

namespace App\Http\Controllers\Datatable;

use App\Classes\Strategies\DatatableStrategy;
use App\Classes\GoogleChat\GoogleChatBuilder;
use App\Enums\DatatableTypeEnum;
use App\Notifications\GoogleChatCardNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Yajra\Datatables\Datatables;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Services\EventService;

class DatatableController
{
    use ResponseTrait;
    
    private array $types = [];

    public function __construct()
    {
        foreach (DatatableTypeEnum::cases() as $enum) {
            $this->types[] = $enum->value;
        }
    }

    /**
     * Handle the incoming request.
     *
     * @param $type
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke($type, Request $request): JsonResponse
    {
        if (!in_array($type, $this->types)) {
            return $this->errorResponse(__('messages.errors.type_not_found'));
        }

        try {
            $strategy = new DatatableStrategy(new EventService());

            return Datatables::of($strategy->createQuery($type, $request->user()))->make(true);
        } catch (Exception $e) {
            $builder = (new GoogleChatBuilder)
                ->title('Handler')
                ->message(
                    json_encode([
                        "error" => $e->getMessage(),
                        "route" => $e->getFile(),
                        "line" => $e->getLine()
                    ])
                );
            Notification::send(null, new GoogleChatCardNotification($builder));
            return response()->json($e);
        }
    }
}
