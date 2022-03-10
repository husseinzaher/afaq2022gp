@isset($candidateData)
<section class="candidates" id="candidates">
    <div class="container text-center">
        <h2 class="main_sec_title"> <img src="{{asset('frontend/img/button_side.png')}}" alt=""><span>
                المرشحين</span><img class="left" src="{{asset('frontend/img/button_side.png')}}" alt=""></h2>
        <div class="owl-carousel owl-theme">
            @forelse($candidateData as $v)
            <div class="card_container">
                <div class="card">
                    <div class="front">
                        <div class="card-top">
                            <div class="card_left">
                                <h6>رقم المرشح</h6>
                            </div>
                            <div class="card_right"><img src="{{asset('frontend/img/card_bg.png')}}" alt="">
                                <img class="candidate_img" src="{{$v->getFirstMediaUrl('photo')}}" alt="">
                                <div class="candidate-number">  <span>{{$v->number}}</span></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="view_candidate_info">{!! $v->title  !!}
                                <button><i class="fa-solid fa-arrow-up-long"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="back">
                        <div class="content">
                            <div class="card-top">
                                <h2 class="title">{!! $v->title  !!}</h2>
                            </div>
                            <div class="card-body">
                                <p class="desc">
                                    {!! $v->hint!!}
                                </p>
                            </div>
                            {{--<div class="card-footer"><a class="view_candidate_info" href="{{route('frontend.candate.show',$v->id)}}">اقرا
                                    المزيد
                                    <button><i class="fa-solid fa-arrow-up-long"></i></button></a></div>--}}
                        </div>
                    </div>
                </div>
            </div>
            @empty

            @endforelse

        </div>
        <div class="about_info">
            <table class="table">
                <thead>
                <tr>
                    <th class="table-title" scope="col"> موعد التصويت</th>
                    <th class="table-title" scope="col"> التصويت يبدا - ينتهي</th>
                    <th class="table-title" scope="col"> الساعة يبدا - ينتهي</th>
                    <th class="table-title" scope="col"> مجموع الساعات</th>
                </tr>
                </thead>
                <tbody style="border-top:none;">
                <tr>
                    <td class="table-title">Voting Will START</td>
                    <td>2022/03/23 - 2022/03/21</td>
                    <td>من 9 صباحاً - 9 مساءً</td>
                    <td> 60 ساعة</td>
                </tr>
                </tbody>
            </table>
        </div>
        <button class="vote_now"> <span class="text">
                قم بالتصويت الأن للمرشح</span><span class="icon">
                <i class="fa-solid fa-arrow-left-long"></i></span></button>
    </div>
</section>
@endisset