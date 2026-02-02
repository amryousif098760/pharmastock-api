<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Http\Request;

class PharmaciesController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q',''));
        $approval = trim((string)$request->get('approval',''));
        $rows = User::query()
            ->where('role','pharmacy')
            ->with('pharmacy')
            ->when($q !== '', fn($qr)=>$qr->where(function($x) use ($q) {
                $x->where('name','like',"%$q%")
                  ->orWhere('email','like',"%$q%")
                  ->orWhere('phone','like',"%$q%");
            }))
            ->when($approval !== '', fn($qr)=>$qr->where('approval_status',$approval))
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $approvals = ['pending','approved','rejected'];
        return view('admin.pharmacies.index', compact('rows','q','approval','approvals'));
    }

    public function show(User $user)
    {
        $user->load('pharmacy');
        return view('admin.pharmacies.show', compact('user'));
    }

    public function approve(User $user)
{
    $user->update(['approval_status' => 'approved']);
    return back()->with('ok','Approved');
}

public function reject(User $user)
{
    $user->update(['approval_status' => 'rejected']);
    return back()->with('ok','Rejected');
}

}
