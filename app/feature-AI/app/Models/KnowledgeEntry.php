<?php
declare(strict_types=1);

final class KnowledgeEntry
{
    public function __construct(public int $id, public string $topic, public string $summary, public string $source, public string $tier, public string $updated) {}

    /** @return list<KnowledgeEntry> */
    public static function all(): array
    {
        return [
            new self(1, 'Hypertension basics', 'High blood pressure may not cause noticeable symptoms. Regular checks and a conversation with a health professional can help you understand your readings.', 'WHO, Tunisian Ministry of Health', 'informational', '2026-10-20'),
            new self(2, 'Diabetes prevention', 'Regular movement, balanced meals and preventive health visits are common parts of diabetes prevention guidance. A professional can help make advice personal to you.', 'WHO, Tunisian Ministry of Health', 'informational', '2026-10-18'),
            new self(3, 'Sleep habits', 'A steady sleep and wake time, a calm wind-down routine and a comfortable sleep space can support healthy rest. Persistent sleep difficulties are worth discussing with a professional.', 'WHO, Mayo Clinic', 'informational', '2026-10-16'),
            new self(4, 'Hydration', 'Hydration needs vary with activity, weather and individual health. Drinking water regularly and noticing thirst can be useful everyday habits.', 'WHO', 'informational', '2026-10-14'),
            new self(5, 'Nutrition basics', 'A varied diet that includes vegetables, fruit, pulses and whole grains can support general wellbeing. Local, accessible foods can be part of a balanced pattern.', 'WHO, Tunisian Ministry of Health', 'informational', '2026-10-12'),
            new self(6, 'Physical activity', 'Regular movement can support overall health. Choose an activity and pace that feel manageable, and ask a professional for tailored guidance if you have concerns.', 'WHO', 'informational', '2026-10-10'),
            new self(7, 'Stress and wellbeing', 'Taking brief pauses, talking with someone you trust and keeping supportive daily routines may help with everyday stress. If distress persists or feels overwhelming, reach out to a qualified professional.', 'WHO, Mayo Clinic', 'informational', '2026-10-08'),
            new self(8, 'Blood donation basics', 'Blood donation eligibility depends on local guidance and individual health. Contact an official donation center to learn the current requirements before donating.', 'Tunisian Ministry of Health', 'informational', '2026-10-06'),
            new self(9, 'Hand hygiene', 'Clean your hands with soap and water, especially before eating and after using the toilet. If soap and water are unavailable, follow local public health advice for hand sanitizer.', 'WHO', 'informational', '2026-10-04'),
            new self(10, 'Vaccination in general', 'Vaccines help protect people from certain infections. Recommended vaccines and timing vary, so check the current Tunisian schedule with a health professional or public health service.', 'WHO, Tunisian Ministry of Health', 'informational', '2026-10-02'),
            new self(11, 'Persistent symptoms', 'A fever that persists for several days, blood in bodily fluids or ongoing dizziness should be assessed by a qualified health professional. Please contact a clinician for personal advice.', 'Tunisian Ministry of Health, Mayo Clinic', 'consult', '2026-09-30'),
            new self(12, 'Chest pain', 'New or severe chest pain may need immediate emergency assessment.', 'WHO', 'urgent', '2026-09-28'),
        ];
    }
}
