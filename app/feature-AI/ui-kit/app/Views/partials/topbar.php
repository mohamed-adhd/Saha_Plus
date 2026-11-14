<?php $crumbs = $breadcrumb ?? ['Saha+', $title ?? 'Overview']; ?>
<header class="sp-topbar">
  <div class="sp-topbar__leading">
    <button class="sp-topbar__menu" id="spSidebarOpen" type="button" aria-label="Open navigation" aria-controls="spSidebar" aria-expanded="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
    <div class="sp-topbar__crumb"><span class="sp-topbar__brand"><?= htmlspecialchars((string)($crumbs[0] ?? 'Saha+'), ENT_QUOTES, 'UTF-8') ?></span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><strong><?= htmlspecialchars((string)($crumbs[1] ?? ($title ?? 'Overview')), ENT_QUOTES, 'UTF-8') ?></strong></div>
  </div>
  <div class="sp-topbar__actions"><a class="sp-topbar__emergency" href="tel:190"><span class="sp-topbar__emergency-dot"></span><span>Emergency <strong>190</strong></span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 17 17 7M7 7h10v10"/></svg></a></div>
</header>
