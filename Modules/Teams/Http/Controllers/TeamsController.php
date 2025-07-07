<?php

namespace Modules\Teams\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Teams\Http\Requests\GenerateSlotsRequest;
use Modules\Teams\Http\Requests\SetAvailabilityRequest;
use Modules\Teams\Http\Resources\TeamResource;
use Modules\Teams\Models\Team;
use Modules\Teams\Repositories\TeamRepositoryInterface;
use Modules\Teams\Services\TimeSlotService;
use Modules\Tenants\Models\Tenant;

class TeamsController extends Controller
{
    use ApiResponseTrait;



    protected $teams;
    protected TimeSlotService $slots;
    public function __construct(TeamRepositoryInterface $teams,TimeSlotService $slots)
    {
        $this->teams = $teams;
        $this->slots = $slots;
    }

    public function index()
    {
        $teams = $this->teams->allByTenant();

        return $this->responseData(TeamResource::collection($teams), msg: 'Teams retrieved successfully');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);

        $team = $this->teams->createForTenant($data);

        return $this->responseData(new TeamResource($team), msg:'Team created successfully');
    }

    public function setAvailability(SetAvailabilityRequest $request, $id)
    {
        $availabilities = $this->teams->setAvailability($id, $request->validated()['availabilities']);

        return $this->responseData($availabilities, msg: 'Availability set successfully');
    }

    public function generateSlots(GenerateSlotsRequest $request,$id)
    {


        $team = $this->teams->findById($id, ['availabilities', 'bookings']);

        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $slots = $this->slots->generateSlots($team, $from, $to);

        return $this->responseData($slots, msg: 'Available slots generated successfully.');
    }

}
