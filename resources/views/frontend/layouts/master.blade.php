<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="مبادرة آفاق 2020. ومن بين الإنجازات الرئيسية للمبادرة، التي انتهت في عام 2022، ما يلي: زيادة الاستثمارات، وتعزيز أطر السياسات والأطر القانونية، ,والتي الهدف منها تطوير القطاع الصحي بمكة المكرمة لأحدث ما توصل إليه العلم والتقنية وإنشاء منشآت طبية تعليمية لتدريب وتأهيل كوادر طبية وطنية لتقديم أرقي الخدمات لضيوف الرحمن وسكان مكة المكرمة و توطــــين صناعة المنتجات الاتسهلاكية في القطاع الطبي  و جذب رؤوس الأموال والصناديق الاستثمارية لإنشاء وتطوير المستشفيات ومعاهد التخصصات الطبية">
    <meta name="keywords" content=" مبادرة , افاق , 2022 , مبادرة 2022 , تكنولوجيا , تطوير , استثمارات , التخصصات الطبية , رؤوس الاموال , مكة المكرمة ">
    <meta name="author" content="مبادرة افاق">
    <link rel="icon" href="{{asset('frontend/img/favicon.png')}}">
    <title>آفاق | الصفحة الرئيسية</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="{{asset('frontend/css/all.css')}}">
</head>
<body>
<div class="loading-animation active"><img src="{{asset('frontend/img/LOGO-DARK.svg')}}" alt="logo"></div>
<nav class="navbar navbar-expand-lg navbar-dark" id="nav">
    <div class="container-fluid">
        <a class="navbar-brand col-lg-4" href="index.html"><img src="{{asset('frontend/img/LOGO-DARK.svg')}}" alt="logo"></a>
        <button class="navbar-toggler" type="button">
            <i class="fa-solid fa-align-left"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" href="index.html" aria-current="page">الرئيسية</a>
                <a class="nav-link" href="#candidates"> المرشحين </a><a class="nav-link" href="#electoral_target">اهداف الحملة </a><a class="nav-link" href="#campaignNews">اخبار المرشحين</a><a class="nav-link" href="#tech_support">التواصل معنا</a></div>
        </div>
    </div>
</nav>
<div class="sidebar">
    <div class="sidebar-overlay animated fadeOut"></div>
    <div class="sidebar-content"><a class="nav-link active" href="index.html" aria-current="page">الرئيسية</a>
        <a class="nav-link" href="#candidates"> المرشحين </a><a class="nav-link" href="#electoral_target">اهداف الحملة </a><a class="nav-link" href="#campaignNews">اخبار المرشحين</a><a class="nav-link" href="#tech_support">التواصل معنا</a>
    </div>
</div>


@yield('content')




<footer> <img class="bg_ex" src="{{asset('frontend/img/bgex8.png')}}" alt="">
    <div class="container">
        <div class="row">
            <ul class="col-lg-4 col-md-12">
                <li class="logo_content"><img src="{{asset('frontend/img/LOGO-DARK.svg')}}" alt="logo"></li>
                <li class="social_links">
                    <div class="icon"><a href=""> <i class="fa-brands fa-whatsapp"></i></a></div>
                    <div class="icon"><a href=""> <i class="fa-brands fa-twitter"></i></a></div>
                    <div class="icon"><a href=""> <i class="fa-brands fa-telegram"></i></a></div>
                    <div class="icon"><a href=""> <i class="fa-brands fa-facebook-f"></i></a></div>
                </li>
            </ul>
            <div class="col-lg-8 col-md-12">
                <div class="container-fluid">
                    <div class="row">
                        <ul class="col-lg-4">
                            <li class="title">
                                <h4>أفاق</h4>
                            </li>
                            <li class="item"> <a href="##">عن افاق </a></li>
                        </ul>
                        <ul class="col-lg-4">
                            <li class="title">
                                <h4>السياسات</h4>
                            </li>
                            <li class="item"> <a href="##">سياسات الخصوصية</a></li>
                            <li class="item"> <a href="##">شروط الإستخدام</a></li>
                        </ul>
                        <ul class="col-lg-4">
                            <li class="title">
                                <h4>المساعدة</h4>
                            </li>
                            <li class="item"> <a href="##">الأسئلة الشائعة</a></li>
                            <li class="item"> <a href="##">تواصل معنا</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="border-top: 2px solid #ae8b50;margin-top:1rem;">
                <p>جميع الحقوق محفوظة لمكة مسؤوليتي @ 2020</p>
            </div>
        </div>
    </div>
</footer>
<script src="{{asset('frontend/js/nav-js.js')}}"> </script>
<script src="{{asset('frontend/js/bootstrap.min.js')}}"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/ScrollTrigger.min.js"></script>
<script src="{{asset('frontend/js/main.js')}}"> </script>
<script src="{{asset('frontend/js/homepage_animation.js')}}"> </script>
</body>
</html>