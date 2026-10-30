<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Staff registration requests (Staff Management → "Requests" modal).
 *
 * A staff member who registers from the staff portal is saved with
 * status = 'pending'. Here the admin either:
 *   • accepts  → status becomes 'registered' and the account can log in, or
 *   • rejects  → the request is removed (so the person may re-apply).
 *
 * Activity-log entries are written automatically by the LogAdminActivity
 * middleware (every admin PUT/DELETE route), so nothing is logged here.
 */
class AdminStaffRequestsController extends Controller
{
    private const AVATAR_CLASSES = ['bg-staff-1', 'bg-staff-2', 'bg-staff-3', 'bg-staff-4', 'bg-staff-5', 'bg-staff-6'];

    /** GET /admin/staffs/requests (JSON) — all pending requests, oldest first. */
    public function index(): JsonResponse
    {
        $requests = Staff::pending()
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->values()
            ->map(fn (Staff $s, int $i) => $this->present($s, $i));

        return response()->json([
            'count'    => $requests->count(),
            'requests' => $requests,
        ]);
    }

    /** PUT /admin/staffs/requests/{staff}/approve */
    public function approve(Request $request, Staff $staff): JsonResponse
    {
        if ($staff->status !== Staff::STATUS_PENDING) {
            return response()->json(['message' => 'This request has already been handled.'], 409);
        }

        $staff->update([
            'status'    => Staff::STATUS_REGISTERED,
            'is_active' => true,
        ]);

        $this->clearNotification($staff);

        return response()->json([
            'message' => "\"{$this->name($staff)}\" approved and added to staff.",
            'staff'   => [
                'id'              => $staff->id,
                'staff_code'      => $this->code($staff),
                'first_name'      => $staff->first_name,
                'last_name'       => $staff->last_name,
                'email'           => $staff->email,
                'contact_number'  => $staff->contact_number,
                'address'         => $staff->address,
                'is_active'       => $staff->is_active,
                'profile_picture' => $staff->profile_picture ? asset($staff->profile_picture) : null,
            ],
        ]);
    }

    /** DELETE /admin/staffs/requests/{staff} — reject (remove) a pending request. */
    public function reject(Request $request, Staff $staff): JsonResponse
    {
        if ($staff->status !== Staff::STATUS_PENDING) {
            return response()->json(['message' => 'This request has already been handled.'], 409);
        }

        $name = $this->name($staff);

        if ($staff->profile_picture && Str::startsWith($staff->profile_picture, 'storage/')) {
            Storage::disk('public')->delete(Str::after($staff->profile_picture, 'storage/'));
        }

        $this->clearNotification($staff);

        // forceDelete so the unique e-mail is freed and the person can register again.
        $staff->forceDelete();

        return response()->json(['message' => "Registration request from \"{$name}\" rejected."]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private function present(Staff $s, int $index): array
    {
        return [
            'id'           => $s->id,
            'first'        => $s->first_name,
            'last'         => $s->last_name,
            'email'        => $s->email,
            'contact'      => $s->contact_number,
            'address'      => $s->address,
            'image'        => $s->profile_picture ? asset($s->profile_picture) : null,
            'submitted'    => optional($s->created_at)->format('M j, Y · g:i A'),
            'avatarClass'  => self::AVATAR_CLASSES[$index % count(self::AVATAR_CLASSES)],
        ];
    }

    private function name(Staff $s): string
    {
        return trim("{$s->first_name} {$s->last_name}");
    }

    private function code(Staff $s): string
    {
        return 'STF-' . str_pad($s->id, 4, '0', STR_PAD_LEFT);
    }

    /** The "new staff request" bell notification is no longer relevant once handled. */
    private function clearNotification(Staff $s): void
    {
        Notification::where('ref_key', "staff_request:{$s->id}")->delete();
    }
}