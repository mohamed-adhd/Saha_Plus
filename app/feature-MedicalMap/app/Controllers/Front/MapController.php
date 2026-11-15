<?php
declare(strict_types=1);

/** Front Office: the public Medical Map page and the data it loads. */
final class MapController extends Controller
{
    protected string $layout = 'layout_front';
    protected string $page = 'health-map';      // key of "Health Map" in shared/config/menu.php
    protected string $title = 'Medical Map';
    protected array $stylesheets = ['assets/css/map.css'];
    protected array $scripts = ['assets/js/map.js'];
    protected array $externalStylesheets = ['https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'];
    protected array $externalScripts = ['https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'];

    /** The map page: filters + empty map. The markers are loaded afterwards by assets/js/map.js. */
    public function index(): void
    {
        // Governorates with the centre of each one, so the map can zoom on the chosen governorate.
        $governorates = [];
        foreach (Governorate::names() as $name) {
            $governorates[$name] = Governorate::center($name);
        }

        $this->render('front/map', [
            'specialties' => Doctor::SPECIALTIES,
            'types' => Facility::TYPES,
            'governorates' => $governorates,
            'citiesByGovernorate' => Facility::citiesByGovernorate(),
            'mapConfig' => require __DIR__ . '/../../../config/map.php',
            'dataUrl' => base_url('index.php?page=map-data'),
        ]);
    }

    /**
     * JSON answer for the map: the facilities matching the filters, each with its doctors.
     * Example: index.php?page=map-data&specialty=Dermatology&types[]=clinic&governorate=Tunis
     */
    public function data(): void
    {
        $specialty = trim((string)($_GET['specialty'] ?? ''));
        $governorate = (string)($_GET['governorate'] ?? '');
        $filters = [
            'specialty' => mb_substr($specialty, 0, 60),
            'types' => is_array($_GET['types'] ?? null) ? array_map('strval', $_GET['types']) : [],
            'governorate' => $governorate,
            'city' => mb_substr(trim((string)($_GET['city'] ?? '')), 0, 80),
        ];

        // A place that is not one of the 24 governorates gives no result at all (Tunisia only).
        $knownPlace = $governorate === '' || Governorate::exists($governorate);
        $facilities = $knownPlace ? Facility::search($filters) : [];

        // Group the doctors by facility: [facilityId => list of doctors]
        $doctorsByFacility = [];
        foreach (Doctor::all() as $doctor) {
            $doctorsByFacility[$doctor->facilityId][] = $doctor;
        }

        $result = [];
        foreach ($facilities as $facility) {
            $doctors = [];
            foreach ($doctorsByFacility[$facility->id] ?? [] as $doctor) {
                $doctors[] = [
                    'name' => $doctor->fullName(),
                    'specialty' => $doctor->specialty,
                    'phone' => $doctor->phone,
                    'email' => $doctor->email,
                    // true when this doctor is the reason the facility matches the specialty search
                    'match' => $filters['specialty'] !== '' && mb_stripos($doctor->specialty, $filters['specialty']) !== false,
                ];
            }
            $result[] = [
                'id' => $facility->id,
                'name' => $facility->name,
                'type' => $facility->type,
                'typeLabel' => $facility->typeLabel(),
                'address' => $facility->address,
                'city' => $facility->city,
                'governorate' => $facility->governorate,
                'lat' => $facility->latitude,
                'lng' => $facility->longitude,
                'phone' => $facility->phone,
                'emergency' => $facility->emergencyStatus,
                'isDemo' => $facility->isDemo,
                'doctors' => $doctors,
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['count' => count($result), 'facilities' => $result], JSON_UNESCAPED_UNICODE);
    }

    /**
     * The shared sidebar links every module to index.php?page=coming-soon&module=KEY.
     * Our own key opens the map; the other modules show a "coming soon" message.
     */
    public function comingSoon(): void
    {
        $module = (string)(filter_input(INPUT_GET, 'module', FILTER_UNSAFE_RAW) ?: '');
        if ($module === 'health-map') {
            header('Location: ' . base_url('index.php?page=map'));
            return;
        }
        $this->page = $module;
        $this->title = 'Coming soon';
        $this->stylesheets = [];
        $this->scripts = [];
        $this->externalStylesheets = [];
        $this->externalScripts = [];
        $this->render('front/coming_soon');
    }
}
