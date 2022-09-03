<?php

namespace App\Events;

use App\Models\Staff;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StaffRoleChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $staff;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Staff $staff)
    {
        $this->staff = $staff;
    }

}
