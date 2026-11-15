<?php
declare(strict_types=1);

/** Back Office: list of facilities (add / edit / delete arrive in the CRUD phase). */
final class FacilityAdminController extends Controller
{
    protected string $page = 'admin-facilities';
    protected string $title = 'Facilities';

    public function index(): void
    {
        // Number of doctors per facility: [facilityId => count]
        $doctorCounts = [];
        foreach (Doctor::all() as $doctor) {
            $doctorCounts[$doctor->facilityId] = ($doctorCounts[$doctor->facilityId] ?? 0) + 1;
        }

        $this->render('back/facilities', [
            'facilities' => Facility::all(),
            'doctorCounts' => $doctorCounts,
        ]);
    }
}
