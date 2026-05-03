@extends('Users.home')

@section('content')
    <!-- === مكتبات الأنماط والأيقونات === -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- **الخطوة 1: إضافة مكتبة Leaflet.js (بديل خرائط جوجل )** -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        /* --- متغيرات الألوان والأنماط --- */
        :root {
            --primary-color: #4e6b3a;
            --secondary-color: #8aa57c;
            --accent-color: #f8a31b;
            --dark-color: #2c3e50;
            --light-color: #f9f9f9;
            --text-color: #333;
            --light-text: #fff;
            --border-color: #e0e0e0;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1  );
            --transition: all 0.3s ease-in-out;
            --gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        /* --- تصميم الصفحة العام --- */
        .add-address-page {
            background-color: var(--light-color);
            padding: 60px 0;
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
        }

        /* --- بطاقة إضافة العنوان --- */
        .add-address-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow);
            max-width: 850px;
            margin: auto;
        }

        .card-header-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--dark-color);
            text-align: center;
            margin-bottom: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .card-header-title i {
            color: var(--primary-color);
        }

        /* --- تصميم حقول الإدخال --- */
        .input-group-custom {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group-custom .form-control {
            width: 100%;
            padding: 15px 20px 15px 50px; /* مساحة للأيقونة */
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
            background-color: #fdfdfd;
            font-size: 1rem;
        }

        .input-group-custom .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(78, 107, 58, 0.15);
            outline: none;
        }

        .input-group-custom .input-icon {
            position: absolute;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            color: var(--secondary-color);
            transition: var(--transition);
        }
        
        .input-group-custom .form-control:focus + .input-icon {
            color: var(--primary-color);
        }

        /* --- تصميم قسم الخريطة --- */
        .map-section {
            margin-top: 30px;
            border: 1px solid var(--border-color);
            border-radius: 15px;
            overflow: hidden;
        }
        
        /* **الخطوة 2: تعديل بسيط على حاوية الخريطة** */
        #map {
            height: 350px;
            width: 100%;
            background-color: #f0f0f0; /* لون خلفية احتياطي أثناء التحميل */
        }
        
        .coordinates-display {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        /* --- الأزرار --- */
        .btn-action {
            width: 100%;
            padding: 16px;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-save-address {
            background: var(--gradient);
            color: white;
            margin-top: 30px;
        }

        .btn-save-address:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(78, 107, 58, 0.25);
        }
        
        .btn-get-location {
            background-color: var(--dark-color);
            color: white;
            margin-top: 15px;
        }
        
        .btn-get-location:hover {
            background-color: #1e2b38;
            transform: translateY(-2px);
        }

        /* --- تصميم متجاوب --- */
        @media (max-width: 768px) {
            .add-address-card {
                padding: 25px;
            }
            .card-header-title {
                font-size: 1.8rem;
            }
            .coordinates-display {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>

    <div class="add-address-page">
        <div class="container">
            <div class="add-address-card">
                <h2 class="card-header-title">
                    <i class="fas fa-plus-circle"></i> إضافة عنوان جديد
                </h2>
                
                <form action="{{ route('addresses.store') }}" method="POST" id="addressForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group-custom">
                                <input type="text" name="country" class="form-control" placeholder="الدولة" value="{{ old('country') }}" required>
                                <i class="fas fa-globe-asia input-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group-custom">
                                <input type="text" name="region" class="form-control" placeholder="المنطقة / المحافظة" value="{{ old('region') }}" required>
                                <i class="fas fa-map input-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group-custom">
                                <input type="text" name="city" class="form-control" placeholder="المدينة" value="{{ old('city') }}" required>
                                <i class="fas fa-city input-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group-custom">
                                <input type="text" name="street_address" class="form-control" placeholder="الشارع / العنوان التفصيلي" value="{{ old('street_address') }}" required>
                                <i class="fas fa-road input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="map-section">
                        <div id="map"></div>
                    </div>

                    <div class="coordinates-display">
                        <div class="input-group-custom flex-grow-1">
                            <input type="text" name="longitude" id="longitude" class="form-control" placeholder="خط الطول" value="{{ old('longitude') }}" readonly>
                            <i class="fas fa-ruler-horizontal input-icon"></i>
                        </div>
                        <div class="input-group-custom flex-grow-1">
                            <input type="text" name="latitude" id="latitude" class="form-control" placeholder="خط العرض" value="{{ old('latitude') }}" readonly>
                            <i class="fas fa-ruler-vertical input-icon"></i>
                        </div>
                    </div>

                    <button type="button" class="btn-action btn-get-location" id="getLocation">
                        <i class="fas fa-location-arrow"></i> تحديد موقعي الحالي
                    </button>

                    <button type="submit" class="btn-action btn-save-address">
                        <i class="fas fa-save"></i> حفظ العنوان
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- **الخطوة 3: كود JavaScript الجديد لتشغيل خريطة Leaflet** -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const latField = document.getElementById('latitude');
            const lonField = document.getElementById('longitude');
            
            // 1. إعداد الخريطة (تبدأ في الرياض كقيمة افتراضية)
            const defaultCoords = [24.7136, 46.6753];
            const map = L.map('map').setView(defaultCoords, 13);

            // 2. إضافة طبقة الخريطة الأساسية من OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            } ).addTo(map);

            // 3. إنشاء أيقونة مخصصة للعلامة (اختياري لكنه يضيف لمسة جمالية)
            const customIcon = L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                shadowSize: [41, 41]
            } );

            // 4. إنشاء علامة (Marker) يمكن للمستخدم تحريكها
            const marker = L.marker(defaultCoords, {
                draggable: true,
                icon: customIcon
            }).addTo(map);

            // دالة لتحديث الحقول والإحداثيات
            function updateCoordinates(lat, lng) {
                latField.value = lat.toFixed(6);
                lonField.value = lng.toFixed(6);
            }

            // تحديث الحقول بالإحداثيات الافتراضية عند التحميل
            updateCoordinates(defaultCoords[0], defaultCoords[1]);

            // 5. تحديث الحقول عند تحريك العلامة
            marker.on('dragend', function () {
                const latLng = marker.getLatLng();
                updateCoordinates(latLng.lat, latLng.lng);
            });

            // 6. تحديث موقع العلامة عند النقر على الخريطة
            map.on('click', function(e) {
                const latLng = e.latlng;
                marker.setLatLng(latLng);
                updateCoordinates(latLng.lat, latLng.lng);
            });

            // 7. زر "تحديد موقعي الحالي"
            document.getElementById('getLocation').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const userLatLng = [position.coords.latitude, position.coords.longitude];
                            map.setView(userLatLng, 16); // تقريب الخريطة على موقع المستخدم
                            marker.setLatLng(userLatLng);
                            updateCoordinates(userLatLng[0], userLatLng[1]);
                        },
                        function(error) {
                            alert('تعذر الحصول على موقعك الحالي. يرجى التأكد من تفعيل خدمات الموقع في المتصفح.');
                            console.error('Geolocation Error:', error);
                        }
                    );
                } else {
                    alert('متصفحك لا يدعم خدمة تحديد الموقع.');
                }
            });
        });
    </script>
@stop
