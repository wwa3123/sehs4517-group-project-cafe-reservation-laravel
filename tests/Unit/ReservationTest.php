<?php

namespace Tests\Unit;

use App\Models\Reservation;
use Carbon\Carbon;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    public function test_date_assignment_is_normalized_to_a_date_only_value(): void
    {
        $reservation = new Reservation;
        $reservation->date = '2026-10-01 18:30:00';

        $this->assertSame('2026-10-01', $reservation->getAttributes()['date']);
        $this->assertInstanceOf(Carbon::class, $reservation->date);
        $this->assertSame('2026-10-01', $reservation->date->toDateString());
    }
}
