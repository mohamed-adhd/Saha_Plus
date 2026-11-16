# 🩸 Don de sang

Site web de gestion des dons de sang : localiser les centres accessibles sur une carte, suivre les stocks et les besoins urgents, vérifier son éligibilité et réserver un don (nom et coordonnées **ou en anonyme**).

> Version **sans base de données** : HTML, CSS et JavaScript purs. Les données sont enregistrées dans le navigateur (`localStorage`).

## Fonctionnalités

| Profil | Ce qu'il peut faire |
|---|---|
| **Visiteur** | Voir la carte des centres, consulter les besoins urgents, consulter la matrice de compatibilité |
| **Patient** | Vérifier son éligibilité, réserver un rendez-vous (option anonyme), annuler un rendez-vous |
| **Admin** | Gérer les stocks de sang, gérer les rendez-vous (statuts) |

**La carte** (Leaflet + OpenStreetMap)
- Marqueurs colorés selon le stock : 🟢 normal · 🟠 besoin · 🔴 critique
- Filtre **♿ Accès PMR uniquement**
- Bouton **📍 Le plus proche de moi** : 3 centres les plus proches avec la distance en km et un lien d'itinéraire
- Fenêtre d'information par centre avec un lien direct vers la prise de rendez-vous

## Technologies
HTML5 · CSS3 · JavaScript · [Leaflet](https://leafletjs.com) 1.9.4 · OpenStreetMap · `localStorage`

## Structure

```
don-de-sang/
├── index.html          → l'application (Visiteur, Patient, Admin)
├── presentation.html   → page de présentation du projet (thème vert pastel)
├── css/style.css
├── js/app.js           → logique, données et carte
└── README.md
```

## Lancer le projet (VS Code)

1. Installer l'extension **Live Server** (Ritwick Dey).
2. **Fichier → Ouvrir le dossier…** et choisir `don-de-sang` (après avoir décompressé le zip).
3. Clic droit sur `index.html` → **Open with Live Server**.
4. Pour la présentation, faire de même avec `presentation.html`.

Une connexion internet est nécessaire pour la carte (Leaflet et tuiles OpenStreetMap). Aucun PHP ni MySQL n'est requis.

## Utilisation

- **Réserver en anonyme** : onglet *Patient*, cocher « Rester anonyme » : les champs nom et coordonnées sont désactivés et le rendez-vous apparaît comme « Anonyme ».
- **Réserver depuis la carte** : cliquer sur un marqueur puis « Prendre rendez-vous » : le centre est déjà sélectionné.
- **Gérer les stocks** : onglet *Admin*. Le niveau d'urgence se met à jour automatiquement et la couleur du marqueur change sur la carte.

## Règles de gestion

- **Urgence automatique** : quantité < 5 → `critical`, < 15 → `low`, sinon `normal`.
- **Éligibilité** : âge de 18 à 65 ans, poids ≥ 50 kg, dernier don il y a au moins 8 semaines, bonne santé.
- **Statuts d'un rendez-vous** : `pending` → `confirmed` → `done`, ou `cancelled`.

## Modèle de données

```
BloodStock (0..*) ── (1) Facility (1) ── (0..*) DonationAppointment
```

- **Facility** : id, name, city, lat, lng, accessible
- **BloodStock** : id, bloodType (O- … AB+), quantity, urgencyLevel (normal / low / critical), facility_id, updatedAt
- **DonationAppointment** : id, user_id, facility_id, bloodType, date, status, anonymous, name, contact

Clés `localStorage` : `stocks`, `appts`, `uid`.

## Personnaliser

- **Ajouter ou corriger un hôpital** : modifier la liste `facilities` en haut de `js/app.js` (`lat`, `lng`, `accessible`).
  > Les coordonnées GPS et l'accès PMR fournis sont des **données de démonstration approximatives** : à vérifier avant tout usage réel.
- **Remettre les données à zéro** : F12 → onglet *Application* → *Stockage local* → effacer les données du site.

## Dépannage

| Problème | Solution |
|---|---|
| La carte est grise ou vide | Vérifier la connexion internet |
| « Le plus proche de moi » ne répond pas | Autoriser la localisation dans le navigateur (fonctionne via Live Server / `localhost`) |
| Les anciennes données réapparaissent | Effacer le stockage local (voir ci-dessus) |
| Rien ne s'affiche en ouvrant le zip | Décompresser d'abord le zip, puis ouvrir le dossier |

## Limites

- Les données restent dans le navigateur : elles ne sont pas partagées entre appareils ni entre utilisateurs.
- L'espace Admin n'a pas de mot de passe (démonstration).
- Les stocks ne sont pas décrémentés automatiquement quand un don passe à `done`.

## Pistes d'amélioration
Version PHP/MySQL avec authentification (voir le projet `sante-app`), notifications par e-mail ou SMS, tableau de bord avec statistiques, export PDF.
