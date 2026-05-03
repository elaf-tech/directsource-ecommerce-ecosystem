<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-color: #4e6b3a;
        --secondary-color: #8aa57c;
        --accent-color: #f8a31b;
        --dark-color: #2c3e50;
        --light-color: #f9f9f9;
        --text-color: #333;
        --light-text: #fff;
        --shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
        --gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Tajawal', sans-serif;
    }

    body {
        background-color: var(--light-color);
        color: var(--text-color);
        overflow-x: hidden;
        line-height: 1.6;
    }

    /* التصميم العام */
    .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* الشريط العلوي */
    .top-bar {
        background: var(--gradient);
        color: var(--light-text);
        padding: 8px 0;
        font-size: 0.9rem;
    }

    .top-bar-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .top-bar-contact {
        display: flex;
        gap: 20px;
    }

    .top-bar-contact a {
        color: var(--light-text);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .top-bar-offers a {
        color: var(--accent-color);
        font-weight: 600;
        text-decoration: none;
    }

    /* شريط التنقل الرئيسي */
    .navbar {
        background-color: var(--light-text);
        box-shadow: var(--shadow);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
    }

    .logo {
        font-size: 1.8rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        color: var(--primary-color);
        text-decoration: none;
    }

    .logo i {
        margin-left: 8px;
        color: var(--accent-color);
    }

    .nav-links {
        display: flex;
        gap: 25px;
    }

    .nav-links a {
        color: var(--dark-color);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
        position: relative;
    }

    .nav-links a:hover {
        color: var(--primary-color);
    }

    .nav-links a::after {
        content: '';
        position: absolute;
        bottom: -5px;
        right: 0;
        width: 0;
        height: 2px;
        background: var(--primary-color);
        transition: var(--transition);
    }

    .nav-links a:hover::after {
        width: 100%;
    }

    .nav-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .search-bar {
        position: relative;
    }

    .search-bar input {
        padding: 10px 15px;
        padding-left: 40px;
        border-radius: 30px;
        border: 1px solid #ddd;
        width: 250px;
        transition: var(--transition);
    }

    .search-bar input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(78, 107, 58, 0.2);
    }

    .search-bar i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #777;
    }

    .icon-button {
        position: relative;
        color: var(--dark-color);
        font-size: 1.3rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .icon-button:hover {
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    .badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: var(--accent-color);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* قسم البطل الجديد - بدلاً من السلايدر */
    .hero-section {
        margin: 30px 0;
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow);
        height: 450px;
        background: linear-gradient(to right, var(--dark-color), var(--primary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        padding: 40px;
    }

    .hero-content {
        max-width: 800px;
        z-index: 2;
        position: relative;
        animation: fadeInUp 1s ease-out;
    }

    .hero-content h2 {
        font-size: 3rem;
        margin-bottom: 20px;
        font-weight: 800;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .hero-content p {
        font-size: 1.4rem;
        margin-bottom: 30px;
        opacity: 0.9;
        line-height: 1.6;
    }

    .hero-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 30px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: bold;
        display: inline-block;
        transition: var(--transition);
        cursor: pointer;
        border: none;
    }

    .btn-primary {
        background-color: var(--accent-color);
        color: white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-primary:hover {
        background-color: #ff7e00;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .btn-secondary {
        background-color: transparent;
        color: white;
        border: 2px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary:hover {
        background-color: white;
        color: var(--dark-color);
        transform: translateY(-3px);
    }

    /* تأثيرات الخلفية */
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,213.3C1248,235,1344,213,1392,202.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
        background-size: cover;
        background-position: bottom;
        opacity: 0.2;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 20% 50%, rgba(78, 107, 58, 0.4) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(248, 163, 27, 0.3) 0%, transparent 50%);
    }

    /* الأقسام - تحسين التباين */
    .section {
        margin: 60px 0;
        background-color: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: var(--shadow);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        position: relative;
    }

    .section-header::after {
        content: '';
        position: absolute;
        bottom: -10px;
        right: 0;
        width: 70px;
        height: 3px;
        background: var(--gradient);
        border-radius: 3px;
    }

    .section-title {
        font-size: 1.8rem;
        color: var(--dark-color);
        font-weight: 800;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 12px;
        color: var(--accent-color);
        font-size: 1.5em;
    }

    .view-all {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        transition: var(--transition);
    }

    .view-all:hover {
        color: var(--accent-color);
        transform: translateX(-5px);
    }

    .view-all i {
        margin-right: 5px;
    }

    /* البطاقات - تحسين التصميم */
    .cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
    }

    .card {
        background-color: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        border: 1px solid #eee;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
    }

    .card-img {
        height: 200px;
        background-size: cover;
        background-position: center;
        position: relative;
        transition: var(--transition);
    }

    .card:hover .card-img {
        transform: scale(1.05);
    }

    .card-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: var(--gradient);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .card-content {
        padding: 20px;
    }

    .card-title {
        font-weight: 700;
        margin-bottom: 12px;
        font-size: 1.1rem;
        color: var(--dark-color);
        line-height: 1.4;
    }

    .card-desc {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 15px;
        line-height: 1.5;
    }

    .card-price-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .card-price {
        color: var(--primary-color);
        font-weight: 800;
        font-size: 1.3rem;
    }

    .card-old-price {
        color: #999;
        text-decoration: line-through;
        font-size: 0.9rem;
        margin-right: 10px;
    }

    .card-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: var(--light-color);
        color: var(--primary-color);
        border: 1px solid #eee;
    }

    .btn-icon:hover {
        background-color: var(--primary-color);
        color: white;
        transform: rotate(10deg);
    }

    /* القوائم الأفقية - تحسين التصميم */
    .horizontal-scroll {
        display: flex;
        overflow-x: auto;
        padding: 15px 5px;
        gap: 20px;
        scrollbar-width: thin;
    }

    .horizontal-scroll::-webkit-scrollbar {
        height: 6px;
    }

    .horizontal-scroll::-webkit-scrollbar-thumb {
        background: var(--gradient);
        border-radius: 10px;
    }

    /* بطاقات العلامات التجارية - تصميم موحد */
    .brand-card {
        min-width: 170px;
        background-color: white;
        border-radius: 12px;
        padding: 15px;
        display: flex;
        align-items: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        flex-direction: column;
        text-align: center;
    }

    .brand-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .brand-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--gradient);
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 15px;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .brand-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .brand-name {
        font-weight: 700;
        color: var(--dark-color);
        font-size: 0.95rem;
    }

    /* قسم المميزات */
    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin: 40px 0;
    }

    .feature-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: var(--gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 15px;
    }

    .feature-title {
        font-size: 1.2rem;
        margin-bottom: 10px;
        color: var(--dark-color);
    }

    .feature-desc {
        color: #666;
        font-size: 0.9rem;
    }

    /* قسم العروض الخاصة */
    .offer-section {
        background: var(--gradient);
        border-radius: 15px;
        padding: 40px;
        color: white;
        margin: 50px 0;
        position: relative;
        overflow: hidden;
    }

    .offer-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(-15deg);
    }

    .offer-content {
        position: relative;
        z-index: 2;
        max-width: 600px;
    }

    .offer-title {
        font-size: 2.2rem;
        margin-bottom: 15px;
        font-weight: 800;
    }

    .offer-desc {
        font-size: 1.1rem;
        margin-bottom: 25px;
        opacity: 0.9;
    }

    .offer-timer {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .timer-item {
        background: rgba(0, 0, 0, 0.3);
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        min-width: 70px;
    }

    .timer-value {
        font-size: 1.8rem;
        font-weight: 700;
        display: block;
    }

    .timer-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* التذييل */
    footer {
        background: linear-gradient(to right, var(--dark-color), var(--primary-color));
        color: white;
        padding: 60px 0 20px;
        margin-top: 80px;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
    }

    .footer-section h3 {
        margin-bottom: 20px;
        font-size: 1.4rem;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-section h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 50px;
        height: 3px;
        background-color: var(--accent-color);
        border-radius: 3px;
    }

    .footer-links {
        list-style: none;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: var(--transition);
        display: block;
    }

    .footer-links a:hover {
        color: var(--accent-color);
        padding-right: 8px;
    }

    .footer-links a i {
        margin-left: 8px;
    }

    .social-icons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-icons a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 1.2rem;
        transition: var(--transition);
    }

    .social-icons a:hover {
        background: var(--accent-color);
        transform: translateY(-3px);
    }

    .copyright {
        text-align: center;
        margin-top: 50px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
    }

    /* تصميم الفئات الجديد */
    .categories-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }

    .category-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        text-align: center;
        text-decoration: none;
        color: inherit;
        display: block;
        position: relative;
    }

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }

    .category-image-container {
        position: relative;
        width: 100%;
        height: 180px;
        overflow: hidden;
        background: linear-gradient(45deg, #f5f7fa, #e4e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .category-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .category-card:hover .category-image {
        transform: scale(1.08);
    }

    .category-name {
        display: block;
        padding: 18px 10px;
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--dark-color);
        background: white;
        transition: var(--transition);
    }

    .category-card:hover .category-name {
        color: var(--primary-color);
        background: #f9fafb;
    }

    .category-products {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--accent-color);
        color: white;
        font-size: 0.8rem;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        z-index: 2;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }

    /* تأثيرات ألوان مختلفة للفئات */
    .category-card:nth-child(3n+1) .category-products {
        background: var(--primary-color);
    }

    .category-card:nth-child(3n+2) .category-products {
        background: #00cec9;
    }

    .category-card:nth-child(3n+3) .category-products {
        background: #ff7675;
    }

    /* التصميم المتجاوب */
    @media (max-width: 1200px) {
        .categories-container {
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        }
    }

    @media (max-width: 992px) {
        .categories-container {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 768px) {
        .categories-container {
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .category-image-container {
            height: 160px;
        }
        
        .section-title {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 576px) {
        .categories-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .category-image-container {
            height: 140px;
        }
        
        .category-name {
            padding: 15px 8px;
            font-size: 1rem;
        }
        
        .container {
            padding: 20px;
        }
    }

    /* تصميم العداد */
    .categories-count {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #eee;
    }

    .count-item {
        text-align: center;
        padding: 15px 25px;
        background: #f8f9fa;
        border-radius: 10px;
        min-width: 150px;
        border: 1px solid #eee;
    }

    .count-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 5px;
    }

    .count-label {
        color: #666;
        font-weight: 600;
    }

    /* تصميم زر عرض الكل */
    .view-all-container {
        text-align: center;
        margin-top: 40px;
    }

    .view-all-btn {
        display: inline-flex;
        align-items: center;
        padding: 12px 30px;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 700;
        transition: var(--transition);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .view-all-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background: linear-gradient(to right, var(--secondary-color), var(--primary-color));
    }

    .view-all-btn i {
        margin-right: 8px;
    }

    /* أنيميشن جديدة */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .custom-pagination {
        display: flex;
        justify-content: center;
        margin-top: 30px;
        list-style: none;
        gap: 10px;
    }

    .custom-pagination li {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    .custom-pagination li a {
        display: block;
        padding: 8px 12px;
        background: #fff;
        color: #333;
        text-decoration: none;
        transition: 0.3s;
    }

    .custom-pagination li a:hover {
        background: var(--primary-color);
        color: white;
    }

    .custom-pagination li.active a {
        background: var(--primary-color);
        color: white;
    }
</style>