<?php
/**
 * File: footer.php
 * Deskripsi: Footer dengan desain modern glassmorphism (Fixed Centered)
 */
?>
<style>
    .main-footer {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-top: 1px solid rgba(255, 255, 255, 0.5);
        padding: 30px 0;
        margin-top: 20px; /* Dikurangi agar tidak terlalu jauh ke bawah */
        position: relative;
        z-index: 10;
        /* Hapus margin-left 280px agar kembali ke tengah */
        margin-left: 0; 
        transition: all 0.3s ease;
        width: 100%;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px; /* Batasi lebar maksimal agar rapi */
        margin: 0 auto;    /* Memastikan konten di dalam footer berada di tengah */
        padding: 0 40px;
    }

    .footer-logo {
        color: #1e3a8a;
        font-weight: 800;
        font-size: 1.1rem;
        text-decoration: none;
        letter-spacing: 1px;
    }

    .footer-logo i {
        color: #ff6b00;
    }

    .copyright-text {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        margin: 0;
    }

    .student-id {
        color: #1e3a8a;
        font-weight: 700;
        background: rgba(30, 58, 138, 0.05);
        padding: 4px 12px;
        border-radius: 8px;
        display: inline-block;
        margin-left: 5px;
    }

    @media (max-width: 992px) {
        .footer-content {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
    }
</style>

<footer class="main-footer animate__animated animate__fadeInUp">
    <div class="footer-content">
        <div>
            <a href="dashboard.php" class="footer-logo">
                <i class="fas fa-train me-2"></i>BERANGKATIN
            </a>
        </div>
        <div>
            <p class="copyright-text">
                &copy; <?= date('Y'); ?> Copyright by 
                <span class="student-id">23552011067_TIF-23CNS-A_UAS-WEB1</span>
            </p>
        </div>
    </div>
</footer>