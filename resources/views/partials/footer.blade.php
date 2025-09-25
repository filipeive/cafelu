{{-- <style>
    /* ===== FOOTER ===== */
    /* ===== FOOTER STYLES ===== */
    .main-footer {
        background: linear-gradient(135deg, var(--dark-color) 0%, #2d3748 100%);
        backdrop-filter: blur(20px);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.9);
        margin-top: auto;
        position: relative;
        overflow: hidden;
    }

    .main-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg,
                transparent 0%,
                var(--primary-color) 50%,
                transparent 100%);
    }

    .footer-content {
        padding: 2rem 0 1rem;
    }

    /* Footer Main Section */
    .footer-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-brand {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .footer-logo-img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary-color);
    }

    .footer-brand-name {
        font-weight: 700;
        font-size: 1.25rem;
        background: var(--beach-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .footer-tagline {
        margin: 0;
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .footer-stats {
        display: flex;
        gap: 1.5rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: var(--border-radius);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .stat-item i {
        color: var(--primary-color);
        font-size: 1rem;
    }

    .stat-item span {
        font-weight: 600;
        font-family: 'Courier New', monospace;
    }

    /* Footer Info Sections */
    .footer-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .footer-section {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .footer-title {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .system-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .version-badge {
        background: var(--primary-gradient);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        width: fit-content;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
    }

    .status-indicator.active {
        color: var(--success-color);
    }

    .status-indicator.active i {
        color: var(--success-color);
        font-size: 0.6rem;
        animation: pulse 2s infinite;
    }

    .support-links {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .support-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.875rem;
        transition: var(--transition);
        padding: 0.25rem 0;
    }

    .support-link:hover {
        color: var(--primary-color);
        transform: translateX(5px);
    }

    .support-link i {
        font-size: 1rem;
        width: 16px;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .contact-info small {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .contact-info i {
        color: var(--primary-color);
        font-size: 0.9rem;
        width: 16px;
    }

    /* Footer Bottom */
    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-copyright p {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .tech-info {
        color: rgba(255, 255, 255, 0.6);
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
    }

    .footer-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-footer-action {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.8);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        cursor: pointer;
    }

    .btn-footer-action:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    /* Animations */
    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .footer-content {
            padding: 1.5rem 0 0.5rem;
        }

        .footer-main {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }

        .footer-brand {
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-stats {
            justify-content: center;
        }

        .footer-info {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            text-align: center;
        }

        .footer-bottom {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .system-info {
            align-items: center;
        }
    }

    @media (max-width: 576px) {
        .footer-stats {
            flex-direction: column;
            gap: 0.75rem;
        }

        .stat-item {
            justify-content: center;
        }

        .support-links {
            flex-direction: row;
            justify-content: center;
            flex-wrap: wrap;
        }
    }

    /* Footer Collapsed State */
    .main-footer.collapsed {
        padding: 0.5rem 0;
    }

    .main-footer.collapsed .footer-content>*:not(.footer-bottom) {
        display: none;
    }

    .main-footer.collapsed .footer-bottom {
        padding-top: 0;
        border-top: none;
    }

    /* Scroll to Top Animation */
    .scroll-top {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
    }
</style>
 --}}
 <style>
 /*   :root{
     --beach-gradient: linear-gradient(135deg, #0c7992 0%, #066c7e 50%, #fbbf24 100%);
    }  */
/* Footer Simples */

/* ===== FOOTER COMPACTO FIXO ===== */
.main-footer {
    background: linear-gradient(135deg, #1fb3d4 0%, #072b31 50%, #ff9100 100%);
    backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
    position: relative;
    bottom: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
}



.main-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, 
        transparent 0%, 
        var(--primary-color) 50%, 
        transparent 100%);
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Footer Main */
.footer-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.25rem;
}

.footer-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.footer-logo {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    object-fit: cover;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.footer-info {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}

.footer-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: rgb(255, 255, 255);
    line-height: 1;
}

.footer-subtitle {
    font-size: 0.65rem;
    color: rgba(255, 255, 255, 0.6);
    line-height: 1;
}

/* Footer Contacts */
.footer-contacts {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.contact-item {
    display: flex;
    align-items: center;
}

.contact-link, .contact-text {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.contact-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--success-color);
    transform: translateY(-1px);
}

.contact-text {
    cursor: default;
}

.contact-link i, .contact-text i {
    font-size: 0.8rem;
}

.contact-link i {
    color: var(--success-color);
}

.contact-text i {
    color: var(--primary-color);
}

/* Footer Bottom */
.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    padding-top: 0.25rem;
}

.footer-legal {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.65rem;
    color: rgba(255, 255, 255, 0.5);
}

.copyright {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.developer {
    color: rgba(255, 255, 255, 0.6);
    font-style: italic;
}

.footer-stats {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.6);
}

/* Tooltip Customization */
.tooltip {
    font-size: 0.7rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer-main {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
    
    .footer-contacts {
        justify-content: center;
        gap: 0.75rem;
    }
    
    .footer-legal {
        flex-direction: column;
        gap: 0.25rem;
        text-align: center;
    }
    
    .footer-content {
        padding: 0 0.5rem;
    }
}

@media (max-width: 480px) {
    .footer-contacts {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .contact-link, .contact-text {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
    
    .footer-legal {
        font-size: 0.6rem;
    }
}

/* Hover Effects */
.footer-brand:hover .footer-title {
    color: var(--primary-color);
    transition: color 0.2s ease;
}

.footer-stats:hover {
    color: rgba(255, 255, 255, 0.8);
}

/* Compact Mode for Small Screens */
@media (max-height: 600px) {
    .main-footer {
        padding: 0.25rem 0;
    }
    
    .footer-main {
        margin-bottom: 0.1rem;
    }
    
    .footer-bottom {
        padding-top: 0.1rem;
    }
}

/* Print Styles */
@media print {
    .main-footer {
        display: none;
    }
}
</style>
