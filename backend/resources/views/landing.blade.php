<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Al-Amine RDV - Système de Gestion de Rendez-vous Médicaux</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: rgba(241, 245, 249, 0.95);
            background: radial-gradient(circle at 20% -10%, rgba(59, 130, 246, 0.2), transparent 55%),
                        radial-gradient(circle at 80% 0%, rgba(14, 165, 233, 0.18), transparent 60%),
                        linear-gradient(180deg, #0f172a 0%, #020617 100%);
            overflow-x: hidden;
        }

        :root {
            --primary: #3B82F6;
            --primary-dark: #1E40AF;
            --primary-light: #DBEAFE;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --light-bg: #F9FAFB;
            --white: #ffffff;
            --gray: #6B7280;
            --gray-light: #9CA3AF;
            --dark: #111827;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        html {
            scroll-behavior: smooth;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 4rem;
            height: 80px;
            background: rgba(3, 30, 93, 0.63);
            backdrop-filter: blur(16px) saturate(1.05);
            -webkit-backdrop-filter: blur(16px) saturate(1.05);
            box-shadow: 0 14px 40px rgb(55, 152, 249);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(59, 130, 246, 0.18);
            transition: backdrop-filter 0.35s ease, background 0.35s ease, box-shadow 0.35s ease;
        }

        nav.scrolled {
            padding: 0.8rem 4rem;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.85);
            background: rgba(15, 23, 42, 0.78);
            backdrop-filter: blur(26px) saturate(1.25);
            -webkit-backdrop-filter: blur(26px) saturate(1.25);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo-img {
            width: 42px;
            height: 52px;
            border-radius: 12px;
            object-fit: cover;
            
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.01em;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
            font-size: 0.95rem;
        }

        .nav-links a {
            color: rgba(226, 232, 240, 0.88);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: rgba(59, 130, 246, 0.8);
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: #ffffff;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.75rem 1.8rem;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            box-shadow: 0 20px 45px rgba(37, 99, 235, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 24px 55px rgba(59, 130, 246, 0.45);
        }

        .btn-outline {
            background: rgba(37, 99, 235, 0.08);
            /* Utiliser une couleur de texte foncée pour améliorer le contraste et la lisibilité */
            color: #0f172a; /* slate-900 */
            border: 1px solid rgba(59, 130, 246, 0.4);
            font-weight: 600;
        }

        .btn-outline:hover {
            background: rgba(59, 130, 246, 0.2);
            color: white;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
            padding: 7rem 4rem 6rem;
            max-width: 1800px;
            margin: 0 auto;
            min-height: calc(100vh - 80px);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.92));
            border-radius: 42px;
            box-shadow: 0 40px 110px rgba(15, 23, 42, 0.28);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: -160px -200px -140px -200px;
            background: radial-gradient(circle at 18% 22%, rgba(59, 130, 246, 0.18), transparent 60%),
                        radial-gradient(circle at 82% 35%, rgba(14, 165, 233, 0.16), transparent 65%);
            filter: blur(90px);
            z-index: 0;
        }

        .hero-content {
            padding-right: 2rem;
            animation: fadeInLeft 0.8s ease-out;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-label {
            color: var(--primary);
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-light);
            padding: 0.55rem 1.35rem;
            border-radius: 999px;
            border: 1px solid rgba(59, 130, 246, 0.25);
        }

        .hero-label::before {
            content: '🏥';
            font-size: 1.1rem;
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            color: var(--dark);
            line-height: 1.15;
            letter-spacing: -0.03em;
        }

        .hero-content h1 .highlight {
            position: relative;
            display: inline-flex;
            align-items: center;
            padding: 0 0.2em;
        }

        .hero-content h1 .highlight::after {
            content: '';
            position: absolute;
            width: 115%;
            height: 14px;
            background: rgba(59, 130, 246, 0.2);
            bottom: 6px;
            left: -7%;
            z-index: -1;
            border-radius: 999px;
        }

        .dynamic-text {
            display: inline-block;
            min-width: 8ch;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.45s ease, transform 0.45s ease;
        }

        .dynamic-text.fade-out {
            opacity: 0;
            transform: translateY(-12px);
        }

        .dynamic-text.fade-in {
            opacity: 1;
            transform: translateY(0);
        }

        /* Reveal Animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal-left {
            transform: translateX(-40px);
        }

        .reveal-right {
            transform: translateX(40px);
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translate(0, 0);
        }

        /* Parallax */
        .parallax {
            will-change: transform;
        }

        /* Animated gradients */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .gradient-animated {
            background-size: 200% 200% !important;
            animation: gradientShift 12s ease infinite;
        }

        /* Hero Particles */
        .hero-particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .hero-particles span {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            animation: floatParticle 12s ease-in-out infinite;
        }

        .hero-particles span:nth-child(odd) {
            background: rgba(59, 130, 246, 0.35);
            width: 10px;
            height: 10px;
        }

        @keyframes floatParticle {
            0%, 100% { transform: translate3d(0, 0, 0); opacity: 0; }
            20% { opacity: 1; }
            50% { transform: translate3d(20px, -40px, 0); }
            80% { opacity: 1; }
        }

        /* Timeline */
        .timeline-section {
            position: relative;
            padding: 6rem 4rem 5.5rem;
            background: linear-gradient(165deg, rgba(15, 23, 42, 0.92), #0f172a);
            color: #e2e8f0;
            overflow: hidden;
        }

        .timeline-section::before {
            content: '';
            position: absolute;
            inset: -40% -20% 30% -40%;
            background: radial-gradient(circle at top, rgba(59, 130, 246, 0.4), transparent 70%);
            opacity: 0.6;
        }

        .timeline-section .section-badge {
            background: rgba(96, 165, 250, 0.18);
            color: rgba(226, 232, 240, 0.95);
        }

        .timeline-section .section-heading {
            color: rgba(248, 250, 252, 0.96);
        }

        .timeline-section .section-desc {
            color: rgba(226, 232, 240, 0.78);
        }

        .timeline-section .section-actions p {
            color: rgba(203, 213, 225, 0.75);
        }

        .timeline-section .btn-secondary {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.25), rgba(14, 116, 144, 0.25));
            color: rgba(226, 232, 240, 0.96);
            border: 1px solid rgba(96, 165, 250, 0.35);
            box-shadow: 0 20px 45px rgba(15, 118, 230, 0.25);
        }

        .timeline-section .btn-secondary:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.45), rgba(14, 116, 144, 0.35));
            box-shadow: 0 28px 70px rgba(59, 130, 246, 0.35);
        }

        .timeline-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            z-index: 1;
        }

        .timeline-track {
            position: relative;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 2.6rem;
            margin-top: 3.5rem;
        }

        .timeline-track::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0), rgba(125, 211, 252, 0.6), rgba(59, 130, 246, 0));
            box-shadow: 0 0 25px rgba(56, 189, 248, 0.35);
            transform: translateY(-50%);
            z-index: 0;
        }

        .timeline-step {
            position: relative;
            padding: 2.6rem 2.2rem;
            background: rgba(15, 23, 42, 0.7);
            border-radius: 22px;
            border: 1px solid rgba(96, 165, 250, 0.25);
            box-shadow: 0 28px 70px rgba(8, 47, 73, 0.55);
            text-align: left;
            overflow: hidden;
            backdrop-filter: blur(8px);
            transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        }

        .timeline-step::before {
            content: attr(data-step);
            position: absolute;
            top: -12px;
            margin-top: 12px;
            left: 2.2rem;
            width: 46px;
            height: 46px;
            border-radius: 16px;
            background: linear-gradient(135deg, #3B82F6, #1E40AF);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.32);
        }

        .timeline-step::after {
            content: '';
            position: absolute;
            inset: 15% 12% -35% -20%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.35), transparent 70%);
            filter: blur(18px);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .timeline-step:hover::after {
            opacity: 1;
        }

        .timeline-step:hover {
            transform: translateY(-8px);
            box-shadow: 0 36px 95px rgba(37, 99, 235, 0.45);
            border-color: rgba(125, 211, 252, 0.45);
        }

        .timeline-step h3 {
            font-size: 1.25rem;
            margin-bottom: 0.85rem;
            color: #f8fafc;
        }

        .timeline-step p {
            color: rgba(226, 232, 240, 0.84);
            line-height: 1.7;
            font-size: 0.96rem;
        }

        /* Testimonials */
        .testimonials-section {
            position: relative;
            padding: 6rem 4rem 5rem;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.95), rgba(226, 232, 240, 0.65));
            overflow: hidden;
        }

        .testimonials-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 18% 24%, rgba(96, 165, 250, 0.3), transparent 58%);
        }

        .testimonials-section::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 82% 72%, rgba(59, 130, 246, 0.24), transparent 60%);
        }

        .testimonials-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            z-index: 1;
        }

        .testimonial-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.4rem;
            margin: 2.4rem 0 2.8rem;
        }

        .summary-card {
            position: relative;
            padding: 1.35rem 1.5rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 22px 55px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .summary-card::after {
            content: '';
            position: absolute;
            inset: 35% -30% -40% -30%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.28), transparent 70%);
            filter: blur(18px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .summary-card:hover::after {
            opacity: 1;
        }

        .summary-title {
            font-weight: 700;
            color: var(--dark);
            font-size: 1.02rem;
        }

        .summary-desc {
            font-size: 0.85rem;
            color: var(--gray);
            margin-top: 0.4rem;
        }

        .summary-rating {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.8rem;
            font-weight: 600;
            color: var(--primary);
        }

        .testimonials-slider {
            position: relative;
            overflow: hidden;
        }

        .testimonial-track {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .testimonial-card {
            min-width: 100%;
            padding: 3.2rem 3rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 34px;
            border: 1px solid rgba(59, 130, 246, 0.14);
            box-shadow: 0 28px 70px rgba(15, 23, 42, 0.16);
            position: relative;
            overflow: hidden;
        }

        .testimonial-card::before {
            content: '';
            position: absolute;
            inset: 12% -20% auto -15%;
            height: 55%;
            background: radial-gradient(circle at top, rgba(59, 130, 246, 0.28), transparent 60%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .testimonial-card::after {
            content: '';
            position: absolute;
            inset: 55% -35% -35% -35%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.32), transparent 70%);
            filter: blur(22px);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .testimonial-card:hover::before,
        .testimonial-card:hover::after {
            opacity: 1;
        }

        .testimonial-card p {
            color: var(--gray);
            font-size: 1.06rem;
            line-height: 1.85;
            margin-bottom: 2.1rem;
            position: relative;
            z-index: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .testimonial-author img {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.35);
        }

        .testimonial-nav {
            position: absolute;
            right: 0;
            top: -4.5rem;
            display: flex;
            gap: 0.8rem;
        }

        .testimonial-nav button {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: none;
            background: white;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.16);
            color: var(--primary);
            font-size: 1.2rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .testimonial-nav button:hover {
            transform: translateY(-3px);
        }

        .testimonial-dots {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .testimonial-dots button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: none;
            background: rgba(59, 130, 246, 0.24);
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .testimonial-dots button.active {
            transform: scale(1.2);
            background: var(--primary);
        }

        /* Micro interactions */
        .doctor-action {
            position: relative;
            overflow: hidden;
        }

        .doctor-action::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(59, 130, 246, 0.12);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.5s ease, height 0.5s ease;
        }

        .doctor-action:hover::before {
            width: 180px;
            height: 180px;
        }

        /* Counter styling */
        .stat-number {
            position: relative;
        }

        .stat-number::after {
            content: '+';
            font-size: 1.2rem;
            margin-left: 0.2rem;
            color: var(--primary);
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--primary);
            background: rgba(59, 130, 246, 0.12);
            padding: 0.45rem 1rem;
            border-radius: 999px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .section-badge::before {
            content: '✦';
        }

        .section-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .section-header-text {
            max-width: 600px;
        }

        .section-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: flex-end;
            justify-content: space-between;
        }

        .section-actions .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.85rem 1.8rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(226, 232, 240, 0.92);
            border: 1px solid rgba(96, 165, 250, 0.45);
            background: rgba(37, 99, 235, 0.18);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 18px 55px rgba(15, 23, 42, 0.35);
        }

        .section-actions .btn-secondary:hover {
            background: rgba(59, 130, 246, 0.32);
            transform: translateY(-2px);
        }

        .section-actions p {
            margin: 0;
            color: rgba(148, 163, 184, 0.78);
            font-size: 0.85rem;
            text-align: right;
            max-width: 220px;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: var(--gray);
            margin-bottom: 2.5rem;
            line-height: 1.8;
            max-width: 550px;
        }

        .hero-buttons {
            display: flex;
            gap: 1.2rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .hero-buttons .btn {
            padding: 1rem 2.5rem;
            font-size: 1rem;
            font-weight: 600;
        }

        .hero-buttons .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hero-image {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeInRight 0.8s ease-out;
            z-index: 1;
        }

        .hero-image-container {
            position: relative;
            width: 100%;
            max-width: 500px;
        }

        .hero-image-bg {
            position: absolute;
            width: 450px;
            height: 450px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(59, 130, 246, 0.04));
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: translate(-50%, -50%) scale(1);
            }
            50% {
                transform: translate(-50%, -50%) scale(1.05);
            }
        }

        .hero-image-bg::before {
            content: '';
            position: absolute;
            width: 120%;
            height: 120%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), transparent);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 3s ease-in-out infinite reverse;
        }

        .hero-image img {
            max-width: 990px;
            width: 100%;
            position: relative;
            z-index: 1;
            border-radius: 24px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.2);
            transition: transform 0.3s ease;
        }

        .hero-image img:hover {
            transform: scale(1.02);
        }

        /* Features Below Hero */
        .hero-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: -3rem auto 4rem;
            max-width: 1200px;
            padding: 0 4rem;
            position: relative;
            z-index: 10;
            perspective: 1200px;
        }

        .feature-box {
            background: rgba(15, 23, 42, 0.85);
            padding: 2.3rem;
            border-radius: 24px;
            box-shadow: 0 22px 55px rgba(15, 23, 42, 0.4);
            display: flex;
            align-items: center;
            gap: 1.8rem;
            border: 1px solid rgba(59, 130, 246, 0.28);
            transition: all 0.45s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(8px);
            transform-style: preserve-3d;
        }

        .feature-box::after {
            content: '';
            position: absolute;
            inset: 15% 10% -30% 10%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.35), transparent 70%);
            opacity: 0;
            filter: blur(18px);
            transition: opacity 0.4s ease;
        }

        .feature-box:hover {
            transform: translateY(-10px) rotateX(3deg);
            box-shadow: 0 36px 95px rgba(37, 99, 235, 0.35);
            border-color: rgba(96, 165, 250, 0.5);
        }

        .feature-box:hover::after {
            opacity: 1;
        }

        .feature-box .icon {
            font-size: 2.4rem;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(59, 130, 246, 0.18);
            border-radius: 16px;
            flex-shrink: 0;
            color: rgba(226, 232, 240, 0.95);
            box-shadow: inset 0 0 0 1px rgba(96, 165, 250, 0.3);
        }

        .feature-box .number {
            font-size: 1.2rem;
            font-weight: 700;
            color: rgba(248, 250, 252, 0.95);
            margin-bottom: 0.3rem;
        }

        .feature-box p {
            font-size: 0.9rem;
            color: rgba(203, 213, 225, 0.78);
        }

        /* Sections communes */
        .section {
            padding: 5rem 4rem;
        }

        .section-title {
            color: rgba(125, 211, 252, 0.85);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-heading {
            font-size: 3.2rem;
            font-weight: 900;
            color: rgba(248, 250, 252, 0.96);
            margin-bottom: 1.2rem;
            letter-spacing: -0.03em;
        }

        .section-desc {
            color: rgba(203, 213, 225, 0.82);
            margin-bottom: 3.5rem;
            max-width: 600px;
            line-height: 1.8;
            font-size: 1.05rem;
        }

        /* Insights Section */
        .insights-section {
            padding: 5.5rem 4rem;
            background: radial-gradient(circle at 10% 15%, rgba(37, 99, 235, 0.3), transparent 65%),
                        linear-gradient(150deg, rgba(8, 47, 73, 0.92), rgba(15, 23, 42, 0.92));
            position: relative;
            overflow: hidden;
        }

        .insights-section::before {
            content: '';
            position: absolute;
            inset: -220px -260px auto -180px;
            height: 60%;
            background: radial-gradient(circle at 30% 40%, rgba(59, 130, 246, 0.32), transparent 70%);
            filter: blur(90px);
        }

        .insights-section::after {
            content: '';
            position: absolute;
            inset: auto -240px -280px 40%;
            height: 58%;
            background: radial-gradient(circle at 50% 60%, rgba(14, 165, 233, 0.28), transparent 70%);
            filter: blur(95px);
        }

        .insights-grid {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .insights-image {
            position: relative;
        }

        .insights-image::before {
            content: '';
            position: absolute;
            inset: -8% -10% auto -6%;
            height: 60%;
            background: radial-gradient(circle at top, rgba(59, 130, 246, 0.35), transparent 70%);
            filter: blur(25px);
        }

        .insights-image img {
            max-width: 100%;
            border-radius: 26px;
            box-shadow: 0 45px 120px rgba(8, 47, 73, 0.45);
            border: 1px solid rgba(96, 165, 250, 0.25);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2.5rem;
        }

        .stat-box {
            padding: 1.8rem;
            background: rgba(15, 23, 42, 0.78);
            border-radius: 18px;
            border: 1px solid rgba(59, 130, 246, 0.28);
            box-shadow: 0 24px 70px rgba(8, 47, 73, 0.4);
            position: relative;
            overflow: hidden;
        }

        .stat-box::after {
            content: '';
            position: absolute;
            inset: 35% -20% -40% -20%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.32), transparent 70%);
            filter: blur(20px);
            opacity: 0;
            transition: opacity 0.35s ease;
        }

        .stat-box:hover::after {
            opacity: 1;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: rgba(191, 219, 254, 0.95);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(226, 232, 240, 0.85);
            font-size: 1rem;
            font-weight: 600;
        }

        /* Doctors Section */
        .doctors-section {
            position: relative;
            padding: 6.5rem 4rem 5.5rem;
            background: radial-gradient(circle at 15% 12%, rgba(30, 64, 175, 0.35), transparent 60%),
                        radial-gradient(circle at 85% 15%, rgba(37, 99, 235, 0.28), transparent 65%),
                        linear-gradient(150deg, rgba(8, 47, 73, 0.94), rgba(15, 23, 42, 0.94));
            overflow: hidden;
        }

        .doctors-section::before {
            content: '';
            position: absolute;
            inset: -200px -220px auto -200px;
            height: 70%;
            background: radial-gradient(circle at 25% 40%, rgba(59, 130, 246, 0.32), transparent 70%);
            filter: blur(95px);
        }

        .doctors-section::after {
            content: '';
            position: absolute;
            inset: auto -220px -260px 35%;
            height: 60%;
            background: radial-gradient(circle at 60% 60%, rgba(96, 165, 250, 0.28), transparent 70%);
            filter: blur(90px);
        }

        .doctors-container {
            position: relative;
            max-width: 1400px;
            margin: 0 auto;
            z-index: 1;
        }

        .section-pill-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.75rem;
        }

        .section-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.2rem;
            border-radius: 999px;
            border: 1px solid rgba(59, 130, 246, 0.25);
            background: rgba(255, 255, 255, 0.7);
            color: var(--primary);
            font-weight: 600;
            font-size: 0.85rem;
            backdrop-filter: blur(6px);
        }

        .section-metrics {
            margin: 2.5rem 0 3.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .metric-card {
            position: relative;
            padding: 1.8rem 1.6rem;
            border-radius: 20px;
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(59, 130, 246, 0.28);
            box-shadow: 0 28px 70px rgba(8, 47, 73, 0.42);
            display: flex;
            align-items: center;
            gap: 1rem;
            backdrop-filter: blur(8px);
        }

        .metric-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.16), transparent 60%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .metric-card:hover::after {
            opacity: 1;
        }

        .metric-icon {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: rgba(37, 99, 235, 0.25);
            font-size: 1.4rem;
            color: rgba(226, 232, 240, 0.95);
        }

        .metric-text {
            position: relative;
            z-index: 1;
        }

        .metric-value {
            font-weight: 700;
            font-size: 1.4rem;
            color: rgba(248, 250, 252, 0.95);
        }

        .metric-label {
            font-size: 0.9rem;
            color: rgba(203, 213, 225, 0.78);
            margin-top: 0.2rem;
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
        }

        .doctor-card {
            position: relative;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92), rgba(8, 47, 73, 0.92));
            border-radius: 32px;
            overflow: hidden;
            border: 1px solid rgba(59, 130, 246, 0.28);
            box-shadow: 0 38px 95px rgba(8, 47, 73, 0.48);
            transition: transform 0.45s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.45s ease;
        }

        .doctor-card::before {
            content: '';
            position: absolute;
            inset: auto -28% 36% -28%;
            height: 56%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.18), rgba(59, 130, 246, 0));
            opacity: 0;
            transition: opacity 0.5s ease;
            pointer-events: none;
        }

        .doctor-card:hover {
            transform: translateY(-16px) scale(1.01);
            box-shadow: 0 32px 80px rgba(59, 130, 246, 0.22);
            border-color: rgba(59, 130, 246, 0.35);
        }

        .doctor-card:hover::before {
            opacity: 1;
        }

        .doctor-figure {
            position: relative;
            padding: 2.75rem 2.5rem 0;
            text-align: center;
        }

        .doctor-figure::after {
            content: '';
            position: absolute;
            inset: auto 15% -22% 15%;
            height: 42%;
            background: radial-gradient(circle at top, rgba(59, 130, 246, 0.48), transparent 65%);
            z-index: 0;
        }

        .doctor-figure img {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            transition: transform 0.45s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .doctor-card:hover .doctor-figure img {
            transform: translateY(-8px);
        }

        .doctor-badge {
            position: absolute;
            top: 1.6rem;
            left: 2.2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(59, 130, 246, 0.16);
            color: var(--primary);
            padding: 0.55rem 1.3rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid rgba(59, 130, 246, 0.25);
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.18);
            z-index: 1;
        }

        .doctor-info {
            padding: 0 2.5rem 2.75rem;
            position: relative;
            z-index: 2;
        }

        .doctor-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: rgba(248, 250, 252, 0.96);
            margin-bottom: 0.55rem;
        }

        .doctor-specialty {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.9rem;
            color: rgba(125, 211, 252, 0.9);
            font-weight: 600;
            background: rgba(37, 99, 235, 0.24);
            padding: 0.5rem 1.3rem;
            border-radius: 999px;
            margin-bottom: 1.25rem;
        }

        .doctor-meta {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            color: rgba(203, 213, 225, 0.78);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .doctor-footer {
            margin-top: 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.2rem;
            font-size: 0.9rem;
        }

        .doctor-rating {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 600;
            color: rgba(250, 204, 21, 0.92);
        }

        .doctor-action {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.7rem 1.45rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            border: none;
            color: white;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 18px 48px rgba(37, 99, 235, 0.38);
        }

        .doctor-action::after {
            content: '→';
            font-size: 0.85rem;
        }

        .doctor-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 35px rgba(59, 130, 246, 0.3);
        }

        /* Departments Section */
        .departments-section {
            position: relative;
            padding: 6.5rem 4rem 5.5rem;
            background: radial-gradient(circle at 12% 18%, rgba(59, 130, 246, 0.22), transparent 58%),
                        radial-gradient(circle at 85% 22%, rgba(14, 165, 233, 0.18), transparent 65%),
                        linear-gradient(150deg, rgba(248, 250, 252, 0.98), rgba(226, 232, 240, 0.78));
            overflow: hidden;
        }

        .departments-section::before {
            content: '';
            position: absolute;
            inset: -160px -180px auto -160px;
            height: 65%;
            background: radial-gradient(circle at 30% 40%, rgba(59, 130, 246, 0.25), transparent 70%);
            filter: blur(90px);
        }

        .departments-section::after {
            content: '';
            position: absolute;
            inset: auto -140px -220px 45%;
            height: 60%;
            background: radial-gradient(circle at 60% 60%, rgba(14, 165, 233, 0.24), transparent 70%);
            filter: blur(95px);
        }

        .departments-orb {
            position: absolute;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.8), rgba(59, 130, 246, 0.25));
            filter: blur(85px);
            opacity: 0.55;
            pointer-events: none;
        }

        .departments-orb.orb-left {
            top: -120px;
            left: -90px;
        }

        .departments-orb.orb-right {
            bottom: -140px;
            right: -70px;
        }

        .departments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .dept-item {
            position: relative;
            padding: 2.6rem 2rem;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 26px 70px rgba(15, 23, 42, 0.14);
            overflow: hidden;
            transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
            backdrop-filter: blur(6px);
        }

        .dept-item::before {
            content: '';
            position: absolute;
            inset: 10% 12% auto 10%;
            height: 46%;
            background: radial-gradient(circle at top, rgba(59, 130, 246, 0.24), transparent 65%);
            opacity: 0;
            transition: opacity 0.35s ease;
        }

        .dept-item::after {
            content: '';
            position: absolute;
            inset: 55% -32% -35% -32%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.32), transparent 70%);
            filter: blur(20px);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .dept-item:hover {
            transform: translateY(-14px);
            box-shadow: 0 42px 110px rgba(59, 130, 246, 0.28);
            border-color: rgba(59, 130, 246, 0.38);
        }

        .dept-item:hover::before,
        .dept-item:hover::after {
            opacity: 1;
        }

        .dept-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: rgba(59, 130, 246, 0.18);
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 1.1rem;
            box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.12);
        }

        .dept-name {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--dark);
        }

        .dept-desc {
            margin-top: 0.65rem;
            font-size: 0.92rem;
            color: var(--gray);
            line-height: 1.7;
        }

        /* Services Section */
        .services-section {
            position: relative;
            padding: 6rem 4rem 5.5rem;
            background: radial-gradient(circle at 12% 18%, rgba(14, 165, 233, 0.22), transparent 58%),
                        radial-gradient(circle at 82% 68%, rgba(59, 130, 246, 0.16), transparent 60%),
                        var(--light-bg);
            overflow: hidden;
        }

        .services-section::before {
            content: '';
            position: absolute;
            inset: -25% -10% auto -15%;
            height: 65%;
            background: radial-gradient(circle at 30% 40%, rgba(59, 130, 246, 0.18), transparent 70%);
            filter: blur(50px);
        }

        .services-container {
            position: relative;
            max-width: 1400px;
            margin: 0 auto;
            z-index: 1;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.3rem;
        }

        .service-card {
            position: relative;
            padding: 2.6rem 2.3rem;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 28px 70px rgba(15, 23, 42, 0.18);
            overflow: hidden;
            transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.4s ease;
        }

        .service-card::before {
            content: '';
            position: absolute;
            inset: 10% 10% auto 10%;
            height: 45%;
            background: radial-gradient(circle at top, rgba(59, 130, 246, 0.25), transparent 60%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .service-card::after {
            content: '';
            position: absolute;
            inset: 40% -30% -30% -30%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.32), transparent 70%);
            filter: blur(22px);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .service-card:hover {
            transform: translateY(-14px) scale(1.01);
            box-shadow: 0 42px 110px rgba(59, 130, 246, 0.28);
            border-color: rgba(59, 130, 246, 0.35);
        }

        .service-card:hover::before,
        .service-card:hover::after {
            opacity: 1;
        }

        .service-icon {
            width: 62px;
            height: 62px;
            border-radius: 20px;
            background: rgba(59, 130, 246, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
            box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.12);
        }

        .service-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.35rem 0.95rem;
            border-radius: 999px;
            background: rgba(59, 130, 246, 0.14);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1.2rem;
        }

        .service-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .service-card p {
            color: var(--gray);
            line-height: 1.7;
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }

        .service-footer {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            margin-top: 1.6rem;
            font-weight: 600;
            color: var(--primary);
            position: relative;
            z-index: 1;
        }

        /* Appointment Section */
        .appointment-section {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 5rem 4rem;
            border-radius: 40px;
            text-align: center;
            max-width: 900px;
            margin: 5rem auto;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(59, 130, 246, 0.35);
        }

        .appointment-section::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }

        .appointment-section::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -30px;
            left: -30px;
        }

        .appointment-section h3 {
            font-size: 2.2rem;
            color: white;
            margin-bottom: 1rem;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        .appointment-section p {
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 2rem;
            font-size: 1.1rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            z-index: 1;
        }

        .appointment-btn {
            position: relative;
            z-index: 1;
            background: white;
            color: var(--primary);
            padding: 1.2rem 3rem;
            font-size: 1.1rem;
            font-weight: 700;
        }

        /* Newsletter */
        .newsletter {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: white;
            padding: 5rem 4rem;
            border-radius: 40px;
            margin: 5rem auto;
            max-width: 1300px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(59, 130, 246, 0.3);
        }

        .newsletter::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            top: -100px;
            left: -100px;
        }

        .newsletter h3 {
            font-size: 2rem;
            margin-bottom: 0.8rem;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        .newsletter p {
            margin-bottom: 2rem;
            opacity: 0.95;
            font-size: 1.05rem;
            position: relative;
            z-index: 1;
        }

        .newsletter-form {
            display: flex;
            gap: 1rem;
            max-width: 500px;
            margin: 0 auto;
            flex-wrap: wrap;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .newsletter-form input {
            flex: 1;
            min-width: 250px;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .newsletter-form input:focus {
            outline: 3px solid rgba(255, 255, 255, 0.5);
        }

        .newsletter-form button {
            padding: 1rem 2rem;
            background: white;
            color: var(--primary);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .newsletter-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Auth Modals */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: linear-gradient(135deg, #ffffff 0%, #f8fbfc 100%);
            padding: 2.8rem;
            border-radius: 28px;
            width: 90%;
            max-width: 480px;
            box-shadow: 0 25px 80px rgba(59, 130, 246, 0.2), 0 0 1px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: 1px solid rgba(59, 130, 246, 0.15);
        }

        #registerModal .modal-content {
            max-width: 500px;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px) scale(0.95);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .close-btn {
            position: absolute;
            top: 1.2rem;
            right: 1.2rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #cbd5e1;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: #f1f5f9;
            color: var(--primary);
            transform: rotate(90deg);
        }

        .modal-title {
            font-size: 1.6rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.2rem;
            padding-right: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-error {
            margin-top: 0.3rem;
            color: #dc2626;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .register-grid {
            display: grid;
            gap: 0.8rem;
        }

        #registerModal .register-grid .form-group {
            margin-bottom: 0;
        }

        .register-grid .full-width {
            grid-column: 1 / -1;
        }

        @media (min-width: 768px) {
            #registerModal .modal-content {
                max-width: 520px;
            }

            .register-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.9rem 1.2rem;
            }

            .register-grid .full-width {
                grid-column: span 2;
            }
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #334155;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            background: #fff;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-group input::placeholder {
            color: #cbd5e1;
        }

        .modal-footer {
            margin-top: 1.5rem;
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .modal-footer p {
            color: #64748b;
            margin-bottom: 0.3rem;
            font-size: 0.9rem;
        }

        .modal-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .modal-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: #e0e0e0;
            padding: 4rem 4rem 2rem;
            margin-top: 4rem;
            position: relative;
            overflow: hidden;
        }

        footer::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            top: -200px;
            right: -200px;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            position: relative;
            z-index: 1;
        }

        .footer-col h4 {
            color: white;
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        .footer-col a {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.95rem;
            margin-bottom: 0.8rem;
            
            padding-left: 0;
        }

        .footer-col a:hover {
            color: white;
            padding-left: 8px;
        }

        .footer-bottom {
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 3rem;
            padding-top: 2rem;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .footer-bottom a {
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.3s;
            text-decoration: none;
        }

        .footer-bottom a:hover {
            color: white;
        }

        /* Scroll to Top Button */
        .scroll-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.45);
            z-index: 999;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .scroll-to-top:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(59, 130, 246, 0.55);
        }

        .scroll-to-top.active {
            display: flex;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            nav {
                padding: 1.2rem 2rem;
            }

            .hero {
                padding: 3rem 2rem;
                gap: 3rem;
            }

            .hero-content h1 {
                font-size: 2.8rem;
            }

            .hero-features,
            .section {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        @media (max-width: 768px) {
            nav {
                padding: 1rem 1.5rem;
            }

            .logo {
                font-size: 1.3rem;
            }

            .logo-icon {
                width: 35px;
                height: 35px;
            }

            .nav-links {
                display: none;
            }

            .hero {
                grid-template-columns: 1fr;
                gap: 3rem;
                padding: 2rem 1.5rem;
                min-height: auto;
            }

            .hero-content {
                padding-right: 0;
                text-align: center;
            }

            .hero-content h1 {
                font-size: 2.2rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-image-bg {
                width: 300px;
                height: 300px;
            }

            .hero-image img {
                max-width: 280px;
            }

            .hero-features {
                grid-template-columns: 1fr;
                padding: 0 1.5rem;
                gap: 1.5rem;
                margin: -2rem auto 3rem;
            }

            .feature-box {
                padding: 1.5rem;
            }

            .section-heading {
                font-size: 2rem;
            }

            .insights-grid {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .doctors-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2rem;
            }

            .departments-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .section {
                padding: 3rem 1.5rem;
            }

            .appointment-section {
                padding: 3rem 2rem;
                margin: 3rem 1.5rem;
            }

            .appointment-section h3 {
                font-size: 1.8rem;
            }

            .newsletter {
                margin: 3rem 1.5rem;
                padding: 3rem 2rem;
            }

            .newsletter h3 {
                font-size: 1.6rem;
            }

            footer {
                padding: 3rem 1.5rem 1.5rem;
            }

            .footer-content {
                gap: 2rem;
            }

            .modal-content {
                padding: 2rem;
                margin: 1rem;
            }

            .section-heading {
                font-size: 2rem;
            }

            .section-header {
                font-size: 1.8rem;
            }

            .hero-buttons {
                flex-direction: column;
                width: 100%;
            }

            .hero-buttons .btn {
                width: 100%;
            }

            .section-heading {
                font-size: 1.6rem;
            }

            .section-actions {
                gap: 0.75rem;
            }

            .doctors-grid {
                grid-template-columns: 1fr;
            }

            .departments-grid {
                grid-template-columns: 1fr;
            }

            .dept-item {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <img src="{{ asset('photos/logoalmine.png') }}" alt="Al-Amine RDV" class="logo-img" style="width: 180px; height: auto; border-radius: 0;">
        </div>
        <div class="nav-links">
            <a href="#home">Accueil</a>
            <a href="#doctors">Docteurs</a>
            <a href="#services">Services</a>
            <a href="#contact">Contact</a>
        </div>
        <div class="nav-buttons">
            <button class="btn btn-primary" onclick="openLoginModal()">Se connecter</button>
            <button class="btn btn-primary" onclick="openRegisterModal()">Créer un Compte</button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="hero-label reveal">Une Équipe d'Experts à Votre Service</div>
            <h1 class="reveal">
                Nous Guidons Votre Santé Pour Développer Votre
                <span class="highlight">
                    <span class="dynamic-text" id="dynamicWord" data-dynamic-words='["Bien-être", "Santé", "Confiance"]'>Bien-être</span>
                </span>
            </h1>
            <p class="reveal">Prenez rendez-vous facilement avec nos praticiens qualifiés. Une plateforme moderne pour gérer tous vos besoins médicaux en quelques clics. Votre santé mérite le meilleur accompagnement.</p>
            <div class="hero-buttons">
                <button class="btn btn-primary btn-icon reveal" onclick="openRegisterModal()">
                    <span>TROUVER UN DOCTEUR</span>
                    <span>→</span>
                </button>
                <button class="btn btn-outline reveal" onclick="window.location.href='tel:+221770000000'">
                    <span>📞 Appelez: +221 77 000 0000</span>
                </button>
            </div>
        </div>
        <div class="hero-image parallax reveal" data-parallax-speed="0.25">
            <div class="hero-image-container">
                <div class="hero-image-bg"></div>
                <div class="hero-particles">
                    <span style="top: 18%; left: 18%; animation-delay: 0s"></span>
                    <span style="top: 32%; left: 72%; animation-delay: 2s"></span>
                    <span style="top: 58%; left: 28%; animation-delay: 4s"></span>
                    <span style="top: 78%; left: 60%; animation-delay: 6s"></span>
                    <span style="top: 46%; left: 84%; animation-delay: 8s"></span>
                </div>
                <img src="{{ asset('photos/doctorgeneraliste.png') }}" alt="Docteur Professionnel Al-Amine RDV">
            </div>
        </div>
    </section>

    <!-- Features Below Hero -->
    <div class="hero-features">
        <div class="feature-box reveal">
            <div class="icon">📋</div>
            <div>
                <div class="number">Réservation Facile</div>
                <p>Prenez rendez-vous en ligne 24/7</p>
            </div>
        </div>
        <div class="feature-box reveal">
            <div class="icon">⚕️</div>
            <div>
                <div class="number">Experts Qualifiés</div>
                <p>Plus de 50 praticiens certifiés</p>
            </div>
        </div>
        <div class="feature-box reveal">
            <div class="icon">🏥</div>
            <div>
                <div class="number">Service de Qualité</div>
                <p>Soins médicaux d'excellence</p>
            </div>
        </div>
    </div>

    <!-- Insights Section -->
    <section class="insights-section">
        <div class="insights-grid">
            <div class="insights-image">
                <img src="{{ asset('photos/illustration(dessin).png') }}" alt="Illustration Santé">
            </div>
            <div>
                <div class="section-badge">Nos Chiffres Clés</div>
                <h2 class="section-heading">La plateforme de soins qui connecte vos besoins aux meilleurs spécialistes</h2>
                <p class="section-desc">Al-Amine RDV orchestre chaque étape de votre parcours de soins : tri intelligent des spécialistes, préparation des consultations, suivi post-visite et notifications personnalisées pour garantir un accompagnement continu.</p>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-number">23+</div>
                        <p class="stat-label">Spécialités Médicales</p>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">50+</div>
                        <p class="stat-label">Médecins Experts</p>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">5000+</div>
                        <p class="stat-label">Patients Satisfaits</p>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">24/7</div>
                        <p class="stat-label">Service Disponible</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Departments Section -->
    <section class="departments-section">
        <div class="doctors-container">
            <span class="departments-orb orb-left"></span>
            <span class="departments-orb orb-right"></span>
            <div class="section-header reveal">
                <div class="section-header-text">
                    <div class="section-badge">Nos Spécialités</div>
                    <h2 class="section-heading">Départements médicaux d'excellence accessibles en quelques clics</h2>
                    <p class="section-desc">Une offre complète qui couvre la prévention, le diagnostic avancé, les traitements sur-mesure et le suivi post-opératoire avec un même niveau d'exigence.</p>
                </div>
                <div class="section-actions">
                    <button class="btn-secondary" onclick="window.location.href='#services'">
                        Explorer le parcours patient
                        <span>→</span>
                    </button>
                    <p>Comparez les délais de prise en charge par spécialité et choisissez l'équipe la plus adaptée.</p>
                </div>
            </div>
            <div class="departments-grid">
                <div class="dept-item reveal">
                    <div class="dept-icon">🦷</div>
                    <div class="dept-name">Dentaire</div>
                    <div class="dept-desc">Soins esthétiques, implants, suivi orthodontique et prévention précoce.</div>
                </div>
                <div class="dept-item">
                    <div class="dept-icon">♿</div>
                    <div class="dept-name">Réadaptation</div>
                    <div class="dept-desc">Rééducation fonctionnelle assistée par capteurs et suivi kinésithérapeute.</div>
                </div>
                <div class="dept-item">
                    <div class="dept-icon">🫀</div>
                    <div class="dept-name">Cardiologie</div>
                    <div class="dept-desc">Plateau technique complet pour diagnostics rapides et monitorings continus.</div>
                </div>
                <div class="dept-item">
                    <div class="dept-icon">💊</div>
                    <div class="dept-name">Pharmacie</div>
                    <div class="dept-desc">Ordonnances numériques, livraison express et rappels de traitements.</div>
                </div>
                <div class="dept-item">
                    <div class="dept-icon">👁️</div>
                    <div class="dept-name">Ophtalmologie</div>
                    <div class="dept-desc">Examens haute précision, chirurgie laser et suivi post-opératoire intelligent.</div>
                </div>
                <div class="dept-item">
                    <div class="dept-icon">🧠</div>
                    <div class="dept-name">Neurologie</div>
                    <div class="dept-desc">Prise en charge intégrée des pathologies cérébrales et cognitives complexes.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Appointment Section -->
    <section style="padding: 2rem 4rem;">
        <div class="appointment-section gradient-animated reveal">
            <h3>✨ Prenez Rendez-vous en Quelques Clics</h3>
            <p>Réservez votre consultation médicale en ligne facilement et rapidement. Des milliers de patients nous font confiance chaque jour pour leur santé.</p>
            <button class="btn btn-primary appointment-btn" onclick="openRegisterModal()">TROUVER UN DOCTEUR →</button>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <div class="services-container">
            <div class="section-header reveal">
                <div class="section-header-text">
                    <div class="section-badge">Nos Services</div>
                    <h2 class="section-heading">Un écosystème de services médicaux piloté par la data</h2>
                    <p class="section-desc">Chaque service Al-Amine RDV est conçu pour fluidifier votre parcours de santé : automatisation des démarches, suivi en temps réel, coordination des équipes et sécurité des données.</p>
                </div>
                <div class="section-actions">
                    <button class="btn-secondary" onclick="openLoginModal()">
                        Accéder à mon espace patient
                        <span>→</span>
                    </button>
                    <p>Retrouvez vos comptes rendus, ordonnances, paiements et rappels dans une interface unique.</p>
                </div>
            </div>

            <div class="services-grid">
                <div class="service-card reveal">
                    <div class="service-icon">🏥</div>
                    <span class="service-tag">Premium</span>
                    <h3>Visite à domicile Premium</h3>
                    <p>Bénéficiez d'une consultation complète à domicile avec transmission instantanée des comptes-rendus à votre médecin traitant et suivi infirmier programmé.</p>
                    <div class="service-footer">
                        Planification express <span>· 24h/24</span>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-icon">💻</div>
                    <span class="service-tag">Digital</span>
                    <h3>Téléconsultation Augmentée</h3>
                    <p>Accédez à nos spécialistes en quelques minutes, partagez vos examens sécurisés et recevez un plan d'action détaillé avec rappels automatisés.</p>
                    <div class="service-footer">
                        Qualité HD <span>· Sans déplacement</span>
                    </div>
                </div>
                <div class="service-card reveal">
                    <div class="service-icon">📋</div>
                    <span class="service-tag">Smart</span>
                    <h3>Ordonnances & logistique</h3>
                    <p>Prescription digitale, livraison express, rappels de prise et suivi d'observance. Tout est synchronisé avec votre pharmacien référent.</p>
                    <div class="service-footer">
                        Traçabilité totale <span>· 100% sécurisée</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="timeline-section" id="parcours">
        <div class="timeline-container">
            <div class="section-header reveal">
                <div class="section-header-text">
                    <div class="section-badge">Parcours Patient</div>
                    <h2 class="section-heading">Une expérience connectée du premier contact au suivi post-consultation</h2>
                    <p class="section-desc">Chaque étape est guidée par nos équipes et automatisée par la plateforme pour vous faire gagner du temps et sécuriser vos données.</p>
                </div>
                <div class="section-actions">
                    <button class="btn-secondary" onclick="openRegisterModal()">
                        Démarrer mon parcours
                        <span>→</span>
                    </button>
                    <p>Créez votre dossier patient en ligne et suivez votre feuille de route personnalisée.</p>
                </div>
            </div>

            <div class="timeline-track">
                <div class="timeline-step reveal" data-step="01">
                    <h3>Pré-qualification</h3>
                    <p>Analyse de vos besoins et proposition instantanée des spécialistes adaptés.</p>
                </div>
                <div class="timeline-step reveal" data-step="02">
                    <h3>Planification intelligente</h3>
                    <p>Prise de rendez-vous avec synchronisation agenda et rappels multicanaux.</p>
                </div>
                <div class="timeline-step reveal" data-step="03">
                    <h3>Consultation augmentée</h3>
                    <p>Notes médicales centralisées, examens pré-chargés et ordonnances digitales.</p>
                </div>
                <div class="timeline-step reveal" data-step="04">
                    <h3>Suivi & reporting</h3>
                    <p>Notifications personnalisées, plan de soins et rapports accessibles 24/7.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section" id="temoignages">
        <div class="testimonials-container">
            <div class="section-header reveal">
                <div class="section-header-text">
                    <div class="section-badge">Témoignages</div>
                    <h2 class="section-heading">Ils nous confient leur santé, voici leurs retours</h2>
                    <p class="section-desc">Patients, praticiens et partenaires partagent leur expérience de la plateforme Al-Amine RDV.</p>
                </div>
            </div>

            <div class="testimonial-summary">
                <div class="summary-card">
                    <div class="summary-title">Indice de confiance</div>
                    <div class="summary-desc">Évaluations consolidées sur les 12 derniers mois.</div>
                    <div class="summary-rating">★ 4.9 · 5 800 avis</div>
                </div>
                <div class="summary-card">
                    <div class="summary-title">Temps moyen de réponse</div>
                    <div class="summary-desc">Notifications push, SMS et emails coordonnés.</div>
                    <div class="summary-rating">⏱️ 3 min · Support multicanal</div>
                </div>
                <div class="summary-card">
                    <div class="summary-title">Recommandations</div>
                    <div class="summary-desc">Par des patients, praticiens et institutions partenaires.</div>
                    <div class="summary-rating">🤝 96% · Net Promoter Score</div>
                </div>
            </div>

            <div class="testimonials-slider">
                <div class="testimonial-track" id="testimonialTrack">
                    <div class="testimonial-card">
                        <p>« Grâce à Al-Amine RDV, j'ai planifié tous mes suivis de grossesse sans appeler une seule fois. Les notifications et la téléconsultation m'ont fait gagner un temps précieux. »</p>
                        <div class="testimonial-author">
                            <img src="https://i.pravatar.cc/100?img=12" alt="Awa Diop">
                            <div>
                                <strong>Awa Diop</strong>
                                <div style="color: var(--gray); font-size: 0.9rem;">Patiente, Dakar</div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <p>« La gestion des agendas et des comptes rendus est totalement automatisée. Mon équipe peut se concentrer sur la prise en charge plutôt que sur l'administratif. »</p>
                        <div class="testimonial-author">
                            <img src="https://i.pravatar.cc/100?img=5" alt="Dr Mamadou Ndiaye">
                            <div>
                                <strong>Dr Mamadou Ndiaye</strong>
                                <div style="color: var(--gray); font-size: 0.9rem;">Chirurgien, Hôpital Al-Amine RDV</div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <p>« Nous avons intégré nos équipes de support patient en moins de deux semaines. Les tableaux de bord nous donnent une visibilité complète sur la satisfaction. »</p>
                        <div class="testimonial-author">
                            <img src="https://i.pravatar.cc/100?img=16" alt="Fatou Mbaye">
                            <div>
                                <strong>Fatou Mbaye</strong>
                                <div style="color: var(--gray); font-size: 0.9rem;">Responsable Expérience Patient</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-nav">
                    <button id="testimonialPrev">‹</button>
                    <button id="testimonialNext">›</button>
                </div>

                <div class="testimonial-dots" id="testimonialDots"></div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section style="padding: 2rem 4rem;">
        <div class="newsletter">
            <h3>📧 Abonnez-vous à Notre Newsletter</h3>
            <p>Recevez des conseils santé, des rappels de rendez-vous et les dernières actualités médicales directement dans votre boîte mail.</p>
            <div class="newsletter-form">
                <input type="email" placeholder="Entrez votre adresse email...">
                <button type="button">S'ABONNER</button>
            </div>
        </div>
    </section>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeLoginModal()">&times;</button>
            <h2 class="modal-title">Connexion</h2>

            @php
                $loginError = session('authError') ?? $errors->first('email');
            @endphp

            @if($loginError)
                <div style="background:#FEE2E2;border:1px solid #FCA5A5;color:#991B1B;padding:0.875rem 1rem;border-radius:0.5rem;margin-bottom:1rem;display:flex;align-items:center;gap:0.75rem;">
                    <span style="font-size:1.25rem;">⚠️</span>
                    <div>
                        <strong>Erreur de connexion</strong>
                        <p style="margin:0.25rem 0 0;font-size:0.875rem;">{{ $loginError }}</p>
                    </div>
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="login_email">Adresse Email</label>
                    <input type="email" id="login_email" name="email" value="{{ old('email') }}" required placeholder="votre@email.com">
                </div>
                <div class="form-group">
                    <label for="login_password">Mot de passe</label>
                    <input type="password" id="login_password" name="password" required placeholder="••••••••">
                </div>
                <div class="form-group" style="text-align:right;margin-top:-0.5rem;margin-bottom:1rem;">
                    <a href="{{ route('password.request') }}" style="font-size:0.875rem;color:#3B82F6;text-decoration:none;">
                        Mot de passe oublié ?
                    </a>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem;">Se Connecter</button>
                <div class="modal-footer">
                    <p>Pas de compte? <a onclick="switchToRegister()">Créer un compte</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeRegisterModal()">&times;</button>
            <h2 class="modal-title">Créer un Compte Patient</h2>

            @if($errors->any())
                <div style="background:#FEE2E2;border:1px solid #FCA5A5;color:#991B1B;padding:0.875rem 1rem;border-radius:0.5rem;margin-bottom:1rem;">
                    <strong style="display:flex;align-items:center;gap:0.5rem;"><span>⚠️</span> Erreurs dans le formulaire:</strong>
                    <ul style="margin:0.5rem 0 0;padding-left:1.5rem;font-size:0.875rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="register-grid">
                    <div class="form-group">
                        <label for="reg_name">Nom de famille</label>
                        <input type="text" id="reg_name" name="name" value="{{ old('name') }}" required placeholder="Ex: Ndiaye">
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_prenom">Prénom</label>
                        <input type="text" id="reg_prenom" name="prenom" value="{{ old('prenom') }}" required placeholder="Ex: Awa">
                        @error('prenom')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_email">Adresse Email</label>
                        <input type="email" id="reg_email" name="email" value="{{ old('email') }}" required placeholder="vous@example.com">
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_phone">Numéro de Téléphone</label>
                        <input type="tel" id="reg_phone" name="telephone" value="{{ old('telephone') }}" required placeholder="+221 77 000 0000">
                        @error('telephone')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_birthdate">Date de naissance</label>
                        <input type="date" id="reg_birthdate" name="date_naissance" value="{{ old('date_naissance') }}" required>
                        @error('date_naissance')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_sexe">Sexe</label>
                        <select id="reg_sexe" name="sexe" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="M" @selected(old('sexe') === 'M')>Homme</option>
                            <option value="F" @selected(old('sexe') === 'F')>Femme</option>
                        </select>
                        @error('sexe')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_cni">Numéro CNI</label>
                        <input type="text" id="reg_cni" name="numero_cni" value="{{ old('numero_cni') }}" required inputmode="numeric" pattern="[0-9]{13}" placeholder="13 chiffres">
                        @error('numero_cni')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_quartier">Quartier</label>
                        <input type="text" id="reg_quartier" name="quartier" value="{{ old('quartier') }}" placeholder="Quartier (optionnel)">
                        @error('quartier')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_ville">Ville</label>
                        <input type="text" id="reg_ville" name="ville" value="{{ old('ville', 'Dakar') }}" placeholder="Ville">
                        @error('ville')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group full-width">
                        <label for="reg_adresse">Adresse complète</label>
                        <input type="text" id="reg_adresse" name="adresse" value="{{ old('adresse') }}" placeholder="Rue, villa, étage...">
                        @error('adresse')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_password">Mot de passe</label>
                        <input type="password" id="reg_password" name="password" required placeholder="••••••••">
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="reg_password_confirm">Confirmer le mot de passe</label>
                        <input type="password" id="reg_password_confirm" name="password_confirmation" required placeholder="••••••••">
                        @error('password_confirmation')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem;">Créer un Compte</button>
                <div class="modal-footer">
                    <p>Vous avez déjà un compte? <a onclick="switchToLogin()">Se Connecter</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-col">
                <h4>Al-Amine RDV</h4>
                <p style="color: #e0e0e0; font-size: 0.9rem; line-height: 1.6;">Fournisseur de soins de santé de premier plan engagé à offrir l'excellence dans les soins médicaux et la satisfaction des patients.</p>
            </div>
            <div class="footer-col">
                <h4>Menu</h4>
                <a href="#home">À Propos</a>
                <a href="#doctors">Docteurs</a>
                <a href="#services">Services</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="footer-col">
                <h4>Aide</h4>
                <a href="#">Nous Contacter</a>
                <a href="#">FAQ</a>
                <a href="#">Support</a>
                <a href="#">Politique de Confidentialité</a>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <p style="color: #e0e0e0; font-size: 0.9rem;">📞 Urgence: +221 77 000 0000</p>
                <p style="color: #e0e0e0; font-size: 0.9rem;">📧 Email: support@al-amine.sn</p>
                <p style="color: #e0e0e0; font-size: 0.9rem;">📍 Dakar, Sénégal</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Al-Amine RDV - Système de Gestion de Rendez-vous Médicaux. Tous droits réservés. | <a href="#">Conditions d'Utilisation</a> | <a href="#">Politique de Confidentialité</a></p>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTop" onclick="scrollToTop()">↑</button>

    <script>
        // Dynamic text animation
        function initDynamicText() {
            const dynamicWordElement = document.getElementById('dynamicWord');

            if (!dynamicWordElement) {
                return;
            }

            let words = [];
            const dataset = dynamicWordElement.getAttribute('data-dynamic-words');

            if (dataset) {
                try {
                    words = JSON.parse(dataset);
                } catch (error) {
                    console.warn('dynamicWord dataset parsing failed:', error);
                }
            }

            if (!Array.isArray(words) || words.length === 0) {
                words = [dynamicWordElement.textContent.trim() || 'Bien-être'];
            }

            let currentWordIndex = 0;
            dynamicWordElement.textContent = words[currentWordIndex];

            if (words.length < 2) {
                return;
            }

            function rotateWord() {
                dynamicWordElement.classList.add('fade-out');

                setTimeout(() => {
                    currentWordIndex = (currentWordIndex + 1) % words.length;
                    dynamicWordElement.textContent = words[currentWordIndex];
                    dynamicWordElement.classList.remove('fade-out');
                    dynamicWordElement.classList.add('fade-in');
                }, 220);

                setTimeout(() => {
                    dynamicWordElement.classList.remove('fade-in');
                }, 700);
            }

            setTimeout(() => {
                rotateWord();
                setInterval(rotateWord, 3200);
            }, 1200);
        }

        function initRevealAnimations() {
            const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');

            if (!revealElements.length) {
                return;
            }

            const addVisibleClass = (el) => el.classList.add('is-visible');

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            addVisibleClass(entry.target);
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    root: null,
                    threshold: 0.15,
                    rootMargin: '0px 0px -10% 0px'
                });

                revealElements.forEach((el) => observer.observe(el));
            } else {
                const revealOnScroll = () => {
                    const viewportHeight = window.innerHeight || document.documentElement.clientHeight;

                    revealElements.forEach((el) => {
                        if (el.classList.contains('is-visible')) {
                            return;
                        }

                        const rect = el.getBoundingClientRect();
                        if (rect.top <= viewportHeight * 0.85) {
                            addVisibleClass(el);
                        }
                    });
                };

                revealOnScroll();
                window.addEventListener('scroll', revealOnScroll, { passive: true });
            }
        }

        function onDocumentReady(callback) {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', callback, { once: true });
            } else {
                callback();
            }
        }

        onDocumentReady(() => {
            initDynamicText();
            initRevealAnimations();
        });

        // Modal functions
        function openLoginModal() {
            document.getElementById('loginModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function openRegisterModal() {
            document.getElementById('registerModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function switchToLogin() {
            closeRegisterModal();
            openLoginModal();
        }

        function switchToRegister() {
            closeLoginModal();
            openRegisterModal();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            const modal = params.get('modal');

            if (modal === 'login') {
                openLoginModal();
            }

            if (modal === 'register') {
                openRegisterModal();
            }

            // Open modals based on session flash
            @if(session('openLogin'))
                openLoginModal();
            @endif

            @if(session('openRegister'))
                openRegisterModal();
            @endif
        });

        // Close modals on outside click
        window.onclick = function(event) {
            const loginModal = document.getElementById('loginModal');
            const registerModal = document.getElementById('registerModal');
            if (event.target == loginModal) {
                closeLoginModal();
            }
            if (event.target == registerModal) {
                closeRegisterModal();
            }
        }

        // Scroll to top button
        const scrollToTopBtn = document.getElementById('scrollToTop');

        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('active');
            } else {
                scrollToTopBtn.classList.remove('active');
            }

            // Navbar scroll effect
            const nav = document.querySelector('nav');
            if (window.pageYOffset > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
