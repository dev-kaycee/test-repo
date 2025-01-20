<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>The Grasshopper</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #34495e;
            --secondary-color: #4b8da0;
            --text-color: #000000;
            --bg-gradient: linear-gradient(to right, #216592, #4b8da0, #34495e);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Tahoma, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            background-color: var(--primary-color);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            width: 200px;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            width: 0;
            height: 3px;
            background: var(--secondary-color);
            position: absolute;
            left: 0;
            bottom: -6px;
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            background-color: var(--secondary-color);
            color: var(--text-color);
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn:hover {
            background-color: #34495e;
            color: #fff;
        }

        .hero {
            background: var(--bg-gradient);
            color: #fff;
            padding: 100px 0 50px;
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .hero .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .hero-content {
            flex: 4;
            max-width: 65%;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .hero-image {
            flex: 1;
            max-width: 50%;
            text-align: right;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 170px;
        }

        .features {
            background-color: #f8f9fa;
            padding: 5rem 0;
        }

        .feature-cards {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .feature-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-color);
        }

        .feature-card p {
            color: #666;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .feature-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .feature-link:hover {
            color: var(--primary-color);
        }

        .feature-link i {
            margin-left: 0.5rem;
            transition: transform 0.3s ease;
        }

        .feature-link:hover i {
            transform: translateX(5px);
        }

        .card {
            background-color: #fff;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 250px;
            max-width: 350px;
        }

        .card h3 {
            margin-bottom: 1rem;
        }

        .card p {
            color: #666;
        }

        .section-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        #plan {
            padding: 4rem 0;
        }

        .plans-list {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 50px;
        }

        .plan-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .plan-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .plan-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .plan-header h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .price {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .price .currency {
            font-size: 1.5rem;
            vertical-align: super;
        }

        .price .period {
            font-size: 1rem;
            color: #666;
        }

        .features-list {
            list-style-type: none;
            padding: 0;
            margin-bottom: 1.5rem;
        }

        .features-list li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .features-list i {
            color: #4CAF50;
            margin-right: 0.5rem;
        }

        .plan-description {
            text-align: center;
            margin-bottom: 1.5rem;
            flex-grow: 1;
            font-weight: bold;
        }

        .plan-card .btn {
            width: 100%;
            text-align: center;
        }

        .plan-card.standard {
            transform: scale(1.05);
            border: 2px solid var(--secondary-color);
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 2rem 0;
        }

        .social-links {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .social-links a {
            color: #fff;
            font-size: 24px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .social-links a:hover {
            color: var(--secondary-color);
        }

        .copyright {
            font-size: 14px;
            color: #bbb;
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }

            .hero-content, .hero-image {
                max-width: 100%;
            }

            .hero-image {
                order: -1;
            }

            .nav-links {
                display: none;
            }

            .feature-cards, .plans-list {
                flex-direction: column;
                align-items: center;
            }

            .card, .plans-list div {
                width: 100%;
                max-width: none;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <nav>
            <div class="logo">
{{--                <a href="#home">Logo</a>--}}
{{--                <h4>The Grasshopper</h4>--}}
            </div>
            <ul class="nav-links">
                <li><a href="#features">Features</a></li>
                <li><a href="#plan">Pricing</a></li>
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('book-demo') }}" class="btn">Book A Demo</a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <h1>The Grasshopper</h1>
            <p>
                The Grasshopper is a web and mobile-based project management technology developed and customizable to enhance organisational performance. This all-in-one platform integrates project organization, advanced analytics, financial management tools, and resource optimization features. With an interactive dashboard offering real-time insights, seamless quoting and invoicing, and environmental impact tracking, The Grasshopper aims to streamline operations, improve performance, and foster growth while prioritizing environmental stewardship.
            </p>
        </div>
        <div class="hero-image">
            <img src="{{ asset('img/robotic-plant.png') }}" alt="Robotic arm holding a plant">
        </div>
    </div>
</section>

<section id="features" class="features">
    <div class="container">
        <h2 class="section-header">Our Features</h2>
        <div class="feature-cards">
            @foreach(['Project Management', 'Asset Management', 'Supplier Management'] as $feature)
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-{{ $feature === 'Project Management' ? 'tasks' : ($feature === 'Asset Management' ? 'chart-line' : 'building') }}"></i>
                    </div>
                    <h3>{{ $feature }}</h3>
                    <p>
                        @switch($feature)
                            @case('Project Management')
                                Organize, plan, and oversee business projects efficiently. Ensure timely completion within budget constraints.
                                @break
                            @case('Asset Management')
                                Track, manage, and optimize company assets. Critical for SMMEs to enhance business performance and compliance.
                                @break
                            @case('Supplier Management')
                                Manage and evaluate supplier performance while tracking expenditure and maintaining procurement relationships.
                                @break
                        @endswitch
                    </p>
                    <a href="#" class="feature-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="plan" class="pricing">
    <div class="container">
        <h2 class="section-header">Our Plans</h2>
        <div class="plans-list">
            @foreach(['Basic', 'Standard', 'Premium'] as $plan)
                <div class="plan-card {{ strtolower($plan) }}">
                    <div class="plan-header">
                        <i class="fas fa-{{ $plan === 'Basic' ? 'leaf' : ($plan === 'Standard' ? 'tree' : 'award') }}"></i>
                        <h2>{{ $plan }}</h2>
                        <div class="price">
                            <span class="currency">R</span>
                            <span class="amount">{{ $plan === 'Basic' ? '800' : ($plan === 'Standard' ? '1400' : 'Custom') }}</span>
                            @if($plan !== 'Premium')
                                <span class="period">/month</span>
                            @endif
                        </div>
                    </div>
                    <ul class="features-list">
                        @if($plan === 'Basic')
                            <li><i class="fas fa-check"></i> 10 Users</li>
                            <li><i class="fas fa-check"></i> 50 Projects</li>
                            <li><i class="fas fa-check"></i> Dashboard Insights</li>
                            <li><i class="fas fa-check"></i> Project Tracking</li>
                            <li><i class="fas fa-check"></i> Finance Management</li>
                            <li><i class="fas fa-check"></i> Resource Allocation</li>
                            <li><i class="fas fa-check"></i> Basic Support</li>
                        @elseif($plan === 'Standard')
                            <li><i class="fas fa-check"></i> All Basic Features</li>
                            <li><i class="fas fa-check"></i> Unlimited Users</li>
                            <li><i class="fas fa-check"></i> Unlimited Projects</li>
                            <li><i class="fas fa-check"></i> Priority Support</li>
                            <li><i class="fas fa-check"></i> Live Training</li>
                            <li><i class="fas fa-check"></i> Limited Custom Changes</li>
                            <li><i class="fas fa-check"></i> Reports On Request</li>
                        @else
                            <li><i class="fas fa-check"></i> All Standard Features</li>
                            <li><i class="fas fa-check"></i> Custom Support</li>
                            <li><i class="fas fa-check"></i> Onboarding Assistance</li>
                            <li><i class="fas fa-check"></i> Report Analysis</li>
                            <li><i class="fas fa-check"></i> Performance Optimization</li>
                            <li><i class="fas fa-check"></i> Tailored Features</li>
                            <li><i class="fas fa-check"></i> Dedicated Account Manager</li>
                        @endif
                    </ul>
                    <p class="plan-description">
                        @if($plan === 'Basic')
                            Essential features to get started and grow.
                        @elseif($plan === 'Standard')
                            Optimise operations across your entire organisation.
                        @else
                            Tailored features to elevate your organization's performance.
                        @endif
                    </p>
                    <a href="{{ route('book-demo') }}" class="btn">Get Started</a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <ul class="social-links">
            @foreach(['linkedin', 'facebook', 'instagram'] as $social)
                <li><a href="https://www.{{ $social }}.com" target="_blank" aria-label="{{ ucfirst($social) }}"><i class="fab fa-{{ $social }}"></i></a></li>
            @endforeach
        </ul>
        <p class="copyright">&copy; {{ date('Y') }} Grasshopper Green Technologies. All Rights Reserved.</p>
    </div>
</footer>
</body>
</html>