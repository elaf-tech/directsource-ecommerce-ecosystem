<script>
    // السلايدر - تم التعديل
    const slidesContainer = document.querySelector('.slides-container');
    const slides = document.querySelector('.slides');
    const slideItems = document.querySelectorAll('.slide');
    const dotsContainer = document.querySelector('.slider-dots');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    
    let currentSlide = 0;
    const slideCount = slideItems.length;
    let slideInterval;
    
    // إنشاء النقاط
    for (let i = 0; i < slideCount; i++) {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (i === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(i));
        dotsContainer.appendChild(dot);
    }
    
    const dots = document.querySelectorAll('.dot');
    
    // الانتقال لشريحة محددة
    function goToSlide(n) {
        currentSlide = n;
        slides.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        // تحديث النقاط النشطة
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentSlide);
        });
        
        // إعادة تشغيل المؤقت التلقائي
        restartAutoSlide();
    }
    
    // الشريحة التالية
    function nextSlide() {
        goToSlide((currentSlide + 1) % slideCount);
    }
    
    // الشريحة السابقة
    function prevSlide() {
        goToSlide((currentSlide - 1 + slideCount) % slideCount);
    }
    
    // إضافة أحداث النقر للأزرار
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);
    
    // تشغيل السلايدر تلقائياً
    function startAutoSlide() {
        slideInterval = setInterval(nextSlide, 5000);
    }
    
    // إيقاف السلايدر التلقائي
    function stopAutoSlide() {
        clearInterval(slideInterval);
    }
    
    // إعادة تشغيل السلايدر التلقائي
    function restartAutoSlide() {
        stopAutoSlide();
        startAutoSlide();
    }
    
    // إضافة أحداث لإيقاف التمرير التلقائي عند التفاعل
    slidesContainer.addEventListener('mouseenter', stopAutoSlide);
    slidesContainer.addEventListener('mouseleave', startAutoSlide);
    
    // بدء السلايدر التلقائي
    startAutoSlide();
    
    // عداد التنازلي للعرض
    function startCountdown() {
        const days = document.querySelector('.timer-item:nth-child(1) .timer-value');
        const hours = document.querySelector('.timer-item:nth-child(2) .timer-value');
        const minutes = document.querySelector('.timer-item:nth-child(3) .timer-value');
        const seconds = document.querySelector('.timer-item:nth-child(4) .timer-value');
        
        let countDownDate = new Date();
        countDownDate.setDate(countDownDate.getDate() + 2);
        
        let x = setInterval(function() {
            let now = new Date().getTime();
            let distance = countDownDate - now;
            
            days.innerHTML = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
            hours.innerHTML = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
            minutes.innerHTML = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
            seconds.innerHTML = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
            
            if (distance < 0) {
                clearInterval(x);
                days.innerHTML = "00";
                hours.innerHTML = "00";
                minutes.innerHTML = "00";
                seconds.innerHTML = "00";
            }
        }, 1000);
    }
    
    // بدء عداد التنازلي
    startCountdown();
    
    // تأثيرات عند التمرير
    window.addEventListener('scroll', function() {
        const cards = document.querySelectorAll('.card');
        const windowHeight = window.innerHeight;
        
        cards.forEach(card => {
            const cardTop = card.getBoundingClientRect().top;
            if (cardTop < windowHeight - 100) {
                card.style.opacity = 1;
                card.style.transform = 'translateY(0)';
            }
        });
    });
    
    // تهيئة البطاقات للظهور عند التمرير
    document.querySelectorAll('.card').forEach(card => {
        card.style.opacity = 0;
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });
    
    // تشغيل تأثير الظهور عند التحميل
    window.addEventListener('load', function() {
        setTimeout(() => {
            document.querySelectorAll('.card').forEach((card, i) => {
                setTimeout(() => {
                    const cardTop = card.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;
                    if (cardTop < windowHeight - 100) {
                        card.style.opacity = 1;
                        card.style.transform = 'translateY(0)';
                    }
                }, i * 100);
            });
        }, 500);
    });
</script>