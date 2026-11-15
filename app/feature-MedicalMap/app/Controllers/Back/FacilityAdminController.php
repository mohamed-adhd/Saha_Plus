<?php
declare(strict_types=1);

/** Back Office: manage the facilities (list, add, edit, delete). */
final class FacilityAdminController extends Controller
{
    protected string $page = 'admin-facilities';
    protected string $title = 'Facilities';
    protected array $stylesheets = ['assets/css/admin.css'];
    protected array $scripts = ['assets/js/admin.js'];

    /** List, with a search box and a type filter (index.php?page=admin-facilities&q=...&type=...). */
    public function index(): void
    {
        $search = trim((string)($_GET['q'] ?? ''));
        $type = (string)($_GET['type'] ?? '');

        // Number of doctors per facility: [facilityId => count]
        $doctorCounts = [];
        foreach (Doctor::all() as $doctor) {
            $doctorCounts[$doctor->facilityId] = ($doctorCounts[$doctor->facilityId] ?? 0) + 1;
        }

        $this->render('back/facilities', [
            'facilities' => Facility::adminSearch($search, $type),
            'doctorCounts' => $doctorCounts,
            'search' => $search,
            'type' => $type,
        ]);
    }

    /** Empty form (add) or form filled with an existing facility (edit, ?id=). */
    public function form(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id === 0) {
            // Default values of a new facility: flagged as demonstration data until it is verified.
            $this->showForm(0, ['isDemo' => true], []);
            return;
        }
        $facility = Facility::find($id);
        if ($facility === null) {
            $this->flash('danger', 'This facility does not exist.');
            $this->redirect('admin-facilities');
        }
        $this->showForm($id, $facility->toFormData(), []);
    }

    /** Receives the form: validates, then inserts or updates. */
    public function save(): void
    {
        $this->requirePost('admin-facilities');
        $id = (int)($_POST['id'] ?? 0);
        if ($id !== 0 && Facility::find($id) === null) {
            $this->flash('danger', 'This facility does not exist.');
            $this->redirect('admin-facilities');
        }

        [$data, $errors] = Facility::validate($_POST);
        if ($errors !== []) {
            http_response_code(422);              // invalid form: show it again with the messages
            $this->showForm($id, $data, $errors);
            return;
        }

        if ($id === 0) {
            Facility::create($data);
            $this->flash('success', 'Facility "' . $data['name'] . '" added.');
        } else {
            Facility::update($id, $data);
            $this->flash('success', 'Facility "' . $data['name'] . '" updated.');
        }
        $this->redirect('admin-facilities');
    }

    /** Deletes a facility, only if no doctor is attached to it. */
    public function delete(): void
    {
        $this->requirePost('admin-facilities');
        $facility = Facility::find((int)($_POST['id'] ?? 0));
        if ($facility === null) {
            $this->flash('danger', 'This facility does not exist.');
            $this->redirect('admin-facilities');
        }

        $doctorCount = Doctor::countByFacility($facility->id);
        if ($doctorCount > 0) {
            $this->flash('danger', '"' . $facility->name . '" still has ' . $doctorCount
                . ' doctor(s). Move or delete them before deleting the facility.');
            $this->redirect('admin-facilities');
        }

        Facility::delete($facility->id);
        $this->flash('success', 'Facility "' . $facility->name . '" deleted.');
        $this->redirect('admin-facilities');
    }

    /** @param array<string, mixed> $values @param array<string, string> $errors */
    private function showForm(int $id, array $values, array $errors): void
    {
        $this->title = $id === 0 ? 'Add facility' : 'Edit facility';
        // The form contains a small Leaflet map to choose the location.
        $this->externalStylesheets = ['https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'];
        $this->externalScripts = ['https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'];

        $centers = [];
        foreach (Governorate::names() as $name) {
            $centers[$name] = Governorate::center($name);
        }
        $mapConfig = require __DIR__ . '/../../../config/map.php';

        $this->render('back/facility_form', [
            'id' => $id,
            'values' => $values,
            'errors' => $errors,
            'types' => Facility::TYPES,
            'governorates' => Governorate::names(),
            'pickerConfig' => [
                'bounds' => $mapConfig['bounds'],
                'provider' => $mapConfig['providers']['streets'],   // street map: easier to place a building
                'centers' => $centers,
            ],
        ]);
    }
}
