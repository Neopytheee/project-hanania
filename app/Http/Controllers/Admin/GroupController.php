<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Group;
use App\Models\GroupMembership;
use App\Services\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

   public function index()
    {
        // UBAH: memberships menjadi activeMemberships
        $groups = Group::with(['departure', 'activeMemberships'])->latest()->paginate(10);
        return view('admin.groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|unique:groups,code',
            'name'     => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        try {
            $this->groupService->create($request->only(['code', 'name', 'capacity']));
            return back()->with('success', 'Rombongan baru berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * TAMPILAN DETAIL GRUP (YANG TADI ERROR)
     */
    public function show(Group $group)
    {
        // UBAH: memberships menjadi activeMemberships
        $group->load(['departure', 'activeMemberships.enrollment.customer', 'activeMemberships.enrollment.travelPackage']);

        $eligibleEnrollments = Enrollment::with(['customer', 'travelPackage'])
            ->whereIn('status', ['funds_sufficient', 'waiting_schedule'])
            ->whereDoesntHave('groupMemberships', function ($query) {
                $query->where('status', 'active');
            })
            ->get();

        return view('admin.groups.show', compact('group', 'eligibleEnrollments'));
    }

    /**
     * PROSES MASUKKAN JAMAAH KE GRUP
     */
    public function assign(Request $request, Group $group)
    {
        $request->validate(['enrollment_id' => 'required|exists:enrollments,id']);

        try {
            $enrollment = Enrollment::findOrFail($request->enrollment_id);
            $this->groupService->assignEnrollment($group, $enrollment, auth()->user());

            return back()->with('success', 'Jamaah berhasil dimasukkan ke rombongan!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * PROSES KELUARKAN JAMAAH DARI GRUP
     */
    public function remove(Request $request, GroupMembership $membership)
    {
        try {
            $reason = $request->input('reason', 'Dikeluarkan oleh Admin');
            $this->groupService->removeEnrollment($membership, auth()->user(), $reason);

            return back()->with('success', 'Jamaah berhasil dikeluarkan dari rombongan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}