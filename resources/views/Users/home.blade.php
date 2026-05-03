<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجرك الإلكتروني - التسوق المباشر من المورد</title>
@include('Users.include.css')
</head>
<body>
    <!-- الشريط العلوي -->
  @include('Users.include.navbar')

    <!-- المحتوى الرئيسي -->
   @yield('content')
    <!-- التذييل -->
  @include('Users.include.footer')

  @include('Users.include.js')
</body>
</html>