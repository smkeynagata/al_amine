<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Al-Amine - Système de Gestion de Rendez-vous Médicaux</title>
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
            color: #1a1a1a;
            background: #ffffff;
            overflow-x: hidden;
        }

        :root {
            --teal: #0ea5a5;
            --teal-dark: #0d8484;
            --teal-light: #e6f7f7;
            --light-bg: #f8fafb;
            --white: #ffffff;
            --gray: #6b7280;
            --gray-light: #9ca3af;
            --dark: #1f2937;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.1);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.12);
        }

        html {
            scroll-behavior: smooth;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 4rem;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            position: sticky;
            top: 0;
            z-index: 1000;
            
        }

        nav.scrolled {
            padding: 1rem 4rem;
            box-shadow: 0 4px 30px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--teal);
            display: flex;
            align-items: center;
            gap: 0.7rem;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(14, 165, 165, 0.3);
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
            font-size: 0.95rem;
        }

        .nav-links a {
            color: var(--dark);
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
            background: var(--teal);
            
            transform: translateX(-50%);
        }

        .nav-links a:hover {
            color: var(--teal);
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
            background: linear-gradient(135deg, var(--teal), var(--teal-dark));
            color: white;
            box-shadow: 0 4px 15px rgba(14, 165, 165, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 165, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--teal);
            border: 2px solid var(--teal);
        }

        .btn-outline:hover {
            background: var(--teal);
            color: white;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
            padding: 5rem 4rem;
            max-width: 1400px;
            margin: 0 auto;
            min-height: calc(100vh - 100px);
        }

        .hero-content {
            padding-right: 2rem;
            animation: fadeInLeft 0.8s ease-out;
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
            color: var(--teal);
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--teal-light);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
        }

        .hero-label::before {
            content: '✨';
            font-size: 1.1rem;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--dark);
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .hero-content h1 .highlight {
            color: var(--teal);
            font-weight: 800;
            position: relative;
            display: inline-block;
        }

        .hero-content h1 .highlight::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 12px;
            background: rgba(14, 165, 165, 0.2);
            bottom: 8px;
            left: 0;
            z-index: -1;
            border-radius: 4px;
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
            background: linear-gradient(135deg, rgba(14, 165, 165, 0.12), rgba(14, 165, 165, 0.04));
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
            background: linear-gradient(135deg, rgba(14, 165, 165, 0.05), transparent);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 3s ease-in-out infinite reverse;
        }

        .hero-image img {
            max-width: 420px;
            width: 100%;
            position: relative;
            z-index: 1;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
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
        }

        .feature-box {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border: 1px solid rgba(14, 165, 165, 0.1);
        }

        .feature-box .icon {
            font-size: 2.5rem;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--teal-light);
            border-radius: 16px;
            flex-shrink: 0;
        }

        .feature-box .number {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.3rem;
        }

        .feature-box p {
            font-size: 0.9rem;
            color: var(--gray);
        }

        /* Sections communes */
        .section {
            padding: 5rem 4rem;
        }

        .section-title {
            color: var(--teal);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-heading {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 1.2rem;
            letter-spacing: -0.02em;
        }

        .section-desc {
            color: var(--gray);
            margin-bottom: 3.5rem;
            max-width: 600px;
            line-height: 1.8;
            font-size: 1.05rem;
        }

        /* Insights Section */
        .insights-section {
            padding: 5rem 4rem;
            background: white;
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

        .insights-image img {
            max-width: 100%;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2.5rem;
        }

        .stat-box {
            padding: 1.5rem;
            background: var(--teal-light);
            border-radius: 16px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--teal);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--dark);
            font-size: 1rem;
            font-weight: 600;
        }

        /* Doctors Section */
        .doctors-section {
            padding: 5rem 4rem;
            background: var(--light-bg);
        }

        .doctors-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
        }

        .doctor-card {
            position: relative;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            
            cursor: pointer;
        }

        .doctor-card:hover {
            box-shadow: 0 12px 40px rgba(14, 165, 165, 0.15);
        }

        .doctor-image {
            width: 100%;
            height: 320px;
            object-fit: cover;
            background: linear-gradient(135deg, var(--teal-light), #ffffff);
            transition: none;
        }

        .doctor-info {
            padding: 1.8rem;
            position: relative;
            z-index: 2;
        }

        .doctor-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.6rem;
        }

        .doctor-specialty {
            font-size: 0.9rem;
            color: var(--teal);
            font-weight: 600;
            background: var(--teal-light);
            display: inline-block;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            margin-top: 0.5rem;
        }

        /* Departments Section */
        .departments-section {
            padding: 5rem 4rem;
            background: white;
        }

        .departments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .dept-item {
            text-align: center;
            padding: 2.5rem 1.5rem;
            background: var(--light-bg);
            border-radius: 20px;
            cursor: pointer;
            
            border: 2px solid #e5e7eb;
        }

            font-size: 1rem;
            font-weight: 600;
            color: var(--dark);
            transition: color 0.3s ease;
        }

        /* Services Section */
        .services-section {
            padding: 5rem 4rem;
            background: var(--light-bg);
        }

        .services-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
        }

        .service-card {
            padding: 3rem 2.5rem;
            background: white;
            border-radius: 24px;
            border: 2px solid #e5e7eb;
            
            position: relative;
            overflow: hidden;
        }

            transition: transform 0.4s ease;
        }

            border-color: var(--teal);
            box-shadow: 0 15px 50px rgba(14, 165, 165, 0.15);
            transform: translateY(-8px);
        }

        .service-icon {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            display: inline-block;
            transition: transform 0.4s ease;
        }

            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .service-card p {
            font-size: 1rem;
            color: var(--gray);
            line-height: 1.7;
        }

        /* Appointment Section */
        .appointment-section {
            background: linear-gradient(135deg, var(--teal), var(--teal-dark));
            padding: 4rem 3rem;
            border-radius: 30px;
            text-align: center;
            max-width: 900px;
            margin: 4rem auto;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(14, 165, 165, 0.3);
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
            color: var(--teal);
            padding: 1.2rem 3rem;
            font-size: 1.1rem;
            font-weight: 700;
        }

        /* Newsletter */
        .newsletter {
            background: linear-gradient(135deg, #0d8484, var(--teal));
            color: white;
            padding: 4rem 3rem;
            border-radius: 30px;
            margin: 4rem auto;
            max-width: 1300px;
            text-align: center;
            position: relative;
            overflow: hidden;
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
            color: var(--teal);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
            background-color: white;
            padding: 3rem;
            border-radius: 24px;
            width: 90%;
            max-width: 480px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.3);
            animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
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
            top: 1.5rem;
            right: 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--gray-light);
            cursor: pointer;
            border: none;
            background: var(--light-bg);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            
        }

        .close-btn:hover {
            background: var(--teal-light);
            color: var(--teal);
            transform: rotate(90deg);
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 1.5rem;
            padding-right: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.6rem;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            
            background: var(--light-bg);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--teal);
            background: white;
            box-shadow: 0 0 0 4px rgba(14, 165, 165, 0.1);
        }

        .form-group input::placeholder {
            color: var(--gray-light);
        }

        .modal-footer {
            margin-top: 2rem;
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .modal-footer p {
            color: var(--gray);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .modal-footer a {
            color: var(--teal);
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            
        }

        .modal-footer a:hover {
            text-decoration: underline;
            color: var(--teal-dark);
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #0a6b6b, #0d8484);
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
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dark));
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 20px rgba(14, 165, 165, 0.4);
            
            z-index: 999;
        }

        .scroll-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(14, 165, 165, 0.5);
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

            .modal-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .hero-content h1 {
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
            <div class="logo-icon">⚕️</div>
            <span>Al-Amine</span>
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
            <div class="hero-label">Une Équipe d'Experts à Votre Service</div>
            <h1>Nous Guidons Votre Santé Pour Développer Votre <span class="highlight">Bien-être</span></h1>
            <p>Prenez rendez-vous facilement avec nos praticiens qualifiés. Une plateforme moderne pour gérer tous vos besoins médicaux en quelques clics. Votre santé mérite le meilleur accompagnement.</p>
            <div class="hero-buttons">
                <button class="btn btn-primary btn-icon" onclick="openRegisterModal()">
                    <span>TROUVER UN DOCTEUR</span>
                    <span>→</span>
                </button>
                <button class="btn btn-outline" onclick="window.location.href='tel:+221770000000'">
                    <span>📞 Appelez: +221 77 000 0000</span>
                </button>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-image-container">
                <div class="hero-image-bg"></div>
                <img src="{{ asset('photos/doctorgeneraliste.png') }}" alt="Docteur Professionnel Al-Amine">
            </div>
        </div>
    </section>

    <!-- Features Below Hero -->
    <div class="hero-features">
        <div class="feature-box">
            <div class="icon">📋</div>
            <div>
                <div class="number">Réservation Facile</div>
                <p>Prenez rendez-vous en ligne 24/7</p>
            </div>
        </div>
        <div class="feature-box">
            <div class="icon">⚕️</div>
            <div>
                <div class="number">Experts Qualifiés</div>
                <p>Plus de 50 praticiens certifiés</p>
            </div>
        </div>
        <div class="feature-box">
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
                <h3 class="section-title">Nos Chiffres</h3>
                <h2 class="section-heading">Tous les Experts en Santé en Un Seul <span style="color: var(--teal);">ENDROIT</span></h2>
                <p style="color: var(--gray); margin-bottom: 2.5rem; line-height: 1.8; font-size: 1.05rem;">Une plateforme complète qui réunit les meilleurs professionnels de santé pour vous offrir un service d'excellence.</p>
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

    <!-- Doctors Section -->
    <section class="doctors-section" id="doctors">
        <div class="doctors-container">
            <div class="section-title">Notre Équipe Médicale</div>
            <h2 class="section-heading">Rencontrez Nos Experts de Classe Mondiale</h2>
            <p class="section-desc">Des professionnels de santé hautement qualifiés et expérimentés, dédiés à votre bien-être. Chaque membre de notre équipe apporte son expertise pour vous offrir les meilleurs soins.</p>

            <div class="doctors-grid">
                <div class="doctor-card">
                    <img src="{{ asset('photos/doctorgeneraliste.png') }}" alt="Dr. Jean Dupont" class="doctor-image">
                    <div class="doctor-info">
                        <div class="doctor-name">Dr. Jean Dupont</div>
                        <div class="doctor-specialty">Médecine Générale</div>
                    </div>
                </div>

                <div class="doctor-card">
                    <img src="{{ asset('photos/nursedoctor.png') }}" alt="Dr. Sarah Martin" class="doctor-image">
                    <div class="doctor-info">
                        <div class="doctor-name">Dr. Sarah Martin</div>
                        <div class="doctor-specialty">Cardiologie</div>
                    </div>
                </div>

                <div class="doctor-card">
                    <img src="{{ asset('photos/illustrationmandoctor.png') }}" alt="Dr. Ahmed Hassan" class="doctor-image">
                    <div class="doctor-info">
                        <div class="doctor-name">Dr. Ahmed Hassan</div>
                        <div class="doctor-specialty">Chirurgie Générale</div>
                    </div>
                </div>

                <div class="doctor-card">
                    <img src="{{ asset('photos/nursewoman.png') }}" alt="Dr. Émilie Laurent" class="doctor-image">
                    <div class="doctor-info">
                        <div class="doctor-name">Dr. Émilie Laurent</div>
                        <div class="doctor-specialty">Pédiatrie</div>
                    </div>
                </div>

                <div class="doctor-card">
                    <img src="{{ asset('photos/femalenursing.png') }}" alt="Dr. Lisa Dubois" class="doctor-image">
                    <div class="doctor-info">
                        <div class="doctor-name">Dr. Lisa Dubois</div>
                        <div class="doctor-specialty">Dermatologie</div>
                    </div>
                </div>

                <div class="doctor-card">
                    <img src="{{ asset('photos/5e1ed4dd235552e1935b7c9048ed2abc.png') }}" alt="Dr. Michel Bernard" class="doctor-image">
                    <div class="doctor-info">
                        <div class="doctor-name">Dr. Michel Bernard</div>
                        <div class="doctor-specialty">Neurologie</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Departments Section -->
    <section class="departments-section">
        <div class="doctors-container">
            <div class="section-title">Nos Spécialités</div>
            <h2 class="section-heading">Départements Médicaux d'Excellence</h2>
            <div class="departments-grid">
                <div class="dept-item">
                    <div class="dept-icon">🦷</div>
                    <div class="dept-name">Dentaire</div>
                </div>
                <div class="dept-item">
                    <div class="dept-icon">♿</div>
                    <div class="dept-name">Réadaptation</div>
                </div>
                <div class="dept-item">
                    <div class="dept-icon">🫀</div>
                    <div class="dept-name">Cardiologie</div>
                </div>
                <div class="dept-item">
                    <div class="dept-icon">💊</div>
                    <div class="dept-name">Pharmacie</div>
                </div>
                <div class="dept-item">
                    <div class="dept-icon">👁️</div>
                    <div class="dept-name">Ophtalmologie</div>
                </div>
                <div class="dept-item">
                    <div class="dept-icon">🧠</div>
                    <div class="dept-name">Neurologie</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Appointment Section -->
    <section style="padding: 2rem 4rem;">
        <div class="appointment-section">
            <h3>✨ Prenez Rendez-vous en Quelques Clics</h3>
            <p>Réservez votre consultation médicale en ligne facilement et rapidement. Des milliers de patients nous font confiance chaque jour pour leur santé.</p>
            <button class="btn btn-primary appointment-btn" onclick="openRegisterModal()">TROUVER UN DOCTEUR →</button>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <div class="services-container">
            <div class="section-title">Nos Services</div>
            <h2 class="section-heading">Des Services Médicaux d'Excellence</h2>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🏥</div>
                    <h3>Visite à Domicile</h3>
                    <p>Bénéficiez de soins médicaux professionnels dans le confort de votre domicile. Nos médecins se déplacent avec tout l'équipement nécessaire.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">💻</div>
                    <h3>Téléconsultation</h3>
                    <p>Consultez nos experts en ligne 24h/24 et 7j/7. Une solution pratique et sécurisée pour vos consultations médicales à distance.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">📋</div>
                    <h3>Ordonnances en Ligne</h3>
                    <p>Recevez vos prescriptions médicales directement en ligne avec livraison rapide de vos médicaments à domicile.</p>
                </div>
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
            <h2 class="modal-title">Connexion Patient</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="login_email">Adresse Email</label>
                    <input type="email" id="login_email" name="email" required placeholder="votre@email.com">
                </div>
                <div class="form-group">
                    <label for="login_password">Mot de passe</label>
                    <input type="password" id="login_password" name="password" required placeholder="••••••••">
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
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="reg_name">Nom Complet</label>
                    <input type="text" id="reg_name" name="name" required placeholder="Votre nom complet">
                </div>
                <div class="form-group">
                    <label for="reg_email">Adresse Email</label>
                    <input type="email" id="reg_email" name="email" required placeholder="votre@email.com">
                </div>
                <div class="form-group">
                    <label for="reg_phone">Numéro de Téléphone</label>
                    <input type="tel" id="reg_phone" name="phone" placeholder="+221 77 000 0000">
                </div>
                <div class="form-group">
                    <label for="reg_password">Mot de passe</label>
                    <input type="password" id="reg_password" name="password" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label for="reg_password_confirm">Confirmer le mot de passe</label>
                    <input type="password" id="reg_password_confirm" name="password_confirmation" required placeholder="••••••••">
                </div>
                <input type="hidden" name="role" value="PATIENT">
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
                <h4>Al-Amine</h4>
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
            <p>&copy; 2025 Al-Amine - Système de Gestion de Rendez-vous Médicaux. Tous droits réservés. | <a href="#">Conditions d'Utilisation</a> | <a href="#">Politique de Confidentialité</a></p>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTop" onclick="scrollToTop()">↑</button>

    <script>
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
