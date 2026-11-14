<?php /** @var list<string> $bloodTypes */ ?>
<div class="container-fluid px-3 px-lg-5 py-4 py-lg-5">
  <div class="auth-wrap">
    <section class="sp-card" data-sp-reveal aria-labelledby="registerTitle">
      <div class="sp-card__body p-4 p-lg-5">
        <span class="sp-pill">Join Saha+</span>
        <h1 id="registerTitle" class="h2 mt-3">Create your account</h1>
        <p class="text-muted">It only takes a minute.</p>
        <form id="registerForm" class="auth-form" novalidate>
          <div class="row g-3">
            <div class="col-sm-6 sp-field">
              <label for="regFirstName">First name</label>
              <input class="sp-input" id="regFirstName" name="firstName" autocomplete="given-name" required>
              <span class="sp-field__hint auth-error" data-error-for="regFirstName"></span>
            </div>
            <div class="col-sm-6 sp-field">
              <label for="regLastName">Last name</label>
              <input class="sp-input" id="regLastName" name="lastName" autocomplete="family-name" required>
              <span class="sp-field__hint auth-error" data-error-for="regLastName"></span>
            </div>
          </div>
          <div class="sp-field">
            <label for="regEmail">Email</label>
            <input class="sp-input" id="regEmail" name="email" type="email" autocomplete="email" required>
            <span class="sp-field__hint auth-error" data-error-for="regEmail"></span>
          </div>
          <div class="sp-field">
            <label for="regBlood">Blood type <small class="text-muted">(optional)</small></label>
            <select class="sp-select" id="regBlood" name="bloodType">
              <option value="">I don't know</option>
              <?php foreach ($bloodTypes as $type): ?><option><?= e($type) ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="row g-3">
            <div class="col-sm-6 sp-field">
              <label for="regPassword">Password</label>
              <input class="sp-input" id="regPassword" name="password" type="password" autocomplete="new-password" required minlength="8">
              <span class="sp-field__hint auth-error" data-error-for="regPassword"></span>
            </div>
            <div class="col-sm-6 sp-field">
              <label for="regConfirm">Confirm password</label>
              <input class="sp-input" id="regConfirm" name="confirm" type="password" autocomplete="new-password" required>
              <span class="sp-field__hint auth-error" data-error-for="regConfirm"></span>
            </div>
          </div>
          <button class="sp-button sp-button--primary w-100" type="submit">Create account</button>
        </form>
        <p class="mt-4 mb-0">Already registered? <a href="<?= e(base_url('index.php?page=login')) ?>">Log in</a></p>
      </div>
    </section>
  </div>
</div>
