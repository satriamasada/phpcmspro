<?php
// admin/sections-content.php
require_once __DIR__ . '/../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;

$hero = $pdo->query("SELECT * FROM sections WHERE section_key = 'hero'")->fetch();
$about = $pdo->query("SELECT * FROM sections WHERE section_key = 'about'")->fetch();
?>

<div class="admin-card" style="padding:0;">
    <div style="display:flex; border-bottom:1px solid var(--border);">
        <button class="section-tab active" data-target="#tab-hero" style="padding: 1rem 2rem; background:none; border:none; border-bottom:2px solid var(--admin-accent); font-weight:700; cursor:pointer; font-size:12px; color:var(--admin-accent);">Hero Section</button>
        <button class="section-tab" data-target="#tab-about" style="padding: 1rem 2rem; background:none; border:none; font-weight:700; cursor:pointer; font-size:12px; color:var(--admin-text-muted);">About Us</button>
    </div>
    
    <div style="padding: 2.5rem;">
        <!-- Hero Tab -->
        <div id="tab-hero" class="tab-content">
            <h3 style="margin-bottom: 2rem;">Hero Text & Primary Call-to-Action</h3>
            <form class="ajax-section-form">
                <input type="hidden" name="update_section" value="1">
                <input type="hidden" name="section_key" value="hero">
                <div class="form-group">
                    <label>Main Headline (HTML allowed)</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($hero['title'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Sub-headline / Tagline</label>
                    <textarea name="subtitle" rows="3" required><?= htmlspecialchars($hero['subtitle'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn-primary">Update Hero Section</button>
            </form>
        </div>

        <!-- About Tab -->
        <div id="tab-about" class="tab-content" style="display:none;">
            <h3 style="margin-bottom: 2rem;">About Section Story & Details</h3>
            <form class="ajax-section-form">
                <input type="hidden" name="update_section" value="1">
                <input type="hidden" name="section_key" value="about">
                <div class="form-group">
                    <label>Headline Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($about['title'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Short Introduction</label>
                    <input type="text" name="subtitle" value="<?= htmlspecialchars($about['subtitle'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Detailed Content / Journey</label>
                    <textarea name="content" rows="6" required><?= htmlspecialchars($about['content'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn-primary">Update About Section</button>
            </form>
        </div>
    </div>
</div>

<script>
    $('.section-tab').on('click', function() {
        const target = $(this).data('target');
        $('.section-tab').removeClass('active').css({'border-bottom':'none', 'color':'var(--admin-text-muted)'});
        $(this).addClass('active').css({'border-bottom':'2px solid var(--admin-accent)', 'color':'var(--admin-accent)'});
        $('.tab-content').hide();
        $(target).fadeIn();
    });

    $('.ajax-section-form').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        $.ajax({
            url: 'sections.php',
            type: 'POST',
            data: $form.serialize(),
            success: function() {
                Swal.fire({
                    title: 'Updated!',
                    text: 'Page section content has been saved.',
                    icon: 'success',
                    confirmButtonColor: '#0066ff'
                });
            },
            error: function() {
                Swal.fire('Error', 'Failed to save changes.', 'error');
            }
        });
    });
</script>
