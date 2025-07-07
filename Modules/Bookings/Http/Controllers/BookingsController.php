<?php

namespace Modules\Bookings\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Bookings\Http\Requests\StoreBookingRequest;
use Modules\Bookings\Http\Resources\BookingResource;
use Modules\Bookings\Models\Booking;
use Modules\Bookings\Repositories\BookingRepositoryInterface;
use Modules\Bookings\Services\BookingServiceInterface;
use Modules\Tenants\Models\Tenant;

class BookingsController extends Controller
{
    use ApiResponseTrait;
    protected $bookings;

    public function __construct(BookingServiceInterface $bookings)
    {
        $this->bookings = $bookings;
    }

    public function index(Request $request)
    {
        $bookings = $this->bookings->listUserBookings($request->user()->id);

        return $this->responseData(BookingResource::collection($bookings),msg: "Bookings retrieved successfully.");

    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = Tenant::current()?->id;
        $data['user_id'] = $request->user()->id;

        try {
            $booking = $this->bookings->createBooking($data);
            return $this->responseData(new BookingResource($booking),msg: "Booking created successfully");
        } catch (\Exception $e) {

            return $this->errorResponse(409,msg: "Failed to create booking: " . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->bookings->cancelBooking($id, $request->user()->id);

        return $this->responseData(null,msg: "Booking cancelled successfully");


    }

}
