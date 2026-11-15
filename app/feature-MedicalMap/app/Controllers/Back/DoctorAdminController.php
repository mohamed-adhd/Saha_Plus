<?php
declare(strict_types=1);

/** Back Office: list of doctors (add / edit / delete arrive in the CRUD phase). */
final class DoctorAdminController extends Controller
{
    protected string $page = 'admin-doctors';
    protected string $title = 'Doctors';

    public function index(): void
    {
        // Facility names by id, to show where each doctor works: [facilityId => Facility]
        $facilitiesById = [];
        foreach (Facility::all() as $facility) {
            $facilitiesById[$facility->id] = $facility;
        }

        $this->render('back/doctors', [
            'doctors' => Doctor::all(),
            'facilitiesById' => $facilitiesById,
        ]);
    }
}
