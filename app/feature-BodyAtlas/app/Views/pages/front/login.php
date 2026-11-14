<div class="container-fluid px-3 px-lg-5 py-4 py-lg-5">
  <div class="auth-wrap">
    <section class="sp-card" data-sp-reveal aria-labelledby="loginTitle">
      <div class="sp-card__body p-4 p-lg-5">
        <span class="sp-pill">Welcome back</span>
        <h1 id="loginTitle" class="h2 mt-3">Log in to Saha+</h1>
        <p class="text-muted">Access your recordings, conversations and donation appointments.</p>
        <form id="loginForm" class="auth-form" novalidate>
          <div class="sp-field">
            <label for="loginEmail">Email</label>
            <input class="sp-input" id="loginEmail" name="email" type="email" autocomplete="email" required>
            <span class="sp-field__hint auth-error" data-error-for="loginEmail"></span>
          </div>
          <div class="sp-field">
            <label for="loginPassword">Password</label>
            <input class="sp-input" id="loginPassword" name="password" type="password" autocomplete="current-password" required minlength="8">
            <span class="sp-field__hint auth-error" data-error-for="loginPassword"></span>
          </div>
          <button class="sp-button sp-button--primary w-100" type="submit">Log in</button>
        </form>
        <p class="mt-4 mb-0">No account yet? <a href="<?= e(base_url('index.php?page=register')) ?>">Create one</a></p>
      </div>
    </section>
  </div>
</div>
