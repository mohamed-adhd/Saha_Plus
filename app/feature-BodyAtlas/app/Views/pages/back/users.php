<?php
/** @var list<User> $users */
/** @var list<string> $bloodTypes */
$roles = ['patient' => 'Patient', 'therapist' => 'Therapist', 'admin' => 'Admin'];
?>
<section class="content-area" aria-labelledby="page-title"><div class="container-fluid">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 id="page-title" class="h3">Users</h1><p class="text-muted mb-0">Patients, therapists and administrators of Saha+.</p></div>
    <button class="btn btn-primary" type="button" data-add="userModal">Add user</button>
  </div>
  <div class="row g-3 mb-4">
    <?php foreach ($roles as $key => $label): ?>
      <div class="col-sm-4"><div class="card"><div class="card-body"><p class="text-muted small mb-1"><?= e($label) ?>s</p><p class="h3 mb-0"><?= count(array_filter($users, fn (User $u) => $u->role === $key)) ?></p></div></div></div>
    <?php endforeach; ?>
  </div>
  <div class="card"><div class="card-body">
    <div class="d-flex flex-wrap gap-2 mb-3">
      <input class="form-control admin-search" type="search" placeholder="Search a user…" aria-label="Search users" data-search="userTable">
      <select class="form-select admin-filter" aria-label="Filter by role" data-filter="userTable" data-filter-col="role">
        <option value="">All roles</option>
        <?php foreach ($roles as $value => $label): ?><option value="<?= e($value) ?>"><?= e($label) ?></option><?php endforeach; ?>
      </select>
    </div>
    <div class="table-responsive">
      <table class="table align-middle" id="userTable" data-crud="userModal" data-label="user">
        <thead><tr><th>#</th><th>First name</th><th>Last name</th><th>Email</th><th>Role</th><th>Blood type</th><th>Registered</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php foreach ($users as $user): ?>
          <tr data-row="<?= e(json_encode(['id' => $user->id, 'firstName' => $user->firstName, 'lastName' => $user->lastName, 'email' => $user->email, 'role' => $user->role, 'bloodType' => $user->bloodType ?? '', 'createdAt' => $user->createdAt])) ?>">
            <td data-col="id"><?= (int)$user->id ?></td>
            <td data-col="firstName" class="fw-semibold"><?= e($user->firstName) ?></td>
            <td data-col="lastName"><?= e($user->lastName) ?></td>
            <td data-col="email"><?= e($user->email) ?></td>
            <td data-col="role" data-badge><span class="role-badge role-badge--<?= e($user->role) ?>"><?= e($roles[$user->role]) ?></span></td>
            <td data-col="bloodType"><?= e($user->bloodType ?? '—') ?></td>
            <td data-col="createdAt" class="text-muted small"><?= e($user->createdAt) ?></td>
            <td class="text-end text-nowrap">
              <button class="btn btn-sm btn-outline-primary" type="button" data-edit>Edit</button>
              <button class="btn btn-sm btn-outline-danger" type="button" data-delete>Delete</button>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div></div>
</div></section>

<div class="modal fade content-area" id="userModal" tabindex="-1" aria-labelledby="userModalTitle" aria-hidden="true"><div class="modal-dialog">
  <form class="modal-content needs-validation" novalidate>
    <div class="modal-header"><h2 class="modal-title fs-5" id="userModalTitle" data-title-add="Add user" data-title-edit="Edit user">Add user</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
    <div class="modal-body">
      <input type="hidden" name="id"><input type="hidden" name="createdAt">
      <div class="row g-3">
        <div class="col-sm-6"><label class="form-label" for="userFirst">First name</label><input class="form-control" id="userFirst" name="firstName" required minlength="2"><div class="invalid-feedback">Enter a first name.</div></div>
        <div class="col-sm-6"><label class="form-label" for="userLast">Last name</label><input class="form-control" id="userLast" name="lastName" required minlength="2"><div class="invalid-feedback">Enter a last name.</div></div>
        <div class="col-12"><label class="form-label" for="userEmail">Email</label><input class="form-control" id="userEmail" name="email" type="email" required><div class="invalid-feedback">Enter a valid email.</div></div>
        <div class="col-sm-6"><label class="form-label" for="userRole">Role</label><select class="form-select" id="userRole" name="role" required><?php foreach ($roles as $value => $label): ?><option value="<?= e($value) ?>"><?= e($label) ?></option><?php endforeach; ?></select></div>
        <div class="col-sm-6"><label class="form-label" for="userBlood">Blood type</label><select class="form-select" id="userBlood" name="bloodType"><option value="">—</option><?php foreach ($bloodTypes as $type): ?><option><?= e($type) ?></option><?php endforeach; ?></select></div>
      </div>
    </div>
    <div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit">Save</button></div>
  </form>
</div></div>
