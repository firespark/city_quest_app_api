@extends('layout')

@section('content')


<section class="paddind" id="Portfolio">
        <!--main-section-start-->
        <div class="container">
            <h2 class="h1">Популярные квест-экскурсии</h2>
            <div class="h3">Список всех доступных квестов. Для тех, кто в Воронеже, также доступен экскурсионный квест "Мистический Воронеж"</div>
            <div class="portfolioFilter">

                <ul class="Portfolio-nav wow fadeIn delay-02s">
                    <li><a class="btn btn-outline-info" href="#">Воронеж</a></li>
                    <li><a class="btn btn-outline-info" href="#">Москва</a></li>
                    <li><a class="btn btn-outline-info" href="#">Санкт-Петербург</a></li>
                    <li><a class="btn btn-outline-info" href="#">...Другой</a></li>
                </ul>
            </div>

        </div>
        <div class="row portfolioContainer wow fadeInUp delay-04s">
            @foreach($quests as $quest)
            <div class="col-sm-6">
                <div class="portfoloio-image-wrapper">
                    <a href="{{route('quests.show', $quest->slug)}}"><img src="{{$quest->getImage()}}" alt="
                        "></a>
                    <div class="portfolio-description">
                        <div class="h3">
                            <a href="{{route('quests.show', $quest->slug)}}">{{$quest->title}}</a>
                        </div>
                        <p><a href="{{route('cities.show', $quest->city->slug)}}">{{$quest->getCityTitle()}}</a></p>
                    </div>
                </div>
                
            </div>
            @endforeach
            
        </div>
    </section>
    <!--main-section-end-->

    <section class="alabaster paddind" id="sights">
            <div class="container">
                <h2 class="h1">Достопримечательности</h2>
                <div class="h3">Если у вас появились вопросы или интересные предложения, свяжитесь с нами через социальные сети или с помощью формы ниже.</div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="sight-item">
                            <img src="/img/sights/004-city-hall.png" alt="">
                            <div class="h3"><h4>Архитектура</h4></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="sight-item">
                            <img src="/img/sights/010-memorial.png" alt="">
                            <div class="h3"><h4>Памятники</h4></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="sight-item">
                            <img src="/img/sights/015-moai.png" alt="">
                            <div class="h3"><h4>Арт-объекты</h4></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="sight-item">
                            <img src="/img/sights/017-museum-2.png" alt="">
                            <div class="h3"><h4>Музеи</h4></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="sight-item">
                            <img src="/img/sights/018-ancient-jar.png" alt="">
                            <div class="h3"><h4>История</h4></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="sight-item">
                            <img src="/img/sights/022-park-1.png" alt="">
                            <div class="h3"><h4>Общественные пространства</h4></div>
                        </div>
                    </div>
                        
                    
                </div>
            </div>
        </section>

    <section class="paddind" id="sights">
            <div class="container">
                <h2 class="h1">Как играть?</h2>
                <div class="h3">Если у вас появились вопросы или интересные предложения, свяжитесь с нами через социальные сети или с помощью формы ниже.</div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-info mb-3" style="max-width: 20rem;">
                          <div class="card-body text-center">
                            <h4 class="card-title">Выбери город и квест-экскурсию</h4>
                            <p class="card-text">В каждом городе могут быть доступны несколько квест-экскурсий. Если нужного города в списке нет, сообщи нам.</p>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info mb-3" style="max-width: 20rem; border: none;">
                          <div class="card-body text-center">
                            <h4 class="card-title">Авторизуйся</h4>
                            <p class="card-text">Авторизация нужна для сохранения результата. Авторизоваться можно с помощью соцсетей или емейла.</p>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info mb-3" style="max-width: 20rem;">
                          <div class="card-body text-center">
                            <h4 class="card-title">Отгадывай достопримечательности и гуляй по городу</h4>
                            <p class="card-text">Прежде чем узнать что-то о достопримечательности, ее нужно угадать. А затем, когда она известна, ответить на вопрос о ней.</p>
                          </div>
                        </div>
                    </div>
                              
                    
                </div>
            </div>
        </section>

    <!--business-talking-end-->

    <section class="alabaster paddind" id="Portfolio">
        <!--main-section-start-->
        <div class="container">
            <h2 class="h1">Еще квесты</h2>
            <div class="h3">Список всех доступных квестов. Для тех, кто в Воронеже, также доступен экскурсионный квест "Мистический Воронеж"</div>
            
        </div>
        <div class="row portfolioContainer wow fadeInUp delay-04s">
 
            <div class="col-sm-6">
                <div class="portfoloio-image-wrapper">
                    <a href="#"><img src="/img/the-blurred.jpg" alt="
                        "></a>
                    <div class="portfolio-description">
                        <div class="h3">
                            <a href="#">Квест-Экскурсия по Москве</a>
                        </div>
                        <p><a href="#">Москва</a></p>
                    </div>
                </div>
                
            </div>
            <div class="col-sm-6">
                <div class="portfoloio-image-wrapper">
                    <a href="#"><img src="/img/the-blurred.jpg" alt="
                        "></a>
                    <div class="portfolio-description">
                        <div class="h3">
                            <a href="#">Квест-Экскурсия по Москве</a>
                        </div>
                        <p><a href="#">Москва</a></p>
                    </div>
                </div>
                
            </div>
            
        </div>
    </section>
    
        <section class="paddind" id="contact">
            <div class="container">
                <h2 class="h1">Обратная связь</h2>
                <div class="h3">Если у вас появились вопросы или интересные предложения, свяжитесь с нами через социальные сети или с помощью формы ниже.</div>
            <div class="row">
                <div class="col-lg-6 col-sm-7 wow fadeInLeft contact">
                    <div class="contact-info-box phone clearfix">
                        <i class="fa fa-telegram"></i> Telegram:
                        <span>+7(999) 720-5259</span>
                    </div>
                    <div class="contact-info-box phone clearfix">
                        <i class="fa fa-whatsapp"></i> Whatsapp:
                        <span>+7(999) 720-5259</span>
                    </div>
                    <div class="contact-info-box email clearfix">
                        <i class="fa fa-envelope"></i> Email:
                        <span>info@gagara-web.ru</span>
                    </div>
                    <ul class="social-link">
                        <li class="facebook">Социальные сети: </li>
                        <li class="facebook"><a href="#" target="_blank"><i class="fa fa-vk"></i></a></li>
                        <li class="facebook"><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li class="facebook"><a href="#" target="_blank"><i class="fa fa-instagram"></i></a></li>
                        
                    </ul>
                </div>
                <div class="col-lg-6 col-sm-5 wow fadeInUp delay-05s">
                    <div class="form">

                        <div id="sendmessage"></div>
                        <div id="errormessage"></div>
                        <form role="form" class="contactForm" id="sendApply">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Ваше имя*" required/>
                                
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" placeholder="Email или телефон для связи*" required/>
                                
                            </div>
                            
                            <div class="form-group">
                                <textarea class="form-control" name="message" rows="3" placeholder="Сообщение*" required></textarea>
                                
                            </div>

                            <div class="checkbox">
                              <label><input type="checkbox" name="agree" checked required>Я даю свое согласие на <a href="/privacy-policy/" target="_blank">обработку персональных данных</a></label>
                            </div>

                            <div class="text-center"><button type="submit" class="btn btn-info">Связаться</button></div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <section class="alabaster paddind" id="sights">
            <div class="container">
                <h2 class="h1">Будь в курсе</h2>
                <div class="h3">Подпишись на новые квесты</div>
                <div class="row">
                    @include('admin.errors')
                    <div id="sendmessage"></div>
                    <div id="errormessage"></div>
                        <form role="form" class="contactForm" id="subscribeQuests" action="/subscribe" method="post">
                            @csrf
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email*" required/>
                                
                            </div>

                            <div class="checkbox">
                              <label><input type="checkbox" name="agree" checked required>Я даю свое согласие на <a href="/privacy-policy/" target="_blank">обработку персональных данных</a></label>
                            </div>

                            <div class="text-center"><button type="submit" class="btn btn-info">Подписаться</button></div>
                        </form>
                        
                    
                </div>
            </div>
        </section>

@endsection