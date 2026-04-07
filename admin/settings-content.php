<?php
// admin/settings-content.php
require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin');
?>

<div class="admin-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2.5rem;">
        <div>
            <h2>Site Configuration</h2>
            <p style="color:var(--text-muted); font-size:0.85rem;">Global identity and branding settings.</p>
        </div>
        <button type="submit" form="settingsForm" class="btn-primary" style="padding:1rem 2rem; font-size:1rem; border:none; cursor:pointer;">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </div>

    <form id="settingsForm">
        <input type="hidden" name="update_settings" value="1">
        
        <div class="grid-2" style="display:grid; grid-template-columns:1fr 1fr; gap:3rem;">
            <!-- Website Identity -->
            <div class="card" style="border:1.5px solid var(--border); padding:2.5rem; border-radius:20px;">
                <h3 style="margin-bottom:2rem; font-size:1.2rem; display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-id-card" style="color:var(--primary);"></i> Identity & Branding
                </h3>
                
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label>Website Name</label>
                    <input type="text" name="settings[site_name]" value="<?= get_setting('site_name', 'SoftCo Tech') ?>" required style="width:100%; padding:15px; border-radius:10px; border:1px solid #e2e8f0;">
                </div>

                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label>Tagline / Motto</label>
                    <input type="text" name="settings[site_motto]" value="<?= get_setting('site_motto', 'Innovating Software Solutions') ?>" style="width:100%; padding:15px; border-radius:10px; border:1px solid #e2e8f0;">
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card" style="border:1.5px solid var(--border); padding:2.5rem; border-radius:20px;">
                <h3 style="margin-bottom:2rem; font-size:1.2rem; display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-headset" style="color:var(--primary);"></i> Contact & Support
                </h3>
                
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label>Official Email</label>
                    <input type="email" name="settings[site_email]" value="<?= get_setting('site_email', 'hello@softco.tech') ?>" required style="width:100%; padding:15px; border-radius:10px; border:1px solid #e2e8f0;">
                </div>

                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label>Official Phone</label>
                    <input type="tel" name="settings[site_phone]" value="<?= get_setting('site_phone', '+62 812 3456 7890') ?>" required style="width:100%; padding:15px; border-radius:10px; border:1px solid #e2e8f0;">
                </div>
                
                <div class="form-group">
                    <label>Office Address</label>
                    <textarea name="settings[site_address]" style="width:100%; padding:15px; border-radius:10px; border:1px solid #e2e8f0;" rows="2"><?= get_setting('site_address', 'Jakarta, Indonesia') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Visual / Theme Settings -->
        <div class="card" style="border:1.5px solid var(--border); padding:2.5rem; border-radius:20px; margin-top:2rem;">
            <h3 style="margin-bottom:2rem; font-size:1.2rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-palette" style="color:var(--primary);"></i> Visual & Theme Preferences
            </h3>
            
            <div style="display:flex; gap:3rem;">
                <div style="flex:1;">
                    <label style="display:block; margin-bottom:1rem; font-weight:700;">Default Theme Mode</label>
                    <div style="display:flex; gap:1rem;">
                        <label style="background:#f8fafc; padding:1.5rem; border-radius:15px; border:2px solid var(--border); cursor:pointer; flex:1; text-align:center;">
                            <input type="radio" name="settings[theme_mode]" value="light" <?= get_setting('theme_mode') == 'light' ? 'checked' : '' ?> style="display:none;" onchange="this.parentElement.style.borderColor='var(--primary)'">
                            <i class="fas fa-sun" style="font-size:1.5rem; display:block; margin-bottom:0.5rem; color:#f59e0b;"></i>
                            Light Mode
                        </label>
                        <label style="background:#1e293b; color:white; padding:1.5rem; border-radius:15px; border:2px solid var(--border); cursor:pointer; flex:1; text-align:center;">
                            <input type="radio" name="settings[theme_mode]" value="dark" <?= get_setting('theme_mode') == 'dark' ? 'checked' : '' ?> style="display:none;">
                            <i class="fas fa-moon" style="font-size:1.5rem; display:block; margin-bottom:0.5rem; color:#6366f1;"></i>
                            Dark Mode
                        </label>
                    </div>
                </div>
                <div style="flex:1;">
                     <label style="display:block; margin-bottom:1rem; font-weight:700;">Brand Primary Color</label>
                     <input type="color" name="settings[primary_color]" value="<?= get_setting('primary_color', '#0066FF') ?>" style="width:100%; height:100px; padding:5px; border-radius:15px; border:1px solid #e2e8f0; cursor:pointer;">
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $('#settingsForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'settings.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                Swal.fire('Settings Saved!', 'Application settings have been updated.', 'success').then(() => {
                    location.reload();
                });
            }
        });
    });
</script>
