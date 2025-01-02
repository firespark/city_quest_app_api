@extends('layout')

@section('content')

    <section class="alabaster paddind" id="Portfolio">
        <!--main-section-start-->
        <div class="container">
            <h2 class="h1">Вход</h2>
            
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="form">
                        @include('admin.errors')
                        <div class="sendmessage"></div>
                        <div class="errormessage"></div>
                        <form role="form" class="contactForm" id="loginUser" method="post" action="/login">
                            @csrf
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email*" required/>
                                
                            </div>
                            
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Пароль*" required/>
                                
                            </div>


                            <div class="text-center"><button type="submit" class="btn btn-info">Войти</button></div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </section>


@endsection