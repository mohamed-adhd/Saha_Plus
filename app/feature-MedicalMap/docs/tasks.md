# Medical Map – task list

Suggested GitHub Issues (one per line). Label them `medical-map`; the status shows where the module stands today.

| # | Issue title | Status |
|---|---|---|
| 1 | Review and validate the use-case diagram | Done – to be validated by the tutor |
| 2 | Review and validate the class diagram | Done – to be validated by the tutor |
| 3 | Integrate the Front Office template (shared Saha+ shell) | Done |
| 4 | Integrate the Back Office template (shared Saha+ shell) | Done |
| 5 | Implement the Facility model | Done |
| 6 | Implement the Doctor model | Done |
| 7 | Implement the Tunisia-only interactive map | Done |
| 8 | Implement specialty search | Done |
| 9 | Implement facility-type filtering | Done |
| 10 | Implement facility details | Done |
| 11 | Implement facility CRUD | Done |
| 12 | Implement doctor CRUD | Done |
| 13 | Validate geographical restrictions | Done (rectangle; real border still open) |
| 14 | Test the complete module | Done on one machine – to repeat on a teammate's XAMPP |
| 15 | Document the implementation | Done (README) |

## Open follow-ups

| Issue title | Who decides |
|---|---|
| Point `shared/app/Models/Module.php` to `feature-MedicalMap` | Ibrahim (owner of `shared/`) |
| Link the shared sidebar to the real module folders | Ibrahim (owner of `shared/`) |
| Agree on how donation centers are shared with the Blood Donation module | Rayen + Rached |
| Add an administrator login to the Back Office | Whole team |
| Confirm the satellite tile provider (Esri account) or switch to OpenStreetMap | Rayen |
| Use the real border of Tunisia instead of a rectangle | Rayen |
| Replace demonstration data with verified data from an official source | Rayen |

## Git workflow used

1. Work on the branch `feature/medical-map`, never directly on `main`.
2. `git status` and `git diff` before each commit, to see exactly what goes in.
3. `git add app/feature-MedicalMap` – only this module's folder.
4. `git commit -m "Medical Map: <what changed>"`.
5. `git push -u origin feature/medical-map`, then open a pull request on GitHub for review.
