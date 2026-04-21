<?php
require_once('./config.php');
require_once('./admin/includes/db.php');
require_once('./links.php');

$posts = [];
try {
    $pdo = get_db();
    $posts = $pdo->query("SELECT id, title, slug, excerpt, featured_image, created_at FROM blog_posts WHERE status='published' ORDER BY created_at DESC")->fetchAll();
} catch (Throwable $e) {}
?>

<title>Blog : Hasnet ICT Solution</title>
</head>
<body>
    <div class="body-overlay"></div>
    <button class="scrolltop-btn"><i class="fa-solid fa-angle-up"></i></button>

    <?php require_once('./header.php'); require_once('./modal.php'); ?>

    <!-- Hero -->
    <section class="gh-hero-section overflow-hidden" style="background:#27235f;min-height:260px;display:flex;align-items:center;">
        <div class="container text-center text-white py-5">
            <h1 class="fw-bold mb-2">Our Blog</h1>
            <p class="mb-0 opacity-75">Insights, updates and tech tips from Hasnet ICT Solution.</p>
        </div>
    </section>

    <!-- Blog Posts -->
    <section class="py-5" style="background:#f8f9fb;">
        <div class="container">
            <?php if (empty($posts)): ?>
                <div class="text-center py-5">
                    <i class="fa-regular fa-newspaper" style="font-size:3rem;color:#ccc"></i>
                    <h4 class="mt-3 text-muted">No posts published yet.</h4>
                    <p class="text-muted">Check back soon for updates!</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($posts as $p): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
                            <?php if ($p['featured_image']): ?>
                                <img src="<?= APP_URL ?>/uploads/blog/<?= htmlspecialchars($p['featured_image']) ?>" class="card-img-top" style="height:200px;object-fit:cover" alt="">
                            <?php else: ?>
                                <div style="height:200px;background:linear-gradient(135deg,#27235f,#4a3fbf);display:flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-newspaper" style="font-size:2.5rem;color:rgba(255,255,255,.4)"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-4">
                                <small class="text-muted"><?= date('M j, Y', strtotime($p['created_at'])) ?></small>
                                <h5 class="mt-2 mb-2 fw-bold" style="color:#27235f"><?= htmlspecialchars($p['title']) ?></h5>
                                <p class="text-muted" style="font-size:14px"><?= htmlspecialchars($p['excerpt'] ?? '') ?></p>
                                <a href="blog-post.php?slug=<?= htmlspecialchars($p['slug']) ?>" class="template-btn primary-btn btn-small mt-2">Read More</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once('./footer.php'); ?>
</body>
</html>
