<style>
       

        /* تصميم الفئات الجديد */
       /* ======== تصميم الفئات المصغرة والأنيقة ======== */
.categories-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.category-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
    text-decoration: none;
    color: inherit;
    display: block;
    position: relative;
}

.category-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.category-image-container {
    position: relative;
    width: 100%;
    height: 120px;
    overflow: hidden;
    background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid #eee;
}

.category-image {
    width: 70%;
    height: 70%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.category-card:hover .category-image {
    transform: scale(1.1);
}

.category-name {
    display: block;
    padding: 12px 8px;
    font-weight: 700;
    font-size: 0.95rem;
    color: #333;
    background: #fff;
    transition: color 0.3s ease, background 0.3s ease;
}

.category-card:hover .category-name {
    color: #ff6b6b; /* لون جذاب عند التمرير */
    background: #fff5f5;
}

.category-products {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #ff6b6b;
    color: white;
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 15px;
    font-weight: 600;
    z-index: 2;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* ألوان مختلفة للعدادات */
.category-card:nth-child(3n+1) .category-products {
    background: #ff6b6b;
}
.category-card:nth-child(3n+2) .category-products {
    background: #00cec9;
}
.category-card:nth-child(3n+3) .category-products {
    background: #fdcb6e;
}

/* تصميم متجاوب */
@media (max-width: 768px) {
    .categories-container {
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }
    
    .category-image-container {
        height: 100px;
    }
    
    .category-name {
        font-size: 0.9rem;
        padding: 10px 6px;
    }
}

@media (max-width: 576px) {
    .categories-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .category-image-container {
        height: 90px;
    }
    
    .category-name {
        font-size: 0.85rem;
        padding: 8px 5px;
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
    </style>

   

    <script>
        // تأثيرات عند التمرير
        document.addEventListener('DOMContentLoaded', function() {
            const categoryCards = document.querySelectorAll('.category-card');
            
            categoryCards.forEach((card, index) => {
                // تأثير تأخير للظهور
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 + (index * 100));
            });
            
            // تأثير عند التمرير فوق البطاقات
            categoryCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.cursor = 'pointer';
                });
            });
        });
    </script>
