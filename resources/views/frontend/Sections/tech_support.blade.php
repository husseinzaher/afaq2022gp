<section class="tech_support" id="tech_support">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="form_container">
                    <h2 class="title">الدعم الفني</h2>
                    <h5 class="subtitle">يرجى كتابة وتوضيح برسالتك المشكلة بالتحديد حتى نقدم المساعدة</h5>
                    <form name="myForm" action="" onsubmit="return validateForm()" method="post">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input_container">
                                        <label class="form-label" for="username">الاسم</label>
                                        <input class="form-control" type="text" id="username" name="username">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input_container">
                                        <label class="form-label" for="phoneNumber">رقم الجوال</label>
                                        <input class="form-control" type="text" id="phoneNumber" name="phoneNumber">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input_container">
                                        <label class="form-label" for="msgTitle">عنوان الرسالة</label>
                                        <input class="form-control" type="text" id="msgTitle" name="msgTitle">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input_container">
                                        <label class="form-label" for="msgDesc">محتوى الرسالة</label>
                                        <textarea class="form-control" cols="30" rows="10" id="msgDesc" name="msgDesc"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit">ارسال</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-md-12"><img src="{{asset('frontend/img/techSupport.svg')}}" alt=""></div>
        </div>
    </div>
</section>
