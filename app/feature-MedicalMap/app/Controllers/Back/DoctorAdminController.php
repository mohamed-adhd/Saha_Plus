<?php
declare(strict_types=1);

/** Back Office: manage the doctors (list, add, edit, delete). */
final class DoctorAdminController extends Controller
{
    protected string $page = 'admin-doctors';
    protected string $title = 'Doctors';
    protected array $stylesheets = ['assets/css/admin.css'];
    protected array $scripts = ['assets/js/admin.js'];

    /** List, with a search box and a specialty filter. */
    public function index(): void
    {
        $search = trim((string)($_GET['q'] ?? ''));
        $specialty = (string)($_GET['specialty'] ?? '');

        $this->render('back/doctors', [
            'doctors' => Doctor::adminSearch($search, $specialty),
            'facilitiesById' => $this->facilitiesById(),
            'specialties' => Doctor::SPECIALTIES,
            'search' => $search,
            'specialty' => $specialty,
        ]);
    }

    /** Empty form (add) or form filled with an existing doctor (edit, ?id=). */
    public function form(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id === 0) {
            $this->showForm(0, [], []);
            return;
        }
        $doctor = Doctor::find($id);
        if ($doctor === null) {
            $this->flash('danger', 'This doctor does not exist.');
            $this->redirect('admin-doctors');
        }
        $this->showForm($id, $doctor->toFormData(), []);
    }

    /** Receives the form: validates, then inserts or updates. */
    public function save(): void
    {
        $this->requirePost('admin-doctors');
        $id = (int)($_POST['id'] ?? 0);
        if ($id !== 0 && Doctor::find($id) === null) {
            $this->flash('danger', 'This doctor does not exist.');
            $this->redirect('admin-doctors');
        }

        [$data, $errors] = Doctor::validate($_POST);
        if ($errors !== []) {
            http_response_code(422);
            $this->showForm($id, $data, $errors);
            return;
        }

        $name = 'Dr. ' . $data['firstName'] . ' ' . $data['lastName'];
        if ($id === 0) {
            Doctor::create($data);
            $this->flash('success', $name . ' added.');
        } else {
            Doctor::update($id, $data);
            $this->flash('success', $name . ' updated.');
        }
        $this->redirect('admin-doctors');
    }

    public function delete(): void
    {
        $this->requirePost('admin-doctors');
        $doctor = Doctor::find((int)($_POST['id'] ?? 0));
        if ($doctor === null) {
            $this->flash('danger', 'This doctor does not exist.');
            $this->redirect('admin-doctors');
        }
        Doctor::delete($doctor->id);
        $this->flash('success', $doctor->fullName() . ' deleted.');
        $this->redirect('admin-doctors');
    }

    /** @param array<string, mixed> $values @param array<string, string> $errors */
    private function showForm(int $id, array $values, array $errors): void
    {
        $this->title = $id === 0 ? 'Add doctor' : 'Edit doctor';
        $this->render('back/doctor_form', [
            'id' => $id,
            'values' => $values,
            'errors' => $errors,
            'specialties' => Doctor::SPECIALTIES,
            'facilities' => Facility::all(),
        ]);
    }

    /** @return array<int, Facility> facilities indexed by id, to show where each doctor works */
    private function facilitiesById(): array
    {
        $facilitiesById = [];
        foreach (Facility::all() as $facility) {
            $facilitiesById[$facility->id] = $facility;
        }
        return $facilitiesById;
    }
}
